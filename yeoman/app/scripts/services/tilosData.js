/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */

'use strict';

angular.module('tilosApp')
  .factory('tilosData', ['$rootScope', '$http', 'API_SERVER_ENDPOINT', function ($root, $http, $server) {
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