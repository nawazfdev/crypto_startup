/**
 * Custom Request Handler
 */
var CustomRequest = {
    /**
     * Show the create custom request modal
     */
    showCreateModal: function() {
        // Use Bootstrap modal if available, otherwise use fallback
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalElement = document.getElementById('createCustomRequestModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#createCustomRequestModal').modal('show');
        } else {
            // Fallback
            const modal = document.getElementById('createCustomRequestModal');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                if (!document.querySelector('.modal-backdrop')) {
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
            }
        }
    },

    /**
     * Hide the create custom request modal
     */
    hideCreateModal: function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalElement = document.getElementById('createCustomRequestModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#createCustomRequestModal').modal('hide');
        } else {
            // Fallback
            const modal = document.getElementById('createCustomRequestModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }
        }
    },

    /**
     * Initialize custom request functionality
     */
    init: function() {
        const form = document.getElementById('createCustomRequestForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                CustomRequest.submitForm(form);
            });
        }

        // Handle modal close buttons
        const modal = document.getElementById('createCustomRequestModal');
        if (modal) {
            const closeButtons = modal.querySelectorAll('[data-dismiss="modal"], .close');
            closeButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    CustomRequest.hideCreateModal();
                });
            });
        }
    },

    /**
     * Submit the create request form
     */
    submitForm: function(form) {
        const formData = new FormData(form);
        const data = {};
        
        // Convert FormData to object
        for (let [key, value] of formData.entries()) {
            if (key === '_token') {
                continue; // Skip CSRF token, it's handled in headers
            }
            if (key === 'price' || key === 'goal_amount') {
                data[key] = value ? parseFloat(value) : null;
            } else if (key === 'deadline') {
                data[key] = value || null;
            } else if (key === 'message_id') {
                data[key] = value ? parseInt(value) : null;
            } else if (key === 'creator_id') {
                data[key] = value ? parseInt(value) : null;
            } else if (key === 'creator_username') {
                // Include creator_username if creator_id is not set
                if (!data['creator_id'] && value) {
                    data['creator_username'] = value;
                }
            } else {
                data[key] = value;
            }
        }

        // Validate required fields
        if (!data.creator_id && !data.creator_username) {
            alert('Please enter and select a creator from the search results');
            return;
        }
        
        // Warn if creator_id is not set but username is provided
        if (!data.creator_id && data.creator_username) {
            const confirmSubmit = confirm('Please select a creator from the search results. Click on a creator name to select them. Do you want to continue anyway?');
            if (!confirmSubmit) {
                return;
            }
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        fetch('/custom-requests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof launchToast !== 'undefined') {
                    launchToast('success', 'Success', data.message || 'Custom request created successfully!');
                } else {
                    alert(data.message || 'Custom request created successfully!');
                }
                
                // Reset form
                form.reset();
                
                // Hide modal
                CustomRequest.hideCreateModal();
                
                // Reload page or redirect
                setTimeout(function() {
                    window.location.href = '/custom-requests/my-requests';
                }, 1000);
            } else {
                // Show error
                if (typeof launchToast !== 'undefined') {
                    launchToast('danger', 'Error', data.message || 'Failed to create request');
                } else {
                    alert(data.message || 'Failed to create request');
                }
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof launchToast !== 'undefined') {
                launchToast('danger', 'Error', 'An error occurred. Please try again.');
            } else {
                alert('An error occurred. Please try again.');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        CustomRequest.init();
    });
} else {
    CustomRequest.init();
}
