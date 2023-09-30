<?php

/**
 * ConstructionGateway class to handle construction data operations.
 *
 * The class has a private PDO property to hold the database connection.
 *
 * The getAll() method queries all listed constructions from the database
 * and returns the array of construction data.
 *
 * The get() method queries a construction by ID and returns the
 * construction data array or false if not found.
 *
 * @package  API
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class ConstructionGateway
{
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  /**
   * Get all listed constructions from the database.
   *
   * Queries the tblConstructions table for rows where Listed = 1.
   * Fetches each row, converts character encoding to UTF-8, and adds to return array.
   *
   * @return array|false Array of construction data or false on failure.
   */
  public function getAll(): array | false
  {
    $sql = "SELECT ID, ConstructionNo, ConstructionCity, ConstructionCN, Listed
            FROM tblConstructions WHERE `Listed` = 1";
    $stmt = $this->conn->query($sql);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["ConstructionNo"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionNo"]);
      $row["ConstructionCity"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionCity"]);
      $row["ConstructionCN"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionCN"]);
      $row["Listed"] = (bool) $row["Listed"];

      $data[] = $row;
    }

    return $data;
  }

  /**
   * Get a construction record by ID.
   *
   * @param int $id The ID of the construction to retrieve.
   *
   * @return array|false The construction data as an associative array if found,
   *                     false otherwise.
   */
  public function get(int $id): array | false
  {
    $sql = "SELECT * FROM tblConstructions WHERE ID = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["ConstructionNo"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionNo"]);
      $row["ConstructionCity"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionCity"]);
      $row["ConstructionTitle"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionTitle"]);
      $row["Listed"] = (bool) $row["Listed"];

      $data[] = $row;
    }

    return $data;
  }
}
