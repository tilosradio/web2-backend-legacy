/*global angular*/

angular.module('tilosApp')
	.controller('IndexCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
	function ($scope, $routeParams, $server, $td, $http) {
		'use strict';
		$td.getNews(function (data) {
			$scope.news = data;
		});
		var nowDate = new Date();
		var start = (nowDate / 1000 - 60 * 60 * 3);
		var now = nowDate.getTime() / 1000;
		$scope.now = new Date();
		$scope.Math = window.Math;
		$http.get($server + '/api/episode?start=' + start + '&end=' + (start + 12 * 60 * 60)).success(function (data) {
			for (var i = 0; i < data.length; i++) {
				if (data[i].from <= now && data[i].to > now) {
					$scope.current = data[i];
				}
			}
			$scope.episodes = data;
		});

		//Get Facebook follower count
		$http.get('https://graph.facebook.com/tilosradio').success(function (data) {
			$scope.facebook = data;
		});
	}
]);
