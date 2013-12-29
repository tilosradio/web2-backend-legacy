'use strict';

angular.module('tilosApp')
  .controller('EpisodeEditCtrl', ['$location', '$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', '$cacheFactory', '$rootScope', function ($location, $scope, $routeParams, server, $http, $cacheFactory, $rootScope) {
    var id = $routeParams.id;
    if (id) {
      $http.get(server + '/api/v0/episode/' + id).success(function (data) {
        $scope.episode = data;
        $scope.show = data['show'];
        $scope.episode.show = null;
        if ($scope.episode.text) {
          $scope.episode.content = $scope.episode.text.content;
          $scope.episode.title = $scope.episode.text.title;
        }
      });
    } else {
      $scope.type = 'new';
      $scope.episode = $rootScope.newEpisode;
      $scope.episode.radioshow_id = $scope.episode.show.id;
      $scope.show = $scope.episode.show;
      $scope.episode.show = null;

    }
    $scope.save = function () {
      if ($scope.type == 'new') {
        $http.post(server + '/api/v0/episode', $scope.episode).success(function (data) {
          alert("created successfully");
        });
      } else {
        $http.put(server + '/api/v0/episode/' + $scope.episode.id, $scope.episode).success(function (data) {
          alert("updated successfully");
        });
      }
    };


  }])
;