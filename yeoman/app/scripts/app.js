/*global angular, window*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */


var dbg;
var tilos = angular.module('tilos', ['ngRoute', 'ngSanitize', 'configuration', 'ui.bootstrap']);


tilos.weekStart = function (date) {
  'use strict';
  var first = date.getDate() - date.getDay() + 1;
  date.setHours(0);
  date.setSeconds();
  date.setMinutes(0);
  return new Date(date.setDate(first));
};

tilos.config(['$routeProvider', function ($routeProvider) {
  'use strict';
  $routeProvider.when('/index', {
    templateUrl: 'partials/index.html',
    controller: 'IndexCtrl'
  }).when('/archive', {
    templateUrl: 'partials/program.html',
    controller: 'ProgramCtrl'
  }).when('/show/:id', {
    templateUrl: 'partials/show.html',
    controller: 'ShowCtrl'
  }).when('/author/:id', {
    templateUrl: 'partials/author.html',
    controller: 'AuthorCtrl'
  }).when('/page/:id', {
    templateUrl: 'partials/page.html',
    controller: 'PageCtrl'
  }).when('/shows', {
    templateUrl: 'partials/shows.html',
    controller: 'AllShowCtrl'
  }).otherwise({
    redirectTo: '/index'
  });
}]);

tilos.controller('SideCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  'use strict';
  var nowDate = new Date();
  var start = (nowDate / 1000 - 60 * 60 * 3);
  var now = nowDate.getTime() / 1000;
  $scope.now = new Date();
  $scope.Math = window.Math;
  $http.get($server + '/api/episode?start=' + start + '&end=' + (start + 12 * 60 * 60)).success(function (data) {
    for (var i = 0; i < data.length; i++) {
      if (data[i].from <= now && data[i].to > now) {
        $scope.current = data[i];
      }
    }
    $scope.episodes = data;
    $http.get($server + '/api/show/' + $scope.current.show.id).success(function (data) {
      $scope.current.show = data;
    });

    //Get Facebook follower count
    $http.get('https://graph.facebook.com/tilosradio').success(function (data) {
      $scope.current.facebook = data;
    });

    //TODO: get Twitter follower count - Twitter API needs OAuth

  });

}]);


tilos.controller('Collapse', ['$scope', function ($scope) {
  'use strict';
  $scope.isCollapsed = false;
}]);


tilos.controller('FooterDatepicker', ['$scope', '$timeout', function ($scope, $timeout) {
  'use strict';
  $scope.today = function () {
    $scope.dt = new Date();
  };
  $scope.today();

  $scope.showWeeks = true;
  $scope.toggleWeeks = function () {
    $scope.showWeeks = ! $scope.showWeeks;
  };

  $scope.clear = function () {
    $scope.dt = null;
  };

      // Disable weekend selection
      // $scope.disabled = function (date, mode) {
      //   return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
      // };

      // $scope.toggleMin = function () {
      //   $scope.minDate = ( $scope.minDate ) ? null : new Date();
      // };
      // $scope.toggleMin();

  $scope.open = function () {
    $timeout(function () {
      $scope.opened = true;
    });
  };

  $scope.dateOptions = {
    'year-format': '\'yyyy-mm-dd\'',
    'starting-day': 1
  };
}]);


tilos.controller('IndexCtrl', ['$scope', '$routeParams', 'tilosData', function ($scope, $routeParams, $td) {
  'use strict';
  $td.getNews(function (data) {
    $scope.news = data;
  });
}]);

tilos.controller('AuthorCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  'use strict';
  $http.get($server + '/api/author/' + $routeParams.id).success(function (data) {
    $scope.author = data;
  });
}]);

tilos.controller('PageCtrl', ['$scope', '$routeParams', 'tilosData', function ($scope, $routeParams, $td) {
  'use strict';
  $td.getText($routeParams.id, function (data) {
    $scope.page = data;
  });
}]);

tilos.controller('AllShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  'use strict';
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

tilos.controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  'use strict';
  $http.get($server + '/api/show/' + $routeParams.id).success(function (data) {
    $scope.show = data;
    $scope.server = $server;
  });
}]);

tilos.controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
  'use strict';
  var from = (tilos.weekStart(new Date()) / 1000);
  var to = from + 7 * 24 * 60 * 60;
  $scope.program = {};
  var refDate = new Date();
  refDate.setHours(0);

  refDate.setSeconds(0);
  refDate.setMinutes(0);
  refDate.setMilliseconds(0);
  refDate = refDate.getTime() / 1000;
  var processResult = function (data) {
    var result = $scope.program;
    //index episodes by day
    for (var i = 0; i < data.length; i++) {
      var idx = Math.floor((data[i].from - refDate) / (60 * 60 * 24));
      data[i].idx = idx;
      if (!result[idx]) {
        result[idx] = {
          episodes: []
        };
      }
      result[idx].episodes.push(data[i]);
    }

    //sort every day
    var sortFunction = function (a, b) {
      return a.from - b.from;
    };
    for (var key in result) {
      result[key].episodes.sort(sortFunction);
      result[key].date = result[key].episodes[0].from;
    }
    $scope.program = result;
  };
  $scope.currentDay = 0;
  $scope.prev = function () {
    if ($scope.program[$scope.currentDay - 1]) {
      $scope.currentDay--;
    } else {
      var oldFrom = from;
      from = from - 7 * 24 * 60 * 60;
      $http.get($server + '/api/episode?start=' + from + '&end=' + oldFrom).success(function (data) {
        $scope.currentDay--;
        processResult(data);
      });
    }
  };
  $scope.next = function () {
    if ($scope.program[$scope.currentDay + 1]) {
      $scope.currentDay++;
    } else {
      var oldTo = to;
      to = to + 7 * 24 * 60 * 60;
      $http.get($server + '/api/episode?start=' + oldTo + '&end=' + to).success(function (data) {
        $scope.currentDay++;
        processResult(data);
      });
    }
  };
  $http.get($server + '/api/episode?start=' + from + '&end=' + to).success(function (data) {
    processResult(data);
  });
}]);

tilos.directive('activeLink', ['$location', function (location) {
  'use strict';
  return {
    restrict: 'A',
    link: function (scope, element, attrs) {
      var clazz = attrs.activeLink;

      //TODO it shoud be more error prone
      var path = element.children()[0].href;
      path = path.substring(1 + path.indexOf('#'));
      if (path.charAt(0) !== '/') {
        path = '/' + path;
      }

      scope.location = location;
      scope.$watch('location.path()', function (newPath) {
        dbg = element;
        if (path === newPath) {
          element.addClass(clazz);
        } else {
          element.removeClass(clazz);
        }
      });

    }
  };
}]);

tilos.factory('tilosData', ['$rootScope', '$http', 'API_SERVER_ENDPOINT', function ($root, $http, $server) {
  'use strict';
  return {
    name : 'anonymous',
    getNews: function (callback) {
      if ($root.news) {
        callback($root.news);
      } else {
        $http.get($server + '/api/text/news/list').success(function (data) {
          $root.news = data;
          callback(data);
        });
      }
    },
    getText: function (id, callback) {
      if ($root.text && $root.text[id]) {
        callback($root.text[id]);
      } else {
        if (!$root.text) {
          $root.text = {};
        }
        $http.get($server + '/api/text/' + id).success(function (data) {
          $root.text[id] = data;
          callback(data);
        });
      }
    }
  };
}]);

var server = window.location.protocol + '//' + window.location.hostname;
if (window.location.port && window.location.port !== '9000') {
  server = server + ':' + window.location.port;
}
angular.module('configuration', []).constant('API_SERVER_ENDPOINT', server);
