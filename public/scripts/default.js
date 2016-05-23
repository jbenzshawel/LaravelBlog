/**
 * Created by addison on 2/13/16.
 */
"use strict";

// create LB$ object to store default functions
var LB$ = new Object();

// @param email = email to validate
// @return bool
LB$.validateEmail = function (email) {
    var regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
};

// @param password = string to validate id = id of input field
// @return bool (adds error message if false)
LB$.validatePasswordLength = function (password, id) {
    if(password.trim() == "" || password.trim().length < 5) {
        $(id).addError(ErrorMessages.PasswordLength);
        return false;
    }
    return true;
};

// clears all error messages on a page. If a form id is
// passed the form fields will also be cleared
// @param formid = id of form (optional)
// @return void
LB$.clearErrors = function (formId) {
    $(".input-error").removeClass("input-error");
    $(".error-message").remove();
    if (formId != undefined) {
        var form = document.getElementById(formId);
        form.reset();
    }
};

// clears input errors and values in a modal form
// @param modal = id of modal
// @return void
LB$.clearModalErrors = function (modal) {
    $(modal).on('hidden.bs.modal', function () {
        var id = $(this).find('form').attr('id');
        LB$.clearErrors(id);
    });
};

// Clears input errors on change
// @param field = id of input
// @return void
LB$.updateInputField = function (field) {
    $(field).change(function () {
        if (this.value != "") {
            $(this).removeClass("input-error");
            $("." + field.substring(1) + ".error-message").remove();
        }
    });
};

// function for ajax post request
// @params settings = object for ajax, async = bool, csrfToken = Laravel security token
// @return bool
LB$.post = function (settings, async, csrfToken) {
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
};

// adds a dismissable alert to an element
// @params type = name of what is being updated, action = update or delete, msgId = id of element to update
LB$.alertMsg = function (type, action, msgId) {
    if (type == undefined) return false;
    var deleteMsg = '<div class="alert alert-success alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong>Success!</strong> ' + type + ' has been deleted.' +
        '</div>';
    var updateMsg = '<div class="alert alert-success alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong>Success!</strong> ' + type + ' has been updated.' +
        '</div>';
    if (action == 'delete') $(msgId).html(deleteMsg);
    if (action == 'update') $(msgId).html(updateMsg);
};

// jquery functions
$(function() {
    // $(target).addError
    // @params errorMsg = message to add, field = name of field (used for targeting with LB$.updateInputField)
    $.fn.addError = function(errorMsg, field, altId) {
        if (field == undefined) field = "";
        var targetId = this;
        if (altId != undefined) {
            targetId = altId;
        }
        $(targetId).find('.error-message').remove();
        $(targetId).after("<div class=\"" + field + " error-message text-danger\">" + errorMsg + "</div>");
        $(this).addClass("input-error");
    };
});