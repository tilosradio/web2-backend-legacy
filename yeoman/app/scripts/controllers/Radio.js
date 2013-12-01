'use strict';

angular.module('tilosApp')
	.controller('RadioCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
	function ($scope, $routeParams, $server, $td, $http) {
		var nowDate = new Date();
		var start = (nowDate / 1000 - 60 * 60 * 3);
		var now = nowDate.getTime() / 1000;
		$scope.now = new Date();
		$http.get($server + '/api/episode?start=' + start + '&end=' + (start + 12 * 60 * 60)).success(function (data) {
			for (var i = 0; i < data.length; i++) {
				if (data[i].from <= now && data[i].to > now) {
					$scope.current = data[i];
				}
			}
			$scope.episodes = data;
			$http.get($server + '/api/show/' + $scope.current.show.id).success(function (data) {
				$scope.current.show = data;
			});
		});

	}
]);
