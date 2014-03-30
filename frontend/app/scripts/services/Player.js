'use strict';
var audio;
angular.module('tilosApp')
    .factory('Player', function ($rootScope) {

        var currentDate;

        var episode;

        var idx = 0;

        var len = 0;

        var params = {
            swf_path: '/bower_components/audio5js/audio5js.swf',
            throw_errors: false,
            format_time: false,
        };

        var audio5js = new Audio5js(params);
        audio5js.on('canplay', function () {
//            console.log("canplay " + audio5js.audio.audio.src );
            audio5js.audio.audio.play()
        }, audio5js);
        audio = audio5js;
        var title;
        return {
            title: function(){
            return title;
            },
            player: audio5js,
            episode: function () {
                return episode;
            },
            play: function(epi, t) {
                title = t;
                audio5js.pause();
                episode = epi;
                len = epi.resources.stream.length
                audio5js.load(episode.resources.stream[idx].url);
                $rootScope.playing = true;
            },
            reset: function() {
                audio5js.pause();
                audio5js.audio.audio.src = '';
                audio5js.audio.playing = false;
                try {
                    audio5js.audio.load('');
                } catch (err) {
                    console.log("error");
                }

            },
            next: function () {
                $rootScope.playing = true;

                if (idx + 1 < len) {
                    this.reset();
                    audio5js.load(episode.resources.stream[++idx].url);
                }

            },
            prev: function () {
                $rootScope.playing = true;
                if (idx >= 1) {
                    audio5js.pause();
                    audio5js.audio.audio.src = '';
                    audio5js.audio.audio.load();


                    audio5js.load(episode.resources.stream[--idx].url);
                    audio5js.play();
                }
            },
            stop: function () {
                idx = 0;
                $rootScope.playing = false;
                this.reset();
            },
            pause: function () {
                audio5js.playPause();
            },
            duration: function () {
                return (30  * 60  + 5) * episode.resources.stream.length;
            },
            position: function () {
                return (episode.resources.stream[idx].start + audio5js.position) * 1000
            },
            positionInSecond: function () {
                return Math.round(100 * (audio5js.position + idx * 1805) / (episode.resources.stream.length * 1805));
            }
        };


    }
);