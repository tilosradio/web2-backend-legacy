'use strict';
angular.module('tilosApp')
	.controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http',
	function ($scope, $routeParams, $server, $http) {
		var from = (tilos.weekStart(new Date()) / 1000);
		var to = from + 7 * 24 * 60 * 60;
		$scope.program = {};
		$scope.currentDay = 0;
		var refDate = new Date();
		refDate.setHours(0);

		refDate.setSeconds(0);
		refDate.setMinutes(0);
		refDate.setMilliseconds(0);
		refDate = refDate.getTime() / 1000;

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

		$scope.getDay = function () {
			var newValue = (new Date($scope.program[$scope.currentDay].date).getTime() / 1000);
			var oldFrom = newValue + (24 * 60 * 60);
			$scope.currentDay = Math.round(0 - ((new Date() - (oldFrom * 1000)) / (1000 * 60 * 60 * 24)));
			if (!$scope.program[$scope.currentDay]) {
				$http.get($server + '/api/episode?start=' + newValue + '&end=' + oldFrom).success(function (data) {
					processResult(data);
				});
			}
		};

		var processResult = function (data) {
			var result = $scope.program;
			//index episodes by day
			for (var i = 0; i < data.length; i++) {
				var idx = Math.floor((data[i].plannedFrom - refDate) / (60 * 60 * 24));
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
				return a.plannedFrom - b.plannedTo;
			};
			for (var key in result) {
				result[key].episodes.sort(sortFunction);
				result[key].date = result[key].episodes[0].plannedFrom * 1000;
			}
			$scope.program = result;

			if (result[$scope.currentDay] === undefined) {
				var oldFrom = from;
				from = from - 7 * 24 * 60 * 60;
				$http.get($server + '/api/episode?start=' + from + '&end=' + oldFrom).success(function (data) {
					processResult(data);
				});
			}
		};

		$http.get($server + '/api/episode?start=' + from + '&end=' + to).success(function (data) {
			processResult(data);
		});

		$scope.closeText = 'Close';
		$scope.toggleWeeksText = 'Weeks';
		$scope.currentText = 'Today';
		$scope.clearText = 'Clear';
	}
]);