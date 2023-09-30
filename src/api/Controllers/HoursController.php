<?php

/**
 * HoursController handles API requests related to hours records.
 *
 * Routes requests to retrieve, create, update or delete hours records
 * to appropriate handler methods based on the request method and presence of an ID.
 *
 * @category API
 * @package Controllers
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

class HoursController
{
  public function __construct(private HoursGateway $gateway)
  {
  }

  /**
   * Process an API request for an hours resource or collection.
   *
   * @param string $method The HTTP request method.
   * @param string|null $id The ID of the hours resource if provided.
   *
   * @return void
   */
  public function processRequest(string $method, ?string $id): void
  {
    if ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  /**
   * Handle a request for a single hours resource.
   *
   * @param string $method The HTTP request method.
   * @param string $id The ID of the requested hours record.
   *
   * @return void
   */
  private function processResourceRequest(string $method, string $id): void
  {
    $result = $this->gateway->get($id);

    if (!$result && $method === 'GET') {
      http_response_code(204);
      echo json_encode(["message" => "Registo não encontrado!"]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($result);
        break;

      case 'DELETE':
        echo json_encode($this->gateway->delete($id));
        break;

      case 'OPTIONS':
        header('Access-Control-Max-Age: 300');
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }
  }

  /**
   * Handle a collection request for hours resources.
   *
   * @param string $method The HTTP request method.
   *
   * @return void
   */
  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case 'POST':
        $data = (array) json_decode(file_get_contents("php://input"), true);
        $this->gateway->create($data);
        http_response_code(200);
        break;

      case 'OPTIONS':
        header('Access-Control-Max-Age: 300');
        break;

      default:
        http_response_code(405);
        header("Allow: POST");
        echo json_encode(["message" => "Método de acesso não autorizado! (" . $_SERVER["REQUEST_METHOD"] . ")"]);
    }
  }
}
