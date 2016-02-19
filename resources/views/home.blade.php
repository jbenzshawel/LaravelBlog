@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                 	<p>Hello {{ $user->name }}</p>
                 	<p>Your email: {{ $user->email }}</p>
                 	<p>Last updated: {{ $lastUpdated }}</p>
                    <h2>Approve Comments</h2>
                    <div id="resMsg"></div>
                    <table id="commentsTable" class="display table">
                        <thead>
                            <tr>
                                <th>
                                    Approve
                                </th>
                                <th>
                                    Commenter
                                </th>
                                <th>
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($CommentList))
                            @foreach($CommentList as $comment)
                                <tr>
                                    <td>
                                        <input type="checkbox" value="{{ $comment->ID }}" name="comment" {{ filter_var($comment->Approved, FILTER_VALIDATE_BOOLEAN) ?  'checked=true' :  '' }}">
                                    </td>
                                    <td>
                                        <a href="#" class="showComment" data-commentId="{{ $comment->ID }}">{{ $comment->Name }}</a>
                                    </td>
                                    <td>
                                        {{ date('F d, Y h:i:s A', strtotime($comment->DateCreated)) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="form-group">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="csrf_token"/>
                        <button class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm">Delete Selected</button>
                        <button class="btn btn-success" id="approveComments">Approve Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @if(isset($CommentList))
        @foreach($CommentList as $comment)
            <div class="modal fade" tabindex="-1" id="modal-{{ $comment->ID }}" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">{{ $comment->Name }}</h4>
                        </div>
                        <div class="modal-body">
                            <p>{{ $comment->Comment }}</p>
                            <p>{{ $comment->Email }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        @endforeach
    @endif
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                Are you sure you want to delete the selected comments?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-default" id="yesDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        "use strict";
        window.localStorage.clear();
        window.alertSuccess = '<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong>Success!</strong> Comments status have been updated.' +
                '</div>';
        function approveComment(commentId) {
            if(!isNaN(parseInt(commentId, 10))) {
                var settings = new Object();
                settings.url = "/projects/LaravelBlog/public/posts/approveCommentPostback";
                settings.data = JSON.stringify({ commentId: parseInt(commentId, 10) });
                settings.success = function(data) {
                    if(data == "true") {
                        $("#resMsg").html(window.alertSuccess);
                    }
                };
                ajaxPost(settings, false, $("#csrf_token").val() );
            }
            return false;
        }
        function deleteComment(commentId) {
            if(!isNaN(parseInt(commentId, 10))) {
                var settings = new Object();
                settings.url = "/projects/LaravelBlog/public/posts/deleteCommentPostback";
                settings.data = JSON.stringify({ commentId: parseInt(commentId, 10) });
                settings.success = function(data) {
                    if(data.status = "success") {
                        return true;
                    }
                };
                ajaxPost(settings, false, $("#csrf_token").val() );
            }
            return false;
        }
        $(function() {
            $('#commentsTable').DataTable();
            $(".showComment").click(function(e) {
                e.preventDefault();
                var id = $(this).attr("data-commentId");
                $("#modal-" + id).modal('show');
            });
            $("#approveComments").click(function(e) {
                e.preventDefault();
                $("input[type='checkbox']:checked").each(function() {
                   if(this.value != "") {
                       approveComment(this.value);
                   }
                });
            });
            $("input[type='checkbox']").change(function() {
                if($(this).attr("checked") == "checked") {

                }
            });
        });
    </script>
@endsection