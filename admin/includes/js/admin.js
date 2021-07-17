// function startAjaxLoader() {
//     $(".ajaxloader").removeClass('d-none');
// }

// function stopAjaxLoader() {
//     $(".ajaxloader").addClass('d-none');
// }

var orderFalseIndex = [];
var columnDefs = [];

function getSearchAction() {
    var action = [];
    var empty = true;
    $("#filterForm button[type!=button], #filterForm select, #filterForm input").each(function () {
        if ($(this).val() == null || $(this).val() == "") {
        } else {
            empty = false;
            action.push([$(this).attr('id'), $(this).val()]);
        }
    });
    return action;
}

function getSearchData(action = []) {
    var data = "";
    if (action.length > 0) {
        action.forEach(function (item, index) {
            if (index > 0) {
                data += "&";
            }
            data += item[0] + "=" + item[1];
        });
    }
    return data;
}

function drawTable(action = [], from = '') {
    $("#filterForm select, #filterForm input, #filterForm button[type!=button]").each(function () {
        $.cookie("search_" + $(this).attr("id"), $(this).val());
    });
    var defaultSorting = [[0, "asc"]];
    var columnDefs = [];
    var action = getSearchAction();
    var data = getSearchData(action);
    var pageLength = $("#dataTable_length").children('select').val();
    if (from == "print") {
        pageLength = 500;
        var printHides = [];
        $("thead tr th").each(function (index) {
            if ($(this).data('printhide') == true) {
                printHides.push(index);
            }
        });
        columnDefs.push({
            "targets": printHides,
            "visible": false
        });
    }
    if (from != "") {
        $("table[id^='dataTable'].ajax").DataTable().destroy();
    }
    if ($("table").data('checkbox') == true) {
        // orderFalseIndex.push(0);
        columnDefs.push({
            "width": '1px',
            "targets": 0
        });
    }
    // $("thead tr th").each(function (index) {
    //     if ($(this).data('order') == false) {
    //         orderFalseIndex.push(index);
    //     }
    // });
    // for (let i = 0; i < 10; i++) {
    //     if (!orderFalseIndex.includes(i)) {
    //         defaultSorting = [[i, "asc"]];
    //         break;
    //     }
    // }
    // $("table thead tr th").each(function(index,elem) {
    //     if($(elem).data('default-sort') !== undefined && $(elem).data('default-sort') == true) {
    //         var sort_dir = 'asc';
    //         if($(elem).data('sort-dir') !== undefined && $(elem).data('sort-dir') != '') {
    //             sort_dir = $(elem).data('sort-dir');
    //         }
    //         defaultSorting = [[index,sort_dir]];
    //     }
    // });
    // columnDefs.push({
    //     "orderable": false,
    //     "targets": orderFalseIndex
    // });
    var table = $("table[id^='dataTable'].ajax").DataTable({
        "order": defaultSorting,
        "dom": 't<"table-bottom"irlp><"clear">',
        "columnDefs": columnDefs,
        "pageLength": pageLength,
        "processing": true,
        "serverSide": true,
        "searching": false,
        "createdRow": function (row, data, index) {
            // $("thead tr th").each(function (i) {
            //     if ($(this).hasClass('text-center')) {
            //         $(row).children(":nth-child(" + (i + 1) + ")").addClass('text-center');
            //     }
            // });
        },
        "fnDrawCallback": function () {
            $("#dataTable_previous").html('<i class="fa fa-angle-double-left"></i>');
            $("#dataTable_next").html('<i class="fa fa-angle-double-right"></i>');
            if ($("tbody").text() != "No data available in table") {
                var html = '';
                if ($(".table-tools").html() == undefined) {
                    html += '<div class="table-tools">';
                }
                if(typeof tabletools !== 'undefined' && tabletools !== null) {
                    tabletools.forEach(element => {
                        if (element == 'print') {
                            var printHtml = '';
                            printHtml = '<button type="button" class="btn btn-white print-btn"><i class="fa fa-print"></i></button>';
                            html += printHtml;
                        }
                        if (element == 'export') {
                            var exportHtml = '';
                            exportHtml = '<button type="button" class="btn btn-white btn-success export-btn"><i class="fa fa-file-excel-o"></i></button>';
                            html += exportHtml;
                        }
                    });
                }
                if ($(".table-tools").html() == undefined) {
                    html += '</div>';
                    $(".table-responsive").before(html);
                } else {
                    $(".table-tools").html(html);
                }
            } else {
                $(".table-tools").remove();
            }
        },
        "stateSave": false,
        "ajax": {
            "url": '',
            "type": "POST",
            "data": {
                data: data,
                listing_data: true,
            }
        }
    });
    $(".dataTables_processing").empty();
    $(".dataTables_processing").append('<i class="fa fa-spinner fa-spin" style="font-size: 40px;"></i>');
}

