'use strict';

angular.module('tilosApp')
    .controller('PlayerCtrl', function ($scope, Player) {
        $scope.positionInSecond = 30;
        $scope.duration = 100;
        $scope.player = Player;
        $scope.player.player.on('timeupdate', function () {
            $scope.positionInSecond = Player.positionInSecond();
            $scope.$apply();
        })
    });
