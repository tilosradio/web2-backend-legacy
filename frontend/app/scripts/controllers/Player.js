'use strict';

angular.module('tilosApp')
    .controller('PlayerCtrl', function ($scope, Player) {

        $scope.player = Player;
        $scope.player.player.on('timeupdate', function () {
            $scope.$apply();
        })
    });
