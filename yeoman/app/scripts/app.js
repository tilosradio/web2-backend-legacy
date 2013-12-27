'use strict';
var tilos = angular.module('tilosApp', ['ngRoute', 'ngSanitize', 'configuration', 'ui.bootstrap']);

tilos.weekStart = function (date) {
  var first = date.getDate() - date.getDay() + 1;
  date.setHours(0);
  date.setSeconds(0);
  date.setMinutes(0);
  return new Date(date.setDate(first));
};

tilos.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/index', {
    templateUrl: 'partials/index.html',
    controller: 'RssNewsCtrl'
  }).when('/archive', {
    templateUrl: 'partials/program.html',
    controller: 'ProgramCtrl'
  }).when('/show/:id', {
    templateUrl: 'partials/show.html',
    controller: 'ShowCtrl'
  }).when('/author/:id', {
    templateUrl: 'partials/author.html',
    controller: 'AuthorCtrl'
  }).when('/page/:id', {
    templateUrl: 'partials/page.html',
    controller: 'PageCtrl'
  }).when('/shows', {
    templateUrl: 'partials/shows.html',
    controller: 'AllshowCtrl'
  }).when('/news/:id', {
    templateUrl: 'partials/news.html',
    controller: 'NewsCtrl'
  }).otherwise({
    templateUrl: '/partials/404.html',
    controller: '404Ctrl'
  });
}]);

var server = window.location.protocol + '//' + window.location.hostname;
if (window.location.port && window.location.port !== '9000') {
  server = server + ':' + window.location.port;
}

var tilosHost = window.location.hostname;

angular.module('configuration', []).constant('API_SERVER_ENDPOINT', server);

tilos.factory('validateUrl', function ($sce) {
  return {
    getValidUrl: function (url) {
      return $sce.trustAsResourceUrl(url);
    }
  };
});
