'use strict';

var kwlinieControllers = angular.module('kwlinieControllers', []);

kwlinieControllers.factory('Initializer', function ($window, $q) {

    // maps loader deferred object
    var mapsDefer = $q.defer();

    // Google's url for async maps initialization accepting callback function
    var asyncUrl = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbPBb7qqn6_CMDqwHzwfaWp4CjbDAXvRo&callback=';

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
});

kwlinieControllers.controller('bunkerController', function ($scope, $http, Initializer, gemeentes, deelgemeentes, verbindingen, bunkers, bunkerGemeentes, bunkerDeelgemeentes, $compile) {

    $scope.openInfoWindows = [];

    $scope.altGemeenteNamen = {
        "Grez-Doiceau": "Graven",
        "Wavre": "Waver"
    };

    $scope.bunkerGemeentes = bunkerGemeentes.data;

    $scope.bunkerDeelgemeentes = bunkerDeelgemeentes.data;

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

    $scope.selectedGemeente = null;

    $scope.selectedDeelgemeente = null;

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

    $scope.bunkerTypeColors = {
        "commando 1e lijn": {strokeColor: "#DDDD88", fillColor: "#888800", closed: false, visible: false},
        "commando 2e lijn": {strokeColor: "#DDDD88", fillColor: "#888800", closed: false, visible: false},
        "connectiekamer": {strokeColor: "#DDDD88", fillColor: "#008888", closed: false, visible: false},
        "bruggenhoofd mechelen": {strokeColor: "#DDDD88", fillColor: "#000088", closed: false, visible: true},
        "verdediging 1e lijn": {strokeColor: "#DDDD88", fillColor: "#880000", closed: false, visible: true},
        "verdediging 2e lijn": {strokeColor: "#DDDD88", fillColor: "#008800", closed: false, visible: true},
        "verdediging antitankcentrum": {strokeColor: "#DDDD88", fillColor: "#000000", closed: true, visible: true}
    };

    $scope.gemeentes = gemeentes.data;

    $scope.deelgemeentes = deelgemeentes.data;

    $scope.verbindingen = verbindingen.data;

    $scope.bunkers = bunkers.data;

    $scope.map;

    Initializer.mapsInitialized
        .then(function () {

            var mapOptions = {
                center: {lat: 50.96, lng: 4.56},
                mapTypeControl: true,
                panControl: true,
                zoomControl: true,
                zoom: 10
            };

            var mapElement = document.getElementById('map-canvas');

            $scope.map = new google.maps.Map(mapElement, mapOptions);

            $scope.displaymarkers();
        });

    $scope.drawPoly = function (map, vertices, type) {
        if (vertices.length > 1 && $scope.bunkerTypeColors[type].visible) {
            if ($scope.bunkerTypeColors[type].closed) {
                vertices.push(vertices[0]);
            }
            var polyLine = new google.maps.Polyline({
                map: map,
                path: vertices,
                strokeColor: $scope.bunkerTypeColors[type].fillColor,
                strokeOpacity: 0.5,
                strokeWeight: 3
            });
        }
    };

    $scope.displaymarkers = function () {
        var previous;
        var vertices = [];
        $scope.bunkerMarkers = [];

        for (var i in $scope.bunkers) {
            var bunker = $scope.bunkers[i];
            if (bunker.lat && bunker.lng) {
                var bunkerMarker = new BunkerMarker($scope.map, bunker, $compile, $scope);
                $scope.bunkerMarkers.push(bunkerMarker);

                if (previous && (previous.bunker.type != bunkerMarker.bunker.type
                    || previous.bunker.code != bunkerMarker.bunker.code)) {
                    $scope.drawPoly($scope.map, vertices, previous.bunker.type);
                    vertices = [];
                }

                vertices.push(bunkerMarker.latLng);

                previous = bunkerMarker;
            }
        }
        $scope.drawPoly($scope.map, vertices, previous.bunker.type);
        $scope.teken_verbindingen();
    };

    $scope.teken_verbindingen = function () {
        var bm1;
        var bm2;
        for (var i in $scope.verbindingen) {
            var v = $scope.verbindingen[i];
            bm1 = $scope.getBunkerMarker(v.van);
            bm2 = $scope.getBunkerMarker(v.tot);
            if (bm1 && bm2) {
                var polyLine = new google.maps.Polyline({
                    map: $scope.map,
                    path: [bm1.latLng, bm2.latLng],
                    strokeColor: "#008888",
                    strokeOpacity: 0.5,
                    strokeWeight: 3
                });
            }
        }
    };

    $scope.getBunkerMarker = function (nummer) {
        for (var i in $scope.bunkerMarkers) {
            var bm = $scope.bunkerMarkers[i];
            var type = bm.bunker.type;
            if ((type == "connectiekamer"
                || type == "commando 1e lijn"
                || type == "commando 2e lijn")

                && ($scope.normalizenummer(bm.bunker.nummer) == $scope.normalizenummer(nummer))) {

                return bm;

            }
        }
        return null;
    };

    $scope.normalizenummer = function (nummer) {
        var result = nummer;
        var pattern = /(\s|\/)/gi;

        result = result.replace(pattern, "");
        result = result.toLowerCase();
        return result;
    };

    $scope.gemeenteNaam = function (naam) {
        return $scope.altGemeenteNamen[naam] || naam;
    };

    $scope.gemeenteNaamEquals = function (naam1, naam2) {
        return naam1 == naam2 || $scope.gemeenteNaam(naam1) == $scope.gemeenteNaam(naam2);
    };

    $scope.deelgemeenteChange = function () {
        if ($scope.selectedDeelgemeente && $scope.selectedGemeente && !$scope.gemeenteNaamEquals($scope.selectedDeelgemeente.gemeente, $scope.selectedGemeente.naam)) {
            var gemeente = null;
            var i;
            for (i in $scope.gemeentes) {
                if ($scope.gemeenteNaamEquals($scope.gemeentes[i].naam, $scope.selectedDeelgemeente.gemeente)) {
                    gemeente = $scope.gemeentes[i];
                    break;
                }
            }
            $scope.selectedGemeente = gemeente;
        }
        $scope.filterMarkers();
    };

    $scope.gemeenteChange = function () {
        if ($scope.selectedDeelgemeente && $scope.selectedGemeente && !$scope.gemeenteNaamEquals($scope.selectedDeelgemeente.gemeente, $scope.selectedGemeente.naam)) {
            $scope.selectedDeelgemeente = null;
        }
        $scope.filterMarkers();
    };

    $scope.filterMarkers = function () {
        for (var i in $scope.bunkerMarkers) {
            var bunkerMarker = $scope.bunkerMarkers[i];
            var visible = $scope.filterBunker(bunkerMarker.bunker);
            bunkerMarker.marker.setVisible(visible);
        }
//        bunkerCollection.refresh();
    };

    $scope.filterBunker = function (bunker) {
        return $scope.filterTypeMarkers(bunker.type)
            && $scope.filterAanwezigheid(bunker.aanwezig)
            && ($scope.selectedBunkerCode == null || (bunker.code == $scope.selectedBunkerCode.code))
            && ($scope.selectedGemeente == null || ($scope.gemeenteNaamEquals(bunker.gemeente, $scope.selectedGemeente.naam)))
            && ($scope.selectedDeelgemeente == null || (bunker.deelgemeente == $scope.selectedDeelgemeente.deelgemeente))
            ;
    };

    $scope.cb_bunkerTypes = {
        "bruggenhoofd mechelen": true,
        "commando 1e lijn": true,
        "commando 2e lijn": true,
        "connectiekamer": true,
        "verdediging 1e lijn": true,
        "verdediging 2e lijn": true,
        "verdediging antitankcentrum": true
    };

    $scope.filterTypeMarkers = function (type) {
        return $scope.cb_bunkerTypes[type];
    };

    $scope.cb_aanwezig = {
        'aanwezig': true,
        'afwezig': true,
        'afgebroken': true,
        'nooit gebouwd': true,
        '': true
    };

    $scope.filterAanwezigheid = function (aanwezig) {
        return $scope.cb_aanwezig[aanwezig];
    };


})
;

