/*global angular, window*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */

var tilos = angular.module('tilosApp', ['ngRoute', 'ngSanitize', 'configuration', 'ui.bootstrap']);

tilos.weekStart = function (date) {
  'use strict';
  var first = date.getDate() - date.getDay() + 1;
  date.setHours(0);
  date.setSeconds(0);
  date.setMinutes(0);
  return new Date(date.setDate(first));
};

tilos.config(['$routeProvider', function ($routeProvider) {
  'use strict';
  $routeProvider.when('/index', {
    templateUrl: 'partials/index.html',
    controller: 'IndexCtrl'
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
  }).otherwise({
    redirectTo: '/index'
  });
}]);

var DatepickerCtrl = function ($scope, $timeout) {
	$scope.today = function() {
		$scope.dt = new Date();
	};
	$scope.today();

	$scope.showWeeks = true;
	$scope.toggleWeeks = function () {
		$scope.showWeeks = ! $scope.showWeeks;
	};

	$scope.clear = function () {
		$scope.dt = null;
	};

	$scope.toggleMin = function() {
		$scope.minDate = ( $scope.minDate ) ? null : new Date();
	};
	$scope.toggleMin();

	$scope.open = function() {
		$timeout(function() {
			$scope.opened = true;
		});
	};

	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1
	};
};

var server = window.location.protocol + '//' + window.location.hostname;
if (window.location.port && window.location.port !== '9000') {
  server = server + ':' + window.location.port;
}
angular.module('configuration', []).constant('API_SERVER_ENDPOINT', server);

