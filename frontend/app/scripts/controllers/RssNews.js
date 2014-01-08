'use strict';

angular.module('tilosApp')
  .controller('RssNewsCtrl', ['$scope', 'FeedService', function ($scope, Feed) {
    Feed.parseFeed('http://hirek.tilos.hu/?feed=rss2').then(function (res) {
      $scope.feeds = res.data.responseData.feed.entries;
    });
  }]);