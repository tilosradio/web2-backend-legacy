'use strict';
angular.module('tilosApp')
	.controller('SocialCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
	function ($scope, $routeParams, $server, $td, $http) {
		$scope.Math = window.Math;
		//Get Facebook follower count
		$http.get('https://graph.facebook.com/tilosradio').success(function (data) {
			$scope.facebook = data;
		});

		$scope.share = function(name, definition, alias){
			window.open(
				"http://www.facebook.com/sharer.php?s=100&p[title]="+encodeURIComponent(name)+"&p[summary]="+encodeURIComponent(definition)+"&p[url]="+encodeURIComponent("http://tilos.anzix.net/#/show/"+alias)+""
			);
		}

		$scope.like = function(name, definition, alias){

		}

	}
]);
