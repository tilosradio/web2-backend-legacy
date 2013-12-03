'use strict';
/**
 * Get the episodes array
 */

/*global angular*/

angular.module('tilosApp')
  .controller('EpisodesCtrl', ['$rootScope', '$routeParams', 'API_SERVER_ENDPOINT', 'tilosData', '$http',
    function ($scope, $routeParams, $server, $td, $http) {
      $scope.now = new Date();

      $td.getCurrentEpisodes(function (current, data) {
        $scope.current = current;
        $scope.episodes = data;

      });

    }
  ]);
