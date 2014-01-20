'use strict';
angular.module('tilosAdmin')
    .directive('activeLink', ['$location', function (location) {
      return {
        restrict: 'A',
        link: function (scope, element, attrs) {
          var clazz = attrs.activeLink;
          //TODO it shoud be more error prone
          var path = element.children()[0].attributes[0].value;
          path = path.substring(1 + path.indexOf('#'));
          if (path.charAt(0) !== '/') {
            path = '/' + path;
          }
          scope.location = location;
          scope.$watch('location.path()', function (newPath) {
            if (path === newPath) {
              element.addClass(clazz);
            } else {
              element.removeClass(clazz);
            }
          });

        }
      };
    }]);