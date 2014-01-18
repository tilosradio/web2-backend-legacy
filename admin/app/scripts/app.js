'use strict';

angular.module('tilosAdmin', [
      'ngCookies',
      'ngResource',
      'ngSanitize',
      'ngRoute',
      'configuration',
      'textAngular'
    ])
    .config(function ($routeProvider, $locationProvider, $httpProvider) {
      $httpProvider.defaults.withCredentials = true;

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

angular.module('tilosAdmin').run(function ($rootScope, $location, $http, API_SERVER_ENDPOINT) {

  $rootScope.$on('$locationChangeStart', function (evt, next) {

    var endsWith = function (str, suffix) {
      return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }
    if (!('user' in $rootScope)) {
      if (!/.*password_reset(\?.*)?/g.exec(next) && !endsWith(next, '/password_reminder') && !endsWith(next, '/login')) {
        $location.path("/login");
        //evt.preventDefault();
      }
    }

  });

  $rootScope.initialPath = $location.path();
  $http.get(API_SERVER_ENDPOINT + "/api/v0/user/me").success(function (data) {
    if (data && data.username) {
      $rootScope.user = data;
      if ($rootScope.initialPath) {
        var redirectTo = $rootScope.initialPath;
        $rootScope.initialPath = null;
        $location.path(redirectTo);

      }
    } else {
      $location.path("/login");
    }
  });
});
var server = window.location.protocol + '//' + window.location.hostname;
if (window.location.port && window.location.port !== '9000') {
  server = server + ':' + window.location.port;
}
//support admin deployment
server = server.replace("-admin", "-front");

var tilosHost = window.location.hostname;

angular.module('configuration', []).constant('API_SERVER_ENDPOINT', server);