'use strict';

angular.module('tilosApp')
    .factory('Player', function () {

        var params = {
            swf_path:'/bower_components/audio5js/audio5js.swf',
            throw_errors:true,
            format_time:true
        };

        var audio5js = new Audio5js(params);

        return audio5js;

        return {
            audio5js: new Audio5js({
                swf_path: '/bower_components/audio5js/audio5js.swf',
                throw_errors: true,
                format_time: true,
                ready: function () {
                    var player = this;
                    this.on('canplay', function () {
                        console.log('canplay');
                        player.play();
                    }, this);

                }
            }),
            play: function () {
                this.audio5js.load('http://archive.tilos.hu/online/2013/01/01/tilosradio-20130101-0000.mp3');
                console.log("playing");


            }
        };
    }
);