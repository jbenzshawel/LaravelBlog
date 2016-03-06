/**
 * Created by addison on 2/13/16.
 */
"use strict";

// create LB$ object to store default functions
var LB$ = {
    // $LB.validateEmail
    // @param email = email to validate
    validateEmail : function (email) {
        var regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(email);
    },
    // $LB.validatePasswordLength
    // @param password = string to validate id = id of input field 
    validatePasswordLength : function (password, id) {
        if(password.trim() == "" && password.trim().length < 5) {
            $(id).addError("Password must be at least 5 characters long");
            return false;
        }
        return true;
    },
    // $LB.clearErrors clears all error messages on a page. If a form id is
    // passed the form fields will also be cleared
    // @param formid = id of form (optional)
    clearErrors : function (formId) {
        $(".input-error").removeClass("input-error");
        $(".error-message").remove();
        if (formId != undefined) {
            var form = document.getElementById(formId);
            form.reset();
        }
    },
    // $LB.clearModalErrors clears input errors and values in a modal form
    // @param modal = id of modal
    clearModalErrors : function (modal) {
        $(modal).on('hidden.bs.modal', function () {
            var id = $(this).find('form').attr('id');
            LB$.clearErrors(id);
        });
    },
    // $LB.updateInputField Clears input errors on change
    // @param field = id of input
    updateInputField : function (field) {
        $(field).change(function () {
            if (this.value != "") {
                $(this).removeClass("input-error");
                $("." + field.substring(1) + ".error-message").remove();
            }
        });
    },
    // $LB.post function for ajax post request
    // @params settings = object for ajax, async = bool, csrfToken = Laravel security token
    post : function (settings, async, csrfToken) {
        if (csrfToken == undefined) return false;
        if (async == undefined) async = true;
        if (typeof(settings) === 'object') {
            settings.headers = { 'X-CSRF-TOKEN' : csrfToken};
            settings.type = 'POST';
            settings.contentType = 'application/json';
            settings.async = async;
            $.ajax(settings);
            return true;
        }
        return false;
    },
    // $LB.alertMsg adds a dismissable alert to an element
    // @params type = name of what is being updated, action = update or delete, msgId = id of element to update
    alertMsg : function (type, action, msgId) {
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