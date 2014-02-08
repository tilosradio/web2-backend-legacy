'use strict';

angular.module('tilosApp').config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/episode/:id', {
    templateUrl: 'partials/episode.html',
    controller: 'EpisodeCtrl' });
}]);
/*global angular*/
angular.module('tilosApp')
  .controller('EpisodeCtrl', ['$rootScope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
    function ($scope, $routeParams, $server, $td, $http) {
      var id = $routeParams.id;
      $http.get($server + '/api/v0/episode/' + id, {cache: true}).success(function (data) {
        $scope.episode = data;
      });

    }
  ]);
