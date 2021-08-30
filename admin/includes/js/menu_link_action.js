$(function() {
    $("input[name=display]").change(function() {
        var display = $('input[name=display]:checked').val();
        if(display == 'b' || display == 'i') {
            $(".icon_div").removeClass("d-none");
            $(".icon_div #icon_class").removeClass("ignore");
        } else {
            $(".icon_div").addClass("d-none");
            $(".icon_div #icon_class").addClass("ignore");
        }
    }).trigger('change');
});