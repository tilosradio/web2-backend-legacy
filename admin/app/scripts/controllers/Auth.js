'use strict';

angular.module('tilosAdmin').controller('AuthCtrl', function ($scope, $routeParams, API_SERVER_ENDPOINT, $http, $location) {
  $scope.logout = function () {
    $http.get(API_SERVER_ENDPOINT + '/api/v0/auth/sign_out').success(function (data) {
      $scope.user = null;
      $location.path('/login');
    });
  };
});