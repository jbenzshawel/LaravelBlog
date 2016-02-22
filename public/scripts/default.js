/**
 * Created by addison on 2/13/16.
 */
"use strict";

function validateEmail(email) {
    var regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
}

function clearErrors(clearText, formId) {
    if (clearText == undefined) clearText = false;
    if (clearText) {
        var form = document.getElementById(formId);
        form.reset();
    }
    $(".input-error").removeClass("input-error");
    $(".error-message").remove();
}

function updateInputField(field) {
    $(field).change(function() {
        if(this.value != "") {
            $(this).removeClass("input-error");
            $("." + field.substring(1) + ".error-message").remove();
        }
    });
}

function ajaxPost(settings, async, csrfToken) {
    if (async == undefined) async = true;
    if (csrfToken == undefined) return false;
    if (typeof(settings) === 'object') {
        settings.headers = { 'X-CSRF-TOKEN' : csrfToken};
        settings.type = 'POST';
        settings.contentType = 'application/json';
        settings.async = async;
        $.ajax(settings);
    }
}

function alertMsg(type, action, msgId) {
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

$(function() {
    $.fn.addError = function(errorMsg, field) {
        if (field == undefined) field = "";
        $(this).after("<div class=\"" + field + " error-message text-danger\">" + errorMsg + "</div>");
        $(this).addClass("input-error");
    }
});