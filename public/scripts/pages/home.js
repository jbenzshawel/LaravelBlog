/**
 * Created by addison on 2/24/16.
 */
"use strict";
var postURL = "/projects/LaravelBlog/public/posts";
function approveComment(commentId) {
    if(!isNaN(parseInt(commentId, 10))) {
        sendCommentId(postURL + "/approveComment", commentId);
        return true;
    }
    return false;
}
function unapproveComment(commentId) {
    if(!isNaN(parseInt(commentId, 10))) {
        sendCommentId(postURL + "/unapproveComment", commentId);
        return true;
    }
    return false;
}
function deleteComment(commentId) {
    if(!isNaN(parseInt(commentId, 10))) {
        sendCommentId(postURL + "/deleteComment", commentId);
        return true;
    }
    return false;
}
function showPost(postId) {
    if(!isNaN(parseInt(postId, 10))) {
        sendPostId(postURL + "/show", postId);
        return true;
    }
    return false;
}
function hidePost(postId) {
    if(!isNaN(parseInt(postId, 10))) {
        sendPostId(postURL + "/hide", postId);
        return true;
    }
    return false;
}
function deletePost(postId) {
    if(!isNaN(parseInt(postId, 10))) {
        sendPostId(postURL + "/delete", postId);
        return true;
    }
    return false;
}
function changeUsername(username) {
    clearErrors();
    if(username.length > 0) {
        var settings = new Object();
        settings.url = "/projects/LaravelBlog/public/user/changeName";
        settings.data = JSON.stringify({ name : username });
        settings.success = function(data) {
            if (data == "true") {
                $("#name").html(username);
                $("#changeNameModal").modal('hide');
                return true;
            }
        };
        ajaxPost(settings, true, $("#csrf_token").val());
    }
}
function changeEmail(email) {
    clearErrors();
    if (email.length > 0 && validateEmail(email)) {
        var settings = new Object();
        settings.url = "/projects/LaravelBlog/public/user/changeEmail";
        settings.data = JSON.stringify({ email : email });
        settings.success = function(data) {
            if(data == "true") {
                $("#emailSpan").html(email);
                $("#changeEmailModal").modal('hide');
            }

            return true;
        };
        ajaxPost(settings, true, $("#csrf_token").val());
    }
}

function changePassword(oldPassword, newPassword) {
    clearErrors();
    if (oldPassword.length > 0 && newPassword.length > 0) {
        var settings = new Object();
        settings.url = "/projects/LaravelBlog/public/user/changePassword";
        settings.data = JSON.stringify({ oldPassword : oldPassword, newPassword : newPassword });
        settings.success = function(data) {
            if(data == "true") {
                $("#changeEmailModal").modal('hide');
            } else {
                $("#password").addError("Old password is not correct");
            }
            return true;
        };
        ajaxPost(settings, true, $("#csrf_token").val());
    }
}
function sendCommentId(url, commentId, csrfToken) {
    if(csrfToken == undefined) csrfToken = $("#csrf_token").val();
    var settings = new Object();
    settings.url = url;
    settings.data = JSON.stringify({ commentId: parseInt(commentId, 10) });
    settings.success = function(data) {
        if(data == "true") {
            if (url.indexOf("unapprove") > 0) {
                $("input[data-chbx-cmt-id='" + commentId + "']").parent().closest('td').next('td').html('');
                alertMsg('Comment(s)', 'update', '#resCmtMsg');
            } else if (url.indexOf("approve") > 0) {
                $("input[data-chbx-cmt-id='" + commentId + "']").parent().closest('td').next('td').html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                alertMsg('Comment(s)', 'update', '#resCmtMsg');
            } else if (url.indexOf("delete") > 0) {
                $("input[data-chbx-cmt-id='" + commentId + "']").parent().parent().remove();
                alertMsg('Comment(s)', 'delete', '#resCmtMsg');
            }
            $("input[name='comment']").prop("checked", false);
            return true;
        }
    };
    ajaxPost(settings, false, csrfToken);
}
function sendPostId(url, postId, csrfToken) {
    if(csrfToken == undefined) csrfToken = $("#csrf_token").val();
    var settings = new Object();
    settings.url = url;
    settings.data = JSON.stringify({ postId: parseInt(postId, 10) });
    settings.success = function(data) {
        if(data == "true") {
            if (url.indexOf("hide") > 0) {
                $("input[data-chbx-post-id='" + postId + "']").parent().closest('td').next('td').html('');
                alertMsg('Post(s)', 'update', '#resPostMsg');
            } else if (url.indexOf("show") > 0) {
                $("input[data-chbx-post-id='" + postId + "']").parent().closest('td').next('td').html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                alertMsg('Post(s)', 'update', '#resPostMsg');
            } else if (url.indexOf("delete") > 0) {
                $("input[data-chbx-post-id='" + postId + "']").parent().parent().remove();
                alertMsg('Post(s)', 'delete', '#resPostMsg');
            }
            $("input[name='post']").prop("checked", false);
            return true;
        }
    };
    ajaxPost(settings, false, csrfToken);
}

