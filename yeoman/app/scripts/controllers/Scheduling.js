'use strict';

angular.module('tilosApp').config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/show/:show/scheduling', {
        templateUrl: 'partials/edit/schedulings.html',
        controller: 'SchedulingListCtrl',
        resolve: {
            schedulingList: function ($route, Schedulings) {
                return Schedulings.query({show: $route.current.params.show});
            },
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
            data: function ($route, Schedulings) {
                return Schedulings.get({show: $route.current.params.show, id: $route.current.params.id});
            }
        }});
    $routeProvider.when('/new/show/:show/scheduling', {
        templateUrl: 'partials/edit/scheduling.html',
        controller: 'SchedulingNewCtrl',
    });
}]);
angular.module('tilosApp')
    .controller('SchedulingCtrl', ['$scope', 'scheduling', function ($scope, scheduling) {
        $scope.scheduling = scheduling;

    }]);
angular.module('tilosApp')
    .controller('SchedulingEditCtrl', ['$scope', 'data', 'Schedulings', 'dateFilter', '$location', '$routeParams', function ($scope, data, resource, dateFilter, $location, $routeParams) {
        $scope.scheduling = data;

        data.$promise.then(
            function (data) {
                $scope.$watch('scheduling.validFrom', function (date) {
                    $scope.validFromDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
                });

                $scope.$watch('validFromDate', function (dateString) {
                    $scope.scheduling.validFrom = new Date(dateString).getTime() / 1000;
                });

                $scope.$watch('scheduling.validTo', function (date) {
                    $scope.validToDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
                });

                $scope.$watch('validToDate', function (dateString) {
                    $scope.scheduling.validTo = new Date(dateString).getTime() / 1000;
                });
                $scope.$watch('scheduling.base', function (date) {
                    $scope.baseDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
                });

                $scope.$watch('baseDate', function (dateString) {
                    $scope.scheduling.base = new Date(dateString).getTime() / 1000;
                });


            }
        );

        $scope.save = function () {
            resource.update({show: $routeParams.show, id: $scope.scheduling.id}, $scope.scheduling, function (data) {
                $location.path('/show/' + $routeParams.show + '/scheduling/');
            });
        }
    }]);

angular.module('tilosApp')
    .controller('SchedulingNewCtrl', ['$scope', 'Schedulings', 'dateFilter', '$location', '$routeParams', function ($scope, resource, dateFilter, $location, $routeParams) {
        $scope.scheduling = {};


        $scope.$watch('scheduling.validFrom', function (date) {
            $scope.validFromDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
        });

        $scope.$watch('validFromDate', function (dateString) {
            $scope.scheduling.validFrom = new Date(dateString).getTime() / 1000;
        });

        $scope.$watch('scheduling.validTo', function (date) {
            $scope.validToDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
        });

        $scope.$watch('validToDate', function (dateString) {
            $scope.scheduling.validTo = new Date(dateString).getTime() / 1000;
        });


        $scope.$watch('scheduling.base', function (date) {
            $scope.baseDate = dateFilter(new Date(date * 1000), 'yyyy-MM-dd')
        });

        $scope.$watch('baseDate', function (dateString) {
            $scope.scheduling.base = new Date(dateString).getTime() / 1000;
        });

        $scope.save = function () {
            resource.save({show: $routeParams.show}, $scope.scheduling, function (data) {
                $location.path('/show/' + $routeParams.show + '/scheduling/');
            });
        }
    }]);


angular.module('tilosApp')
    .controller('SchedulingListCtrl', ['$scope', 'schedulingList', '$routeParams', function ($scope, schedulingList, $routeParams) {
        $scope.scheduling = schedulingList;
        $scope.showId = $routeParams.show;
    }]);

angular.module('tilosApp').filter('weekDayName', function () {
    return function (input) {
        return ['Hétfő', "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"][input];
    };
});
angular.module('tilosApp').factory('Schedulings', ['API_SERVER_ENDPOINT', '$resource', function (server, $resource) {
    return $resource(server + '/api/v0/show/:show/scheduling/:id', null, {
        'update': { method: 'PUT'}
    });
}]);

