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

    .controller('bunkerController', function ($scope, $http, Initializer) {

        $scope.bunkers = [];

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
                    zoom: 14
                };

                var mapElement = document.getElementById('map-canvas');

                $scope.map = new google.maps.Map(mapElement, mapOptions);
            });

    });