$(document).ready(function () {
    // When network is selected, fetch available bundles
    $("#service_id").change(function () {
        const serviceId = $(this).val();

        // Clear bundle and amount fields
        $("#bundle").empty().append('<option value="">Choose Bundle...</option>');
        $("#amountToPay").val('');

        if (!serviceId) {
            return;
        }

        // Show loading state
        $("#bundle").append('<option value="">Loading bundles...</option>');

        $.ajax({
            type: "GET",
            url: "/fetch-data-bundles",
            data: { id: serviceId },
            dataType: "json",
            success: function (response) {
                $("#bundle").empty();
                $("#bundle").append('<option value="">Choose Bundle...</option>');

                if (response && response.length > 0) {
                    for (let i = 0; i < response.length; i++) {
                        const code = response[i]["variation_code"];
                        const name = response[i]["name"];

                        $("#bundle").append(
                            '<option value="' + code + '">' + name + '</option>'
                        );
                    }
                } else {
                    $("#bundle").append('<option value="">No bundles available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching bundles:", error);
                $("#bundle").empty();
                $("#bundle").append('<option value="">Error loading bundles</option>');
            }
        });
    });

    // When bundle is selected, fetch the price
    $("#bundle").change(function () {
        const bundle = $(this).val();

        // Clear amount field
        $("#amountToPay").val('');

        if (!bundle) {
            return;
        }

        // Show loading state
        $("#amountToPay").val('Loading...');

        $.ajax({
            type: "GET",
            url: "/fetch-data-bundles-price",
            data: { id: bundle },
            dataType: "json",
            success: function (response) {
                // Response is already formatted with number_format in controller
                $("#amountToPay").val("₦" + response);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching price:", error);
                $("#amountToPay").val('Error');
            }
        });
    });

    // Phone number validation
    window.validateNumber = function () {
        const input = document.getElementById('mobileno');
        const result = document.getElementById('networkResult');
        const value = input.value.replace(/\D/g, ''); // Remove non-digits

        input.value = value; // Update input with digits only

        if (value.length >= 4) {
            const prefix = value.substring(0, 4);
            let network = '';

            // MTN prefixes
            if (['0803', '0806', '0703', '0706', '0813', '0816', '0810', '0814', '0903', '0906', '0913'].includes(prefix)) {
                network = 'MTN';
            }
            // Airtel prefixes
            else if (['0802', '0808', '0708', '0812', '0701', '0902', '0907', '0901'].includes(prefix)) {
                network = 'Airtel';
            }
            // Glo prefixes
            else if (['0805', '0807', '0705', '0815', '0811', '0905'].includes(prefix)) {
                network = 'Glo';
            }
            // 9mobile prefixes
            else if (['0809', '0817', '0818', '0909', '0908'].includes(prefix)) {
                network = '9mobile';
            }

            if (network) {
                result.textContent = '✓ ' + network + ' Network';
                result.className = 'text-success small';
            } else {
                result.textContent = 'Unknown network';
                result.className = 'text-warning small';
            }
        } else {
            result.textContent = '';
        }
    };
});
