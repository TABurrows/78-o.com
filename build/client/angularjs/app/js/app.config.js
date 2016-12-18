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
      //colors: [ '#a9fd6a', '#b6d171' ]
      //colors: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360']
    });
    /*
    // Configure all doughnut charts
    ChartJsProvider.setOptions('doughnut', {
      cutoutPercentage: 60
    });
    ChartJsProvider.setOptions('bubble', {
      tooltips: { enabled: false }
    });
    */
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
