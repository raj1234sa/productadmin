$(document).ready(function() {
    loadBusStops();
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

    $.ajax({
        url: FILE_ADMIN_PASSENGER_EDIT,
        data: dataObj,
        type: 'POST',
        success: function(response) {
            $(".bus_stop_div").html(response);
            renderInputs();
        }
    });
}