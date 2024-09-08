<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
ob_start();
define('BASE_PATH','/');


// # variables
$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
$today = date("F j, Y, g:i a");
$date = date('Y-m-d');

class DB {

    
    private static function con() {
        $config = Config::getInstance();
        $host = $config->get('dbHost');
        $user = $config->get('dbUser');
        $pass = $config->get('dbPass');
        $db = $config->get('dbName');
        $pdo = new PDO("mysql:host=".$host.";dbname=".$db.";
		charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query, $params = array()) {
        $stmt = self::con()->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        return $data;
    }

    public static function insert($query, $params = array()) {
        $stmt = self::con()->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    /**
     * Executes a query and fetches the result.
     *
     * @param string $query The SQL query to execute.
     * @param array $params Parameters to bind to the query.
     * @param bool $singleRow Set to true to fetch only the first row.
     * @return array|int The result set as an associative array, or the number of affected rows, or false on failure.
     */
    public static function executeQuery($query, $params = [], $singleRow = false) {
        try {
            $pdo = self::con();
            $stmt = $pdo->prepare($query);
    
            // Check and ensure all elements in $params are scalar
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    // Throw an exception or handle the specific case, e.g., for IN() clauses
                    throw new InvalidArgumentException("Array to string conversion error on parameter: $key");
                }
            }
    
            $stmt->execute($params);
            if (strpos(strtoupper($query), 'SELECT') === 0) {
                if ($singleRow) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            } else {
                return $stmt->rowCount();
            }
        } catch (PDOException $e) {
            // Handle exceptions or log them, based on your needs
            error_log('Query Failed: ' . $e->getMessage());
            return false;
        } catch (InvalidArgumentException $e) {
            // Handle or log the InvalidArgumentException
            error_log($e->getMessage());
            return false;
        }
    }
    

}