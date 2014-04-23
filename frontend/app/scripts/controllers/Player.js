'use strict';


angular.module('tilosApp').factory("Player", function ($rootScope) {
    $("#jquery_jplayer_1").jPlayer({
        ready: function () {

        },
        swfPath: "/js",
        supplied: "mp3"
    });
    var player = {};
    player.play = function(url) {
        alert(url);
        $rootScope.player = true;
    };
    return player;

})


