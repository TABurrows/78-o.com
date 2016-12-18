var assert = require('assert');

describe('78-o.com home page', function(){
    
    browser.url('http://78-o.com');

    it('should have the right title', function(){
        var title = browser.getTitle();
        //assert.equal(title, 'Reference RESTful API for Bitcoin / GBP price data');
        assert.equal(title, 'Sample REST API for Bitcoin / GBP exchange rates');
    });

    it('should display a canvas element with id=priceChart',function(){
       var graph = browser.element('#priceChart');
       assert.ok(graph.state === "success");
    });

    it('should display a table cell with the last price time (checks for UTC)', function(){
        var timeStamp = browser.element('#stamptime').getText();
        assert.ok(timeStamp.substring(timeStamp.length-3,timeStamp.length) === "UTC");
    });

    it('should display a total volume decimal value', function(){
        var totalVolume = browser.element('#total_vol').getText();
        assert.ok(totalVolume.match(/^\d+.\d+$/));
    });

    it('should display a 24h average value', function(){
        var oneDayAvg = browser.element('#p24h_avg').getText();
        assert.ok(oneDayAvg.match(/^\d+.\d+$/));
    });

    it('should display 5 code elements of the "request" class', function(){
        var codeArray = browser.elements('code.request');
        assert.ok(codeArray.value.length === 5);
    })
});