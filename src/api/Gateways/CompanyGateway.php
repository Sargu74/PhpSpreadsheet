<?php

/**
 * CompanyGateway class to handle company data operations.
 *
 * The class has a private PDO property to hold the database connection.
 *
 * The getAll() method queries all records from the companies table,
 * converts encoding, and returns the array of company data.
 *
 * The get() method queries a single company record by ID, converts encoding,
 * and returns the company data array or false if not found.
 *
 * @category API
 * @package Gateways
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class CompanyGateway
{
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  /**
   * Get all companies from the database.
   *
   * Queries the tblCompanies table and returns all records.
   * Each returned record has string values converted to UTF-8 encoding.
   *
   * @return array|false Array of company records or false on failure.
   */
  public function getAll(): array | false
  {
    $sql = "SELECT * FROM tblCompanies";
    $stmt = $this->conn->query($sql);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["Company"] = iconv("ISO8859-1", "UTF-8", (string) $row["Company"]);
      $row["Address"] = iconv("ISO8859-1", "UTF-8", (string) $row["Address"]);
      $row["NIF"] = iconv("ISO8859-1", "UTF-8", (string) $row["NIF"]);

      $data[] = $row;
    }

    return $data;
  }

  /**
   * Get a company record by ID.
   *
   * @param int $id The ID of the company to retrieve.
   *
   * @return array|false The company data as an associative array if found,
   *                     false otherwise.
   */
  public function get(int $id): array | false
  {
    $sql = "SELECT * FROM tblCompanies WHERE ID = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["Company"] = iconv("ISO8859-1", "UTF-8", (string) $row["Company"]);
      $row["Address"] = iconv("ISO8859-1", "UTF-8", (string) $row["Address"]);
      $row["NIF"] = iconv("ISO8859-1", "UTF-8", (string) $row["NIF"]);

      $data[] = $row;
    }

    return $data;
  }
}
