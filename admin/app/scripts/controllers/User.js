'use strict';

angular.module('tilosAdmin').config(function ($routeProvider) {
  $routeProvider.when('/user/me', {
    templateUrl: 'views/user.html',
    controller: 'UserCtrl'
  });
});

angular.module('tilosAdmin')
    .controller('UserCtrl', function ($scope) {
    });

