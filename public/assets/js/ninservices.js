document.addEventListener('DOMContentLoaded', function () {
    const serviceField = document.getElementById('service_field');
    const fieldPrice = document.getElementById('field-price');
    const fieldDescription = document.getElementById('field-description');

    const genericDataInfo = document.getElementById('generic-data-info');
    const genericInput = document.getElementById('description-field');
    const dobWizard = document.getElementById('dob-wizard');

    const genericSubmitBtn = document.getElementById('generic-submit-btn');
    const dobProceedBtn = document.getElementById('dob-proceed-btn');
    const proceedAttestationBtn = document.getElementById('proceed-attestation-btn');

    if (!serviceField) return;

    // Handle Field Change
    serviceField.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const description = selectedOption.getAttribute('data-description');
        const fieldName = selectedOption.textContent.toLowerCase();

        // Update Price
        if (fieldPrice) {
            fieldPrice.textContent = price ? '₦' + new Intl.NumberFormat('en-NG').format(price) : '₦0.00';
        }

        // Update Description
        if (fieldDescription) {
            fieldDescription.textContent = description || '';
        }

        // Toggle Form Mode
        if (fieldName.includes('date of birth') || fieldName.includes('dob')) {
            // DOB Mode
            if (genericDataInfo) genericDataInfo.classList.add('d-none');
            if (genericInput) genericInput.removeAttribute('required');

            // Show Proceed Button, Hide Submit Button
            if (genericSubmitBtn) genericSubmitBtn.classList.add('d-none');
            if (dobProceedBtn) dobProceedBtn.classList.remove('d-none');

            // Hide Wizard initially (Wait for Proceed)
            if (dobWizard) dobWizard.classList.add('d-none');

        } else {
            // Generic Mode
            if (genericDataInfo) genericDataInfo.classList.remove('d-none');
            if (genericInput) genericInput.setAttribute('required', 'required');

            // Show Submit Button, Hide Proceed Button
            if (genericSubmitBtn) genericSubmitBtn.classList.remove('d-none');
            if (dobProceedBtn) dobProceedBtn.classList.add('d-none');

            // Hide Wizard
            if (dobWizard) dobWizard.classList.add('d-none');
            disableWizardInputs();
        }
    });

    // Proceed Button Click
    if (proceedAttestationBtn) {
        proceedAttestationBtn.addEventListener('click', function () {
            // Check if NIN is entered
            const ninInput = document.querySelector('input[name="nin"]');
            if (!ninInput.value || ninInput.value.length !== 11) {
                alert('Please enter a valid 11-digit NIN first.');
                ninInput.focus();
                return;
            }

            // Show Wizard
            if (dobWizard) {
                dobWizard.classList.remove('d-none');
                enableWizardInputs();
            }

            // Hide Proceed Button (User is now in wizard)
            if (dobProceedBtn) dobProceedBtn.classList.add('d-none');

            // Reset Wizard
            document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
            const step1 = document.getElementById('step-1');
            if (step1) step1.classList.remove('d-none');
            updateProgressBar(1);
        });
    }

    function enableWizardInputs() {
        document.querySelectorAll('.dob-input').forEach(input => {
            if (!input.name.includes('middle_name') && !input.name.includes('middlename')) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
            input.removeAttribute('disabled');
        });
    }

    function disableWizardInputs() {
        document.querySelectorAll('.dob-input').forEach(input => {
            input.removeAttribute('required');
            input.setAttribute('disabled', 'disabled');
        });
    }

    // Wizard Navigation
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = this.closest('.wizard-step');
            const nextStepId = this.getAttribute('data-next');
            const stepNumber = parseInt(nextStepId.split('-')[1]);

            // Validation
            const inputs = currentStep.querySelectorAll('input, select');
            let valid = true;
            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (valid) {
                currentStep.classList.add('d-none');
                const nextStep = document.getElementById(nextStepId);
                if (nextStep) {
                    nextStep.classList.remove('d-none');
                    updateProgressBar(stepNumber);
                }
            } else {
                alert('Please fill all required fields in this step.');
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = this.closest('.wizard-step');
            const prevStepId = this.getAttribute('data-prev');
            const stepNumber = parseInt(prevStepId.split('-')[1]);

            currentStep.classList.add('d-none');
            const prevStep = document.getElementById(prevStepId);
            if (prevStep) {
                prevStep.classList.remove('d-none');
                updateProgressBar(stepNumber);
            }
        });
    });

    function updateProgressBar(step) {
        const progress = (step / 8) * 100;
        const progressBar = document.getElementById('wizard-progress');
        const stepBadge = document.getElementById('step-badge');

        if (progressBar) {
            progressBar.style.width = progress + '%';
            progressBar.textContent = 'Step ' + step + ' of 8';
        }
        if (stepBadge) {
            stepBadge.textContent = 'Step ' + step + ' of 8';
        }
    }
});
