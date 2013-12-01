'use strict';

angular.module('tilosApp')
	.controller('IndexCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
	function ($scope, $routeParams, $server, $td, $http) {
		$scope.Math = window.Math;
		$td.getNews(function (data) {
			$scope.news = data;
		});
	}
]);
