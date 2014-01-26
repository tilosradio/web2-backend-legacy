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
}]);
angular.module('tilosAdmin')
    .controller('TextCtrl', function ($scope, data) {
      $scope.page = data;


    });


angular.module('tilosAdmin').factory('Texts', ['API_SERVER_ENDPOINT', '$resource', function (server, $resource) {
  return $resource(server + '/api/v0/text/:id', null, {
    'update': { method: 'PUT'}
  });
}]);


