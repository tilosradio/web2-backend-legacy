'use strict';

angular.module('tilosApp')
  .controller('PageCtrl', ['$scope', '$routeParams', 'tilosData', '$location','$anchorScroll', function ($scope, $routeParams, $td, $location, $anchorScroll) {
  $td.getText($routeParams.id, function (data) {
    $scope.page = data;

	$scope.gotoTop = function (){
		$location.hash('top');
		$anchorScroll();
	};

	setTimeout(function(){
		$scope.windowHeight = document.getElementById('page').offsetHeight;
		if($scope.windowHeight > 1000){
			$scope.showLink = true;
		}else{
			$scope.showLink = false;
		}
	});

  });
}]);