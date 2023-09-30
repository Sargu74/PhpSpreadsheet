<?php

declare(strict_types=1);

require_once('Includes/Config.php');

spl_autoload_register(function ($class) {
  if (strrpos($class, "Gateway") > 0) {
    require __DIR__ . "/Gateways/$class.php";
  } elseif (strrpos($class, "Controller") > 0) {
    require __DIR__ . "/Controllers/$class.php";
  } else {
    require __DIR__ . "/Includes/$class.php";
  }
});

error_reporting(E_ALL);
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

/**
 * Set CORS headers to allow cross-origin resource sharing.
 *
 * Allow requests from any origin using Access-Control-Allow-Origin.
 * Allow GET, POST and DELETE methods using Access-Control-Allow-Methods.
 * Allow specified headers using Access-Control-Allow-Headers.
 * Set preflight cache max age to 300 seconds with Access-Control-Max-Age.
 *
 * Set content type to JSON with UTF-8 character encoding.
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');
header('Access-Control-Max-Age: 300');
header("Content-type: application/json; charset=UTF-8");

/**
 * Route the API request to the appropriate controller
 * based on the request resource path.
 *
 * Switch on the resource name and instantiate the corresponding
 * gateway and controller classes. Pass the request method and ID
 * to the controller to handle.
 *
 * If no route matches, return a 404 response.
 *
 * @param Database $database The database connection.
 * @param array $parts The request URI parts.
 * @param string|null $id The resource ID if present.
 */
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$id = $parts[3] ?? null;

$database = new Database(DB_NAME);

switch ($parts[2]) {
  case 'constructions':
    $gateway = new ConstructionGateway($database);
    $controller = new ConstructionController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    break;

  case 'employees':
    $gateway = new EmployeeGateway($database);
    $controller = new EmployeeController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    break;

  case 'companies':
    $gateway = new CompanyGateway($database);
    $controller = new CompanyController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    break;

  case 'hours':
    $gateway = new HoursGateway($database);
    $controller = new HoursController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    break;

  default:
    http_response_code(404);
    echo json_encode(["message" => "'Query string' sem correspondência de 'rota'!"]);
}
