$(document).ready(function () {
    var email_regex = /(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
    var phone_regex = /^[0-9]{10}$/;

    $.validator.addMethod("emailPattern", function(value, element) {
        return this.optional(element) || email_regex.test(value);
    });
    $.validator.addMethod("phone", function(value, element) {
        return this.optional(element) || phone_regex.test(value);
    });
});
$.fn.paValidate = function() {
    $(this).validate({ignore: ".ignore"});
    $(this).find('input:text, input:password, select, textarea').each(function () {
        let element = $(this);
        if (element.data('validation-required') !== undefined) {
            if(element.hasClass('date-picker')) {
                element.children("div[class*=col-]").children('.input-group').append(COMMON_REQUIRED_RED_STAR);
            } else {
                if(!element.parent().html().includes(COMMON_REQUIRED_RED_STAR)) {
                    element.after(COMMON_REQUIRED_RED_STAR);
                }
            }
        }

        if(element.data('error') !== undefined) {
            $(this.form).validate().showErrors({
                [element.attr('name')]: element.data('error')
            });
        }

        if (element.data('validation-required') !== undefined) {
            element.rules("add", {
                required: true,
                messages: {
                    required: element.data('validation-required'),
                }
            });
        }

        if (element.data('validation-minlength') !== undefined) {
            element.rules("add", {
                minlength: element.data('validation-minlength'),
                messages: {
                    minlength: element.data('validation-minlength-msg'),
                }
            });
        }

        if (element.data('validation-maxlength') !== undefined) {
            element.rules("add", {
                maxlength: element.data('validation-maxlength'),
                messages: {
                    maxlength: element.data('validation-maxlength-msg'),
                }
            });
        }

        if (element.data('validation-rangelength') !== undefined) {
            element.rules("add", {
                rangelength: [element.data('validation-rangelength-min'),element.data('validation-rangelength-max')],
                messages: {
                    rangelength: element.data('validation-rangelength-msg'),
                }
            });
        }

        if (element.data('validation-min') !== undefined) {
            element.rules("add", {
                min: element.data('validation-min'),
                messages: {
                    min: element.data('validation-min-msg'),
                }
            });
        }

        if (element.data('validation-max') !== undefined) {
            element.rules("add", {
                max: element.data('validation-max'),
                messages: {
                    max: element.data('validation-max-msg'),
                }
            });
        }

        if (element.data('validation-range') !== undefined) {
            element.rules("add", {
                range:  [element.data('validation-range-min'),element.data('validation-range-max')],
                messages: {
                    range: element.data('validation-range-msg'),
                }
            });
        }

        if (element.data('validation-step') !== undefined) {
            element.rules("add", {
                step: element.data('validation-step'),
                messages: {
                    step: element.data('validation-step-msg'),
                }
            });
        }

        if (element.data('validation-email') !== undefined) {
            element.rules("add", {
                emailPattern: true,
                messages: {
                    emailPattern: element.data('validation-email'),
                }
            });
        }

        if (element.data('validation-number') !== undefined) {
            element.rules("add", {
                number: true,
                messages: {
                    number: element.data('validation-number'),
                }
            });
        }

        if (element.data('validation-digits') !== undefined) {
            element.rules("add", {
                digits: true,
                messages: {
                    digits: element.data('validation-digits'),
                }
            });
        }

        if (element.data('validation-equalTo') !== undefined) {
            element.rules("add", {
                equalTo: element.data('validation-equalTo'),
                messages: {
                    equalTo: element.data('validation-equalTo-msg'),
                }
            });
        }

        if (element.data('validation-phone') !== undefined) {
            element.rules("add", {
                phone: true,
                messages: {
                    phone: element.data('validation-phone'),
                }
            });
        }
    });
};