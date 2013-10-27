'use strict';
var dbg;
var tilos = angular.module('tilos', ['ngRoute', 'ngSanitize', 'configuration','ui.bootstrap']);

tilos.weekStart = function(date) {
    var first = date.getDate() - date.getDay() + 1;
    date.setHours(0)
    date.setSeconds(0)
    date.setMinutes(0)
    return new Date(date.setDate(first))
}

tilos.config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/index', {
            templateUrl: 'partials/index.html',
            controller: 'IndexCtrl'
        }).when('/archive', {
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
        }).when('/shows', {
            templateUrl: 'partials/shows.html',
            controller: 'AllShowCtrl'
        }).otherwise({
            redirectTo: '/index'
        });
    }]);

tilos.controller('SideCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        var start = (new Date() / 1000 - 60 * 60 * 3)
        var now = new Date().getTime() / 1000
        $scope.now = new Date();
        $http.get($server + '/api/episode?start=' + start + '&end=' + (start + 12 * 60 * 60)).success(function(data) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].from <= now && data[i].to > now) {                    
                    $scope.current = data[i]
                }
            }
            $scope.episodes = data;
            $http.get($server + '/api/show/' + $scope.current.show.id).success(function(data) {
                $scope.current.show = data;
            });
        });
        
    }]);



tilos.controller('Collapse', ['$scope', function($scope) {
         $scope.isCollapsed = false;
    }]);


tilos.controller('IndexCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/text/news/list').success(function(data) {
            $scope.news = data;
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

tilos.controller('AllShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/show').success(function(data) {
            $scope.shows = data;            
        });
    }]);

tilos.controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/show/' + $routeParams.id).success(function(data) {
            $scope.show = data;
            $scope.server = $server;
        });
    }]);

tilos.controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function($scope, $routeParams, $server, $http) {
        var from = (tilos.weekStart(new Date()) / 1000)
        var to = from + 7 * 24 * 60 * 60
        $scope.program = {}
        var refDate = new Date()
        refDate.setHours(0);

        refDate.setSeconds(0);
        refDate.setMinutes(0);
        refDate.setMilliseconds(0);
        var refDate = refDate.getTime() / 1000;
        var processResult = function(data) {
            var result = $scope.program;
            //index episodes by day
            for (var i = 0; i < data.length; i++) {
                var idx = Math.floor((data[i].from - refDate) / (60 * 60 * 24))
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
            $scope.program = result;
        }
        $scope.currentDay = 0;
        $scope.prev = function() {
            if ($scope.program[$scope.currentDay - 1]) {
                $scope.currentDay--;
            } else {
                var oldfrom = from
                from = from - 7 * 24 * 60 * 60;
                $http.get($server + '/api/episode?start=' + from + '&end=' + oldfrom).success(function(data) {
                    $scope.currentDay--;
                    processResult(data)
                });
            }
        }
        $scope.next = function() {
            if ($scope.program[$scope.currentDay + 1]) {
                $scope.currentDay++;
            } else {
                var oldto = to
                to = to + 7 * 24 * 60 * 60;
                $http.get($server + '/api/episode?start=' + oldto + '&end=' + to).success(function(data) {
                    $scope.currentDay++;
                    processResult(data)
                });
            }
        }
        $http.get($server + '/api/episode?start=' + from + '&end=' + to).success(function(data) {
            processResult(data)
        });
    }]);

tilos.directive('activeLink', ['$location', function(location) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs, controller) {
                var clazz = attrs.activeLink

                //TODO it shoud be more error prone
                var path = element.children()[0].href
                path = path.substring(1 + path.indexOf("#"))
                if (path.charAt(0) != '/') {
                    path = "/" + path
                }

                scope.location = location
                scope.$watch('location.path()', function(newPath) {
                    dbg = element;
                    if (path == newPath) {
                        element.addClass(clazz)
                    } else {
                        element.removeClass(clazz)
                    }
                })

            }
        }

    }]);
var server = window.location.protocol + "//" + window.location.hostname
if (window.location.port && window.location.port != "9000") server = server +":" + window.location.port
angular.module('configuration',[]).constant('API_SERVER_ENDPOINT', server)
