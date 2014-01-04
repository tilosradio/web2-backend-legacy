'use strict';

angular.module('tilosApp')
  .controller('EpisodeEditCtrl', ['$location', '$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', '$cacheFactory', '$rootScope', function ($location, $scope, $routeParams, server, $http, $cacheFactory, $rootScope) {
    var id = $routeParams.id;
    $http.get(server + '/api/v0/episode/' + id).success(function (data) {
      $scope.episode = data;
      $scope.episode.id = id;
      $scope.show = data['show'];
      $scope.episode.show = null;
      if ($scope.episode.text) {
        $scope.episode.content = $scope.episode.text.content;
        $scope.episode.title = $scope.episode.text.title;
      }
    });

    $scope.save = function () {

      $http.put(server + '/api/v0/episode/' + $scope.episode.id, $scope.episode).success(function (data) {
        var httpCache = $cacheFactory.get('$http');
        httpCache.remove(server + '/api/v0/episode/' + $scope.episode.id);
        httpCache.remove(server + '/api/v0/show/' + $scope.show.id);
        $location.path('/episode/' + $scope.episode.id);
      }).error(function (data) {
          if (data.error) {
            $scope.error = data.error;
          } else {
            $scope.error = "Unknown.error";
          }
        });
      ;

    };


  }])
;