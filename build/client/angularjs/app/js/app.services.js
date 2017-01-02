'use strict';

var services = angular.module('app.services', [ 'ngResource']);

services.factory('priceData', ['$resource', function($resource){
    return $resource("/api/prices/bitcoin/index.php",
     {}, {
        get: {method: 'GET', cache: false, isArray: false},
        save: {method: 'POST', cache: false, isArray: false}
    })
}]);
