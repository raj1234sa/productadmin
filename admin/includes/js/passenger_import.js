$(function () {
    $("#sheet_details").PATable({
        sort: false,
        searching: true,
        paginate: false,
        info: false
    });

    $("#bus_stop_tbl").PATable({
        searching: true,
    });

    $("#passenger_import_wiz").on("showStep", function (e, anchorObject, stepNumber, stepDirection, stepPosition) {
        $(".sw-btn-prev").removeClass('disabled');
        $(".sw-btn-next").removeClass('disabled d-none');
        $(".sw-btn-import").addClass("disabled d-none");
        if (stepPosition === 'first') {
            $(".sw-btn-prev").addClass('disabled');
        } else if (stepPosition === 'last') {
            $(".sw-btn-next").addClass('disabled d-none');
            $(".sw-btn-import").removeClass("disabled d-none");
        }
    });

    $("#step-1 a").click(function () {
        $("#passenger_import_wiz").smartWizard("goToStep", 1);
    })
});

function importData(e) {
    alert('improt')
}