$(function() {
    $('#commentsTable').DataTable();
    $('#postsTable').DataTable();
    $(".showComment").click(function(e) {
        e.preventDefault();
        var id = $(this).attr("data-commentId");
        $("#modal-" + id).modal('show');
    });
    $("#approveComments").click(function(e) {
        e.preventDefault();
        $("input[name='comment']:checked").each(function() {
            if(this.value != "") {
                approveComment(this.value);
            }
        });
    });
    $("#unapproveComments").click(function(e) {
        e.preventDefault();
        $("input[name='comment']:checked").each(function() {
            if(this.value != "") {
                unapproveComment(this.value);
            }
        });
    });
    $("#yesDelete").click(function(e) {
        e.preventDefault();
        if($(this).attr("data-delete-type") == "comments") {
            $("input[name='comment']:checked").each(function() {
                if (this.value != "") {
                    deleteComment(this.value);
                }
            });
        } else if($(this).attr("data-delete-type") == "posts") {
            $("input[name='post']:checked").each(function() {
                if (this.value != "") {
                    deletePost(this.value);
                }
            });
        }
        $("#confirmDeleteModal").modal('hide');
    });
    $("#deleteCommentModal").click(function(e) {
        e.preventDefault();
        if($("input[name='comment']:checked").size() > 0) {
            $("#yesDelete").attr("data-delete-type", "comments");
            $("#confirmDeleteModal").modal('show');
        }
    });
    $("#deletePostModal").click(function(e) {
        e.preventDefault();
        if($("input[name='post']:checked").size() > 0) {
            $("#yesDelete").attr("data-delete-type", "posts");
            $("#confirmDeleteModal").modal('show');
        }
    });
    $("#hidePosts").click(function(e) {
        e.preventDefault();
        $("input[name='post']:checked").each(function() {
            if(this.value != "") {
                hidePost(this.value);
            }
        })
    });
    $("#showPosts").click(function(e) {
        e.preventDefault();
        $("input[name='post']:checked").each(function() {
            if(this.value != "") {
                showPost(this.value);
            }
        })
    });
    $("#changeUsername").click(function(e) {
        e.preventDefault();
        $("#changeNameModal").modal('show');
    });
    $("#changeEmail").click(function(e) {
        e.preventDefault();
        $("#changeEmailModal").modal('show');
    });
    $("#changePassword").click(function(e) {
        e.preventDefault();
        $("#changePasswordModal").modal('show');
    });
    $("#submitName").click(function(e) {
        e.preventDefault();
        if ($("#username").val() != "" && $("#username").val().length > 3) {
            changeUsername($("#username").val());
        } else {
            $("#username").addError("The name field must be at least 3 characters long", "username");
        }
    });
    $("#submitEmail").click(function(e) {
        e.preventDefault();
        if ($("#email").val() != "" && validateEmail($("#email").val())) {
            changeEmail($("#email").val());
        } else {
            $("#email").addError("The email must be of the format address@example.com", "email");
        }
    });
    $("#submitPassword").click(function(e) {
        e.preventDefault();
        clearErrors();
        var isValid = true;
        if (validatePasswordLength($("#oldPassword").val(), "#oldPassword")) {
            isValid = false;
        }
        if (validatePasswordLength($("#newPassword").val(), "#newPassword")) {
            isValid = false;
        }
        if ($("#newPassword").val() != $("#confirmPassword").val()) {
            $("#confirmPassword").addError("Confirm password does not match new password");
            isValid = false;
        }
        if (isValid) {
            changePassword($("#oldPassword").val(), $("#newPassword").val());
        }
    });
    var modals = ["#changePasswordModal", "#changeNameModal", "#changeEmailModal"];
    for(var i = 0, modal; modal = modals[i++];) {
        clearModalErrors(modal);
    }
    var inputs = ["#email", "#username", "#oldPassword", "#newPassword", "#confirmPassword"];
    for(var j = 0, input; input = inputs[j++];) {
        updateInputField(input);
    }
});