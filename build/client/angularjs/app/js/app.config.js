 'use strict';

  var app = angular.module('app', 
      [ 'ngRoute',
      'app.services', 
      'app.controllers',
      'chart.js', 
      'ui.bootstrap',]
      );

  app.config(['ChartJsProvider',
    '$routeProvider',
    '$locationProvider',
    function (ChartJsProvider, $routeProvider, $locationProvider) {
    // Configure chart defaults
    ChartJsProvider.setOptions({
    });
    // Configure the routes
    $routeProvider
    .when('/chart', 
    { templateUrl: 'js/views/chart.html',
      controller: 'chartViewCtrl'})
    .when('/about',
    { templateUrl: 'js/views/about.html',
      controller: 'aboutViewCtrl'
    });
  }]);
