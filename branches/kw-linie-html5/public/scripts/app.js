'use strict';

angular.module('kwlinie', [
    'ngRoute'
])
    .config(['$routeProvider', function($routeProvider){
        $routeProvider
            .when('/', {
                templateUrl: 'views/welcome.html',
                controller: 'bunkerController'
            })
            .when('/bunkermap', {
                templateUrl: 'views/bunkermap.html',
                controller: 'bunkermapController'
            })
            .when('/bunkerlist', {
                templateUrl: 'views/bunkerlist.html',
                controller: 'bunkerlistController'
            })
            .otherwise({
                redirectTo: '/'
            });
    }]);