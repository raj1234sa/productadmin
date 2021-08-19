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
    // $("#filterForm select, #filterForm input, #filterForm button[type!=button]").each(function () {
    //     $.cookie("search_" + $(this).attr("id"), $(this).val());
    // });
    // var defaultSorting = [[0, "asc"]];
    // var columnDefs = [];
    // var action = getSearchAction();
    // var data = getSearchData(action);
    // var pageLength = $("#dataTable_length").children('select').val();
    // if (from == "print") {
    //     pageLength = 500;
    //     var printHides = [];
    //     $("thead tr th").each(function (index) {
    //         if ($(this).data('printhide') == true) {
    //             printHides.push(index);
    //         }
    //     });
    //     columnDefs.push({
    //         "targets": printHides,
    //         "visible": false
    //     });
    // }
    // if (from != "") {
    //     $("table[id^='dataTable'].ajax").DataTable().destroy();
    // }
    // if ($("table").data('checkbox') == true) {
    //     // orderFalseIndex.push(0);
    //     columnDefs.push({
    //         "width": '1px',
    //         "targets": 0
    //     });
    // }
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
    // var table = $("table[id^='dataTable'].ajax").DataTable({
    //     "order": defaultSorting,
    //     "dom": 't<"table-bottom"irlp><"clear">',
    //     "columnDefs": columnDefs,
    //     "pageLength": pageLength,
    //     "processing": true,
    //     "serverSide": true,
    //     "searching": false,
    //     "createdRow": function (row, data, index) {
    //         // $("thead tr th").each(function (i) {
    //         //     if ($(this).hasClass('text-center')) {
    //         //         $(row).children(":nth-child(" + (i + 1) + ")").addClass('text-center');
    //         //     }
    //         // });
    //     },
    //     "fnDrawCallback": function () {
    //         $("#dataTable_previous").html('<i class="fa fa-angle-double-left"></i>');
    //         $("#dataTable_next").html('<i class="fa fa-angle-double-right"></i>');
    //         if ($("tbody").text() != "No data available in table") {
    //             var html = '';
    //             if ($(".table-tools").html() == undefined) {
    //                 html += '<div class="table-tools">';
    //             }
    //             if(typeof tabletools !== 'undefined' && tabletools !== null) {
    //                 tabletools.forEach(element => {
    //                     if (element == 'print') {
    //                         var printHtml = '';
    //                         printHtml = '<button type="button" class="btn btn-white print-btn"><i class="fa fa-print"></i></button>';
    //                         html += printHtml;
    //                     }
    //                     if (element == 'export') {
    //                         var exportHtml = '';
    //                         exportHtml = '<button type="button" class="btn btn-white btn-success export-btn"><i class="fa fa-file-excel-o"></i></button>';
    //                         html += exportHtml;
    //                     }
    //                 });
    //             }
    //             if ($(".table-tools").html() == undefined) {
    //                 html += '</div>';
    //                 $(".table-responsive").before(html);
    //             } else {
    //                 $(".table-tools").html(html);
    //             }
    //         } else {
    //             $(".table-tools").remove();
    //         }
    //     },
    //     "stateSave": false,
    //     "ajax": {
    //         "url": '',
    //         "type": "POST",
    //         "data": {
    //             data: data,
    //             listing_data: true,
    //         }
    //     }
    // });
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

$(document).ajaxStart(function () {
    $("i.ajax_loader").removeClass('d-none');
}).ajaxStop(function () {
    $("i.ajax_loader").addClass('d-none');
});

