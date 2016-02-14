/**
 * Created by addison on 2/13/16.
 */
"use strict";

function validateEmail(email) {
    var regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
}

function clearErrors(clearText, formId) {
    if(clearText == undefined) clearText = false;
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

$(function() {
    $.fn.addError = function(errorMsg, field) {
        if (field == undefined) field = "";
        $(this).after("<div class=\"" + field + " error-message text-danger\">" + errorMsg + "</div>");
        $(this).addClass("input-error");
    }
});