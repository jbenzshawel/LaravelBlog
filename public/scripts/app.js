'use strict';

var SimpleCMS = angular.module('SimpleCMS', [
    'ngRoute',
    'simpleControllers'
]);

SimpleCMS.config(['$routeProvider', 
  function ($routeProvider) {
        if (isAuthenticated()) {
            $routeProvider.
              when('/posts', {
                    templateUrl: '/partials/posts.html',
                    controller: 'postsController'
                })
              .when('/posts/create', {
                    templateUrl: '/partials/newPost.html',
                    controller: 'postsController'
                });
        }
        
    }
]);