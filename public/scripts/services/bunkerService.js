'use strict';

var kwlinieServices = angular.module('kwlinieServices', []);

kwlinieServices.service('bunkerService', [ "$http", function ($http) {


    this.getBunkerTypes = function () {
        return $http.get("api/v1/bunkerTypes");
    };

    this.getBunkerToestanden = function () {
        return $http.get("api/v1/bunkerToestanden");
    };

    this.getBunkers = function () {
        return $http.get("api/v1/bunkers");
    };

    this.getGemeentes = function () {
        return $http.get("api/v1/gemeentes");
    };

    this.getDeelgemeentes = function () {
        return $http.get("api/v1/deelgemeentes");
    }


}]);