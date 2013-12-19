'use strict';

angular.module('tilosApp')
  .filter('RssDate', function () {
    return function (value) {
      return new Date(value).toLocaleString();
    };
  });