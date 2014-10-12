'use strict';

angular.module('kwlinie')

    .service('bunkerService', [ "$http", function ($http) {

        this.getBunkerTypes = function () {
            return $http.get("api/v1/bunkerTypes");
        };

        this.getBunkerToestanden = function () {
            return $http.get("api/v1/bunkerToestanden");
        };

        this.getBunkers = function() {
            return $http.get("api/v1/bunkers");
        }

    }]);