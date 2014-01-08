'use strict';

angular.module('tilosApp')
  .controller('AuthCtrl', ['$rootScope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, server, $http) {
    if (!$scope.user) {
      $http.get(server + '/api/v0/user/me').success(function (data) {
        $scope.user = data;
      });
    }
    $scope.logout = function () {
      $http.get(server + '/api/v0/auth/sign_out').success(function (data) {
        $scope.user = null;
      });
    };
  }]);