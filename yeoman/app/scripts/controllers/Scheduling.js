'use strict';

angular.module('tilosApp').config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/show/:show/scheduling', {
        templateUrl: 'partials/schedulings.html',
        controller: 'SchedulingListCtrl',
        resolve: {
            schedulingList: function ($route, Schedulings) {
                return Schedulings.query({show: $route.current.params.show});
            }
        }});
    $routeProvider.when('/show/:show/scheduling/:id', {
        templateUrl: 'partials/scheduling.html',
        controller: 'SchedulingCtrl',
        resolve: {
            scheduling: function ($route, Schedulings) {
                return Schedulings.get({show: $route.current.params.show, id: $route.current.params.id});

            }
        }});
    $routeProvider.when('/edit/show/:show/scheduling/:id', {
        templateUrl: 'partials/edit/scheduling.html',
        controller: 'SchedulingEditCtrl',
        resolve: {
            scheduling: function ($route, Schedulings) {
                return Schedulings.get({show: $route.current.params.show, id: $route.current.params.id});
            }
        }});
}]);
angular.module('tilosApp')
    .controller('SchedulingCtrl', ['$scope', 'scheduling', function ($scope, scheduling) {
        $scope.scheduling = scheduling;
    }]);
angular.module('tilosApp')
    .controller('SchedulingEditCtrl', ['$scope', 'scheduling', function ($scope, scheduling) {
        $scope.scheduling = scheduling;
    }]);


angular.module('tilosApp')
    .controller('SchedulingListCtrl', ['$scope', 'schedulingList', function ($scope, schedulingList) {
        $scope.scheduling = schedulingList;
    }]);

angular.module('tilosApp').filter('weekDayName', function () {
    return function (input) {
        return ['Hétfő', "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"][input];
    };
});
angular.module('tilosApp').factory('Schedulings', ['API_SERVER_ENDPOINT', '$resource', function (server, $resource) {
    return $resource(server + '/api/v0/show/:show/scheduling/:id');
}]);