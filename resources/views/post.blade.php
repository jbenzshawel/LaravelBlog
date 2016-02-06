@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>

                    <div class="panel-body posts">
                        <h2>{{ $post->title }}</h2>
                        <p>{!! $post->content !!}</p>

                        <h2>Comments</h2>
                        <div class="comment-list"></div>

                        <div class="new-comment">
                            <div id="postbackResult"></div>
                            <a href="#" id="newComment">New Comment</a>
                            <form class="col-md-8 col-md-offset-2" id="createComment">
                                <div class="form-group">
                                    <label for="name">
                                       Name
                                    </label>
                                    <input id="name" type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        Email
                                    </label>
                                    <input id="email" type="text" name="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="comment">
                                        Comment
                                    </label>
                                    <textarea id="comment" type="text" name="comment" class="form-control"></textarea>
                                </div>
                                <div class="form-group center-button">
                                    <button type="submit" class="btn btn-default" id="saveComment">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    "use strict";
    function createComment() {
        $(".error-message").remove();
        $(".input-error").removeClass("input-error");
        var $name = $("#name");
        var $email = $("#email");
        var $comment = $("#comment");
        var isValid = true;

        if($name.val() == "" || $name.val() == null) {
            $name.after("<div class=\"name error-message text-danger\">The name field is required</div>");
            $name.addClass("input-error");
            isValid = false;
        }
        if(!validateEmail($email.val())) {
            $email.after("<div class=\"email error-message text-danger\">A valid email is required</div>");
            $email.addClass("input-error");
            isValid = false;
        }
        if($comment.val() == "" || $comment.val() == null) {
            $comment.after("<div class=\"comment error-message text-danger\">The comment field is required</div>");
            $comment.addClass("input-error");
            isValid = false;
        }

        if(isValid) {
            var model = {
                name : $name.val(),
                email : $email.val(),
                comment : $comment.val()
            };
            var settings = new Object();
            settings.url = "/projects/LaravelBlog/public/posts/createCommentPostback";
            settings.type = "POST";
            settings.contentType = "application/json";
            settings.data = JSON.stringify(model),
                    settings.headers = { 'X-CSRF-TOKEN' : $("#crsf_token").val() },
                    settings.success = function(data) {
                        if(data == "true") {
                            $("#postbackResult").html("<div class=\"alert alert-success alert-dismissable\">" +
                                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                                    "Your comment has been created!</div>");
                        }
                    };
            $.ajax(settings);
        }
    }

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $(function() {
       $("#createComment").hide();
        $("#newComment").click(function (e) {
            e.preventDefault();
            $("#createComment").show();
            $("#newComment").hide();
        });
        $("#saveComment").click(function (e) {
            e.preventDefault();
            createComment();
        })
        $("#name").change(function() {
           if(this.value != "") {
               $(this).removeClass("input-error");
               $(".name.error-message").remove();
           }
        });
        $("#email").change(function() {
            if(validateEmail(this.value)) {
                $(this).removeClass("input-error");
                $(".email.error-message").remove();
            }
        });
        $("#comment").change(function() {
            if(this.value != "") {
                $(this).removeClass("input-error");
                $(".comment.error-message").remove();
            }
        });
    });
</script>

@endsection