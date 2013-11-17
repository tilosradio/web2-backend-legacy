/*global angular*/

angular.module('tilosApp')
	.controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http',
	function ($scope, $routeParams, $server, $http) {
		'use strict';
		var from = (tilos.weekStart(new Date()) / 1000);
		var to = from + 7 * 24 * 60 * 60;
		$scope.program = {};
		var refDate = new Date();
		refDate.setHours(0);

		refDate.setSeconds(0);
		refDate.setMinutes(0);
		refDate.setMilliseconds(0);
		refDate = refDate.getTime() / 1000;
		var processResult = function (data) {

			var result = $scope.program;
			//index episodes by day
			for (var i = 0; i < data.length; i++) {
				var idx = Math.floor((data[i].from - refDate) / (60 * 60 * 24));
				data[i].idx = idx;
				if (!result[idx]) {
					result[idx] = {
						episodes: []
					};
				}
				result[idx].episodes.push(data[i]);
			}

			//sort every day
			var sortFunction = function (a, b) {
				return a.from - b.from;
			};
			for (var key in result) {
				result[key].episodes.sort(sortFunction);
				result[key].date = result[key].episodes[0].from*1000;
			}
			$scope.program = result;

		};

		$scope.currentDay = 0;
		$scope.prev = function () {
			if ($scope.program[$scope.currentDay - 1]) {
				$scope.currentDay--;
			} else {
				var oldFrom = from;
				from = from - 7 * 24 * 60 * 60;
				$http.get($server + '/api/episode?start=' + from + '&end=' + oldFrom).success(function (data) {
					$scope.currentDay--;
					processResult(data);
				});
			}
		};

		$scope.next = function () {
			if ($scope.program[$scope.currentDay + 1]) {
				$scope.currentDay++;
			} else {
				var oldTo = to;
				to = to + 7 * 24 * 60 * 60;
				$http.get($server + '/api/episode?start=' + oldTo + '&end=' + to).success(function (data) {
					$scope.currentDay++;
					processResult(data);
				});
			}
		};

		$http.get($server + '/api/episode?start=' + from + '&end=' + to).success(function (data) {
			processResult(data);
		});

		$scope.closeText = "Close";
		$scope.toggleWeeksText = "Weeks";
		$scope.currentText = "Today";
		$scope.clearText = "Clear";
	}
]);