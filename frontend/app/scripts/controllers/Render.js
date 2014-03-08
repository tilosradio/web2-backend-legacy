'use strict';

angular.module('tilosApp').controller('RenderCtrl', function ($scope, FeedService, $http, API_SERVER_ENDPOINT, $location, $anchorScroll) {
	$scope.gotoTop = function (){
		$location.hash('top');
		$anchorScroll();
	};

	$scope.$on('$routeChangeSuccess', function () {
		//@todo setTimeout isn't the best choice here, but I couldn't find a better solution.
		setTimeout(function(){
			$scope.windowHeight = document.getElementById('mainIndex').offsetHeight;
			if($scope.windowHeight > 1000){
				$scope.showLink = true;
			}else{
				$scope.showLink = false;
			}
		},3000);
	});

});