'use strict';

angular.module('tilosApp').config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/show/:show/scheduling', {
        templateUrl: 'partials/schedulings.html',
        controller: 'SchedulingCtrl' });
}]);
angular.module('tilosApp')
    .controller('SchedulingCtrl', ['$scope', '$routeParams', 'API_SERVER_ENDPOINT', '$http', function ($scope, $routeParams, $server, $http) {
        $http.get($server + '/api/v0/show/' + $routeParams.show + "/scheduling", {cache: true}).success(function (data) {
            $scope.scheduling = data;
        });
    }]);

angular.module('tilosApp').filter('weekDayName', function () {
    return function (input) {
        return ['Hétfő', "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"][input];
    };
});