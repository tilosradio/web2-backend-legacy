'use strict';

angular.module('tilosApp')
  .controller('AuthorCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  $http.get($server + '/api/v0/author/' + $routeParams.id).success(function (data) {
    $scope.author = data;
  });
}]);