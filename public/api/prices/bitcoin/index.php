<?php
//A simple REST Service
//require("RequestValidator.php");
require("DataRequester.php");

//Configure app constants
$HEADER_JSON = 'Content-Type:application/json; charset=UTF-8';

//Set the exception handler for unknown requests
set_exception_handler(function($err){
    //Get the HTTP error Number or default to 400
    $code = $err->getCode() ? : 400;
    //Set the header so clients know this is JSON
    header($HEADER_JSON, NULL, $code);
    //Now write out the json encoded error details
    echo json_encode(["Error" => $err->getMessage()]);
});

//Extract the HTTP verb from the request
$HTTPVerb = $_SERVER['REQUEST_METHOD'];
//Extract the path elements of the URI
//$uri_elements = explode('/', $_SERVER['PATH_INFO']); 
$uri_elements = explode('/', $_SERVER['REQUEST_URI']);
$dataSet = new DataSet();

//The simple Route handler
switch($uri_elements[2].strtolower()){
    case 'prices':
    case 'bitcoin':
        break;
    default:
        throw new Exception('Unknown Endpoint'.$uri_elements[2].strtolower(), 404);
}


//Handle each supported HTTP Verb in turn
switch($HTTPVerb) {
    case 'GET':
        if(isset($uri_elements[2])) {
            try {
                $dataOut = $dataSet->getOne($uri_elements[2]);
            } catch(UnexpectedValueException $e) {
                throw new Exception('Resource does not exist', 404);
            }
        } else {
                $dataOut = $dataSet->getMostRecent(6);
        }
        break;
    //POST and PUT share a lot of functionality, run them together
    case 'POST':
    case 'PUT':
        //gather json input
        $params = json_decode(file_get_contents("php://input"), true);
        if(!$params) {
            throw new Exception('Supplied input is missing or invalid');
        } else {
            if($HTTPVerb === 'PUT') {
                    $key = $uri_elements[2];
                    $item = $dataSet->update($key, $params);
                    $status = 204;
            } else {
                $item = $dataSet->create($params);
                $status = 201;
            }
            $dataSet->save();
            //skip the default outputter
            header("Locaiont: ".$item['url'], null, $status);
            exit;
            break;
        }
        break;
    case 'DELETE':
        $key = $uri_elements[2];
        $dataSet->remove($key);
        $dataSet->save();
        header("Location: /api/prices", null, 204);
        exit;
        break;
    default:
        throw new Execption('Requested method is missing or invalid', 405);
};

//Return output to client
header($HEADER_JSON);
//echo json_encode($dataOut);
echo json_encode("You've done it and reached the end! ".$uri_elements[0].strtolower()." 1 is".$uri_elements[1].strtolower());

?>