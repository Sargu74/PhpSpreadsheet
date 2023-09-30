<?php

/**
 * Database class to handle database connections.
 *
 * The constructor takes the database name and stores it in a private property.
 *
 * The getConnection() method returns a PDO connection to the database. It uses
 * the Microsoft Access ODBC driver and specifies the database file, username,
 * password, and sets error mode to exceptions.
 *
 * @package Database
 */
class Database
{
  /**
   * Constructor for the Database class.
   *
   * @param string $name The name of the database to connect to.
   */
  public function __construct(private string $name)
  {
  }

  /**
   * Get a PDO database connection.
   *
   * Returns a PDO instance connected to the database using the
   * Microsoft Access ODBC driver.
   *
   * The database file path, username, password, and error mode are configured.
   *
   * @return PDO The PDO database connection instance.
   */
  public function getConnection(): PDO
  {
    return new PDO(
      "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; Dbq=$this->name;",
      "Uid=Admin;",
      "Pwd=;",
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]
    );
  }
}
