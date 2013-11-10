/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */

'use strict';

angular.module('tilosApp')
  .controller('CollapseCtrl', ['$scope', function ($scope) {
  $scope.isCollapsed = false;
}]);
