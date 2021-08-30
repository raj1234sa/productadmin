$(function () {
    $("#dataTable").PATable({
        tabletools: true,
        tablebuttons: ['print', 'export'],
        serverSide: true,
        search: {
            form: '#frm',
            button: '#btn',
        },
    });

    $("body").delegate(".show_password_btn", "click", function() {
        $('#show_password').modal('show');
        var pid = $(this).data('pass_id');
        $.ajax({
            type: "POST",
            data: {pid: pid, action: 'show_password'},
            success: function (resp) {
                resp = JSON.parse(resp);
                $(".modal#show_password").find('.email_modal').text(resp.email);
                $(".modal#show_password").find('.password_modal').text(resp.password);
            }
        });
    });
});