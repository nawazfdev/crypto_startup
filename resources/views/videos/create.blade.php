@extends('layouts.generic')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Upload Video</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="/videos/videos" enctype="multipart/form-data" id="videoUploadForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required 
                                   placeholder="Enter video title" maxlength="191">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Tell people about your video..." maxlength="1000">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="description-count">0</span>/1000 characters
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="video" class="form-label">Video File *</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" 
                                   id="video" name="video" accept="video/mp4,video/webm,video/mov,video/avi" required>
                            @error('video')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Maximum file size: 20MB. Supported formats: MP4, WebM, MOV, AVI
                            </small>
                            <div id="video-preview" style="display: none;" class="mt-2">
                                <video controls style="max-width: 100%; height: 200px;">
                                    <source id="video-source" src="" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail (Optional)</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('thumbnail')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Maximum file size: 5MB. Supported formats: JPG, PNG, GIF
                            </small>
                            <div id="thumbnail-preview" style="display: none;" class="mt-2">
                                <img id="thumbnail-image" src="" alt="Thumbnail preview" style="max-width: 200px; height: auto;">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tags" class="form-label">Tags (Optional)</label>
                            <input type="text" class="form-control" 
                                   id="tags" name="tags" value="{{ old('tags') }}" 
                                   placeholder="Enter tags separated by commas">
                            <small class="form-text text-muted">
                                Add relevant tags to help people discover your video
                            </small>
                        </div>

                        <div class="form-group d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-upload"></i> Upload Video
                            </button>
                            <a href="{{ route('videos.reels') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Reels
                            </a>
                        </div>

                        <!-- Upload Progress -->
                        <div id="upload-progress" style="display: none;" class="mt-3">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">Uploading video...</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    border-bottom: none;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e1e5e9;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.alert {
    border-radius: 10px;
    border: none;
}

.invalid-feedback {
    font-size: 0.875rem;
}

.progress {
    height: 6px;
    border-radius: 3px;
}

#video-preview video,
#thumbnail-preview img {
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.file-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.875rem;
}

.file-info i {
    color: #28a745;
    margin-right: 5px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('videoUploadForm');
    const videoInput = document.getElementById('video');
    const thumbnailInput = document.getElementById('thumbnail');
    const submitBtn = document.getElementById('submitBtn');
    const descriptionTextarea = document.getElementById('description');
    const descriptionCount = document.getElementById('description-count');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    
    // Character counter for description
    function updateDescriptionCount() {
        const count = descriptionTextarea.value.length;
        descriptionCount.textContent = count;
        if (count > 1000) {
            descriptionCount.style.color = '#dc3545';
        } else {
            descriptionCount.style.color = '#6c757d';
        }
    }
    
    descriptionTextarea.addEventListener('input', updateDescriptionCount);
    updateDescriptionCount(); // Initial count
    
    // Video file validation and preview
    videoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 20 * 1024 * 1024; // 20MB in bytes
            
            // Remove existing file info
            const existingInfo = this.parentElement.querySelector('.file-info');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            if (file.size > maxSize) {
                alert('File size must be less than 20MB');
                this.value = '';
                document.getElementById('video-preview').style.display = 'none';
                return;
            }
            
            // Show file info
            const fileInfo = document.createElement('div');
            fileInfo.className = 'file-info';
            fileInfo.innerHTML = `
                <i class="fas fa-file-video"></i> 
                ${file.name} (${(file.size / (1024*1024)).toFixed(2)} MB)
            `;
            this.parentElement.appendChild(fileInfo);
            
            // Show video preview
            const videoPreview = document.getElementById('video-preview');
            const videoSource = document.getElementById('video-source');
            const url = URL.createObjectURL(file);
            videoSource.src = url;
            videoPreview.style.display = 'block';
            
            // Clean up object URL when video loads
            videoSource.parentElement.addEventListener('loadeddata', function() {
                URL.revokeObjectURL(url);
            });
        } else {
            document.getElementById('video-preview').style.display = 'none';
        }
    });
    
    // Thumbnail file validation and preview
    thumbnailInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            
            if (file.size > maxSize) {
                alert('Thumbnail size must be less than 5MB');
                this.value = '';
                document.getElementById('thumbnail-preview').style.display = 'none';
                return;
            }
            
            // Show thumbnail preview
            const thumbnailPreview = document.getElementById('thumbnail-preview');
            const thumbnailImage = document.getElementById('thumbnail-image');
            const url = URL.createObjectURL(file);
            thumbnailImage.src = url;
            thumbnailPreview.style.display = 'block';
            
            // Clean up object URL when image loads
            thumbnailImage.addEventListener('load', function() {
                URL.revokeObjectURL(url);
            });
        } else {
            document.getElementById('thumbnail-preview').style.display = 'none';
        }
    });
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        // Basic validation
        const title = document.getElementById('title').value.trim();
        const video = videoInput.files[0];
        
        if (!title) {
            alert('Please enter a video title');
            e.preventDefault();
            return;
        }
        
        if (!video) {
            alert('Please select a video file');
            e.preventDefault();
            return;
        }
        
        // Disable submit button and show progress
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        uploadProgress.style.display = 'block';
        
        // Simulate progress (since we can't track real upload progress with standard form)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 500);
        
        // Clear progress simulation after form submits
        setTimeout(() => {
            clearInterval(progressInterval);
        }, 1000);
    });
    
    // Prevent form resubmission
    let formSubmitted = false;
    form.addEventListener('submit', function(e) {
        if (formSubmitted) {
            e.preventDefault();
            return false;
        }
        formSubmitted = true;
    });
});
</script>
@endpush
@endsection