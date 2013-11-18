/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */
'use strict';

angular.module('tilosApp')
	.controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
		$http.get($server + '/api/show/' + $routeParams.id).success(function (data) {
			$scope.show = data;
			$scope.server = $server;
		});

		//@TODO: this section is from index.js, should be elsewhere so it could be reused, not copied!!
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
	}
]);