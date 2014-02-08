'use strict';

angular.module('tilosAdmin').config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/page/:id', {
    templateUrl: 'views/page.html',
    controller: 'TextCtrl',
    resolve: {
      data: function ($route, Texts) {
        return Texts.get({id: $route.current.params.id});
      },
    }});

  $routeProvider.when('/edit/page/:id', {
    templateUrl: 'views/page-form.html',
    controller: 'TextEditCtrl',
    resolve: {
      data: function ($route, Texts) {
        return Texts.get({id: $route.current.params.id});
      },
    }});
}]);


angular.module('tilosAdmin')
    .controller('TextEditCtrl', function ($http, $routeParams, API_SERVER_ENDPOINT, $location, $scope, Texts, $cacheFactory, data) {
      $scope.text = data;
      $scope.save = function () {
        $http.put(API_SERVER_ENDPOINT + '/api/v0/text/' + $scope.text.id, $scope.text).success(function (data) {
          var httpCache = $cacheFactory.get('$http');
          httpCache.remove(API_SERVER_ENDPOINT + '/api/v0/text/' + $scope.text.id);
          httpCache.remove(API_SERVER_ENDPOINT + '/api/v0/show');
          $location.path('/page/' + $scope.text.id);
        });
      }
    }
);
;

angular.module('tilosAdmin')
    .controller('TextCtrl', function ($scope, data) {
      $scope.page = data;


    });


angular.module('tilosAdmin').factory('Texts', ['API_SERVER_ENDPOINT', '$resource', function (server, $resource) {
  return $resource(server + '/api/v0/text/:id', null, {
    'update': { method: 'PUT'}
  });
}]);


