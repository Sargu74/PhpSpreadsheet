<?php

/**
 * HoursGateway class to handle hours data operations.
 *
 * The class has a private PDO property to hold the database connection.
 *
 * The get() method queries hours records for the given employee ID.
 * It joins related tables, converts encoding, and returns records array.
 *
 * The create() method inserts a new hours record into the database.
 *
 * The delete() method deletes a hours record by ID.
 *
 * @category API
 * @version  GIT:
 * @link     http://www.hashbangcode.com/
 */
class HoursGateway
{
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  /**
   * Get hours records for an employee.
   *
   * @param int $id The ID of the employee.
   *
   * @return array|false Array of hours records or false if none found.
   */
  public function get(int $id): array | false
  {
    $sql = "SELECT
              A.ID,
              B.Employee,
              A.RecordDate,
              D.Company,
              C.ConstructionNo + ' ' + C.ConstructionCity AS ConstructionPlace,
              A.LaborNormalHours,
              A.LaborExtraHours
            FROM tblEmployees AS B
              INNER JOIN (tblConstructions AS C
              INNER JOIN (tblCompanies AS D
              INNER JOIN tblLaborHours AS A
                ON D.ID = A.CompanyID)
                ON C.ID = A.ConstructionID)
                ON B.ID = A.EmployeeID
            WHERE A.EmployeeID = :id
            AND A.Reviewed = false
            ORDER BY A.RecordDate DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row["ID"] = iconv("ISO8859-1", "UTF-8", $row["ID"]);
      $row["RecordDate"] = iconv("ISO8859-1", "UTF-8", (string) $row["RecordDate"]);
      $row["Company"] = iconv("ISO8859-1", "UTF-8", (string) $row["Company"]);
      $row["ConstructionPlace"] = iconv("ISO8859-1", "UTF-8", (string) $row["ConstructionPlace"]);
      $row["LaborNormalHours"] = iconv("ISO8859-1", "UTF-8", $row["LaborNormalHours"]);
      $row["LaborExtraHours"] = iconv("ISO8859-1", "UTF-8", $row["LaborExtraHours"]);

      $data[] = $row;
    }

    return $data;
  }

  /**
   * Insert a new hours record into the database.
   *
   * @param array $data Associative array of data for the new record.
   *                    Requires keys:
   *                      - employeeId (int)
   *                      - recordDate (string)
   *                      - company (int)
   *                      - constructionPlace (int)
   *                      - laborHours (float)
   *
   * @return void
   */

  /**
   * Delete a hours record by ID.
   *
   * @param int $id The ID of the record to delete.
   *
   * @return int The number of rows deleted.
   */
  public function create(array $data): void
  {
    $extraHours = $data['laborHours'] > 9 ? $data['laborHours'] - 9 : 0;
    $data['laborHours'] -= $extraHours;

    $sql = "INSERT INTO tblLaborHours
              (EmployeeID, RecordDate, CompanyID, ConstructionID, beginHours, endHours, LaborNormalHours, LaborExtraHours)
            VALUES
              (:employeeId, :recordDate, :companyId, :constructionId, :beginHours, :endHours, :laborNormalHours, :laborExtraHours)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":employeeId", (int) $data['employeeId'], PDO::PARAM_INT);
    $stmt->bindValue(":recordDate", $data['recordDate'], PDO::PARAM_STR);
    $stmt->bindValue(":companyId", (int) $data['company'], PDO::PARAM_INT);
    $stmt->bindValue(":constructionId", (int) $data['constructionPlace'], PDO::PARAM_INT);
    $stmt->bindValue(":beginHours", (string) $data['beginHours'], PDO::PARAM_STR);
    $stmt->bindValue(":endHours", (string) $data['endHours'], PDO::PARAM_STR);
    $stmt->bindValue(":laborNormalHours", (string) str_replace(".", ",", $data['laborHours']), PDO::PARAM_STR);
    $stmt->bindValue(":laborExtraHours", (string) str_replace(".", ",", $extraHours), PDO::PARAM_STR);

    $stmt->execute();
  }

  /**
   * Delete a hours record by ID.
   *
   * @param int $id The ID of the record to delete.
   *
   * @return int The number of rows deleted.
   */
  public function delete(int $id): int
  {
    $sql = "DELETE FROM tblLaborHours WHERE id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount();
  }
}
