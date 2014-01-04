'use strict';

angular.module('tilosApp')
  .controller('EpisodeNewCtrl', ['$location', '$scope', 'API_SERVER_ENDPOINT', '$http', '$cacheFactory', '$rootScope', function ($location, $scope, server, $http, $cacheFactory, $rootScope) {

    $scope.type = 'new';
    $scope.episode = $rootScope.newEpisode;
    $scope.episode.radioshow_id = $scope.episode.show.id;
    $scope.show = $scope.episode.show;
    $scope.episode.show = null;


    $scope.save = function () {
      $http.post(server + '/api/v0/episode', $scope.episode).success(function (data) {
        var httpCache = $cacheFactory.get('$http');
        httpCache.remove(server + '/api/v0/show/' + $scope.show.id);
        $location.path('/episode/' + data.data.id);
      }).error(function (data) {
          if (data.error) {
            $scope.error = data.error;
          } else {
            $scope.error = "Unknown.error";
          }
        });

    };


  }])
;