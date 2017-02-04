@extends('layouts.app')

@section('styles')
    <!-- Code Mirror -->
    <link rel="stylesheet" type="text/css" href="/projects/LaravelBlog/public/styles/codemirror/codemirror.css">
    <link rel="stylesheet" type="text/css" href="/projects/LaravelBlog/public/styles/codemirror/material.css">
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror.js"></script>
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror-mode/xml/xml.js"></script>
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror-mode/javascript/javascript.js"></script>
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror-mode/css/css.js"></script>
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror-mode/vbscript/vbscript.js"></script>
    <script type="text/javascript" src="/projects/LaravelBlog/public/scripts/vendor/codemirror/codemirror-mode/htmlmixed/htmlmixed.js"></script>
@endsection

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
                            <label for="content-editor">Content</label>
                            <textarea id="content-editor"></textarea>
                        </div>

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
<script type="text/javascript">
    'use strict';
    var mixedMode = {
        name: "htmlmixed",
        scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
            mode: null},
            {matches: /(text|application)\/(x-)?vb(a|script)/i,
                mode: "vbscript"}]
    };
    var contentEditor =  CodeMirror.fromTextArea(document.getElementById("content-editor"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: mixedMode,
        theme: "material"
    });

    $(function() {
        $("#submitPost").click(function(e) {
            e.preventDefault();
            createPost();
        });
        contentEditor.refresh();
    });

    function createPost() {
        LB$.clearErrors();
        var $title = $("#title");
        //var content = $("#content").code();
        var userID = "{{ $user->id }}";
        var isValid = true;
        if($title.val().trim().length == 0 || $title.val() == "") {
            $title.addError(ErrorMessages.Title);
            isValid = false;
        }
        if(content.trim().length == 0 || content == "") {
            $("#content").addError(ErrorMessages.Content);
            isValid = false;
        }
        if(isValid) {
            var model = {
                userID : parseInt(userID, 10),
                title : $title.val(),
                content : content
            };
            var settings = new Object();
            settings.url = "/projects/LaravelBlog/public/posts/create";
            settings.data = JSON.stringify(model);
            settings.success = function(data) {
                if(data == "true") {
                    $("#postbackResult").html("<div class=\"alert alert-success alert-dismissable\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "Your post has been created!</div>");
                }
            };
            LB$.post(settings, true, $("#crsf_token").val());
        }
    }

</script>
@endsection