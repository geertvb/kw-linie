'use strict';

var altGemeenteNamen = {
    "Grez-Doiceau": "Graven",
    "Wavre": "Waver"
};

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
                },
                bunkerGemeentes: function (bunkerService) {
                    return bunkerService.getBunkerGemeentes();
                },
                bunkerDeelgemeentes: function (bunkerService) {
                    return bunkerService.getBunkerDeelgemeentes();
                }
            }
        })
        .otherwise({
            redirectTo: '/'
        });
}]);

kwlinieApp.filter('gemeenteIn', function () {
    return function (items, gemeenteNamen) {
        var filtered = [];
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            if (gemeenteNamen.indexOf(item.naam) >= 0 || (altGemeenteNamen[item.naam] && gemeenteNamen.indexOf(altGemeenteNamen[item.naam]) >= 0)) {
                filtered.push(item);
            }
        }
        return filtered;
    };
});

kwlinieApp.filter('deelgemeenteIn', function () {
    return function (items, deelgemeenteNamen) {
        var filtered = [];
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            if (deelgemeenteNamen.indexOf(item.deelgemeente) >= 0 || (altGemeenteNamen[item.deelgemeente] && deelgemeenteNamen.indexOf(altGemeenteNamen[item.deelgemeente]) >= 0)) {
                filtered.push(item);
            }
        }
        return filtered;
    };
});