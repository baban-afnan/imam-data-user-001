$(document).ready(function () {
    $("#service_id").change(function () {
        $service_id = $(this).val();
        $.ajax({
            //create an ajax request to get session data
            type: "get",
            url: "fetch-data-bundles",
            data: { id: $service_id },
            dataType: "json", //expect json File to be returned
            success: function (response) {
                var len = response.length;
                $("#type").empty();
                $("#type").append(
                    "<option value=''>" + "Choose Type..." + "</option>"
                );

                for (var i = 0; i < len; i++) {
                    var code = response[i]["variation_code"];
                    var name = response[i]["name"];

                    $("#type").append(
                        "<option value='" + code + "'>" + name + "</option>"
                    );
                }
            },
            error: function (data) {
                //Do Nothing
            },
        });
    });

    //Get amount
    $("#type").change(function () {
        $type = $(this).val();
        $.ajax({
            //create an ajax request to get session data
            type: "get",
            url: "fetch-data-bundles-price",
            data: { id: $type },
            dataType: "json", //expect json File to be returned
            success: function (response) {
                $("#amountToPay").val("₦ " + response);
            },
            error: function (data) {
                //Do Nothing
            },
        });
    });
});
