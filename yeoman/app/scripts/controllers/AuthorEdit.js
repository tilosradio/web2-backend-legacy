'use strict';

angular.module('tilosApp')
  .controller('AuthorEditCtrl', ['$location', '$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', '$cacheFactory', function ($location, $scope, $routeParams, server, $http, $cacheFactory) {
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
          var httpCache = $cacheFactory.get('$http');
          httpCache.remove(server + '/api/v0/author/' + $scope.author.id);
          $location.path('/author/' + $scope.author.id);
        });
      } else {

      }
    }
  }]);