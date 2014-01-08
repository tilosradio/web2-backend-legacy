'use strict';

angular.module('tilosAdmin').config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/login', {
        templateUrl: 'views/login.html',
        controller: 'LoginCtrl',
      }
  );

}])
;

angular.module('tilosAdmin')
    .controller('LoginCtrl', ['$rootScope', '$scope', '$location', 'API_SERVER_ENDPOINT', '$http', function ($rootScope, $scope, $location, server, $http) {
      $scope.logindata = {};
      $scope.reminderdata = {};

      $scope.reminder = function () {


        $http.post(server + '/api/v0/auth/password_reset', $scope.reminderdata).success(function (data) {
          if (data.success) {
            $scope.message = data.message;
          } else {
            $scope.remindererror = "Password reset error";
          }
        }).error(function (data) {
              $scope.remindererror = data.error;
            });
      }
      ;
      $scope.login = function () {
        var data = {};
        $http.post(server + '/api/v0/auth/sign_in', $scope.logindata).success(function (data) {
          if (data.success) {
            $rootScope.user = data.data;
            $scope.loginerror = "";
            $http.get(server + '/api/v0/user/me').success(function (data) {
              $rootScope.user = data;
              $location.path('/index');
            });

          } else {
            $scope.loginerror = "Login error";
          }

        }).error(function (data) {
              if (data.error) {
                $scope.loginerror = data.error;
              } else {
                $scope.loginerror = "Unknown.error";
              }
            });


      }
    }
    ])
;