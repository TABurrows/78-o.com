to setup webdriver.io e2e testing:

download the latest gecko and crhome drivers to:
    ./bin/drivers/

Initialize npm init with test settings:
    Test Command> 'wdio ./test/e2e/wdio.conf.js'
so that final file has section:
  "scripts": {
    "test": "export PATH=$PATH:./bin/drivers && wdio ./test/e2e/wdio.conf.js"
  },

Install package with npm:
    npm install webdriverio

Config wdio with the wdio config script:
    ./node_modules/.bin/wdio config
Set up mocha, allure, error shots to ./test/e2e/errors/screenshots and make this directory structure

Download the required drivers to ./bin/drivers/:
    wget https://github.com/mozilla/geckodriver/releases/download/v0.11.1/geckodriver-v0.11.1-linux64.tar.gz

Configure wdio.conf.js and set the browser

Run the tests
    npm test
