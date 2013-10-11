'use strict';

var tilos = angular.module('tilos', ['ngRoute']);
     
tilos.config(['$routeProvider', function($routeProvider) {
   $routeProvider.when('/index', {
      templateUrl: 'partials/index.html',
      controller: 'IndexCtrl'
   }).when('/program', {
      templateUrl: 'partials/program.html',
      controller:  'ProgramCtrl'
   }).otherwise({
      redirectTo: '/index'
   });
}]);

tilos.controller('IndexCtrl', ['$scope', '$routeParams', function($scope, $routeParams) {
   $scope.test = "test";
}]);
var res;
tilos.controller('ProgramCtrl', ['$scope', '$routeParams', '$http', function($scope, $routeParams, $http) {
   $http.get('episode.json').success(function(data) {
       $scope.episodes = data;
   });
}]);
