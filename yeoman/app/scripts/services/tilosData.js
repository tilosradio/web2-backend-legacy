/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */

'use strict';

angular.module('tilosApp')
  .factory('tilosData', ['$rootScope', '$http', 'API_SERVER_ENDPOINT', function ($root, $http, $server) {


    return {
      name: 'anonymous',
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
      getFacebookData: function (callback) {
        if ($root.facebook) {
          callback($root.facebook);
        } else {
          //Get Facebook follower count
          $http.get('https://graph.facebook.com/tilosradio').success(function (data) {
            $root.facebook = data;
            callback(data);
          });
        }
      },
      getCurrentEpisodes: function (callback) {
        var nowDate = new Date();
        var start = (nowDate / 1000 - 60 * 60 * 3);
        var now = nowDate.getTime() / 1000;
        var current;
        $http.get($server + '/api/episode?start=' + start + '&end=' + (start + 12 * 60 * 60)).success(function (data) {
          for (var i = 0; i < data.length; i++) {
            if (data[i].plannedFrom <= now && data[i].plannedTo > now) {
              current = data[i];
            }
          }
          $http.get($server + '/api/show/' + current.show.id).success(function (sd) {
            current.show = sd;
            callback(current, data);

          });
        });
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