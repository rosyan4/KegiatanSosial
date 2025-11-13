// resources/js/admin-settings.js
class AdminSettings {
    constructor() {
        this.initializeEventListeners();
        this.checkUnsavedChanges();
    }

    initializeEventListeners() {
        // Group navigation
        document.querySelectorAll('.setting-group-link').forEach(link => {
            link.addEventListener('click', (e) => {
                this.handleGroupNavigation(e);
            });
        });

        // Setting changes
        document.querySelectorAll('.setting-input').forEach(input => {
            input.addEventListener('change', () => {
                this.handleSettingChange(input);
            });
        });

        // Save button
        document.getElementById('save-settings')?.addEventListener('click', () => {
            this.saveSettings();
        });

        // Before unload warning
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }

    handleGroupNavigation(e) {
        const target = e.target.closest('a');
        const group = target.getAttribute('href').replace('#group-', '');
        
        // Update active state
        document.querySelectorAll('.setting-group-link').forEach(link => {
            link.classList.remove('active');
        });
        target.classList.add('active');
        
        // Update title
        this.updateGroupTitle(target.textContent.trim());
    }

    updateGroupTitle(title) {
        const titleElement = document.getElementById('current-group-title');
        if (titleElement) {
            titleElement.textContent = title;
        }
    }

    handleSettingChange(input) {
        const card = input.closest('.setting-card');
        const originalValue = input.getAttribute('data-original-value');
        const currentValue = this.getInputValue(input);

        if (currentValue !== originalValue) {
            card.classList.add('changed');
        } else {
            card.classList.remove('changed');
        }

        this.toggleSaveButton();
    }

    getInputValue(input) {
        switch (input.type) {
            case 'checkbox':
                return input.checked ? '1' : '0';
            case 'select-one':
                return input.value;
            default:
                return input.value;
        }
    }

    hasUnsavedChanges() {
        return document.querySelectorAll('.setting-card.changed').length > 0;
    }

    toggleSaveButton() {
        const saveButtonContainer = document.getElementById('save-button-container');
        if (this.hasUnsavedChanges()) {
            saveButtonContainer?.classList.remove('d-none');
        } else {
            saveButtonContainer?.classList.add('d-none');
        }
    }

    async saveSettings() {
        const form = document.getElementById('settings-form');
        const saveButton = document.getElementById('save-settings');
        
        if (!form || !saveButton) return;

        // Show loading state
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.showAlert(result.message || 'Pengaturan berhasil disimpan!', 'success');
                
                // Update original values
                this.updateOriginalValues();
                this.toggleSaveButton();
            } else {
                throw new Error('Gagal menyimpan pengaturan');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Gagal menyimpan pengaturan: ' + error.message, 'danger');
        } finally {
            // Reset button
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';
        }
    }

    updateOriginalValues() {
        document.querySelectorAll('.setting-input').forEach(input => {
            const currentValue = this.getInputValue(input);
            input.setAttribute('data-original-value', currentValue);
            input.closest('.setting-card').classList.remove('changed');
        });
    }

    showAlert(message, type) {
        // Remove existing alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => alert.remove());

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.insertBefore(alert, cardBody.firstChild);
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    new AdminSettings();
});