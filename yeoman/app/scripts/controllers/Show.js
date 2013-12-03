'use strict';

angular.module('tilosApp')
	.controller('ShowCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', 'validateUrl', function ($scope, $routeParams, $server, $http, validateUrl) {
		$http.get($server + '/api/show/' + $routeParams.id).success(function (data) {
			$scope.show = data;
			$scope.server = $server;

			$scope.show.sharecount = 0;

			$scope.likeURL = validateUrl.getValidUrl('http://www.facebook.com/plugins/like.php?href=http%3A%2F%2F'+server+'%2F%23%2Fshow%2F'+$scope.show.alias+'&width&layout=standard&action=like&show_faces=true&share=true');

			$http.get("https://graph.facebook.com/fql?q=SELECT%20url,%20normalized_url,%20share_count,%20like_count,%20comment_count,%20total_count,commentsbox_count,%20comments_fbid,%20click_count%20FROM%20link_stat%20WHERE%20url=%27http%3A%2F%2F"+server+"%2F%23%2Fshow%2F"+$scope.show.alias+"%27").success(function (data) {
				if(data.data[0] != undefined){
					$scope.show.sharecount = data.data[0].share_count;
				}

			});

		});

	}
]);