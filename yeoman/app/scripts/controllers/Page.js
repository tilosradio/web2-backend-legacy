/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */
'use strict';

angular.module('tilosApp')
  .controller('PageCtrl', ['$scope', '$routeParams', 'tilosData', function ($scope, $routeParams, $td) {
  $td.getText($routeParams.id, function (data) {
    $scope.page = data;
  });
}]);