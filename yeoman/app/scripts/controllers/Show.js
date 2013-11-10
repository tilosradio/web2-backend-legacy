/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */
'use strict';

angular.module('tilosApp')
  .controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  $http.get($server + '/api/show/' + $routeParams.id).success(function (data) {
    $scope.show = data;
    $scope.server = $server;
  });
}]);