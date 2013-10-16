'use strict';
var dbg;
var tilos = angular.module('tilos', ['ngRoute', 'configuration']);

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
        }).when('/page/:id', {
            templateUrl: 'partials/page.html',
            controller: 'PageCtrl'
        }).otherwise({
            redirectTo: '/index'
        });
    }]);

tilos.controller('IndexCtrl', ['$scope', '$routeParams', function($scope, $routeParams) {
        $scope.test = "test";
        var start = Math.round(d.getTime()/1000)
        $http.get($server + '/api/author/' + $routeParams.id).success(function(data) {
            $scope.author = data;
        });
    }]);

tilos.controller('AuthorCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/author/' + $routeParams.id).success(function(data) {
            $scope.author = data;
        });
    }]);

tilos.controller('PageCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/text/' + $routeParams.id).success(function(data) {
            $scope.page = data;
        });
    }]);

tilos.controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/show/' + $routeParams.id).success(function(data) {
            $scope.show = data;
        });
    }]);

tilos.controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        var currentDay = 3;
        $http.get($server + '/api/episode').success(function(data) {

            var refDate = new Date()
            refDate.setHours(0);

            refDate.setSeconds(0);
            refDate.setMinutes(0);
            refDate.setMilliseconds(0);
            var refDate = refDate.getTime() / 1000;

            var result = {}
            //index episodes by day
            for (var i = 0; i < data.length; i++) {
                var idx = Math.floor((data[i].from - refDate) / (60 * 60 * 24))
                data[i]['datestr'] = new Date(data[i].from * 1000).toString();
                data[i]['idx'] = idx
                if (!result[idx]) {
                    result[idx] = {'episodes': []}
                }
                result[idx].episodes.push(data[i])
            }

            //sort every day
            for (var key in result) {
                result[key].episodes.sort(function(a, b) {
                    return a.from - b.from
                })
                result[key].date = result[key].episodes[0].from
            }
            $scope.currentDay = currentDay;
            $scope.program = result;
            $scope.prev = function() {
                $scope.currentDay--;
            }
            $scope.next = function() {
                $scope.currentDay++;
            }
            dbg = $scope.program


        });
    }]);
