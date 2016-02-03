'use strict'; 

var simpleControllers = angular.module('simpleControllers', []);

simpleControllers.controller('postsController', ['$scope', '$http', function ($scope, $http) {
    $scope.title = 'Posts';
        var data;
        $scope.newPost = function (post) {
            data = {
                    Title: post.title, 
                    Content: post.content, 
                    Attachment: false
                };
            makePostRequest(
                "/posts/create", 
                data,
                "Your post has been created. You will now be taken back to the psots page.", 
                
                $http,
                $scope
            ); 
    }
}]);