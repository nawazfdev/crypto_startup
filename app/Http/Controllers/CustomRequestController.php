<?php

namespace App\Http\Controllers;

use App\Models\CustomRequest;
use App\Models\CustomRequestContribution;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomRequestController extends Controller
{
    /**
     * Display marketplace of custom requests
     */
    public function marketplace(Request $request)
    {
        $requests = CustomRequest::where('is_marketplace', true)
            ->where('status', '!=', CustomRequest::STATUS_CANCELLED)
            ->with(['creator', 'contributions'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('custom-requests.marketplace', compact('requests'));
    }

    /**
     * Display a single custom request
     */
    public function show($id)
    {
        $customRequest = CustomRequest::with(['creator', 'requester', 'contributions.contributor'])
            ->findOrFail($id);

        return view('custom-requests.show', compact('customRequest'));
    }

    /**
     * Store a new custom request
     */
    public function store(Request $request)
    {
        // Handle creator lookup by username or ID
        $creatorId = $request->input('creator_id');
        $creatorUsername = $request->input('creator_username');
        
        // If creator_id is not provided, try to find by username
        if (!$creatorId && $creatorUsername) {
            $username = trim($creatorUsername);
            // Remove @ if user typed it
            $username = ltrim($username, '@');
            
            // Try exact match first
            $creator = \App\User::where('username', $username)->first();
            
            // If not found, try case-insensitive match
            if (!$creator) {
                $creator = \App\User::whereRaw('LOWER(username) = ?', [strtolower($username)])->first();
            }
            
            if ($creator) {
                $creatorId = $creator->id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Creator not found. Please check the username and select from the search results that appear below.'
                ], 422);
            }
        }

        // Validate all fields including creator_id
        $validator = Validator::make(array_merge($request->all(), ['creator_id' => $creatorId]), [
            'creator_id' => 'required|exists:users,id',
            'type' => 'required|in:private,public,marketplace',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'goal_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
            'message_id' => 'nullable|exists:user_messages,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (!$creatorId) {
            return response()->json([
                'success' => false,
                'message' => 'Creator is required. Please select a creator from the search results.'
            ], 422);
        }

        $data = $request->all();
        $data['creator_id'] = $creatorId;
        $data['requester_id'] = Auth::id();
        
        if ($data['type'] === 'marketplace') {
            $data['is_marketplace'] = true;
            $data['goal_amount'] = $data['goal_amount'] ?? 0;
            $data['current_amount'] = 0;
        } else {
            $data['is_marketplace'] = false;
            $data['price'] = $data['price'] ?? 0;
        }

        // Remove creator_username from data
        unset($data['creator_username']);

        $customRequest = CustomRequest::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Custom request created successfully',
            'request' => $customRequest
        ]);
    }

    /**
     * Contribute to a marketplace request
     */
    public function contribute(Request $request, $id)
    {
        $customRequest = CustomRequest::findOrFail($id);

        if (!$customRequest->is_marketplace) {
            return response()->json([
                'success' => false,
                'message' => 'This is not a marketplace request'
            ], 400);
        }

        if ($customRequest->status !== CustomRequest::STATUS_ACCEPTED) {
            return response()->json([
                'success' => false,
                'message' => 'This request is not accepting contributions'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $amount = $request->input('amount');

        // Create contribution record
        $contribution = CustomRequestContribution::create([
            'custom_request_id' => $customRequest->id,
            'contributor_id' => Auth::id(),
            'amount' => $amount,
            'message' => $request->input('message'),
            'status' => CustomRequestContribution::STATUS_PENDING,
        ]);

        // Update current amount (will be updated when payment is confirmed)
        $customRequest->current_amount += $amount;
        $customRequest->save();

        // TODO: Integrate with payment system
        // For now, we'll mark it as completed immediately
        // In production, you'd create a transaction and process payment
        $contribution->status = CustomRequestContribution::STATUS_COMPLETED;
        $contribution->save();

        return response()->json([
            'success' => true,
            'message' => 'Contribution added successfully',
            'contribution' => $contribution,
            'current_amount' => $customRequest->fresh()->current_amount,
            'progress' => $customRequest->fresh()->progress_percentage
        ]);
    }

    /**
     * Accept a custom request (creator accepts)
     */
    public function accept($id)
    {
        $customRequest = CustomRequest::findOrFail($id);

        if ($customRequest->creator_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $customRequest->status = CustomRequest::STATUS_ACCEPTED;
        $customRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Request accepted'
        ]);
    }

    /**
     * Reject a custom request
     */
    public function reject($id)
    {
        $customRequest = CustomRequest::findOrFail($id);

        if ($customRequest->creator_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $customRequest->status = CustomRequest::STATUS_REJECTED;
        $customRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Request rejected'
        ]);
    }

    /**
     * Mark request as completed
     */
    public function complete($id)
    {
        $customRequest = CustomRequest::findOrFail($id);

        if ($customRequest->creator_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $customRequest->status = CustomRequest::STATUS_COMPLETED;
        $customRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Request marked as completed'
        ]);
    }

    /**
     * Cancel a custom request
     */
    public function cancel($id)
    {
        $customRequest = CustomRequest::findOrFail($id);

        if ($customRequest->requester_id !== Auth::id() && $customRequest->creator_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $customRequest->status = CustomRequest::STATUS_CANCELLED;
        $customRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Request cancelled'
        ]);
    }

    /**
     * Get user's custom requests
     */
    public function myRequests(Request $request)
    {
        $type = $request->get('type', 'all'); // 'created', 'received', 'all'
        
        $query = CustomRequest::with(['creator', 'requester', 'contributions']);

        if ($type === 'created') {
            $query->where('requester_id', Auth::id());
        } elseif ($type === 'received') {
            $query->where('creator_id', Auth::id());
        } else {
            $query->where(function($q) {
                $q->where('requester_id', Auth::id())
                  ->orWhere('creator_id', Auth::id());
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('custom-requests.my-requests', compact('requests', 'type'));
    }
}
