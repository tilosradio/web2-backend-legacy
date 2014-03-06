'use strict';
var debug;

angular.module('tilosApp')
  .controller('ProgramCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http','datepickerPopupConfig','$timeout','$location','$anchorScroll',
    function ($scope, $routeParams, $server, $http, $popupconfig, $timeout,$location, $anchorScroll) {
      $popupconfig.closeText = "Bezár";
      $popupconfig.toggleWeeksText = 'Hetek száma';
      $popupconfig.currentText = 'Ma';
      $popupconfig.clearText = 'Törlés';

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
        $scope.getDay($scope.gotoDate.getTimestamp());
      };
      var from = (tilos.weekStart(new Date()) / 1000);
      var to = from + 7 * 24 * 60 * 60;
      $scope.program = {};
      var now = new Date();
      now.setToNoon();
      $scope.gotoDate = new Date();
      $scope.currentTimestamp = now.getTimestamp();
      $scope.todayTimestamp = now.getTimestamp();

      $scope.prev = function () {
        $scope.gotoDate = new Date($scope.gotoDate.getTime() - 60 * 60 * 24 * 1000);
        $scope.gotoDate.setToNoon();
        $scope.getDay($scope.gotoDate.getTimestamp());

      };

      $scope.next = function () {
        $scope.gotoDate = new Date($scope.gotoDate.getTime() + 60 * 60 * 24 * 1000);
        $scope.gotoDate.setToNoon();
        $scope.getDay($scope.gotoDate.getTimestamp());
      };

      $scope.getDay = function (timestamp) {
        var from = new Date(timestamp * 1000);
        from.setToDayStart();
        var to = new Date(timestamp * 1000);
        to.setToDayEnd();
        $http.get($server + '/api/v0/episode?start=' + from.getTimestamp() + '&end=' + to.getTimestamp(), {cache: true}).success(function (data) {
          $scope.episodes = data;

			setTimeout(function(){
				$scope.windowHeight = document.getElementById('program').offsetHeight;
				if($scope.windowHeight > 1000){
					$scope.showLink = true;
				}else{
					$scope.showLink = false;
				}
			});

        });
      };

      //Get today's episodes.
      $scope.getDay(now.getTimestamp());

      $scope.today = function () {
        $scope.dt = new Date();
      };
      $scope.today();

      $scope.showWeeks = true;
      $scope.toggleWeeks = function () {
        $scope.showWeeks = !$scope.showWeeks;
      };

      $scope.clear = function () {
        $scope.dt = null;
      };

      $scope.toggleMin = function () {
        $scope.minDate = ($scope.minDate) ? null : new Date();
      };

      $scope.open = function () {
        $timeout(function () {
          $scope.opened = true;
        });
      };
      $scope.goto = function () {
        $scope.gotoDay($scope.gotoDate);
      };
      $scope.dateOptions = {
        'year-format': "'yy'",
        'starting-day': 1
      };

      	$scope.gotoTop = function (){
			$location.hash('top');
			$anchorScroll();
		};

    }
  ]);