'use strict';
angular.module('tilosApp')
	.controller('SocialCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
	function ($scope, $routeParams, $server, $td, $http) {
		$scope.Math = window.Math;
		//Get Facebook follower count
		$http.get('https://graph.facebook.com/tilosradio').success(function (data) {
			$scope.facebook = data;
		});
	}
]);
