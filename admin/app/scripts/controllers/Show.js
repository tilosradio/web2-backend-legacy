'use strict';

angular.module('tilosAdmin').config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/show/:id', {
    templateUrl: 'views/show.html',
    controller: 'ShowCtrl',
    resolve: {
      data: function ($route, Shows) {
        return Shows.get({id: $route.current.params.id});
      },
    }});
  $routeProvider.when('/shows', {
    templateUrl: 'views/shows.html',
    controller: 'ShowListCtrl',
    resolve: {
      list: function ($route, Shows) {
        return Shows.query({id: $route.current.params.id});
      },
    }});
  $routeProvider.when('/edit/show/:id', {
    templateUrl: 'views/show-form.html',
    controller: 'ShowEditCtrl',
    resolve: {
      data: function ($route, Shows) {
        return Shows.get({id: $route.current.params.id});
      },
    }});
}]);
angular.module('tilosAdmin')
    .controller('ShowCtrl', function ($scope, data, API_SERVER_ENDPOINT, $http, $rootScope, $location) {
      $scope.show = data;
      $scope.server = API_SERVER_ENDPOINT;


      $scope.currentShowPage = 0;

      $scope.prev = function () {
        $scope.currentShowPage--;
        var to = $scope.show.episodes[$scope.show.episodes.length - 1].plannedFrom - 60;
        var from = to - 60 * 24 * 60 * 60;
        $http.get(API_SERVER_ENDPOINT + '/api/v0/show/' + data.id + '/episodes?from=' + from + "&to=" + to).success(function (data) {
          $scope.show.episodes = data;
        });

      };
      $scope.next = function () {
        $scope.currentShowPage++;
        var from = $scope.show.episodes[0].plannedTo + 60;
        var to = from + 60 * 24 * 60 * 60;
        $http.get(API_SERVER_ENDPOINT + '/api/v0/show/' + data.id + '/episodes?from=' + from + "&to=" + to).success(function (data) {
          $scope.show.episodes = data;
        });
      };


      $scope.newEpisode = function (episode) {
        $rootScope.newEpisode = episode;
        $rootScope.newEpisode.show = $scope.show;
        $location.path('/new/episode');
      };


    });

angular.module('tilosAdmin')
    .controller('ShowListCtrl', ['$scope', 'list', function ($scope, list) {
      $scope.shows = list;

    }]);

'use strict';

angular.module('tilosAdmin')
    .controller('ShowEditCtrl', ['$location', '$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', '$cacheFactory', 'data',
      function ($location, $scope, $routeParams, server, $http, $cacheFactory, data) {
        $scope.show = data;
        $scope.save = function () {

          $http.put(server + '/api/v0/show/' + $routeParams.id, $scope.show).success(function (data) {
            var httpCache = $cacheFactory.get('$http');
            httpCache.remove(server + '/api/v0/show/' + $scope.show.id);
            httpCache.remove(server + '/api/v0/show');
            $location.path('/show/' + $scope.show.id);
          });

        }
      }
    ])
;

angular.module('tilosAdmin').factory('Shows', ['API_SERVER_ENDPOINT', '$resource', function (server, $resource) {
  return $resource(server + '/api/v0/show/:id', null, {
    'update': { method: 'PUT'}
  });
}]);

