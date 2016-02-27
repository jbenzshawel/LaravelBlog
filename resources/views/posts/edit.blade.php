@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        	<div class="panel panel-default">
                <div class="panel-heading">Edit Post</div>

                <div class="panel-body post">
                    <div id="postbackResult"></div>
                    <form class="col-md-10 col-md-offset-1">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="crsf_token"/>
                     	<div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Post Title" value="{{ $post->title }}"/>
                        </div>

                            <div id="content">{!! $post->content !!}</div>

                        <div class="form-group center-button">
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
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.5.1/summernote.min.js" type="text/javascript"></script>
<script type="text/javascript">
    'use strict';
    var post = {!! json_encode($post) !!};
    function updatePost() {
        clearErrors();
        var $title = $("#title");
        var content = $("#content").code();
        var isValid = true;
        if($title.val().trim().length == 0 || $title.val() == "") {
            $title.addError("The title field is required");
            isValid = false; 
        }
        if(content.trim().length == 0 || content == "") {
            $("#content").addError("The title content is required");
            isValid = false;
        }
        if(isValid) {
            var model = {
                id: post.id,
                title : $title.val(),
                content : content
            };
            var settings = new Object(); 
            settings.url = "/projects/LaravelBlog/public/posts/create";
            settings.data = JSON.stringify(model),
            settings.success = function(data) {
                if(data == "true") {
                    $("#postbackResult").html("<div class=\"alert alert-success alert-dismissable\">" +
                                              "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                                              "Your post has been updated!</div>");
                }
            };
            ajaxPost(settings, true, $("#crsf_token").val());
        }
    }

    $(function() {
        $("#submitPost").click(function(e) {
            e.preventDefault();
            updatePost();
        });
        $("#content").summernote({height:300});
    }) ;

</script>
@endsection