<?php

/**
 * Global connect variables and headers.
 * PHP version 8
 *
 * @category Config File
 * @package  GlinttHS User-form request
 * @author   Sargu74 <pitavo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version  GIT:
 * @link     http://www.hashbangcode.com/
 */

/**
 * Define directory separator constant.
 *
 * Define path constants to core, includes, gateways and assets directories using
 * DOCUMENT_ROOT and directory separator.
 *
 * Define database name constant using DOCUMENT_ROOT and database file name.
 */
define('DS', DIRECTORY_SEPARATOR);

define('CORE_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'api/Core');
define('INC_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'api/Includes');
define('GATEWAY_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'api/Gateways');
define('ASSETS_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'api/Controllers');

define('DB_NAME', $_SERVER['DOCUMENT_ROOT'] . DS . 'api/Database.accdb');
