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
                        <div class="new-comment">
                            <div id="postbackResult"></div>
                            <form class="col-md-8 col-md-offset-2" id="createComment">
                                <div class="form-group">
                                    <button type="button" class="close hideComment" alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
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
                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="csrf_token"/>
                                <div class="form-group center-button">
                                    <button type="submit" class="btn btn-default" id="saveComment">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="comment-list col-md-10 col-md-offset-1" id="listComments">
                            <div style="text-align: center;width:100%;">
                                <a href="#" id="newComment" style="font-weight: 300">New Comment</a>
                            </div>

                        @if($CommentsList != null)
                                @foreach($CommentsList as $comment)
                                    @if(isset($comment) && $comment->Approved)
                                    <div class="comment">
                                        <header>{{ $comment->Name }} <em> {{ date('F d, Y h:i:s A', strtotime($comment->DateCreated)) }}</em></header>
                                        <p>{{ $comment->Comment }}</p>
                                        <footer><a href="#" class="reply" data-commentId="{{$comment->ID}}">reply</a></footer>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
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
    function createComment(parentId) {
        var hasParent = true;
        if (parentId  == undefined) {
            parentId = null;
            hasParent = false;
        }
        LB$.clearErrors();
        var $name = $("#name");
        var $email = $("#email");
        var $comment = $("#comment");
        var postId =  "{{ $post->id }}";
        var isValid = true;

        if($name.val() == "" || $name.val() == null) {
            $name.addError("The name field is required", "name");
            isValid = false;
        }
        if(!LB$.validateEmail($email.val())) {
            $email.addError("A valid email is required", "email");
            isValid = false;
        }
        if($comment.val() == "" || $comment.val() == null) {
            $comment.addError("The comment field is required", "comment");
            isValid = false;
        }

        if(isValid) {
            var model = {
                PostId: postId,
                Name : $name.val(),
                Email : $email.val(),
                Comment : $comment.val(),
                HasParent : hasParent,
                ParentId : parentId
            };
            var settings = new Object();
            settings.url = "/projects/LaravelBlog/public/posts/createComment";
            settings.data = JSON.stringify(model);
            settings.success = function(data) {
                if(data == "true") {
                    $("#postbackResult").html("<div class=\"alert alert-success alert-dismissable\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "Your comment has been created! Once it has been reviewed it will be displayed.</div>");
                    $("#createComment").hide();
                }
            };
            LB$.post(settings, true, $("#csrf_token").val());
        }
    }

    $(function() {
       $("#createComment").hide();
        $("#newComment").click(function (e) {
            e.preventDefault();
            $("#createComment").show();
            $("#newComment").hide();
        });
        $(document).on('click', '.hideComment', function (e) {
            e.preventDefault();
            $("#createComment").hide();
            $("#newComment").show();
            var replyId = $("[id=replyComment]:visible").attr("data-replyId");
            $("[id=replyComment]:visible").hide();
            $("[data-commentId=" + replyId + "]").show();
            LB$.clearErrors("createComment");
        });
        $(document).on('click', '.reply', function(e) {
            e.preventDefault();
            $(this).after('<form id="replyComment" data-replyId="' + $(this).attr("data-commentId") + '">' + $("#createComment").html() + '</form>');
            $(this).hide();
        });
        $("#saveComment").click(function (e) {
            e.preventDefault();
            createComment();
        });
        var fieldIds = [ "#name", "#email", "#comment" ];
        for (var i = 0, field; field = fieldIds[i++];) {
            LB$.updateInputField(field);
        }
    });
</script>

@endsection