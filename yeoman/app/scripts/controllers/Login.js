'use strict';

angular.module('tilosApp')
  .controller('LoginCtrl', ['$rootScope', '$scope', '$location', 'API_SERVER_ENDPOINT', '$http', function ($rootScope, $scope, $location, server, $http) {
    $scope.logindata = [];
    $scope.login = function () {
      var data = {};
      data['username'] = $scope.logindata.username;
      data.password = $scope.logindata.password;
      $http.post(server + '/api/v0/auth/sign_in', data).success(function (data) {
        if (data.success) {
          $rootScope.user = data;
          $location.path('/index');
        }

      });

    }
  }]);