'use strict';

angular.module('tilosApp').config(function ($routeProvider) {
    $routeProvider.when('/audiotest', {
        templateUrl: 'partials/audiotest.html',
        controller: 'AudioTestCtrl',
    });
});
;

angular.module('tilosApp').controller('AudioTestCtrl', ['$scope', function ($scope) {
    $("#jquery_jplayer_1").jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {
                title: "Tilos",
                mp3: "http://archive.tilos.hu/online/2014/04/03/tilosradio-20140403-1000.mp3"
            });
        },
        swfPath: "/jplayer/Jplayer.swf",
        solution: "flash, html",
        supplied: "mp3"
    });
}]);
