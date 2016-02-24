@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="csrf_token"/>
                    <div class="account-section">
                        <h2>Your Account</h2>
                        <dl class="dl-horizontal">
                            <dt>Name:</dt>
                            <dd><span id="name">{{ $user->name }}</span> <a href="#" id="changeUsername"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></dd>

                            <dt>Email:</dt>
                            <dd>{{ $user->email }} <a href="#" id="changeEmail"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></dd>

                            <dt>Password:</dt>
                            <dd>•••••••• <a href="#" id="changePassword"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></dd>
                            <dt>Last updated:</dt>
                            <dd>{{ $lastUpdated }}</dd>
                        </dl>
                    </div>
                    <div class="posts-section">
                        <h2>Manage Posts</h2>
                        <div id="resPostMsg"></div>
                        <table id="postsTable" class="display table">
                            <thead>
                            <tr>
                                <th>
                                    &nbsp;
                                </th>
                                <th>
                                    Visible
                                </th>
                                <th>
                                    Post ID
                                </th>
                                <th>
                                    Post Title
                                </th>
                                <th>
                                    Date Created
                                </th>
                                <th>
                                    Last Updated
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($PostsList))
                                @foreach($PostsList as $post)
                                    <tr>
                                        <td>
                                            <input type="checkbox" value="{{ $post["id"] }}" data-chbx-post-id="{{ $post["id"] }}" name="post">
                                        </td>
                                        <td class="center-text">
                                            {!! $post["visible"] ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : '' !!}
                                        </td>
                                        <td>
                                            {{ $post["id"] }}
                                        </td>
                                        <td>
                                            <a href="#" class="showComment" data-commentId="{{ $post["id"] }}">{{ $post["title"] }}</a>
                                        </td>
                                        <td>
                                            {{ date('F d, Y h:i:s A', strtotime($post["dateCreated"])) }}
                                        </td>
                                        <td>
                                            {{ $post["lastUpdated"]  }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="form-group">
                            <button class="btn btn-danger" data-toggle="modal" id="deletePostModal">Delete Selected</button>
                            <button class="btn btn-warning" id="hidePosts">Hide Selected</button>
                            <button class="btn btn-success" id="showPosts">Show Selected</button>
                        </div>
                    </div>
                    <div class="comments-section">
                        <h2>Approve Comments</h2>
                        <div id="resCmtMsg"></div>
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
                                            <input type="checkbox" value="{{ $comment->ID }}" data-chbx-cmt-id="{{ $comment->ID }}" name="comment">
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
                            <button class="btn btn-danger" data-toggle="modal" id="deleteCommentModal">Delete Selected</button>
                            <button class="btn btn-warning" id="unapproveComments">Hide Selected</button>
                            <button class="btn btn-success" id="approveComments">Approve Selected</button>
                        </div>
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
                Are you sure you want to delete the selected items?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="yesDelete">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="changeNameModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Name</h4>
                </div>
                <div class="modal-body">
                   <form id="nameForm">
                       <div class="form-group">
                           <label for="username">New Name</label>
                           <input type="text" id="username" placeholder="New Name" class="form-control"/>
                       </div>
                   </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="submitName">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" tabindex="-1" id="changeEmailModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Email</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="email">New Email</label>
                            <input type="text" id="email" placeholder="New Email" class="form-control"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" tabindex="-1" id="changePasswordModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="oldPassword">Old Password</label>
                            <input type="password" id="oldPassword" placeholder="Old Password" class="form-control"/>
                        </div>
                    </form>
                    <form>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" placeholder="New Password" class="form-control"/>
                        </div>
                    </form>
                    <form>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" placeholder="Confirm Password" class="form-control"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        "use strict";
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
        function showPost(postId) {
            if(!isNaN(parseInt(postId, 10))) {
                sendPostId("/projects/LaravelBlog/public/posts/showPostback", postId);
                return true;
            }
            return false;
        }
        function hidePost(postId) {
            if(!isNaN(parseInt(postId, 10))) {
                sendPostId("/projects/LaravelBlog/public/posts/hidePostback", postId);
                return true;
            }
            return false;
        }
        function deletePost(postId) {
            if(!isNaN(parseInt(postId, 10))) {
                sendPostId("/projects/LaravelBlog/public/posts/deletePostback", postId);
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
                        clearErrors("nameForm");
                        $("#changeNameModal").modal('hide');
                        return true;
                    }
                }
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
                if($("#username").val() != "" && $("#username").val().length > 3) {
                    changeUsername($("#username").val());
                } else {
                    $("#username").addError("The name field must be at least 3 characters long", "username");
                }
            });
            $("#changeNameModal").on('hidden.bs.modal', function () {
                var id = $(this).find('form').attr('id');
                clearErrors(id);
            })
        });
    </script>
@endsection