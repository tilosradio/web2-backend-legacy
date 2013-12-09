'use strict';
var debug;

angular.module('tilosApp')
  .controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http',
    function ($scope, $routeParams, $server, $http) {

      Date.prototype.setToNoon = function () {
        this.setHours(12, 0, 0, 0);
      }

      Date.prototype.setToDayStart = function () {
        this.setHours(0, 0, 0, 0);
      }


      Date.prototype.setToDayEnd = function () {
        this.setHours(23, 59, 60, 0);
      }

      Date.prototype.isLeapYear = function (year) {
        return (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0));

      }


      Date.prototype.daysInFebruary = function (year) {
        if (this.isLeapYear(year)) {
          // Leap year
          return 29;
        } else {
          // Not a leap year
          return 28;
        }
      };

      Date.prototype.getTimestamp = function () {
        return this.getTime() / 1000;
      };

      Date.prototype.dayIndex = function () {
        var pastYears = 0;
        for (var i = 1990; i < this.getFullYear(); i++) {
          pastYears += 365;
          if (this.isLeapYear(i)) pastYears++;
        }
        var feb = this.daysInFebruary(this.getFullYear());
        var aggregateMonths = [0, // January
          31, // February
          31 + feb, // March
          31 + feb + 31, // April
          31 + feb + 31 + 30, // May
          31 + feb + 31 + 30 + 31, // June
          31 + feb + 31 + 30 + 31 + 30, // July
          31 + feb + 31 + 30 + 31 + 30 + 31, // August
          31 + feb + 31 + 30 + 31 + 30 + 31 + 31, // September
          31 + feb + 31 + 30 + 31 + 30 + 31 + 31 + 30, // October
          31 + feb + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31, // November
          31 + feb + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30, // December
        ];
        return pastYears + aggregateMonths[this.getMonth() - 1] + this.getDate();
      };

      $scope.gotoDay = function (dt) {
        $scope.gotoDate = new Date(dt.getTime());
        $scope.gotoDate.setToNoon();
        $scope.currentTimestamp = $scope.gotoDate.getTimestamp();
        $scope.getDay($scope.currentTimestamp);
      };
      var from = (tilos.weekStart(new Date()) / 1000);
      var to = from + 7 * 24 * 60 * 60;
      $scope.program = {};
      var now = new Date();
      now.setToNoon();
      $scope.gotoDate = new Date();
      $scope.currentTimestamp = now.getTimestamp();

      $scope.prev = function () {
        $scope.currentTimestamp -= 24 * 60 * 60;
        $scope.getDay($scope.currentTimestamp);
        $scope.gotoDate = new Date($scope.currentTimestamp * 1000);

      };

      $scope.next = function () {
        $scope.currentTimestamp += 24 * 60 * 60;
        $scope.getDay($scope.currentTimestamp);
        $scope.gotoDate = new Date($scope.currentTimestamp * 1000);
      };

      $scope.getDay = function (timestamp) {
        var from = new Date(timestamp * 1000);
        from.setToDayStart();
        var to = new Date(timestamp * 1000);
        to.setToDayEnd();
        $http.get($server + '/api/episode?start=' + from.getTimestamp() + '&end=' + to.getTimestamp(), {cache: true}).success(function (data) {
          $scope.episodes = data;
        });
      };

      $scope.getDay($scope.currentTimestamp);
    }
  ]);