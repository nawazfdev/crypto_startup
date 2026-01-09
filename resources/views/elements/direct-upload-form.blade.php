<!-- Simple direct file upload form -->
<div id="direct-upload-form" class="d-none">
    <form id="simple-upload-form" action="{{ route('attachment.upload', ['type' => 'post']) }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="direct-file-input" class="d-none">
    </form>
</div>

<!-- Include utility fix script -->
<script src="{{ asset('js/util-fix.js') }}"></script>

<script>
    // Global fix for jQuery and Dropzone loading issues
    (function() {
        // Check if jQuery is available
        if (typeof jQuery === 'undefined') {
            console.warn('jQuery not detected! Loading from CDN...');
            var jqScript = document.createElement('script');
            jqScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
            jqScript.onload = function() {
                console.log('jQuery loaded successfully!');
                window.$ = jQuery;
                loadDropzone();
            };
            document.head.appendChild(jqScript);
        } else {
            loadDropzone();
        }

        function loadDropzone() {
            if (typeof Dropzone === 'undefined') {
                console.warn('Dropzone not detected! Loading from source...');
                var dzScript = document.createElement('script');
                dzScript.src = '{{ asset('libs/dropzone/dist/dropzone.js') }}';
                dzScript.onload = function() {
                    console.log('Dropzone loaded successfully!');
                    Dropzone.autoDiscover = false;
                    initializeUpload();
                };
                document.head.appendChild(dzScript);
            } else {
                Dropzone.autoDiscover = false;
                initializeUpload();
            }
        }

        function initializeUpload() {
            // Wait for document to be ready
            if (document.readyState !== 'loading') {
                setupUploads();
            } else {
                document.addEventListener('DOMContentLoaded', setupUploads);
            }
        }

        function setupUploads() {
            console.log('Setting up direct file upload handlers...');
            
            // Function to trigger manual file uploads - ensure we only bind this once
            $(document).off('click', '.file-upload-button').on('click', '.file-upload-button', function(e) {
                // Make sure we only handle the click once
                if (e.target === this || $(e.target).hasClass('file-button-text') || $(e.target).is('svg') || $(e.target).is('path')) {
                    console.log('File upload button clicked');
                    
                    // Check if Dropzone is working
                    if (typeof FileUpload !== 'undefined' && FileUpload.myDropzone) {
                        e.preventDefault();
                        e.stopPropagation();
                        FileUpload.myDropzone.hiddenFileInput.click();
                    } else {
                        // Fallback to direct file input
                        e.preventDefault();
                        e.stopPropagation();
                        $('#direct-file-input').click();
                    }
                    
                    return false;
                }
            });

            // Make sure we only bind this event once
            $('#direct-file-input').off('change').on('change', function() {
                if (this.files && this.files[0]) {
                    var formData = new FormData($('#simple-upload-form')[0]);
                    
                    // Show loading indicator
                    if (typeof launchToast !== 'undefined') {
                        launchToast('info', '{{ __("Upload") }}', '{{ __("Uploading your file...") }}', 'now');
                    }
                    
                    $.ajax({
                        url: $('#simple-upload-form').attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                // Handle success
                                if (typeof launchToast !== 'undefined') {
                                    launchToast('success', '{{ __("Success") }}', '{{ __("File uploaded successfully!") }}', 'now');
                                }
                                
                                // Add to FileUpload attachments
                                if (typeof FileUpload !== 'undefined') {
                                    FileUpload.attachaments.push({
                                        attachmentID: response.attachmentID,
                                        path: response.path,
                                        type: response.type,
                                        thumbnail: response.thumbnail || response.path
                                    });
                                    
                                    // Create a mock file preview
                                    var mockFile = {
                                        name: response.attachmentID,
                                        upload: {
                                            attachmentID: response.attachmentID
                                        },
                                        type: response.type,
                                        thumbnail: response.thumbnail || response.path
                                    };
                                    
                                    // Add the file to the preview area
                                    var filePreview = $('<div class="dz-preview ml-1 mr-2 dz-processing dz-success dz-complete"></div>');
                                    $('.dropzone-previews').append(filePreview);
                                    
                                    // Add preview content based on file type
                                    if (response.type === 'image') {
                                        filePreview.append('<div class="dz-image shadow"><img src="' + (response.thumbnail || response.path) + '"/></div>');
                                    } else if (response.type === 'video') {
                                        filePreview.append('<div class="video-preview-item shadow"><video class="video-preview" controls autoplay muted loop src="' + response.path + '"></video></div>');
                                    } else if (response.type === 'audio') {
                                        filePreview.append('<div class="audio-preview-item shadow"><audio controls src="' + response.path + '"></audio></div>');
                                    } else {
                                        filePreview.append('<div class="dz-image shadow"><img src="{{ asset("img/file-icon.png") }}" style="max-width:64px;"/></div>');
                                    }
                                    
                                    // Add remove button
                                    var removeButton = $('<a class="dz-remove" href="javascript:undefined;" data-dz-remove>x</a>');
                                    removeButton.on('click', function() {
                                        if (typeof FileUpload !== 'undefined') {
                                            FileUpload.removeAttachment(response.attachmentID);
                                        }
                                        filePreview.remove();
                                    });
                                    filePreview.append(removeButton);
                                }
                            } else {
                                if (typeof launchToast !== 'undefined') {
                                    launchToast('danger', '{{ __("Error") }}', '{{ __("File upload failed.") }}', 'now');
                                } else {
                                    alert('{{ __("File upload failed.") }}');
                                }
                            }
                        },
                        error: function(xhr) {
                            console.error('Upload error:', xhr);
                            if (typeof launchToast !== 'undefined') {
                                launchToast('danger', '{{ __("Error") }}', '{{ __("File upload failed. Please try again.") }}', 'now');
                            } else {
                                alert('{{ __("File upload failed. Please try again.") }}');
                            }
                        },
                        complete: function() {
                            // Reset the file input
                            $('#direct-file-input').val('');
                        }
                    });
                }
            });
            
            // Handle save button functionality
            $(document).off('click', '.submit-button, .save-button').on('click', '.submit-button, .save-button', function(e) {
                console.log('Save button clicked');
                
                // If there's a form associated with the button, check for uploads in progress
                var $form = $(this).closest('form');
                
                if (typeof FileUpload !== 'undefined' && FileUpload.isLoading) {
                    e.preventDefault();
                    
                    if (typeof launchToast !== 'undefined') {
                        launchToast('warning', '{{ __("Please wait") }}', '{{ __("Please wait for file uploads to complete") }}');
                    } else {
                        alert('{{ __("Please wait for file uploads to complete") }}');
                    }
                    return false;
                }
                
                // Continue with default form submission
                if ($form.length && !$form.data('submitting')) {
                    $form.data('submitting', true);
                    console.log('Submitting form:', $form.attr('id'));
                }
            });
        }
    })();
</script> 