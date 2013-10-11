'use strict';

var tilos = angular.module('tilos', ['ngRoute']);

tilos.config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/index', {
            templateUrl: 'partials/index.html',
            controller: 'IndexCtrl'
        }).when('/program', {
            templateUrl: 'partials/program.html',
            controller: 'ProgramCtrl'
        }).when('/show/:id', {
            templateUrl: 'partials/show.html',
            controller: 'ShowCtrl'
        }).when('/author/:id', {
            templateUrl: 'partials/author.html',
            controller: 'AuthorCtrl'
        }).otherwise({
            redirectTo: '/index'
        });
    }]);

tilos.controller('IndexCtrl', ['$scope', '$routeParams', function($scope, $routeParams) {
        $scope.test = "test";
    }]);

tilos.controller('AuthorCtrl', ['$scope', '$routeParams', '$http', function($scope, $routeParams, $http) {
        $http.get('http://localhost/api/author/' + $routeParams.id).success(function(data) {
            $scope.author = data;
        });
    }]);

tilos.controller('ShowCtrl', ['$scope', '$routeParams', '$http', function($scope, $routeParams, $http) {
        $http.get('http://localhost/api/show/' + $routeParams.id).success(function(data) {
            $scope.show = data;
        });
    }]);

tilos.controller('ProgramCtrl', ['$scope', '$routeParams', '$http', function($scope, $routeParams, $http) {
        $http.get('http://localhost/api/episode').success(function(data) {
            $scope.episodes = data;
        });
    }]);
