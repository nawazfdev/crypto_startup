@extends('layouts.generic')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-plus-circle"></i> Create NFT</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form id="create-nft-form" action="{{ route('nft.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">NFT Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Image <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" required>
                                <label class="custom-file-label" for="image">Choose image...</label>
                            </div>
                            <small class="form-text text-muted">Max size: 10MB. Recommended: 1000x1000px</small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (ETH) <span class="text-danger">*</span></label>
                            <input type="number" step="0.0001" min="0.0001" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            <small class="form-text text-muted">
                                Listing fee: <span id="listing-fee">0.0025</span> ETH
                            </small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="wallet_address">Wallet Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('wallet_address') is-invalid @enderror" 
                                       id="wallet_address" name="wallet_address" 
                                       value="{{ old('wallet_address') }}" 
                                       placeholder="0x..." required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary" id="connect-wallet-btn">
                                        <i class="fab fa-ethereum"></i> Connect MetaMask
                                    </button>
                                </div>
                            </div>
                            @error('wallet_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> After submitting, you'll need to confirm the transaction in MetaMask to complete the NFT creation.
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-magic"></i> Create NFT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/web3.js') }}"></script>
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Connect MetaMask
    document.getElementById('connect-wallet-btn').addEventListener('click', async function() {
        if (typeof window.ethereum !== 'undefined') {
            try {
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                if (accounts.length > 0) {
                    document.getElementById('wallet_address').value = accounts[0];
                    this.innerHTML = '<i class="fas fa-check"></i> Connected';
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-success');
                }
            } catch (error) {
                alert('Error connecting to MetaMask: ' + error.message);
            }
        } else {
            alert('MetaMask is not installed. Please install MetaMask to continue.');
        }
    });

    // Get listing fee
    fetch('{{ route("nft.api.listing-price") }}')
        .then(response => response.json())
        .then(data => {
            if (data.listing_price) {
                document.getElementById('listing-fee').textContent = data.listing_price;
            }
        });
</script>
@endsection

