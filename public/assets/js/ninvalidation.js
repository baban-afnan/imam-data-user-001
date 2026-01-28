document.addEventListener('DOMContentLoaded', function () {
    const serviceTypeSelect = document.getElementById('service_type_select');
    const serviceFieldSelect = document.getElementById('service_field');
    const serviceTypeInput = document.getElementById('service_type');
    const ninWrapper = document.getElementById('nin_wrapper');
    const trackingWrapper = document.getElementById('tracking_wrapper');
    const priceDisplay = document.getElementById('price_display');
    const fieldDescription = document.getElementById('field-description');

    const ninInput = document.querySelector('input[name="nin"]');
    const trackingInput = document.querySelector('input[name="tracking_id"]');

    // Parse the services data from the hidden element
    const servicesDataElement = document.getElementById('service-data');
    let servicesData = [];

    if (servicesDataElement) {
        try {
            servicesData = JSON.parse(servicesDataElement.textContent);
        } catch (e) {
            console.error('Failed to parse service data', e);
        }
    }

    // When choosing service type
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            serviceTypeInput.value = selectedType;

            serviceFieldSelect.innerHTML = '<option value="">-- Choose Field --</option>';
            serviceFieldSelect.disabled = true;
            priceDisplay.textContent = '₦0.00';
            if (fieldDescription) fieldDescription.textContent = '';

            // Hide input fields by default
            ninWrapper.style.display = 'none';
            trackingWrapper.style.display = 'none';
            if (ninInput) ninInput.required = false;
            if (trackingInput) trackingInput.required = false;

            if (selectedType) {
                const filteredServices = servicesData.filter(service => service.type === selectedType);

                filteredServices.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    option.textContent = service.name;
                    option.setAttribute('data-price', service.price);
                    serviceFieldSelect.appendChild(option);
                });

                serviceFieldSelect.disabled = false;

                // Show the correct input field
                if (selectedType === 'validation') {
                    ninWrapper.style.display = 'block';
                    if (ninInput) ninInput.required = true;
                } else if (selectedType === 'ipe') {
                    trackingWrapper.style.display = 'block';
                    if (trackingInput) trackingInput.required = true;
                }
            }
        });
    }

    // When selecting specific service field
    if (serviceFieldSelect) {
        serviceFieldSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');

            if (price) {
                priceDisplay.textContent = '₦' + new Intl.NumberFormat('en-NG', {
                    minimumFractionDigits: 2
                }).format(price);
            } else {
                priceDisplay.textContent = '₦0.00';
            }
        });
    }

    /* Modern Response Modal Handling */
    const responseModal = document.getElementById('responseModal');
    if (responseModal) {
        responseModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const responseText = button.getAttribute('data-response');

            const modalBodyContent = responseModal.querySelector('#responseContent');
            const beautifulResponse = responseModal.querySelector('#beautifulResponse');
            const modalLoading = responseModal.querySelector('#responseLoading');
            const encouragement = responseModal.querySelector('#encouragement');

            if (modalLoading) modalLoading.classList.add('d-none');

            // Clear previous content
            if (beautifulResponse) beautifulResponse.innerHTML = '';
            if (modalBodyContent) {
                modalBodyContent.textContent = '';
                modalBodyContent.classList.add('d-none');
            }

            if (!responseText || responseText === 'No details available.') {
                if (modalBodyContent) {
                    modalBodyContent.textContent = 'No response details available yet.';
                    modalBodyContent.classList.remove('d-none');
                }
                return;
            }

            try {
                // Try to parse as JSON
                const data = JSON.parse(responseText);

                if (typeof data === 'object' && data !== null) {
                    let html = '<div class="response-container">';

                    for (const [key, value] of Object.entries(data)) {
                        // Skip system fields if any left
                        if (['status', 'message', 'response', 'success'].includes(key.toLowerCase())) continue;

                        const label = key.replace(/([A-Z])/g, ' $1')
                            .replace(/_/g, ' ')
                            .replace(/^\w/, c => c.toUpperCase());

                        let displayValue = value;
                        if (typeof value === 'object' && value !== null) {
                            displayValue = JSON.stringify(value, null, 2);
                        }

                        html += `
                            <div class="mb-3 pb-2 border-bottom border-light-subtle">
                                <label class="text-muted small fw-bold d-block text-uppercase mb-1" style="letter-spacing: 0.5px;">${label}</label>
                                <div class="text-dark fw-medium" style="word-break: break-word; line-height: 1.5; font-size: 0.95rem;">
                                    ${displayValue || 'N/A'}
                                </div>
                            </div>`;
                    }

                    html += '</div>';
                    if (beautifulResponse) {
                        beautifulResponse.innerHTML = html;
                        beautifulResponse.classList.remove('d-none');
                    }
                } else {
                    throw new Error('Not an object');
                }
            } catch (e) {
                // Fallback for non-JSON or legacy data
                if (modalBodyContent) {
                    modalBodyContent.textContent = responseText;
                    modalBodyContent.classList.remove('d-none');
                }
            }

            if (encouragement) encouragement.innerText = "Data verified successfully ✓";
        });
    }
});
