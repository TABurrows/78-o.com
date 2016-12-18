'use strict';
  
  var app = angular.module('app.controllers', ['ngRoute']);
  
  app.controller('DataTablesCtrl', ['$scope', '$routeParams', 'priceData',
  function DataTablesCtrl($scope, $routeParams, priceData) {
     $scope.chartTitle = "Bitcoin / GBP Pricing";
    //Scaffold empty variables for price data
    var dateTitle = '';
    //var dateTitle = '';
    //var dateTitleStart = '';
    //var dateTitleEnd = ''; 
    //var asks = [];
    //var bids = [];
    //var labels = [];
    //var prices = [];
    //Set the default limit at 6
    var limit = 6;
    if($routeParams.limit) {
      limit = $routeParams.limit;
    }
    $scope.limit = limit;
    var startDate = new Date(new Date().setDate(new Date().getDate()-1));
    var startHour = startDate.getHours();
    $scope.startHour = startHour;
    if($routeParams.startDate){ 
        startDate = $routeParams.startDate;
    }
    $scope.startDate = startDate.getFullYear() + "-" + (startDate.getMonth()+1) + "-" + startDate.getDate();
    
    priceData.get({startDate: $scope.startDate, startHour: $scope.startHour, limit: $scope.limit},
    function success(response){
        console.log("SUCCESS: " + JSON.stringify(response["results"][0].key));
        $scope.formatData("SUCCESS", response["results"])
    },
    function error(errorResponse) {
        console.log("ERROR: " + errorResponse);
        $scope.formatData("ERROR", errorResponse);
    });
  
    
    //priceData.get();
    //$scope.up
    //Get initial priceData
      //$scope.updateData = function(startDate, startHour, limit) {

    //};
    //$scope.updateData(startDate, startHour, limit);
/*
    priceData.get({startDate: startDate, startHour: startHour, limit: limit},
    function success(response){
        console.log("SUCCESS: " + JSON.stringify(response));
        $scope.formatData("SUCCESS", response);
    },
    function error(errorResponse) {
        console.log("ERROR: " + errorResponse);
        $scope.formatData("ERROR", errorResponse);
    });
    
*/




    $scope.series = ["Ask Price", "Bid Price"]; 
    /* purpley greys:
    $scope.colors = [
      { // grey
        backgroundColor: 'rgba(148,159,177,0.2)',
        pointBackgroundColor: 'rgba(148,159,177,1)',
        pointHoverBackgroundColor: 'rgba(148,159,177,1)',
        borderColor: 'rgba(148,159,177,1)',
        pointBorderColor: '#fff',
        pointHoverBorderColor: 'rgba(148,159,177,0.8)'
      },
      { // dark grey
        backgroundColor: 'rgba(77,83,96,0.2)',
        pointBackgroundColor: 'rgba(77,83,96,1)',
        pointHoverBackgroundColor: 'rgba(77,83,96,1)',
        borderColor: 'rgba(77,83,96,1)',
        pointBorderColor: '#fff',
        pointHoverBorderColor: 'rgba(77,83,96,0.8)'
      }
    ]; */

    $scope.colors = [
      {
  //label: 'ask',
  borderColor: 'rgba(169, 253, 106, 0.7)',
  backgroundColor: 'rgba(169, 253, 106, 0.4)',
  pointBorderColor: 'rgba(182, 209, 113, 0.7)',
  pointBackgroundColor: 'rgba(169, 253, 106, 0.4)',
        pointHoverBackgroundColor: 'rgba(148,159,177,1)',
        pointHoverBorderColor: 'rgba(148,159,177,0.8)'
  //pointBorderWidth: 1,
  //data: [500, 500, 500, 500, 500, 500, 500, 500, 500, 500],
  //fill: true
}, {
  //label: 'bid',
  borderColor: 'rgba(182, 209, 113, 0.7)',
  backgroundColor: 'rgba(182, 209, 113, 0.4)',
 // backgroundColor: 'rgba(182, 209, 113, 0.8)',
  pointBorderColor: 'rgba(182, 209, 113, 0.7)',
  pointBackgroundColor: 'rgba(182, 209, 113, 0.4)',
        pointHoverBackgroundColor: 'rgba(77,83,96,1)',
        pointHoverBorderColor: 'rgba(77,83,96,0.8)'
  //pointBorderWidth: 1,
  //data: [490, 490, 490, 490, 490, 490, 490, 490, 490, 490],
  //fill: true
}
    ];

    $scope.options = { 
      title: 'bitcoin to gbp ' + dateTitle,
      legend: { display: true } 
    };

     $scope.datasetOverride = [
      {
        label: "Ask Price"
        /*,
        borderWidth: 1,
        type: 'bar'
        */
      },
      {
        label: "Bid Price",
        
        borderWidth: 3,
        hoverBackgroundColor: "rgba(255,99,132,0.4)",
        hoverBorderColor: "rgba(255,99,132,1)",
        type: 'line'
        
      }
    ];
    /*
    $scope.randomize = function () {
      $scope.data = $scope.data.map(function (data) {
        return data.map(function (y) {
          y = y + Math.random() * 10 - 5;
          return parseInt(y < 0 ? 0 : y > 100 ? 100 : y);
        });
      });
    };*/
    $scope.limitChanged = function() {
      console.log("'Limit' was changed to " + $scope.limit);
      //$scope.updateData(startDate, startHour, limit);
      
      priceData.get({startDate: $scope.startDate, startHour: $scope.startHour, limit: $scope.limit },
      function success(response){
        $scope.formatData("SUCCESS", response);
      },
      function error(errorResponse) {
        $scope.formatData("ERROR", errorResponse);
      });
      
    };
    //startDate, limit
    $scope.getEarlierPrices = function() {
      console.log("'Get Earlier Prices' Clicked with " + $scope.startDate + " " + $scope.startHour + " " + $scope.limit);
      //var earlierDate = new Date(startDate);
      //var earlierHour = earlierDate.setHours;
      priceData.get({startDate: $scope.startDate, startHour: $scope.startHour, limit: $scope.limit, desc: 'true'},
      function success(response){
        $scope.formatData("SUCCESS", response);
      },
      function error(errorResponse) {
        $scope.formatData("ERROR", errorResponse);
      });
    };

    $scope.getLaterPrices = function() {
      console.log("'Get Earlier Prices' Clicked with " + $scope.startDate + " " + $scope.startHour + " " + $scope.limit);
      //var earlierDate = new Date(startDate);
      //var earlierHour = earlierDate.setHours;
      priceData.get({startDate: $scope.startDate, startHour: $scope.startHour, limit: $scope.limit, desc: 'false'},
      function success(response){
        $scope.formatData("SUCCESS", response);
      },
      function error(errorResponse) {
        $scope.formatData("ERROR", errorResponse);
      });
    };

    $scope.getPrices = function(startDate, startHour, limit) {
      console.log("'Get Prices' invoked.");
    };
    //var fsuccess;
    $scope.errorMessage = '';
    // ------------------------------------------------

    $scope.getPriceData = function(){ console.log("But I am a function ...");};
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
        $scope.errorMessage = "There was an error in connecting to the REST service. " + response;
         //Just generate an empty chart
        asks = [0,0,0,0,0,0];
        bids = [0,0,0,0,0,0];
        labels = ['','','','','',''];
      } else {
        for (var item in response) {
          latestKey = response[item].key;
          if(!earliestKey) earliestKey = latestKey;
          if(response[item].ask) {
            asks.push(response[item].ask);
            askData.push({ key: response[item].key, price: response[item].ask });
          }
          if(response[item].bid) {
            bids.push(response[item].bid);
            bidData.push({ key: response[item].key, price: response[item].bid });
          }
          if(response[item].timestamp) {
            var priceTimeStamp = new Date(response[item].timestamp);
            dateTitleEnd = priceTimeStamp.getFullYear() + "-" + (priceTimeStamp.getMonth()+1) + "-" + priceTimeStamp.getDate();
            if(dateTitle === '')
                dateTitle = dateTitleEnd;
            //Build the dateTitle for the charts title
            if(dateTitleEnd !== dateTitle)
              dateTitle = dateTitle + " - " + dateTitleEnd;
            //console.log("dateTitle is " + dateTitle);
            $scope.chartDates = dateTitle;
            var h = String(priceTimeStamp.getHours() );
            if( h.length === 1)
              h = "0" + h;
            var m = String(priceTimeStamp.getMinutes() );
            if (m.length === 1)
              m = "0" + m;
            labels.push( h + " : " + m );
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


    
    //$scope.updateData = function(startDate, startHour, limit, priceData) {
   
    
    
    /*
    priceData.get({startDate: startDate, startHour: startHour, limit: limit},
    function success(response){
        console.log("SUCCESS: " + JSON.stringify(response));
        $scope.formatData("SUCCESS", response);
    },
    function error(errorResponse) {
        console.log("ERROR: " + errorResponse);
        $scope.formatData("ERROR", errorResponse);
    });
    */
    // ------------------------------------------------
  }]);