setTimeout(function () {
    $(".alert.alert-dismissible").children('button').click();
}, 9000);
var index = 0;

function successMessage(message) {
    index = index + 1;
    $("body div.alert-dismiss").append("<div class='alert alert-success alert-dismissible fade show' id='" + (index) + "' role='alert'><strong>Success!</strong> " + message + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span class='fa fa-times'></span></button></div>");
    setTimeout(() => {
        $(".alert#" + index).children('button').click();
    }, 9000);
}

function failMessage(message) {
    index = index + 1;
    $("body div.alert-dismiss").append("<div class='alert alert-danger alert-dismissible fade show' id='" + (index) + "' role='alert'><strong>Error!</strong> " + message + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span class='fa fa-times'></span></button></div>");
    setTimeout(() => {
        $(".alert#" + index).children('button').click();
    }, 9000);
}

// $(window).load(function() {
//     $('img').each(function() {
//         if ( !this.complete ||typeof this.naturalWidth == "undefined" ||this.naturalWidth == 0) {
//             this.src = DIR_HTTP_IMAGES_COMMON+"no_preview.jpg";
//         }
//     });
// });

$(document).ajaxStart(function() {
    $("i.ajax_loader").removeClass('d-none');
}).ajaxStop(function() {
    $("i.ajax_loader").addClass('d-none');
});
$(document).ready(function () {
    $("button").click(function () {
        $(this).css("outline", 'none !important');
        $(this).css("decoration", 'none !important');
    });
    var count = 0;
    $(".dataTables_processing").empty();
    $(".dataTables_processing").append('<i class="fa fa-spinner fa-spin" style="font-size: 40px;"></i>');
    $("input.d-none, input[type=hidden]").each(function () {
        $(this).addClass('ignore');
    });
    $("#filterForm select, #filterForm input[type!=button]").each(function () {
        var tagName = $(this).prop("tagName").toLowerCase();
        switch (tagName) {
            case "input":
                if ($.cookie("search_" + $(this).attr("id")) != "") {
                    $(this).val($.cookie("search_" + $(this).attr("id")));
                    count++;
                }
                break;
            case "select":
                if ($.cookie("search_" + $(this).attr("id")) != "null") {
                    $(this).val($.cookie("search_" + $(this).attr("id")));
                    count++;
                }
                break;
        }
    });

    if ($('table.ajax.table').length > 0) {
        drawTable(getSearchAction());
    }
    $("#filterForm").submit(function (e) {
        e.preventDefault();
        $("#filterForm button[type=button]#search").click();
    });
    $("#filterForm button#search").click(function () {
        drawTable(getSearchAction(), 'search');
    });
    $("#filterForm button[type=button]#reset").click(function () {
        $("#filterForm button[type!=button], #filterForm select, #filterForm input").val("");
        $("#filterForm button[type=button]#search").click();
    });
    $("input").each(function () {
        if ($(this).data('type') == 'number') {
            var value = 0;
            var min = 1;
            var max = 15000;
            var step = 1;
            if ($(this).val() != '') {
                value = $(this).val();
            }
            if ($(this).attr('min') != undefined && $(this).attr('min') != '') {
                min = $(this).attr('min');
            }
            if ($(this).attr('max') != undefined && $(this).attr('max') != '') {
                max = $(this).attr('max');
            }
            if ($(this).attr('step') != undefined && $(this).attr('step') != '') {
                step = $(this).attr('step');
            }
            $('#' + $(this).attr('id')).ace_spinner({
                value: value, min: min, max: max, step: step, btn_up_class: 'btn-info', btn_down_class: 'btn-info'
            }).closest('.ace-spinner').on('changed.fu.spinbox', function () {
            });
        }
    });
    $("input.only-number").keydown(function (e) {
        var key = e.charCode || e.keyCode || 0;
        return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
    });
    $(".formsubmit").click(function () {
        var ids = $(this).attr('id');
        if (ids == 'formSubmit') {
            $("form").prepend("<input class='d-none' type='text' name='submit_btn' value='"+COMMON_SAVE+"'>");
        } else if (ids == 'formSubmitBack') {
            $("form").prepend("<input class='d-none' type='text' name='submit_btn' value='"+COMMON_SAVE_AND_BACK+"'>");
        }
        $("form").submit();
    });
    $("#formReset").click(function (e) {
        $("form").trigger('reset');
    });
    $(document).delegate('input.change_status.ajax', 'change', function () {
        var url = $(this).data('url');
        var id = $(this).parent().parent().parent().attr('id').split(":")[1];
        var statusCode = $(this).attr("name");
        var status = '0';
        if ($(this).prop('checked')) {
            status = '1';
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id, status: status, status_code: statusCode, action: 'change_status'},
            beforeSend: function () {
                $(".alert.alert-dismissible").remove();
            },
            success: function (response) {
                if (response == 'success') {
                    successMessage("Status is changed successfully.");
                } else {
                    failMessage("Error while changing status.");
                }
            },
            complete: function () {
                drawTable(getSearchAction(), 'change_status');
            }
        });
    });

    $(document).delegate('a.ajax.delete', 'click', function (e) {
        e.preventDefault();
        var atag = $(this);
        bootbox.confirm("Are you sure to delete this record ?", function (result) {
            if (result) {
                var url = $(atag).attr('href');
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        if (response == 'success') {
                            successMessage('Data is deleted successfully.');
                        } else {
                            failMessage(response);
                        }
                    },
                    complete: function () {
                        drawTable(getSearchAction(), 'delete');
                    }
                });
            }
        });
    });
    $("thead tr th > #table_select_all").change(function () {
        $("tbody tr td > input[class*=table_checkbox]").prop('checked', $(this).prop('checked'));
    });

    // if(!ace.vars['touch']) {
    //     $('.chosen-select').chosen({allow_single_deselect:true}); 
    //     //resize the chosen on window resize
    //     $(window)
    //     .off('resize.chosen')
    //     .on('resize.chosen', function() {
    //         $('.chosen-select').each(function() {
    //             var $this = $(this);
    //             $this.next().css({'width': '70%'});
    //         })
    //     }).trigger('resize.chosen');
    //     //resize chosen on sidebar collapse/expand
    //     $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
    //         if(event_name != 'sidebar_collapsed') return;
    //         $('.chosen-select').each(function() {
    //             var $this = $(this);
    //             $this.next().css({'width': '70%'});
    //         })
    //     });

    //     $('#chosen-multiple-style .btn').on('click', function(e){
    //         var target = $(this).find('input[type=radio]');
    //         var which = parseInt(target.val());
    //         if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
    //         else $('#form-field-select-4').removeClass('tag-input-style');
    //     });
    // }

    $(".upload_file").click(function() {
        $($(this).data('trigger')).click();
    });

    $("input[type=file]").change(function() {
        var _th = $(this);
        var file = _th[0].files[0];
        if (file) {
            var params = new FormData();
            params.append('params', JSON.stringify(_th.data()));
            params.append('files', _th[0].files[0]);
            params.append('action', 'add');
            if(_th.data('ajax') !== undefined) {
                $.ajax({
                    url: _th.data('ajax'),
                    data: params,
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        response = JSON.parse(response);
                        if(response.status == 'fail') {
                            failMessage(COMMON_UPLOAD_ERROR);
                        } else {
                            if($("#filepreview_"+_th.attr('id')).length == 0) {
                                _th.parent('.upload_file_div').append("<span id='filepreview_"+_th.attr('id')+"' class='ml-2'></span>");
                            }
                            _th.siblings("input:hidden").val(response.filename);
                            $("#filepreview_"+_th.attr('id')).html(response.preview_html);
                        }
                    }
                });
            }
        }
    });

    $(document).delegate('img.image_zoom', 'click', function() {
        var img_html = $(this).prop('outerHTML');
        img_html = $(img_html).removeAttr('width').removeClass('image_zoom').prop('outerHTML');
        var html = "<div class='img_fullscreen'>";
        html += img_html;
        html += "<button class='close'><i class='fa fa-close'></i></button>";
        html += "</div>";
        $('body').append(html);
    });

    $(document).delegate('.img_fullscreen button.close', 'click', function() {
        $(".img_fullscreen").remove();
    });

    $(".upload_file_div [id^='filepreview_'] i.delete").click(function() {
        var _th = $(this).parent().siblings('input:file.form-hide');
        var params = new FormData();
        params.append('params', JSON.stringify(_th.data()));
        params.append('action', 'delete');
        if(_th.data('ajax') !== undefined && confirm("Are you sure to delete image?")) {
            $.ajax({
                url: _th.data('ajax'),
                data: params,
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if(response.status == 'fail') {
                        failMessage(COMMON_UPLOAD_ERROR);
                    } else {
                        if($("#filepreview_"+_th.attr('id')).length == 0) {
                            _th.parent('.upload_file_div').append("<span id='filepreview_"+_th.attr('id')+"' class='ml-2'></span>");
                        }
                        _th.siblings("input:hidden").val('');
                        $("#filepreview_"+_th.attr('id')).html('<img src="'+DIR_HTTP_IMAGES_COMMON+'no_preview.jpg" width="100">');
                    }
                }
            });
        }
    });
    // autosize($('textarea[class*=autosize]'));

    // $('.date-picker').datepicker({
    //     autoclose: true,
    //     todayHighlight: true
    // })
    // //show datepicker when clicking on the icon
    // .next().on(ace.click_event, function(){
    //     $(this).prev().focus();
    // });

    // $('input[name=date-range-picker]').daterangepicker({
    //     'applyClass' : 'btn-sm btn-success',
    //     'cancelClass' : 'btn-sm btn-default',
    //     locale: {
    //         applyLabel: 'Apply',
    //         cancelLabel: 'Cancel',
    //     }
    // })
    // .prev().on(ace.click_event, function(){
    //     $(this).next().focus();
    // });

    // $('.bootstrap-timepicker input[type=text]').timepicker({
    //     minuteStep: 1,
    //     showSeconds: true,
    //     showMeridian: false,
    //     disableFocus: true,
    //     icons: {
    //         up: 'fa fa-chevron-up',
    //         down: 'fa fa-chevron-down'
    //     }
    // }).on('focus', function() {
    //     $(this).timepicker('showWidget');
    // }).next().on(ace.click_event, function(){
    //     $(this).prev().focus();
    // });
    var main_container_padding = $(".page-container").css("padding");
    var main_content_padding = $(".main-content").css("margin-left");
    $(document).keyup(function (e) {
        if (e.which == 27 && window.printMode == true) {
            drawTable(getSearchAction(), 'show');
            $("#navbar").show();
            $("#breadcrumbs").show();
            $("#sidebar").show();
            $(".main-content").css('margin-left', main_content_padding);
            $(".page-header").show();
            $("#filterForm").show();
            $(".table-tools").show();
            $(".dataTable_processing").show();
            $(".dataTables_length").show();
            $(".dataTables_paginate").show();
            $(".dataTables_info").show();
            $("div.footer").show();
            $(".page-container").css('padding', main_container_padding);
            $(".sidebar-menu").show();
            $(".page-title-area").show();
            $(".header-area").show();
            window.printMode = false;
        }
    });
    $(document).delegate(".print-btn", 'click', function () {
        window.printMode = true;
        drawTable(getSearchAction(), 'print');
        $("#navbar").hide();
        $("#breadcrumbs").hide();
        $("#sidebar").hide();
        $(".main-content").css('margin-left', "0");
        $(".page-header").hide();
        $("#filterForm").hide();
        $(".table-tools").hide();
        $(".dataTable_processing").hide();
        $(".dataTables_length").hide();
        $(".dataTables_paginate").hide();
        $(".dataTables_info").hide();
        $("div.footer").hide();
        $(".sidebar-menu").hide();
        $(".page-title-area").hide();
        $(".header-area").hide();
        $(".page-container").css('padding', "0");
    });
    $(document).delegate('.export-btn', 'click', function () {
        var action = getSearchAction();
        var params = $("table[id^='dataTable']").DataTable().ajax.params();
        params.export = true;
        params.listing_data = undefined;
        $.ajax({
            url: '',
            type: "POST",
            data: params,
            beforeSend: function () {
                // startAjaxLoader();
            },
            success: function (data) {
                data = JSON.parse(data);
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", data.fileName);
                $a[0].click();
                $a.remove()
            },
            complete: function () {
                // stopAjaxLoader();
            }
        });
    });
});
