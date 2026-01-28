document.addEventListener('DOMContentLoaded', function () {
    const commentModal = document.getElementById('commentModal');
    const fieldSelect = document.getElementById('service_field');
    const fieldDescription = document.getElementById('field-description');
    const fieldPrice = document.getElementById('field-price');
    const affidavitSelect = document.getElementById('affidavit');
    const affidavitWrapper = document.getElementById('affidavit_upload_wrapper');
    const totalAmountEl = document.getElementById('total-amount');
    const feeBreakdown = document.getElementById('fee-breakdown');
    const bankSelect = document.getElementById('enrolment_bank');

    /*========================
     COMMENT MODAL HANDLER
    ========================*/
    if (commentModal) {
        commentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const comment = button.getAttribute('data-comment') || 'No comment yet.';
            const modalBody = document.getElementById('commentModalBody');
            if (modalBody) modalBody.innerText = comment.trim();
        });
    }

    /*========================
     AFFIDAVIT TOGGLE
    ========================*/
    if (affidavitSelect && affidavitWrapper) {
        affidavitSelect.addEventListener('change', function () {
            const value = this.value;
            const fileInput = affidavitWrapper.querySelector('input');
            affidavitWrapper.style.display = value === 'available' ? 'block' : 'none';
            if (fileInput) fileInput.required = value === 'available';
            calculateTotalAmount();
        });
    }

    /*========================
     BANK FIELD AJAX LOADER
    ========================*/
    if (bankSelect) {
        bankSelect.addEventListener('change', function () {
            const bankId = this.value;
            if (!fieldSelect) return;

            fieldSelect.innerHTML = '<option value="">Loading...</option>';
            fieldDescription.textContent = '';
            fieldPrice.textContent = '₦0.00';
            calculateTotalAmount();

            if (!bankId) {
                fieldSelect.innerHTML = '<option value="">-- Select Field --</option>';
                return;
            }

            fetch(`/modification-fields/${bankId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    fieldSelect.innerHTML = '<option value="">-- Select Field --</option>';
                    data.forEach(field => {
                        const formattedPrice = parseFloat(field.price).toLocaleString('en-NG', {
                            style: 'currency',
                            currency: 'NGN'
                        });
                        fieldSelect.innerHTML += `
                            <option value="${field.id}" 
                                    data-description="${field.description}" 
                                    data-price="${field.price}">
                                ${field.field_name} - ${formattedPrice}
                            </option>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    fieldSelect.innerHTML = '<option value="">Error loading fields</option>';
                });
        });
    }

    /*========================
     FIELD DESCRIPTION & PRICE
    ========================*/
    if (fieldSelect) {
        fieldSelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const desc = option.getAttribute('data-description') || '';
            const price = parseFloat(option.getAttribute('data-price') || 0);

            fieldDescription.textContent = desc;
            fieldPrice.textContent = '₦' + price.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            calculateTotalAmount();
        });
    }

    /*========================
     TOTAL AMOUNT CALCULATOR
    ========================*/
    function calculateTotalAmount() {
        if (!fieldPrice) return;

        const fieldPriceValue = parseFloat(fieldPrice.textContent.replace(/[^\d.]/g, '')) || 0;
        const affidavitFee = affidavitSelect && affidavitSelect.value === 'not_available' ? 2000 : 0;
        const total = fieldPriceValue + affidavitFee;

        if (totalAmountEl)
            totalAmountEl.textContent = '₦' + total.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        if (feeBreakdown) {
            if (fieldPriceValue > 0) {
                let breakdownText = `Service: ₦${fieldPriceValue.toLocaleString('en-NG', { minimumFractionDigits: 2 })}`;
                if (affidavitFee > 0) {
                    breakdownText += ` + Affidavit: ₦${affidavitFee.toLocaleString('en-NG', { minimumFractionDigits: 2 })}`;
                }
                feeBreakdown.textContent = breakdownText;
            } else {
                feeBreakdown.textContent = '';
            }
        }
    }

    /*========================
     INITIALIZATION ON LOAD
    ========================*/
    if (affidavitSelect && affidavitSelect.value === 'available') {
        affidavitWrapper.style.display = 'block';
    }

    if (bankSelect && bankSelect.value) {
        bankSelect.dispatchEvent(new Event('change'));
    }

    if (fieldSelect && fieldSelect.value) {
        fieldSelect.dispatchEvent(new Event('change'));
    }

    calculateTotalAmount();

    /*========================
     PHONE VALIDATION FORM FEE HANDLER (from new script)
    ========================*/
    const serviceSelect = document.getElementById('service_field');
    const priceDisplay = document.getElementById('field-price');
    const descDisplay = document.getElementById('field-description');

    if (serviceSelect) {
        serviceSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const price = selected.getAttribute('data-price') || 0;
            const desc = selected.getAttribute('data-description') || '';

            priceDisplay.textContent = '₦' + Number(price).toLocaleString(undefined, { minimumFractionDigits: 2 });
            descDisplay.textContent = desc;
        });

        // Initialize on load if a value is already selected
        if (serviceSelect.value) {
            const event = new Event('change');
            serviceSelect.dispatchEvent(event);
        }
    }
});

