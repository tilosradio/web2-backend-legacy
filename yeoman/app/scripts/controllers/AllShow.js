'use strict';

angular.module('tilosApp')
  .controller('AllshowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  $http.get($server + '/api/show').success(function (data) {
    var res = {
      talk: [],
      sound: []
    };
    for (var i = 0; i < data.length; i++) {
      var show = data[i];
      if (show.type) {
        res.talk.push(data[i]);
      } else {
        res.sound.push(data[i]);
      }
    }
    $scope.shows = res;
  });
}]);
