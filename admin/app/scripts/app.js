'use strict';

angular.module('tilosAdmin', [
      'ngCookies',
      'ngResource',
      'ngSanitize',
      'ngRoute',
      'configuration',
      'textAngular'
    ])
    .config(function ($routeProvider, $locationProvider) {
      $locationProvider.html5Mode(true);
      $routeProvider
          .when('/', {
            templateUrl: 'views/main.html',
            controller: 'MainCtrl'
          })
          .otherwise({
            redirectTo: '/'
          });
    });
var k;
angular.module('tilosAdmin').run(function ($rootScope, $location, $http) {
  $rootScope.$on('$locationChangeStart', function (evt, next) {
    var endsWith = function (str, suffix) {
      return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }
    if (!$rootScope.user) {
      if (!/.*password_reset\?.*/g.exec(next) && !endsWith(next, '/password_reminder') && !endsWith(next, '/login') ) {
        $location.url('/login');
      }
    }


  });
});
var server = window.location.protocol + '//' + window.location.hostname;
if (window.location.port && window.location.port !== '9000') {
  server = server + ':' + window.location.port;
}

var tilosHost = window.location.hostname;

angular.module('configuration', []).constant('API_SERVER_ENDPOINT', server);