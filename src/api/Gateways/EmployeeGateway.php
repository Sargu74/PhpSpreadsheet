<?php

/**
 * EmployeeGateway class to handle employee data operations.
 *
 * The class has a private PDO property to hold the database connection.
 *
 * The getAll() method queries all employee records, converts encoding,
 * and returns an array of employee data.
 *
 * The get() method queries an employee record by username, converts encoding,
 * and returns the employee data array or false if not found.
 *
 * @package  API
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
class EmployeeGateway
{
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  /**
   * Get all employees from the database.
   *
   * Queries the tblEmployees table and returns all records.
   * Each returned record has values converted to proper types and encoding.
   *
   * @return array|bool Array of employee records or false on failure.
   */
  public function getAll(): array|bool
  {
    $sql = "SELECT * FROM tblEmployees";
    $stmt = $this->conn->query($sql);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = (int) iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["Employee"] = iconv("ISO8859-1", "UTF-8", (string) $row["Employee"]);
      $row["Address"] = iconv("ISO8859-1", "UTF-8", (string) $row["Address"]);
      $row["NIF"] = iconv("ISO8859-1", "UTF-8", (string) $row["NIF"]);
      $row["Salary"] = (float) iconv("ISO8859-1", "UTF-8", (string) $row["Salary"]);
      $row["Photo"] = $row["Photo"] = "" ? null : $row["Photo"];

      $data[] = $row;
    }

    return $data;
  }

  /**
   * Get an employee record by username.
   *
   * @param string $user The username of the employee to retrieve.
   *
   * @return array|false The employee data as an associative array if found,
   *                     false otherwise.
   */
  public function get(string $user): array | false
  {
    $sql = "SELECT * FROM tblEmployees WHERE User = :user";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":user", $user, PDO::PARAM_STR);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = (int) iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["Employee"] = iconv("ISO8859-1", "UTF-8", (string) $row["Employee"]);
      $row["Address"] = iconv("ISO8859-1", "UTF-8", (string) $row["Address"]);
      $row["NIF"] = iconv("ISO8859-1", "UTF-8", (string) $row["NIF"]);
      $row["Salary"] = (float) iconv("ISO8859-1", "UTF-8", (string) $row["Salary"]);
      $row["Password"] = (string) iconv("ISO8859-1", "UTF-8", (string) $row["Password"]);

      $data = $row;
    }

    return $data;
  }
}
