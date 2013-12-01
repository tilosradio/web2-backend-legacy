'use strict';

angular.module('tilosApp')
	.directive('scroll', function ($window) {
    return function (scope) {
        angular.element($window).bind('scroll', function () {
          if (this.pageYOffset >= 100) {
            scope.boolChangeClass = true;
            console.log('Scrolled below header.');
          } else {
            scope.boolChangeClass = false;
            console.log('Header is in view.');
          }
          scope.$apply();
        });
      };
  });

