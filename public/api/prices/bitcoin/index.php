<?php
require("DataRequest.php");
require("RequestResult.php");
require("RequestValidator.php");

//Set the exception handler for unknown requests
set_exception_handler(function($err){
    //Get the HTTP error Number or default to 400
    $code = $err->getCode() ? : 400;
    //Set the header so clients know this is JSON
    header('Content-Type:application/json; charset=UTF-8', NULL, $code);
    //Now write out the json encoded error details
    echo json_encode(["Error" => $err->getMessage()]);
});


//Start the process timer
$startTimer = microtime(true);

//Extract the HTTP verb from the request
$HTTPVerb = $_SERVER['REQUEST_METHOD'];
//Extract the path elements of the URI
//$uri_elements = explode('/', $_SERVER['PATH_INFO']); 
$uri_elements = explode('/', $_SERVER['REQUEST_URI']);

//Build the response object
$response = (object)[];

//Handle each supported HTTP Verb in turn
switch($HTTPVerb) {
    case 'GET': 
            try {
                //Setup  instances of the required classes
                $dataRequest = new DataRequest('GET');
                //Clean and validate the inputs if any
                $cleanedRequest = filter_input_array(
                    INPUT_GET,
                    $requestValidationSchema,
                    true
                );
                //Build the complete request from the Defaults
                // and the inputs
                foreach($dataRequest->defaults as $key => $value) {
                    if(!array_key_exists($key, $cleanedRequest)) $cleanedRequest[$key] = $value;
                    if(empty($cleanedRequest[$key])) $cleanedRequest[$key] = $value;
                }
                //Add the built $cleanedRequest to the params
                // property of the dataRequest
                $dataRequest->params = $cleanedRequest;
                //Data Request is cleaned and defaults written
                // so process the request
                $requestResult = new RequestResult($dataRequest);
                $requestResult->executeRequest();
                //Build the response object
                $response->status = $requestResult->status;
                $response->results = $requestResult->results;
                $response->status['recordCount'] = sizeof($requestResult->results);
                $response->status['level'] = 'INFO';
                $response->status['messages'] = 'Successfully completed request';
            } catch(UnexpectedValueException $e) {
                throw new Exception('Resource does not exist', 404);
            }
        break;
    //DELETE, POST and PUT share functionality at the moment - run them together
    case 'POST':
    case 'PUT':
    case 'DELETE':
        throw new Exception('Requested method is not yet supported', 405);
        break;
    default:
        throw new Execption('Requested method is missing or invalid', 405);
};


//Stope the response timer
$stopTimer = microtime(true);
$msTimeTaken = round(($stopTimer - $startTimer) * 1000, 3);
$response->status['elapsedTime'] = (string)($msTimeTaken)." ms";

//Return output to client
header('Content-Type:application/json; charset=UTF-8');
echo json_encode($response, JSON_PRETTY_PRINT);

?>