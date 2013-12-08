/*global angular*/
/*jshint indent: 2, undef: true, unused: true, strict: true, trailing: true, camelcase: true, eqeqeq: true, immed: true, white: true, quotmark: single, curly: true */

'use strict';

angular.module('tilosApp')
  .factory('tilosData', ['$rootScope', '$http', 'API_SERVER_ENDPOINT', 'validateUrl', function ($root, $http, $server, validateUrl) {


    return {
      name: 'anonymous',
      getNews: function (callback) {
        if ($root.news) {
          callback($root.news);
        } else {
          $http.get($server + '/api/text/news/list').success(function (data) {
            $root.news = data;

            for(var i = 0; i < $root.news.length; i++){
 				$root.news[i].likeURL = validateUrl.getValidUrl('http://www.facebook.com/plugins/like.php?href=http%3A%2F%2F' + tilosHost + '%2F%23%2Fnews%2F' + $root.news[i].id + '&width&layout=standard&action=like&show_faces=true&share=true');
			}

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