function BunkerMarker(map, bunker, $compile, $scope) {

    function createMarker(latLng, strokeColor, fillColor) {
        var colorSuffix = fillColor.replace("#", "_");
        var marker = new google.maps.Marker({
            map: map,
            visible: true,
            icon: {
                anchor: {x: 3, y: 3},
                url: "img/measle" + colorSuffix + ".png"
            },
            position: latLng
        });
        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                while ($scope.openInfoWindows.length > 0) {
                    $scope.openInfoWindows.pop().close();
                }

                $scope.bunker = bunker;
                var compiled = $compile(contentString)($scope);
                $scope.$apply();
                infowindow.setContent(compiled[0].innerHTML);
                infowindow.open(map, marker);

                $scope.openInfoWindows.push(infowindow);
            };//return fn()
        })(marker));
        return marker;
    }

    var bunkerTypeColors = {
        "commando 1e lijn": {strokeColor: "#DDDD88", fillColor: "#888800", closed: false, visible: false},
        "commando 2e lijn": {strokeColor: "#DDDD88", fillColor: "#888800", closed: false, visible: false},
        "connectiekamer": {strokeColor: "#DDDD88", fillColor: "#008888", closed: false, visible: false},
        "bruggenhoofd mechelen": {strokeColor: "#DDDD88", fillColor: "#000088", closed: false, visible: true},
        "verdediging 1e lijn": {strokeColor: "#DDDD88", fillColor: "#880000", closed: false, visible: true},
        "verdediging 2e lijn": {strokeColor: "#DDDD88", fillColor: "#008800", closed: false, visible: true},
        "verdediging antitankcentrum": {strokeColor: "#DDDD88", fillColor: "#000000", closed: true, visible: true}
    };

    var contentString = '<div id="content">' +
        '<div id="bodyContent">' +
        '{{bunker.nummer}}<br>' +
        '<a href="api/v1/fotos/{{bunker.defaultfoto_id ? bunker.defaultfoto_id : -1}}/content" target="_blank">' +
        '<img ng-src="api/v1/fotos/{{bunker.defaultfoto_id ? bunker.defaultfoto_id : -1}}/thumbnail" width="128" height="96">' +
        '</a>' +
        '<br>' +
        '<span style="white-space: nowrap;">' +
        'Type: {{bunker.type}}<br>' +
        'Gemeente: {{bunker.gemeente}}<br>' +
        'Deelgemeente: {{bunker.deelgemeente}}<br>' +
        'x: {{bunker.x}} y: {{bunker.y}}' +
        '</span>' +
        '</div>' +
        '</div>';


    var infowindow = new google.maps.InfoWindow();

    this.bunker = bunker;
    this.latLng = new google.maps.LatLng(bunker.lat, bunker.lng);
    var strokeColor = bunkerTypeColors[bunker.type].strokeColor;
    var fillColor = bunkerTypeColors[bunker.type].fillColor;
    this.marker = createMarker(this.latLng, strokeColor, fillColor);

}