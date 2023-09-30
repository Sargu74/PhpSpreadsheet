<?php

/**
 * EmployeeController handles API requests related to employees.
 *
 * Routes requests to retrieve a single employee or all employees to the
 * appropriate handler methods based on the request method and presence of a
 * username.
 *
 * @category API
 * @package Controllers
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class EmployeeController
{
  public function __construct(private EmployeeGateway $gateway)
  {
  }

  /**
   * Process an API request for an employee resource or collection.
   *
   * @param string $method The HTTP request method.
   * @param string|null $user The username of the employee resource if provided.
   *
   * @return void
   */
  public function processRequest(string $method, ?string $user): void
  {
    if ($user) {
      $this->processResourceRequest($method, $user);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  /**
   * Handle a request for a single employee resource.
   *
   * @param string $method The HTTP request method.
   * @param string $user The username of the requested employee.
   *
   * @return void
   */
  private function processResourceRequest(string $method, string $user): void
  {
    $user = $this->gateway->get($user);

    if (!$user) {
      http_response_code(404);
      echo json_encode(["message" => "Empregado não encontrado!"]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($user, JSON_THROW_ON_ERROR);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }
  }

  /**
   * Handle a collection request for employee resources.
   *
   * @param string $method The HTTP request method.
   *
   * @return void
   */
  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case 'GET':
        echo json_encode($this->gateway->getAll(), JSON_THROW_ON_ERROR);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, POST");
    }
  }
}
