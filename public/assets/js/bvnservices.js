document.addEventListener('DOMContentLoaded', function () {
    const bankSelect = document.getElementById('enrolment_bank');
    const fieldSelect = document.getElementById('service_field');
    const fieldDescription = document.getElementById('field-description');
    const fieldPriceDisplay = document.getElementById('field-price'); // Shows modification fee
    const affidavitSelect = document.getElementById('affidavit');
    const affidavitUploadWrapper = document.getElementById('affidavit_upload_wrapper');
    const totalAmountDisplay = document.getElementById('total-amount');
    const feeBreakdown = document.getElementById('fee-breakdown');

    let modificationFee = 0;
    let affidavitFee = 0; // This will trigger only if affidavit is NOT available

    // Modification field loading
    if (bankSelect) {
        bankSelect.addEventListener('change', function () {
            const bankId = this.value;
            fieldSelect.innerHTML = '<option value="">Loading...</option>';

            if (bankId) {
                // Fixed URL to match web.php route: /modification-fields/{serviceId}
                fetch(`/modification-fields/${bankId}`)
                    .then(response => response.json())
                    .then(data => {
                        fieldSelect.innerHTML = '<option value="">-- Select Field --</option>';
                        data.forEach(field => {
                            const option = document.createElement('option');
                            option.value = field.id;
                            option.textContent = `${field.field_name} - ₦${new Intl.NumberFormat().format(field.price)}`;
                            option.dataset.price = field.price;
                            option.dataset.description = field.description;
                            fieldSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        fieldSelect.innerHTML = '<option value="">Error loading fields</option>';
                    });
            } else {
                fieldSelect.innerHTML = '<option value="">-- Select Field --</option>';
                resetFields();
            }
        });
    }

    if (fieldSelect) {
        fieldSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                modificationFee = parseFloat(selected.dataset.price);
                fieldDescription.textContent = selected.dataset.description || '';
                fieldPriceDisplay.textContent = '₦' + new Intl.NumberFormat().format(modificationFee);
            } else {
                resetFields();
            }
            calculateTotal();
        });
    }

    if (affidavitSelect) {
        affidavitSelect.addEventListener('change', function () {
            const price = parseFloat(this.getAttribute('data-price')) || 0;
            if (this.value === 'not_available') {
                affidavitUploadWrapper.style.display = 'none';
                affidavitFee = price;
            } else if (this.value === 'available') {
                affidavitUploadWrapper.style.display = 'block';
                affidavitFee = 0;
            } else {
                affidavitUploadWrapper.style.display = 'none';
                affidavitFee = 0;
            }
            calculateTotal();
        });
    }

    function calculateTotal() {
        const total = modificationFee + affidavitFee;
        if (totalAmountDisplay) {
            totalAmountDisplay.textContent = '₦' + new Intl.NumberFormat().format(total);
        }

        if (feeBreakdown) {
            let breakdown = `Modification: ₦${new Intl.NumberFormat().format(modificationFee)}`;
            if (affidavitFee > 0) {
                breakdown += ` + Affidavit: ₦${new Intl.NumberFormat().format(affidavitFee)}`;
            }
            feeBreakdown.textContent = breakdown;
        }
    }

    function resetFields() {
        modificationFee = 0;
        if (fieldDescription) fieldDescription.textContent = '';
        if (fieldPriceDisplay) fieldPriceDisplay.textContent = '₦0.00';
    }

    // CRM logic
    const crmServiceField = document.getElementById('crm_service_field');
    const crmFieldPrice = document.getElementById('crm-field-price');
    const crmFieldDescription = document.getElementById('crm-field-description');

    if (crmServiceField) {
        crmServiceField.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const description = selectedOption.getAttribute('data-description');

            if (crmFieldPrice) {
                crmFieldPrice.textContent = price ? '₦' + new Intl.NumberFormat().format(price) : '₦0.00';
            }

            if (crmFieldDescription) {
                crmFieldDescription.textContent = description || '';
            }
        });
    }

    // Phone Search logic
    const phoneServiceField = document.getElementById('phone_service_field');
    const phoneFieldPrice = document.getElementById('phone-field-price');
    const phoneFieldDescription = document.getElementById('phone-field-description');

    if (phoneServiceField) {
        phoneServiceField.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const description = selectedOption.getAttribute('data-description');

            if (phoneFieldPrice) {
                phoneFieldPrice.textContent = price ? '₦' + new Intl.NumberFormat().format(price) : '₦0.00';
            }

            if (phoneFieldDescription) {
                phoneFieldDescription.textContent = description || '';
            }
        });
    }
});
