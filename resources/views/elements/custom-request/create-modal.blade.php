<div class="modal fade" tabindex="-1" role="dialog" id="createCustomRequestModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create Custom Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createCustomRequestForm">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <div class="form-group">
                        <label for="request_creator_id">{{ __('Creator Username') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="request_creator_username" name="creator_username" placeholder="{{ __('Enter creator username') }}" required autocomplete="off">
                        <input type="hidden" id="request_creator_id" name="creator_id" required>
                        <small class="form-text text-muted">{{ __('Enter the username and select from the search results') }}</small>
                        <div id="creator_selected_indicator" class="mt-2" style="display: none;">
                            <span class="badge badge-success">{{ __('Creator selected') }}: <span id="selected_creator_name"></span></span>
                        </div>
                        <div id="creator_search_results" class="mt-2" style="display: none;"></div>
                    </div>

                    <div class="form-group">
                        <label for="request_type">{{ __('Request Type') }} <span class="text-danger">*</span></label>
                        <select class="form-control" id="request_type" name="type" required>
                            <option value="">{{ __('Select type') }}</option>
                            <option value="private">{{ __('Private Request') }} - {{ __('Sent via private message') }}</option>
                            <option value="public">{{ __('Public Request') }} - {{ __('Visible on creator profile') }}</option>
                            <option value="marketplace">{{ __('Marketplace Request') }} - {{ __('Crowdfunded with contributions') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="request_title">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="request_title" name="title" placeholder="{{ __('e.g., Shave My Head') }}" required maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="request_description">{{ __('Description') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="request_description" name="description" rows="4" placeholder="{{ __('Describe your custom request in detail...') }}" required></textarea>
                    </div>

                    <div id="price_field" class="form-group" style="display: none;">
                        <label for="request_price">{{ __('Price') }} ($) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="request_price" name="price" step="0.01" min="0" placeholder="0.00">
                        <small class="form-text text-muted">{{ __('Fixed price for this request') }}</small>
                    </div>

                    <div id="goal_amount_field" class="form-group" style="display: none;">
                        <label for="request_goal_amount">{{ __('Goal Amount') }} ($) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="request_goal_amount" name="goal_amount" step="0.01" min="0.01" placeholder="1000.00">
                        <small class="form-text text-muted">{{ __('Total amount needed (e.g., $1000 to shave my head)') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="request_deadline">{{ __('Deadline') }} ({{ __('Optional') }})</label>
                        <input type="date" class="form-control" id="request_deadline" name="deadline">
                        <small class="form-text text-muted">{{ __('When should this request be completed?') }}</small>
                    </div>

                    <div id="message_id_field" class="form-group" style="display: none;">
                        <label for="request_message_id">{{ __('Message') }} ({{ __('Optional') }})</label>
                        <input type="number" class="form-control" id="request_message_id" name="message_id" placeholder="{{ __('Message ID if sent via private message') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const requestType = document.getElementById('request_type');
    const priceField = document.getElementById('price_field');
    const goalAmountField = document.getElementById('goal_amount_field');
    const messageIdField = document.getElementById('message_id_field');
    const priceInput = document.getElementById('request_price');
    const goalAmountInput = document.getElementById('request_goal_amount');

    requestType.addEventListener('change', function() {
        const type = this.value;
        
        // Hide all fields first
        priceField.style.display = 'none';
        goalAmountField.style.display = 'none';
        messageIdField.style.display = 'none';
        priceInput.removeAttribute('required');
        goalAmountInput.removeAttribute('required');

        // Show relevant fields based on type
        if (type === 'private' || type === 'public') {
            priceField.style.display = 'block';
            priceInput.setAttribute('required', 'required');
            if (type === 'private') {
                messageIdField.style.display = 'block';
            }
        } else if (type === 'marketplace') {
            goalAmountField.style.display = 'block';
            goalAmountInput.setAttribute('required', 'required');
        }
    });

    // Creator username search
    const creatorUsernameInput = document.getElementById('request_creator_username');
    const creatorIdInput = document.getElementById('request_creator_id');
    const creatorResults = document.getElementById('creator_search_results');
    let searchTimeout;

    if (creatorUsernameInput) {
        creatorUsernameInput.addEventListener('input', function() {
            const username = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (username.length < 2) {
                creatorResults.style.display = 'none';
                creatorIdInput.value = '';
                return;
            }

            searchTimeout = setTimeout(function() {
                fetch('{{ route("search.users") }}?query=' + encodeURIComponent(username) + '&encode_html=false', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(responseData => {
                    creatorResults.innerHTML = '';
                    
                    // Handle different response structures from search API
                    let users = [];
                    if (responseData.success && responseData.data) {
                        // Response structure: {success: true, data: PaginatedCollection}
                        if (responseData.data.data && Array.isArray(responseData.data.data)) {
                            // Paginated structure: data.data.data (array of users)
                            users = responseData.data.data;
                        } else if (responseData.data.users && Array.isArray(responseData.data.users)) {
                            // Encoded HTML structure: data.users
                            users = responseData.data.users;
                        } else if (Array.isArray(responseData.data)) {
                            users = responseData.data;
                        }
                    } else if (responseData.users && Array.isArray(responseData.users)) {
                        users = responseData.users;
                    } else if (Array.isArray(responseData)) {
                        users = responseData;
                    }
                    
                    if (users && users.length > 0) {
                        creatorResults.style.display = 'block';
                        const list = document.createElement('div');
                        list.className = 'list-group';
                        list.style.maxHeight = '300px';
                        list.style.overflowY = 'auto';
                        
                        users.slice(0, 5).forEach(user => {
                            // Handle different user object structures
                            let userId, userName, userUsername;
                            
                            if (user.id) {
                                userId = user.id;
                                userName = user.name || 'Unknown';
                                userUsername = user.username || '';
                            } else if (user.user_id) {
                                userId = user.user_id;
                                userName = user.user_name || 'Unknown';
                                userUsername = user.user_username || '';
                            } else {
                                return; // Skip invalid entries
                            }
                            
                            const item = document.createElement('a');
                            item.href = '#';
                            item.className = 'list-group-item list-group-item-action';
                            item.style.cursor = 'pointer';
                            item.innerHTML = '<strong>' + userName + '</strong> <small class="text-muted">@' + userUsername + '</small>';
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                creatorUsernameInput.value = userUsername;
                                creatorIdInput.value = userId;
                                creatorResults.style.display = 'none';
                                
                                // Show selected indicator
                                const indicator = document.getElementById('creator_selected_indicator');
                                const selectedName = document.getElementById('selected_creator_name');
                                if (indicator && selectedName) {
                                    selectedName.textContent = userName + ' (@' + userUsername + ')';
                                    indicator.style.display = 'block';
                                }
                            });
                            list.appendChild(item);
                        });
                        
                        if (list.children.length > 0) {
                            creatorResults.appendChild(list);
                        } else {
                            creatorResults.style.display = 'none';
                        }
                    } else {
                        creatorResults.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error searching creators:', error);
                    creatorResults.style.display = 'none';
                });
            }, 500);
        });
    }
});
</script>
