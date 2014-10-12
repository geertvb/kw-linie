'use strict';

angular.module('kwlinie')

    .factory('Initializer', function ($window, $q) {

        // maps loader deferred object
        var mapsDefer = $q.defer();

        // Google's url for async maps initialization accepting callback function
        var asyncUrl = 'https://maps.googleapis.com/maps/api/js?callback=';

        // async loader
        var asyncLoad = function (asyncUrl, callbackName) {
            var script = document.createElement('script');
            //script.type = 'text/javascript';
            script.src = asyncUrl + callbackName;
            document.body.appendChild(script);
        };

        // callback function - resolving promise after maps successfully loaded
        $window.googleMapsInitialized = function () {
            mapsDefer.resolve();
        };

        // loading google maps
        asyncLoad(asyncUrl, 'googleMapsInitialized');

        return {

            // usage: Initializer.mapsInitialized.then(callback)
            mapsInitialized: mapsDefer.promise
        };
    })

    .controller('bunkerController', function ($scope, $http, Initializer, bunkerService) {

        $scope.bunkers = [];

        $scope.bunkerCodes = [
            {code: "A", label: "A: Kanaal Mechelen - Leuven (1e lijn)"},
            {code: "Bb", label: "Bb: Bois de Beumont (Wavre)"},
            {code: "BL", label: "BL: Wavre Zuid (1e lijn)"},
            {code: "C", label: "C: Kanaal Mechelen - Leuven (2e lijn)"},
            {code: "Do", label: "Do: Doren"},
            {code: "F", label: "F: Spoorweg Leuven (1e lijn)"},
            {code: "GH", label: "GH: Gasthuisbos"},
            {code: "H", label: "H: Haacht - Kanaal (1e lijn)"},
            {code: "Ha", label: "Ha: Haacht"},
            {code: "He", label: "He: Herent"},
            {code: "Ib", label: "Ib: Itterbeek - Blauwenhoek"},
            {code: "KO", label: "KO: Hoogstraat"},
            {code: "KR", label: "KR: Bonheiden"},
            {code: "L", label: "L: Lier - Haacht (1e lijn)"},
            {code: "LW", label: "LW: Leuven - Wavre"},
            {code: "ML", label: "ML: Massenhoven - Lier"},
            {code: "MS", label: "MS: Maison de Sante (Leuven)"},
            {code: "P", label: "P: Lier - Kanaal (2e lijn)"},
            {code: "Pe", label: "Pe: Peulis"},
            {code: "Ps", label: "Ps: Peulis"},
            {code: "Ro", label: "Ro: Roesselberg"},
            {code: "Ry", label: "Ry: Rymenam"},
            {code: "Sb", label: "Sb: Wavre"},
            {code: "Te", label: "Te: Terbank"},
            {code: "Th", label: "Th: Tildonk"},
            {code: "TPM", label: "TPM: Bruggenhoofd Mechelen"},
            {code: "VA", label: "VA: Connectiekamer 1e lijn"},
            {code: "VB", label: "VB: Connectiekamer 2e lijn"},
            {code: "VC", label: "VC: Connectiekamer dwarsverbinding"},
            {code: "VD", label: "VD: Connectiekamer achterwaartse verbinding"},
            {code: "We", label: "We: Wespelaar"}
        ];

        $scope.selectedBunkerCode = null;

        $scope.bunkerTypes = [
            {label: "bruggenhoofd mechelen", value: "bruggenhoofd mechelen"},
            {label: "commando 1e lijn", value: "commando 1e lijn"},
            {label: "commando 2e lijn", value: "commando 2e lijn"},
            {label: "connectiekamer", value: "connectiekamer"},
            {label: "verdediging 1e lijn", value: "verdediging 1e lijn"},
            {label: "verdediging 2e lijn", value: "verdediging 2e lijn"},
            {label: "verdediging antitankcentrum", value: "verdediging antitankcentrum"}
        ];

        $scope.bunkerToestanden = [
            {label: "Aanwezig", value: "aanwezig"},
            {label: "Afwezig", value: "afwezig"},
            {label: "Afgebroken", value: "afgebroken"},
            {label: "Nooit gebouwd", value: "nooit gebouwd"},
            {label: "Onbekend", value: ""}
        ];

        $scope.map;

        $scope.load = function () {

            $http.get("api/v1/users")

                .success(function (data, status, headers, config) {
                    $scope.bunkers = data;
                })

                .error(function (data, status, headers, config) {
                    alert("AJAX failed!");
                });

        };

        $scope.load();

        Initializer.mapsInitialized
            .then(function () {

                var mapOptions = {
                    center: { lat: 50.883333, lng: 4.70000},
                    mapTypeControl: true,
                    panControl: true,
                    zoomControl: true,
                    zoom: 14
                };

                var mapElement = document.getElementById('map-canvas');

                $scope.map = new google.maps.Map(mapElement, mapOptions);
            });

        bunkerService.getBunkers().success(function (data) {
            $scope.bunkers = data;
        });

    });