$.fn.PATable = function (options) {
    var FILE_FILENAME_WITH_EXT = window.location.href;
    var tableobj = $(this);
    var columnDefs = [];
    if (typeof options.search !== undefined) {
        $("#filterForm select, #filterForm input, #filterForm button[type!=button]").each(function () {
            $.cookie("search_" + $(this).attr("id"), $(this).val());
        });
    }

    $("thead tr th", tableobj).each(function (index) {
        if ($(this).data('orderable') == false) {
            orderFalseIndex.push(index);
        }
    });
    columnDefs.push({
        "sortable": false,
        "targets": orderFalseIndex
    });
    for (let i = 0; i < 10; i++) {
        if (!orderFalseIndex.includes(i)) {
            defaultSorting = [[i, "asc"]];
            break;
        }
    }
    $("thead tr th", tableobj).each(function (index, elem) {
        if ($(elem).data('default-sort') !== undefined && $(elem).data('default-sort') == true) {
            var sort_dir = 'asc';
            if ($(elem).data('sort-dir') !== undefined && $(elem).data('sort-dir') != '') {
                sort_dir = $(elem).data('sort-dir');
            }
            defaultSorting = [[index, sort_dir]];
        }
    });
    $.extend(true, $.fn.dataTable.defaults, {
        "aaSorting": defaultSorting,
        "aoColumnDefs": columnDefs,
        "sDom": 't<"table-bottom"irlp><"clear">',
        "aLengthMenu": [10, 25, 50, 75, 100],
        "sPaginationType": 'full_numbers',
        "oLanguage": {
            "oPaginate": {
                "sFirst": SC_FIRST,
                "sLast": SC_LAST,
                "sNext": SC_NEXT,
                "sPrevious": SC_PREVIOUS,
            },
            "sProcessing": '<i class="fa fa-spinner fa-spin" style="font-size: 40px;"></i>',
            "sEmptyTable": SC_NO_RECORDS_TABLE,
            "sZeroRecords": SC_NO_RECORDS_FOUND,
        },
        "bPaginate": true,
        "bInfo": true,
        "bLengthChange": true,
        "searching": true,
        "bServerSide": true,
        "bStateSave": false,
        "fnStateSaveCallback": function (settings, data) {
            localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
        },
        "fnStateLoadCallback": function (settings) {
            return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
        },
    });

    var opts = $.extend(true, $.fn.dataTable.defaults, options);
    if (opts.serverSide != false) {
        opts.ajax = {
            "url": FILE_FILENAME_WITH_EXT,
            "type": "POST",
            "data": function (d) {
                d.listing_data = true;
            },
        };
    }

    opts.drawCallback = function (settings) {
        if (options.tabletools) {
            tablebuttons = options.tablebuttons;
            if ($.inArray($("tbody").text(), [SC_NO_RECORDS_TABLE, SC_NO_RECORDS_FOUND]) == -1) {
                var html = '';
                if ($(".table-tools").html() == undefined) {
                    html += '<div class="table-tools">';
                }
                if (typeof tablebuttons !== 'undefined' && tablebuttons !== null) {
                    tablebuttons.forEach(element => {
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
        } else {
            $(".table-tools").remove();
        }
        renderInputs();
    };
    var dataTable = tableobj.DataTable(opts);
    this.dataTable = dataTable;
    $(tableobj).data('PATable', this.dataTable);
    // $(tableobj).delegate('input.change_status.ajax', 'change', function () {
    //     changeStatus($(this), dataTable);
    // });
    // $(tableobj).delegate('a.ajax.delete', 'click', function (e) {
    //     e.preventDefault();
    //     delete_record($(this));
    // });
    // $(tableobj.parent().parent().parent()).delegate('button.print-btn', 'click', function () {
    //     print_table($(this), dataTable);
    // });
    if (typeof options.search !== 'undefined') {
        if (typeof options.search.button !== 'undefined') {
            $(options.search.button).click(function () {
                var table_obj = tableobj.data('PATable');
                table_obj.settings()[0].ajax.data = function (data) {
                    data.listing_data = true;
                    data.searchval = $(options.search.form).serialize();
                }
                table_obj.clear().draw();
                // tableobj.DataTable().destroy();
                // dataTable = tableobj.DataTable(opts);
                // this.dataTable = dataTable;
            });
        }
    }
}

$(document).ready(function () {
    $("body")
        .delegate('input.change_status.ajax', 'change', function () {
            var _this = $(this);
            var table_obj = _this.parents('table').data('PATable');
            var url = _this.data('url');
            var id = _this.parent().parent().parent().attr('id').split(":")[1];
            var statusCode = _this.attr("name");
            var status = '0';
            if (_this.prop('checked')) {
                status = '1';
            }
            $.ajax({
                url: url,
                type: 'POST',
                data: { id: id, status: status, status_code: statusCode, action: 'change_status' },
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
                    table_obj.draw();
                }
            });
        })
        .delegate('a.ajax.delete', 'click', function (e) {
            e.preventDefault();
            var _this = $(this);
            var table_obj = _this.parents('table').data('PATable');
            var atag = _this;
            bootbox.confirm({
                message: COMMON_DELETE_WARNING,
                buttons: {
                    cancel: {
                        className: 'btn-secondary btn-default btn-sm'
                    },
                    confirm: {
                        className: 'btn-primary btn-sm'
                    }
                },
                callback: function (result) {
                if (result) {
                    var url = $(atag).attr('href');
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (response) {
                            if (response == 'success') {
                                successMessage(COMMON_DELETE_SUCCESS);
                            } else {
                                failMessage(response);
                            }
                        },
                        complete: function () {
                            table_obj.draw();
                        }
                    });
                }
            }});
        })
        .delegate('button.print-btn', 'click', function () {
            var _this = $(this);
            var table_obj = _this.parent().siblings('div').find('table').data('PATable');
            window.printMode = true;
            table_obj.draw();
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
            $(".action-buttons-div").hide();
            $("form").hide();
        })
        .delegate('.export-btn', 'click', function () {
            var _this = $(this);
            var table_obj = _this.parent().siblings('div').find('table').data('PATable');
            table_obj.settings()[0].ajax.data = function (data) {
                data.export = true;
                data.listing_data = undefined;
            }
            // var params = $("table[id^='dataTable']").DataTable().ajax.params();
            // params.export = true;
            // params.listing_data = undefined;
            $.ajax({
                url: '',
                type: "POST",
                data: table_obj.settings()[0],
                success: function (data) {
                    data = JSON.parse(data);
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", data.fileName);
                    $a[0].click();
                    $a.remove()
                }
            });
        });
});

function renderInputs() {
    $(".selectpicker").selectpicker();

    $("[data-toggle='tooltip']").tooltip();
}

function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(element.data('copy')).select();
    document.execCommand("copy");
    $temp.remove();
}

