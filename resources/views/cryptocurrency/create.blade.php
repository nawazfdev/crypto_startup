@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-primary">🚀 Create New Token</h1>
            <p class="lead text-muted">Launch your own cryptocurrency token on the blockchain</p>
        </div>
    </div>
    
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form action="{{ route('cryptocurrency.store') }}" method="POST" enctype="multipart/form-data" id="tokenForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📝 Basic Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Token Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" 
                                       placeholder="e.g., Bitcoin, Ethereum" value="{{ old('name') }}" required>
                                <small class="text-muted">The full name of your cryptocurrency</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Token Symbol <span class="text-danger">*</span></label>
                                <input type="text" name="symbol" id="symbol" class="form-control form-control-lg" 
                                       placeholder="e.g., BTC, ETH" value="{{ old('symbol') }}" maxlength="10" required>
                                <small class="text-muted">3-10 characters, uppercase only</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-control" 
                                      placeholder="Describe your token's purpose and unique features..." required>{{ old('description') }}</textarea>
                            <div class="d-flex justify-between mt-1">
                                <small class="text-muted">Minimum 50 characters</small>
                                <small id="charCount" class="text-muted">0/2000</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Token Logo</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="logo-preview-container">
                                        <img id="logoPreview" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Ccircle cx='30' cy='30' r='30' fill='%23f8f9fa'/%3E%3Ctext x='30' y='38' text-anchor='middle' font-size='24'%3E🪙%3C/text%3E%3C/svg%3E" 
                                             alt="Logo Preview" class="logo-preview">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                                        <small class="text-muted">Max 2MB, JPG/PNG/GIF/SVG</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Website URL</label>
                                <input type="url" name="website" class="form-control" 
                                       placeholder="https://yourtoken.com" value="{{ old('website') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Whitepaper URL</label>
                            <input type="url" name="whitepaper" class="form-control" 
                                   placeholder="https://yourtoken.com/whitepaper.pdf" value="{{ old('whitepaper') }}">
                        </div>
                    </div>
                </div>

                <!-- Token Configuration -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">⚙️ Token Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Blockchain Network <span class="text-danger">*</span></label>
                                <select name="blockchain_network" class="form-select form-select-lg" required>
                                    <option value="ethereum">Ethereum (ETH) - Most Popular</option>
                                    <option value="binance">Binance Smart Chain (BSC) - Low Fees</option>
                                    <option value="polygon">Polygon (MATIC) - Fast & Cheap</option>
                                    <option value="solana">Solana (SOL) - High Performance</option>
                                    <option value="avalanche">Avalanche (AVAX) - Fast Finality</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Token Type <span class="text-danger">*</span></label>
                                <select name="token_type" class="form-select form-select-lg" required>
                                    <option value="utility">Utility Token - Platform usage</option>
                                    <option value="security">Security Token - Investment</option>
                                    <option value="governance">Governance Token - Voting rights</option>
                                    <option value="payment">Payment Token - Currency</option>
                                    <option value="nft">NFT Collection - Collectibles</option>
                                    <option value="defi">DeFi Token - Finance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supply & Economics -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">💰 Supply & Economics</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Initial Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="initial_price" id="initialPrice" class="form-control form-control-lg" 
                                           value="0.001" step="0.00000001" min="0.00000001" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Total Supply <span class="text-danger">*</span></label>
                                <input type="number" name="total_supply" id="totalSupply" class="form-control form-control-lg" 
                                       value="1000000" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Creator Allocation</label>
                                <input type="number" name="creator_allocation" id="creatorAllocation" class="form-control form-control-lg" 
                                       value="100000" min="0">
                                <small class="text-muted">Tokens you'll receive initially</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Market Cap</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" id="marketCap" class="form-control form-control-lg bg-light" readonly>
                                </div>
                                <small class="text-muted">Total Supply × Initial Price</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Structure -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">💸 Fee Structure</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Creator Fee (%)</label>
                                <div class="input-group">
                                    <input type="number" name="creator_fee_percentage" class="form-control" 
                                           value="5" step="0.01" min="0" max="20">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Platform Fee (%)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" value="2.50" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Liquidity Pool (%)</label>
                                <div class="input-group">
                                    <input type="number" name="liquidity_pool_percentage" class="form-control" 
                                           value="20" step="0.01" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Token Features -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">🔧 Token Features</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="feature-option p-3 border rounded">
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_burning" value="1" id="burning" class="form-check-input" style="width: 20px; height: 20px;">
                                        <label class="form-check-label fw-semibold" for="burning">
                                            🔥 Token Burning
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Allow permanent token destruction</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="feature-option p-3 border rounded">
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_minting" value="1" id="minting" class="form-check-input" style="width: 20px; height: 20px;">
                                        <label class="form-check-label fw-semibold" for="minting">
                                            ⚒️ Token Minting
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Allow new token creation</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="feature-option p-3 border rounded">
                                    <div class="form-check">
                                        <input type="checkbox" name="transferable" value="1" id="transfers" class="form-check-input" style="width: 20px; height: 20px;" checked>
                                        <label class="form-check-label fw-semibold" for="transfers">
                                            🔄 Allow Transfers
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Enable token transfers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Submit -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="form-check mb-4">
                            <input type="checkbox" id="terms" class="form-check-input" style="width: 20px; height: 20px;" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and understand the risks involved in token creation.
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <span id="submitSpinner" class="spinner-border spinner-border-sm me-2 d-none"></span>
                                🚀 CREATE TOKEN
                            </button>
                            <a href="{{ route('cryptocurrency.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            @if(isset($userTokenCount) && isset($maxTokensPerUser))
            <!-- Token Limit -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">🎯 Token Limit</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Created:</span>
                        <strong>{{ $userTokenCount }}/{{ $maxTokensPerUser }}</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: {{ ($userTokenCount / $maxTokensPerUser) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $maxTokensPerUser - $userTokenCount }} tokens remaining</small>
                </div>
            </div>
            @endif
            
            <!-- Summary -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">📊 Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="h6 mb-0" id="summaryMarketCap">$0.00</div>
                                <small class="text-muted">Market Cap</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="h6 mb-0" id="summaryAllocation">0</div>
                                <small class="text-muted">Your Tokens</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="h6 mb-0" id="summaryAvailable">0</div>
                                <small class="text-muted">For Sale</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="h6 mb-0" id="summaryTotal">0</div>
                                <small class="text-muted">Total Supply</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">💡 Information</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">After creating your token:</p>
                    <ul class="small mb-3 ps-3">
                        <li>List on marketplace</li>
                        <li>Build community</li>
                        <li>Track performance</li>
                        <li>Manage economics</li>
                    </ul>
                    <div class="alert alert-info small p-2 mb-0">
                        <strong>💡 Tip:</strong> Clear description attracts more users!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.logo-preview-container {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #dee2e6;
    background: #f8f9fa;
}

