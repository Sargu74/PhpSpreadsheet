<?php

/**
 * ConstructionController handles API requests related to constructions.
 *
 * Routes requests to retrieve a single construction or all constructions to
 * the appropriate handler methods based on the request method and presence of
 * an ID.
 *
 * @category API
 * @package Controllers
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class ConstructionController
{
  public function __construct(private ConstructionGateway $gateway)
  {
  }

  /**
   * Process an API request for a construction resource or collection.
   *
   * @param string $method The HTTP request method.
   * @param string|null $id The ID of the construction resource if provided.
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
   * Handle a request for a single construction resource.
   *
   * @param string $method The HTTP request method.
   * @param string $id The ID of the requested construction.
   *
   * @return void
   */
  private function processResourceRequest(string $method, string $id): void
  {
    $result = $this->gateway->get($id);

    if (!$id) {
      http_response_code(404);
      echo json_encode(["message" => "Obra não encontrada!"]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($result);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }
  }

  /**
   * Handle a collection request for construction resources.
   *
   * @param string $method The HTTP request method.
   *
   * @return void
   */
  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case 'GET':
        echo json_encode($this->gateway->getAll());
        break;

      default:
        http_response_code(405);
        header("Allow: GET, POST");
    }
  }
}
