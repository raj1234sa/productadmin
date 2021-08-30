$(document).ready(function () {
    loadBusStops();

    $("input[name='new_password']").trigger('change');
    $("input[name='new_password']").change(function () {
        if ($("input[name='new_password']:checked").val() == 'no') {
            $(".password_div").addClass('d-none');
            $("#password").addClass('ignore');
        } else {
            $(".password_div").removeClass('d-none');
            $("#password").removeClass('ignore');
        }
    }).trigger('change');
});

function loadBusStops() {
    var country = $("#country").val();
    var state = $("#state").val();
    var city = $("#city").val();

    var dataObj = {
        country: country,
        state: state,
        city: city,
    };

    dataObj.action = 'get_bus_stops';
    dataObj.selected = $("#bus_stop_div").data('selected');

    $.ajax({
        url: FILE_ADMIN_PASSENGER_EDIT,
        data: dataObj,
        type: 'POST',
        success: function (response) {
            $("#bus_stop_div").html(response);
            // $("#form_add_passenger").paValidate();
            renderInputs();
        }
    });
}