$(document).ready(function () {
    renderInputs();

    $('.dummy_accordian').each(function () {
        var _this = $(this);
        var header = _this.data('title');
        var theme = _this.data('theme');
        if (theme == null) { theme = 'gradient_purple'; }
        var show_icon = _this.data('show-icon');
        var id = _this.data('id');
        var acc_class = link_class = '';
        if (theme == 'gradient_purple') {
            acc_class = 'accordion-s3 gradiant-bg mt-3';
        }
        if (show_icon == false) {
            link_class = 'icon-hide';
        }

        var html = '<div class="according ' + acc_class + '"><div class="card"><div class="card-header"><a class="card-link ' + link_class + '" data-toggle="collapse" href="#accordian_' + id + '" aria-expanded="true">' + header + '</a></div><div id="accordian_' + id + '" class="collapse show"><div class="card-body">';

        html += _this.html();

        html += '</div></div></div></div>';
        _this.after(html);
        _this.remove();
        renderInputs();
    });

    if ($.cookie('flash_message')) {
        var flash_message = JSON.parse($.cookie('flash_message'));
        var message = flash_message[0];
        var mode = flash_message[1];
        if (mode == 'success') {
            successMessage(message);
        } else if (mode == 'fail') {
            failMessage(message);
        }
        $.removeCookie('flash_message');
    }

    $("button").click(function () {
        $(this).css("outline", 'none !important');
        $(this).css("decoration", 'none !important');
    });

    $(".reset-btn").click(function () {
        window.location.href = window.location.href.split('?')[0];
    });

    var main_container_padding = $(".page-container").css("padding");
    var main_content_padding = $(".main-content").css("margin-left");
    $(document).keyup(function (e) {
        if (e.which == 27) {
            $(".img_fullscreen").remove();
            if (window.printMode == true) {
                var tabledata = $('table').DataTable();;
                tabledata.draw();
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
                $(".action-buttons-div").show();
                $("form").show();
                window.printMode = false;
            }
        }
    });

    $(".form-ckeditor").each(function () {
        ClassicEditor
            .create(document.querySelector('#' + $(this).attr('id')))
            .then(editor => {
            })
            .catch(error => {
                console.error(error);
            });
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
            $("form").prepend("<input class='d-none' type='text' name='submit_btn' value='" + COMMON_SAVE + "'>");
        } else if (ids == 'formSubmitBack') {
            $("form").prepend("<input class='d-none' type='text' name='submit_btn' value='" + COMMON_SAVE_BACK + "'>");
        }
        $("form").submit();
    });
    $("#formReset").click(function (e) {
        $("form").trigger('reset');
    });
    $("thead tr th > #table_select_all").change(function () {
        $("tbody tr td > input[class*=table_checkbox]").prop('checked', $(this).prop('checked'));
    });

    $(".upload_file").click(function () {
        $($(this).data('trigger')).click();
    });

    $("input[type=file]").change(function () {
        var _th = $(this);
        var file = _th[0].files[0];
        if (file) {
            var params = new FormData();
            params.append('params', JSON.stringify(_th.data()));
            params.append('files', _th[0].files[0]);
            params.append('action', 'add');
            if (_th.data('ajax') !== undefined) {
                $.ajax({
                    url: _th.data('ajax'),
                    data: params,
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.status == 'fail') {
                            failMessage(COMMON_UPLOAD_ERROR);
                        } else {
                            if ($("#filepreview_" + _th.attr('id')).length == 0) {
                                _th.parent('.upload_file_div').append("<span id='filepreview_" + _th.attr('id') + "' class='ml-2'></span>");
                            }
                            _th.siblings("input:hidden").val(response.filename);
                            $("#filepreview_" + _th.attr('id')).html(response.preview_html);
                        }
                    }
                });
            }
        }
    });

    $(document).delegate('img.image_zoom', 'click', function () {
        var img_html = $(this).prop('outerHTML');
        img_html = $(img_html).removeAttr('width').removeClass('image_zoom').prop('outerHTML');
        var html = "<div class='img_fullscreen'>";
        html += img_html;
        html += "<button class='close'><i class='fa fa-close'></i></button>";
        html += "</div>";
        $('body').append(html);
    });

    $(document).delegate('.img_fullscreen button.close', 'click', function () {
        $(".img_fullscreen").remove();
    });

    $(".upload_file_div [id^='filepreview_'] i.delete").click(function () {
        var _th = $(this).parent().siblings('input:file.form-hide');
        var params = new FormData();
        params.append('params', JSON.stringify(_th.data()));
        params.append('action', 'delete');
        if (_th.data('ajax') !== undefined && confirm(COMMON_FILE_DELETE_WARNING)) {
            $.ajax({
                url: _th.data('ajax'),
                data: params,
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.status == 'fail') {
                        failMessage(COMMON_UPLOAD_ERROR);
                    } else {
                        if ($("#filepreview_" + _th.attr('id')).length == 0) {
                            _th.parent('.upload_file_div').append("<span id='filepreview_" + _th.attr('id') + "' class='ml-2'></span>");
                        }
                        _th.siblings("input:hidden").val('');
                        $("#filepreview_" + _th.attr('id')).html('<img src="' + DIR_HTTP_IMAGES_COMMON + 'no_preview.jpg" width="100">');
                    }
                }
            });
        }
    });
    // autosize($('textarea[class*=autosize]'));
    $(document).delegate('.export-btn', 'click', function () {
        var params = $("table[id^='dataTable']").DataTable().ajax.params();
        params.export = true;
        params.listing_data = undefined;
        $.ajax({
            url: '',
            type: "POST",
            data: params,
            success: function (data) {
                data = JSON.parse(data);
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", data.fileName);
                $a[0].click();
                $a.remove()
            }
        });
    });
});
