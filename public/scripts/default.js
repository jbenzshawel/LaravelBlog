/**
 * Created by addison on 2/13/16.
 */
"use strict";

// create LB$ object to store default functions
var LB$ = {
    validateEmail : function validateEmail(email) {
        var regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(email);
    },
    validatePasswordLength : function validatePasswordLength(password, id) {
        if(password.trim() == "" && password.trim().length < 5) {
            $(id).addError("Password must be at least 5 characters long");
            return false;
        }
        return true;
    },
    clearErrors : function clearErrors(formId) {
        $(".input-error").removeClass("input-error");
        $(".error-message").remove();
        if (formId != undefined) {
            var form = document.getElementById(formId);
            form.reset();
        }
    },
    clearModalErrors : function clearModalErrors(modal) {
        $(modal).on('hidden.bs.modal', function () {
            var id = $(this).find('form').attr('id');
            this.clearErrors(id);
        });
    },
    updateInputField : function updateInputField(field) {
        $(field).change(function () {
            if (this.value != "") {
                $(this).removeClass("input-error");
                $("." + field.substring(1) + ".error-message").remove();
            }
        });
    },
    ajaxPost : function ajaxPost(settings, async, csrfToken) {
        if (async == undefined) async = true;
        if (csrfToken == undefined) return false;
        if (typeof(settings) === 'object') {
            settings.headers = { 'X-CSRF-TOKEN' : csrfToken};
            settings.type = 'POST';
            settings.contentType = 'application/json';
            settings.async = async;
            $.ajax(settings);
        }
    },
    alertMsg : function alertMsg(type, action, msgId) {
        if (type == undefined) return false;
        var deleteMsg = '<div class="alert alert-success alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<strong>Success!</strong> ' + type + ' have been deleted.' +
            '</div>';
        var updateMsg = '<div class="alert alert-success alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<strong>Success!</strong> ' + type + ' status have been updated.' +
            '</div>';
        if (action == 'delete') $(msgId).html(deleteMsg);
        if (action == 'update') $(msgId).html(updateMsg);
        }
};

$(function() {
    $.fn.addError = function(errorMsg, field) {
        if (field == undefined) field = "";
        $(this).find('.error-message').remove();
        $(this).after("<div class=\"" + field + " error-message text-danger\">" + errorMsg + "</div>");
        $(this).addClass("input-error");
    };
});