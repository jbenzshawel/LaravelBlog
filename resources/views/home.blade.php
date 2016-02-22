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
                                    &nbsp;
                                </th>
                                <th>
                                    Approved
                                </th>
                                <th>
                                    Post ID
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
                                        <input type="checkbox" value="{{ $comment->ID }}" name="comment">
                                    </td>
                                    <td class="center-text">
                                        {!! filter_var($comment->Approved, FILTER_VALIDATE_BOOLEAN) ?  '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' :  '' !!}
                                    </td>
                                    <td>
                                        {{ $comment->PostID }}
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
                        <button class="btn btn-warning" id="unapproveComments">Hide Selected</button>
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
    <div class="modal fade bs-example-modal-sm" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                Are you sure you want to delete the selected comments?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="yesDelete">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        "use strict";
        window.alertSuccess = '<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong>Success!</strong> Comments status have been updated.' +
                '</div>';
        function sendCommentId(url, commentId, csrfToken) {
            if(csrfToken == undefined) csrfToken = $("#csrf_token").val();
            var settings = new Object();
            settings.url = url;
            settings.data = JSON.stringify({ commentId: parseInt(commentId, 10) });
            settings.success = function(data) {
                if(data == "true") {
                    if (url.indexOf("unapprove") > 0) {
                        $("input[value='" + commentId + "']").parent().closest('td').next('td').html('');
                    } else if (url.indexOf("approve") > 0) {
                        $("input[value='" + commentId + "']").parent().closest('td').next('td').html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                    } else if (url.indexOf("delete") > 0) {
                        $("input[value='" + commentId + "']").parent().parent().remove();
                    }
                    $("input[type='checkbox']").prop("checked", false);
                    return true;
                }
            };
            ajaxPost(settings, false, csrfToken);
        }
        function approveComment(commentId) {
            if(!isNaN(parseInt(commentId, 10))) {
                sendCommentId("/projects/LaravelBlog/public/posts/approveCommentPostback", commentId);
                return true;
            }
            return false;
        }
        function unapproveComment(commentId) {
            if(!isNaN(parseInt(commentId, 10))) {
                sendCommentId("/projects/LaravelBlog/public/posts/unapproveCommentPostback", commentId);
                return true;
            }
            return false;
        }
        function deleteComment(commentId) {
            if(!isNaN(parseInt(commentId, 10))) {
                sendCommentId("/projects/LaravelBlog/public/posts/deleteCommentPostback", commentId);
                return true;
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
            $("#unapproveComments").click(function(e) {
                e.preventDefault();
                $("input[type='checkbox']:checked").each(function() {
                    if(this.value != "") {
                        unapproveComment(this.value);
                    }
                });
            });
            $("#yesDelete").click(function(e) {
                e.preventDefault();
                $("input[type='checkbox']:checked").each(function() {
                    if(this.value != "") {
                        deleteComment(this.value);
                    }
                });
                $("#confirmDeleteModal").modal('hide');
            })

        });
    </script>
@endsection