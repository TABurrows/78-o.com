# 78-o.com
### Reference REST API for Bitcoin / GBP pricing data
A reference site built with HTML, CSS, JavaScript and PHP running on a Linux/nginx cloud stack with a MariaDB datastore.
## Features
* TLS / SSL Encryption with certificates from letsencrypt.org
* Pure JavaScript and jQuery Library functionality
* PHP OOP with traits, parameterized queries and escaped output
* Cloud based platform - automated and orchestrated deployment
* Vagrantfile and full support for Dev 
* JSON with padding support for use beyond CORS restrictions
## Overview
An example of an API client is the graph on the site's home page.
![alt text](https://78-o.com/gfx/readme/site-v1.png "Overview Image")


## Client - Functional Overview
## Client - Technical Overview
There is an AngularJS client example in **build/client/angularjs/app*** source folder.
![alt text](https://78-o.com/gfx/readme/client-v1.png "Client Image")





## API Technical Overview 
## index.php
the root document index.php is responsible for sorting requests according to HTTP verb, assigning the right header and response code and passing off the DataRequest instance to an instance of the DataRequestvalidator ( and creating a DataResult object to be hydrated ) assigning the correct header to the reques and returning the RequestResult object to the client or the appropriate HTTP Status code

the DataRequestValidator.php class is responsible for assessing the safety of the passed in parameters and raising exceptions on breaches of input rules.

the DataRequestHandler.php class is responsible for determining the logic rule of the request and then passing the DataRequest and the DataResult object to the appropriate data access or ORM layer.

A Repository Pattern is used to define the possible data access classes which are responsible for hydrating the DataResult object


## DataRequest class
The DataRequest class is instantiated by a client request. The following supported parameters and 

## API Logic Overview
To meet all the demands of every possible use case, several of the supported Data Request parameters are illogical in combination and are subsequently ingored according to this table






