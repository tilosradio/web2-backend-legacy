'use strict';
/**
 * Datepicker settings
 */

var DatepickerCtrl = function ($scope, $timeout) {

  $scope.today = function () {
    $scope.dt = new Date();
  };
  $scope.today();

  $scope.showWeeks = true;
  $scope.toggleWeeks = function () {
    $scope.showWeeks = !$scope.showWeeks;
  };

  $scope.clear = function () {
    $scope.dt = null;
  };

  $scope.toggleMin = function () {
    $scope.minDate = ($scope.minDate) ? null : new Date();
  };

  $scope.open = function () {
    $timeout(function () {
      $scope.opened = true;
    });
  };
  $scope.goto = function () {
    $scope.gotoDay($scope.gotoDate);
  };
  $scope.dateOptions = {
    'year-format': "'yy'",
    'starting-day': 1
  };

};