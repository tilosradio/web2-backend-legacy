'use strict';

angular.module('tilosApp')
  .controller('AuthorEditCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, server, $http) {
    if ($routeParams.id) {
      $http.get(server + '/api/v0/author/' + $routeParams.id, {cache: true}).success(function (data) {
        $scope.author = data;
      });
    } else {
      $scope.author = [];
    }
    $scope.save = function () {
      if ($scope.author.id) {
        $http.put(server + '/api/v0/author/' + $routeParams.id, $scope.author).success(function (data) {
          $scope.author = data;
        });
      } else {

      }
    }
  }]);