'use strict';
  
  var app = angular.module('app.controllers', ['ngRoute']);
  
  app.controller('DataTablesCtrl', ['$scope', '$routeParams', 'priceData',
  function DataTablesCtrl($scope, $routeParams, priceData) {
    // ------------------------------------------------
    //Month in en-GB short form
    var mon = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];    
    // ------------------------------------------------
    //Pass Route to scope variables
    $scope.limit = $routeParams.limit ? $routeParams.limit : 6;
    $scope.select = $routeParams.select ? $routeParams.select : 'bid|ask|timestamp';
    $scope.order = $routeParams.order ? $routeParams.order : 'desc';
    $scope.by = $routeParams.by ? $routeParams.by : 'id';

    // ------------------------------------------------
    //Initialize chart Variables
    $scope.chartTitle = "Bitcoin / GBP Pricing";
    $scope.messages = '';
    $scope.dateTitle = '';
    $scope.dateRangeStart = '';
    $scope.dateRangeEnd = '';
    $scope.tableTitle = '';
    $scope.startDate = '';
    // ------------------------------------------------
    //Get initial chart data
    priceData.get({select: $scope.select, order: $scope.order, by: $scope.by, limit: $scope.limit},
      function success(response){
        $scope.formatData("SUCCESS", response["results"])
    },
      function error(errorResponse) {
        $scope.formatData("ERROR", errorResponse);
    });
    // ------------------------------------------------
    $scope.series = ["Ask Price", "Bid Price"]; 
    // ------------------------------------------------
    $scope.colors = [
        {
          borderColor: 'rgba(169, 253, 106, 0.7)',
          backgroundColor: 'rgba(169, 253, 106, 0.4)',
          pointBorderColor: 'rgba(182, 209, 113, 0.7)',
          pointBackgroundColor: 'rgba(169, 253, 106, 0.4)',
          pointHoverBackgroundColor: 'rgba(148,159,177,1)',
          pointHoverBorderColor: 'rgba(148,159,177,0.8)'
        }, 
        {
          borderColor: 'rgba(182, 209, 113, 0.7)',
          backgroundColor: 'rgba(182, 209, 113, 0.4)',
          pointBorderColor: 'rgba(182, 209, 113, 0.7)',
          pointBackgroundColor: 'rgba(182, 209, 113, 0.4)',
          pointHoverBackgroundColor: 'rgba(77,83,96,1)',
          pointHoverBorderColor: 'rgba(77,83,96,0.8)',
        }
    ];
    // ------------------------------------------------
    $scope.options = { 
      legend: { display: true } ,
    };
    // ------------------------------------------------
     $scope.datasetOverride = [
      {
        label: "Ask Price",
      },
      {
        label: 'Bid Price',        
        borderWidth: 3,
        hoverBackgroundColor: "rgba(255,99,132,0.4)",
        hoverBorderColor: "rgba(255,99,132,1)",
        type: 'line',        
      }
    ];
    // ------------------------------------------------
    $scope.limitChanged = function() {
      $scope.order = 'DESC';
      priceData.get({select: $scope.select, order: $scope.order, by: $scope.by, limit: $scope.limit, less: "id|" + $scope.latestKey},
        function success(response){
          $scope.formatData("SUCCESS", response["results"])
      },
        function error(errorResponse) {
          $scope.formatData("ERROR", errorResponse);
      });      
    }
    // ------------------------------------------------
    $scope.getEarlierPrices = function() {
      $scope.order = "DESC";
      priceData.get({select: $scope.select, order: 'desc', by: $scope.by, limit: $scope.limit, less: "id|" + $scope.earliestKey},
        function success(response){
          $scope.formatData("SUCCESS", response["results"])
      },
        function error(errorResponse) {
          $scope.formatData("ERROR", errorResponse);
      });
    };
    // ------------------------------------------------
    $scope.getLaterPrices = function() {
      $scope.order = "ASC";
      priceData.get({select: $scope.select, order: $scope.order, by: $scope.by, limit: $scope.limit, greater: "id|" + $scope.latestKey},
        function success(response){
          $scope.formatData("SUCCESS", response["results"])
      },
        function error(errorResponse) {
          $scope.formatData("ERROR", errorResponse);
      });
    }
    // ------------------------------------------------
    $scope.formatData = function(status, response) {
      var dateTitleEnd = '',
      asks = [],
      bids = [],
      prices = [],
      labels = [],
      askData = [],
      bidData = [],
      earliestKey = "",
      latestKey = "";     
      if(status === "ERROR") {
        $scope.messages = "There was an error in connecting to the REST service. " + response;
         //Just generate an empty chart
        asks = [0,0,0,0,0,0];
        bids = [0,0,0,0,0,0];
        labels = ['','','','','',''];
      } else {
        if($scope.order.toUpperCase() !== 'ASC')
          response.reverse();
        //console.log("RESPONSE length is " + response.length);
        if (response.length)
        if(response[0].timestamp){
            var priceTimeStamp = new Date(response[0].timestamp);
            $scope.dateRangeStart = priceTimeStamp.getDate() + " " + mon[priceTimeStamp.getMonth()] + " " + priceTimeStamp.getFullYear();
            $scope.dateTitle = $scope.dateRangeStart;
        }
        for (var item in response) {
          latestKey = response[item].id;
          if(!earliestKey) earliestKey = latestKey;
          if(response[item].ask) {
            asks.push(response[item].ask);
            askData.push({ key: response[item].id, price: response[item].ask });
          }
          if(response[item].bid) {
            bids.push(response[item].bid);
            bidData.push({ key: response[item].id, price: response[item].bid });
          }
          if(response[item].timestamp) {
            var priceTimeStamp = new Date(response[item].timestamp);
            $scope.dateRangeEnd = priceTimeStamp.getDate() + " " + mon[priceTimeStamp.getMonth()] + " " + priceTimeStamp.getFullYear();
            if($scope.dateRangeEnd !== $scope.dateRangeStart) 
                $scope.dateTitle = $scope.dateRangeStart + " to " + $scope.dateRangeEnd;
            $scope.chartDates = $scope.dateTitle;
            var h = String(priceTimeStamp.getHours() );
            if( h.length === 1)
              h = "0" + h;
            var m = String(priceTimeStamp.getMinutes() );
            if (m.length === 1)
              m = "0" + m;
            labels.push( h + " : " + m );
          }
        }
        //Now pad if necessary
        if(response.length < $scope.limit) {
          if(response.length < 4) {
            $scope.latestKey = $scope.latestKey - 3;
            $scope.getLaterPrices();
          } else {
            for(var i = 0; i < ($scope.limit - response.length); i++) {
              labels.push("-");
              asks.push("-");
              askData.push({key: (latestKey + i), price: "-"});
              bids.push("-");
              bidData.push({ key: (latestKey + i), price: "-" });
            }
          }
        }
      }
      //Now write out the collected data
      //Populate the prices dataset
      prices = [asks,bids];
      //Build the chart
      $scope.labels = labels;
      $scope.data = prices;
      $scope.askData = askData;
      $scope.bidData = bidData;
      $scope.earliestKey = earliestKey;
      $scope.latestKey = latestKey;
      };
    // --------------------------------------------
  }]);