.logo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.logo-preview:hover {
    transform: scale(1.1);
}

.feature-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.feature-option:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9ff;
}

.feature-option.selected {
    border-color: #0d6efd !important;
    background-color: #f8f9ff;
}

.card {
    border-radius: 12px;
}

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    border: none;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0b5ed7, #5a0dcf);
    transform: translateY(-1px);
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.input-group-text {
    min-width: 45px;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const symbolInput = document.getElementById('symbol');
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const initialPrice = document.getElementById('initialPrice');
    const totalSupply = document.getElementById('totalSupply');
    const creatorAllocation = document.getElementById('creatorAllocation');
    const marketCap = document.getElementById('marketCap');
    const form = document.getElementById('tokenForm');
    
    // Summary elements
    const summaryMarketCap = document.getElementById('summaryMarketCap');
    const summaryAllocation = document.getElementById('summaryAllocation');
    const summaryAvailable = document.getElementById('summaryAvailable');
    const summaryTotal = document.getElementById('summaryTotal');
    
    // Logo preview
    logoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Symbol uppercase
    symbolInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
    
    // Character count
    descriptionInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length + '/2000';
        charCount.className = length < 50 ? 'text-danger' : length > 1800 ? 'text-warning' : 'text-success';
    });
    
    // Calculate values
    function updateCalculations() {
        const price = parseFloat(initialPrice.value) || 0;
        const supply = parseFloat(totalSupply.value) || 0;
        const allocation = parseFloat(creatorAllocation.value) || 0;
        const available = supply - allocation;
        const cap = price * supply;
        
        marketCap.value = cap.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        summaryMarketCap.textContent = '$' + cap.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        summaryAllocation.textContent = allocation.toLocaleString();
        summaryAvailable.textContent = available.toLocaleString();
        summaryTotal.textContent = supply.toLocaleString();
    }
    
    initialPrice.addEventListener('input', updateCalculations);
    totalSupply.addEventListener('input', updateCalculations);
    creatorAllocation.addEventListener('input', updateCalculations);
    
    // Feature options
    document.querySelectorAll('.feature-option').forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        
        option.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                checkbox.checked = !checkbox.checked;
            }
            option.classList.toggle('selected', checkbox.checked);
        });
        
        checkbox.addEventListener('change', function() {
            option.classList.toggle('selected', this.checked);
        });
        
        // Initialize
        option.classList.toggle('selected', checkbox.checked);
    });
    
    // Form submission
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('submitSpinner');
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Token...';
    });
    
    // Initialize
    updateCalculations();
    descriptionInput.dispatchEvent(new Event('input'));
});
</script>
@endsection