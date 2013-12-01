'use strict';

angular.module('tilosApp')
  .controller('PageCtrl', ['$scope', '$routeParams', 'tilosData', function ($scope, $routeParams, $td) {
  $td.getText($routeParams.id, function (data) {
    $scope.page = data;
  });
}]);