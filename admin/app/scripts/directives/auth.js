angular.module("tilosAdmin").directive('ifAdmin', function ($rootScope) {
  return {
    restrict: 'A',
    link: function (scope, element, attributes) {
      if ($rootScope.user && $rootScope.user.role.name == 'admin') {
        element.removeClass('ng-hide');
      }
    }
  };
});


angular.module("tilosAdmin").directive('ifAuthorAdmin', function ($rootScope, $q) {
  return {
    restrict: 'E',
    transclude: true,
    template: '<span ng-transclude ></span>',
    scope: {
      xxx: "=author"
    },
    link: function (scope, element, attributes) {
      if ($rootScope.user && $rootScope.user.role.name == 'admin') {
        return;
      }
      scope.xxx.$promise.then(function(author){
        if ($rootScope.user.author && $rootScope.user.author.id != author.id) {
          alert('hide');
          element.addClass('ng-hide');
        } else {
          alert('show ' + author.id);
        }
      });




    }
  };
});