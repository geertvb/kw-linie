'use strict';

var kwlinieApp = angular.module('kwlinieApp', [
    'ngRoute',
    'kwlinieServices',
    'kwlinieControllers'
]);

kwlinieApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/bunkerView.html',
            controller: 'bunkerController',
            resolve: {
                gemeentes: function (bunkerService) {
                    return bunkerService.getGemeentes();
                },
                deelgemeentes: function (bunkerService) {
                    return bunkerService.getDeelgemeentes();
                },
                verbindingen: function (bunkerService) {
                    return bunkerService.getVerbindingen();
                },
                bunkers: function (bunkerService) {
                    return bunkerService.getBunkers();
                }
            }
        })
        .otherwise({
            redirectTo: '/'
        });
}]);