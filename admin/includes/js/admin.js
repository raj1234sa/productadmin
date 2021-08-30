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
    this.table = tableobj;
    var columnDefs = [];
    if (options !== undefined) {
        if (options.search !== undefined) {
            $("#filterForm select, #filterForm input, #filterForm button[type!=button]").each(function () {
                $.cookie("search_" + $(this).attr("id"), $(this).val());
            });
        }
    }

    $("thead tr th", this.table).each(function (index) {
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
    $("thead tr th", this.table).each(function (index, elem) {
        if ($(elem).data('default-sort') !== undefined && $(elem).data('default-sort') == true) {
            var sort_dir = 'asc';
            if ($(elem).data('sort-dir') !== undefined && $(elem).data('sort-dir') != '') {
                sort_dir = $(elem).data('sort-dir');
            }
            defaultSorting = [[index, sort_dir]];
        }
    });
    $.extend(true, $.fn.dataTable.defaults, {
        "sorting": defaultSorting,
        "columnDefs": columnDefs,
        "dom": 'ft<"table-bottom"irlp><"clear">',
        "lengthMenu": [10, 25, 50, 75, 100],
        "paginationType": 'full_numbers',
        "language": {
            "paginate": {
                "first": SC_FIRST,
                "last": SC_LAST,
                "next": SC_NEXT,
                "previous": SC_PREVIOUS,
            },
            "processing": '<i class="fa fa-spinner fa-spin" style="font-size: 40px;"></i>',
            "emptyTable": SC_NO_RECORDS_TABLE,
            "zeroRecords": SC_NO_RECORDS_FOUND,
        },
        "paginate": true,
        "info": true,
        "lengthChange": true,
        "searching": false,
        "serverSide": false,
        "stateSave": false,
        "stateSaveCallback": function (settings, data) {
            localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
        },
        "stateLoadCallback": function (settings) {
            return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
        },
        "tablebuttons": ['print'],
    });
    var opts = $.fn.dataTable.defaults;
    if (options !== undefined) {
        var opts = $.extend(true, $.fn.dataTable.defaults, options);
    }
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
        tableobj.find("th.no-print").show();
        if(window.printMode == true) {
            tableobj.find("th.no-print").hide();
            tableobj.find("td.no-print").remove();
        }
        if (opts.tabletools) {
            if ($.inArray($("tbody").text(), [SC_NO_RECORDS_TABLE, SC_NO_RECORDS_FOUND]) == -1) {
                var html = '';
                if ($(".table-tools").html() == undefined) {
                    html += '<div class="table-tools btn-group">';
                }
                if (opts.tablebuttons !== undefined && opts.tablebuttons !== null) {
                    tablebuttons = opts.tablebuttons;
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
    var dataTable = this.table.DataTable(opts);
    this.table_obj = dataTable;
    $(this.table).data('PATable', this);
    if (opts !== undefined) {
        if (opts.search !== undefined) {
            if (opts.search.button !== undefined) {
                $(opts.search.button).click(function () {
                    var tableobj = this.table.data('PATable').table_obj;
                    tableobj.settings()[0].ajax.data = function (data) {
                        data.listing_data = true;
                        data.searchval = $(options.search.form).serialize();
                    }
                    tableobj.clear().draw();
                    // tableobj.DataTable().destroy();
                    // dataTable = tableobj.DataTable(opts);
                    // this.dataTable = dataTable;
                });
            }
        }
    }
}

$(document).ready(function () {
    $("body")
        .delegate('input.change_status.ajax', 'change', function () {
            var _this = $(this);
            var tableObj = _this.parents('table').data('PATable').table_obj;
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
                    tableObj.draw();
                }
            });
        })
        .delegate('a.ajax.delete', 'click', function (e) {
            e.preventDefault();
            var _this = $(this);
            var tableObj = _this.parents('table').data('PATable').table_obj;
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
                                tableObj.draw();
                            }
                        });
                    }
                }
            });
        })
        .delegate('button.print-btn', 'click', function () {
            var _this = $(this);
            var tableObj = _this.parent().siblings('div').find('table').data('PATable');
            window.printMode = true;
            tableObj.table_obj.draw();
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
            var tableObj = _this.parent().siblings('div').find('table').data('PATable').table_obj;
            tableObj.settings()[0].ajax.data = function (data) {
                data.export = true;
                data.listing_data = undefined;
            }
            // var params = $("table[id^='dataTable']").DataTable().ajax.params();
            // params.export = true;
            // params.listing_data = undefined;
            $.ajax({
                url: '',
                type: "POST",
                data: tableObj.settings()[0],
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
    var extrBtn = [];
    if (FILE_FILENAME_WITHOUT_EXT == 'passenger_import') {
        extrBtn.push($('<button></button>').text(COMMON_IMPORT)
            .addClass('btn btn-info sw-btn-import')
            .on('click', function (e) { importData(e) }));
    }
    $(".smartwizard").smartWizard({
        selected: 0, // Initial selected step, 0 = first step
        theme: 'default', // theme for the wizard, related css need to include for other than default theme
        justified: true, // Nav menu justification. true/false
        darkMode: true, // Enable/disable Dark Mode if the theme supports. true/false
        autoAdjustHeight: true, // Automatically adjust content height
        cycleSteps: false, // Allows to cycle the navigation of steps
        backButtonSupport: true, // Enable the back button support
        enableURLhash: false, // Enable selection of the step based on url hash
        transition: {
            animation: 'fade', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            speed: '400', // Transion animation speed
            easing: '', // Transition animation easing. Not supported without a jQuery easing plugin
        },
        toolbarSettings: {
            toolbarPosition: 'top', // none, top, bottom, both
            toolbarButtonPosition: 'right', // left, right, center
            showNextButton: true, // show/hide a Next button
            showPreviousButton: true, // show/hide a Previous button
            toolbarExtraButtons: extrBtn, // Extra buttons to show on toolbar, array of jQuery input/buttons elements
        },
        anchorSettings: {
            anchorClickable: true, // Enable/Disable anchor navigation
            enableAllAnchors: true, // Activates all anchors clickable all times
            markDoneStep: true, // Add done state on navigation
            markAllPreviousStepsAsDone: false, // When a step selected by url hash, all previous steps are marked done
            removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
            enableAnchorOnDoneStep: true, // Enable/Disable the done steps navigation
        },
        keyboardSettings: {
            keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
            keyLeft: [37], // Left key code
            keyRight: [39], // Right key code
        },
        lang: { // Language variables for button
            next: COMMON_NEXT,
            previous: COMMON_PREVIOUS,
        },
        autoAdjustHeight: false,
        disabledSteps: [], // Array Steps disabled
        errorSteps: [], // Highlight step with errors
        hiddenSteps: [], // Hidden steps
    });
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
        var showIcon = _this.data('show-icon');
        var id = _this.data('id');
        var accClass = linkClass = '';
        if (theme == 'gradient_purple') {
            accClass = 'accordion-s3 gradiant-bg mt-3';
        }
        if (showIcon == false) {
            linkClass = 'icon-hide';
        }

        var html = '<div class="according ' + accClass + '"><div class="card"><div class="card-header"><a class="card-link ' + linkClass + '" data-toggle="collapse" href="#accordian_' + id + '" aria-expanded="true">' + header + '</a></div><div id="accordian_' + id + '" class="collapse show"><div class="card-body">';

        html += _this.html();

        html += '</div></div></div></div>';
        _this.after(html);
        _this.remove();
        renderInputs();
    });

    if ($.cookie('flash_message')) {
        var flashMessage = JSON.parse($.cookie('flash_message'));
        var message = flashMessage[0];
        var mode = flashMessage[1];
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

    var mainContainerPadding = $(".page-container").css("padding");
    var mainContentPadding = $(".main-content").css("margin-left");
    $(document).keyup(function (e) {
        if (e.which == 27) {
            $(".img_fullscreen").remove();
            if (window.printMode == true) {
                var tabledata = $('table').DataTable();;
                tabledata.draw();
                $("#navbar").show();
                $("#breadcrumbs").show();
                $("#sidebar").show();
                $(".main-content").css('margin-left', mainContentPadding);
                $(".page-header").show();
                $("#filterForm").show();
                $(".table-tools").show();
                $(".dataTable_processing").show();
                $(".dataTables_length").show();
                $(".dataTables_paginate").show();
                $(".dataTables_info").show();
                $("div.footer").show();
                $(".page-container").css('padding', mainContainerPadding);
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
            if (_th.data('allowed-ext') != undefined) {
                var ext = _th[0].files[0].name.split('.').pop();
                var allowedExt = _th.data('allowed-ext').split(',');
                if ($.inArray(ext, allowedExt) == -1) {
                    alert("Only " + _th.data('allowed-ext') + " extensions are allowed.");
                    return false;
                }
            }
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
                            if (response.file_type == 'image') {
                                if ($("#imgpreview_" + _th.attr('id')).length == 0) {
                                    _th.parent('.upload_file_div').append("<span id='imgpreview_" + _th.attr('id') + "' class='ml-2'></span>");
                                }
                                $("#imgpreview_" + _th.attr('id')).html(response.preview_html);
                            } else {
                                if ($("#filepreview_" + _th.attr('id')).length == 0) {
                                    _th.parent('.upload_file_div').append("<span id='filepreview_" + _th.attr('id') + "' class='ml-2'></span>");
                                }
                                $("#filepreview_" + _th.attr('id')).html(response.file_preview_html);
                            }
                            _th.data('filename', response.filename);
                            _th.siblings("input:hidden").val(response.filename);
                        }
                    }
                });
            }
        }
    });

    $(document).delegate('img.image_zoom', 'click', function () {
        var imgHtml = $(this).prop('outerHTML');
        imgHtml = $(imgHtml).removeAttr('width').removeClass('image_zoom').prop('outerHTML');
        var html = "<div class='img_fullscreen'>";
        html += imgHtml;
        html += "<button class='close'><i class='fa fa-close'></i></button>";
        html += "</div>";
        $('body').append(html);
    });

    $(document).delegate('.img_fullscreen button.close', 'click', function () {
        $(".img_fullscreen").remove();
    });

    $("body").delegate(".upload_file_div [id^='imgpreview_'] i.delete, .upload_file_div [id^='filepreview_'] i.delete", "click", function () {
        var _th = $(this).parent().siblings('input:file.form-hide');
        var params = new FormData();
        params.append('params', JSON.stringify(_th.data()));
        params.append('action', 'delete');
        if (_th.data('ajax') !== undefined) {
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
                                    if (response.file_type == 'image') {
                                        if ($("#imgpreview_" + _th.attr('id')).length == 0) {
                                            _th.parent('.upload_file_div').append("<span id='imgpreview_" + _th.attr('id') + "' class='ml-2'></span>");
                                        }
                                        $("#imgpreview_" + _th.attr('id')).html(response.preview_html);
                                    } else {
                                        $("#filepreview_" + _th.attr('id')).remove();
                                    }
                                    _th.siblings("input:hidden").val('');
                                }
                            }
                        });
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
