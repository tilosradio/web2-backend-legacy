'use strict';

angular.module('tilosApp').config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/password_reset', {
    templateUrl: 'partials/edit/reset.html',
    controller: 'ChangePasswordCtrl' });
}]);
angular.module('tilosApp')
  .controller('ChangePasswordCtrl', ['$rootScope', '$scope', '$location', 'API_SERVER_ENDPOINT', '$http', function ($rootScope, $scope, $location, server, $http) {
    $scope.newpassword = {};
    $scope.newpassword.token = ($location.search()).token;
    $scope.newpassword.email = ($location.search()).email;
    if (!$scope.newpassword.token || !$scope.newpassword.email) {
      $scope.error = "Az email vagy a token paraméter hiányzik";
    }
    $scope.reset = function () {
      $http.post(server + '/api/v0/auth/password_reset', $scope.newpassword).success(function (data) {
        if (data.success) {
          $scope.error = "";
          $scope.message = data.message + "<br/>Belépés a <a href=\"/login\">/login</a> címen";
        } else {
          $scope.error = "Password reset error";
        }
      }).error(function (data) {
          $scope.error = data.error;
        });
    };
  }]);
