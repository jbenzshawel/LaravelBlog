@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        	<div class="panel panel-default">
                <div class="panel-heading">New Post</div>

                <div class="panel-body post">
                    <div id="postbackResult"></div>
                    <form class="col-md-10 col-md-offset-1">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="crsf_token"/>
                     	<div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Post Title"/>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" class="form-control" placeholder="Post Content"></textarea>
                        </div>
                        <div class="form-group" style="text-align:center; margin-top:50px; width:100%;">
                            <button type="submit" class="btn btn-default" id="submitPost">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    'use strict';
    function createPost() {
        $(".error-message").remove();
        $(".input-error").removeClass("input-error");
        var title = $("#title").val(); 
        var content = $("#content").val();
        var userID = "{{ $user->id }}";
        var isValid = true;
        if(title.length == 0 || title == "") {
            $("#title").after("<div class=\"error-message text-danger\">The title field is required</div>"); 
            $("#title").addClass("input-error"); 
            isValid = false; 
        }
        if(content.length == 0 || content == "") {
            $("#content").after("<div class=\"error-message text-danger\">The title content is required</div>"); 
            $("#content").addClass("input-error"); 
            isValid = false;
        }
        if(isValid) {
            var model = {
                userID : parseInt(userID, 10),
                title : title,
                content : content
            };
            var settings = new Object(); 
            settings.url = "/projects/LaravelBlog/public/posts/createPostback";
            settings.type = "POST";
            settings.contentType = "application/json";
            settings.data = JSON.stringify(model),
            settings.headers = { 'X-CSRF-TOKEN' : $("#crsf_token").val() },
            settings.success = function(data) {
                if(data == "true") {
                    $("#postbackResult").html("<div class=\"alert alert-success alert-dismissable\">" +
                                              "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                                              "Your post has been created!</div>");
                }
            };
            $.ajax(settings);
        }
    }

    $(function() {
        $("#submitPost").click(function(e) {
            e.preventDefault();
            createPost();
        });
    }) ;

</script>
@endsection