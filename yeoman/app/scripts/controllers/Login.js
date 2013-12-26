'use strict';

angular.module('tilosApp')
  .controller('LoginCtrl', ['$scope', '$location', 'API_SERVER_ENDPOINT', '$http', function ($scope, $location, server, $http) {
    $scope.logindata = [];
    $scope.login = function () {
      var data = {};
      data['username'] = 'admin';
      data.password = 'qweqweqweqwe';
      $http.post(server + '/api/v0/auth/sign_in', data).success(function (data) {
        if (data.success) {
          $location.path('/index');
        }

      });

    }
  }]);