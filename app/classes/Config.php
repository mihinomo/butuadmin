<?php
class Config {
    private static $instance = null;
    private $settings = [];

    private function __construct() {
        // Load configuration settings
        $this->settings = $this->readConfigFileToArray('config.mino');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    public function get($key) {
        return $this->settings[$key] ?? null;
    }

    private function readConfigFileToArray($filePath) {
        // Check if the file exists to prevent warnings
        if (!file_exists($filePath)) {
            throw new Exception("Config file does not exist: {$filePath}");
        }
        
        $configArray = [];
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip lines that are either empty or comments
            if (empty($line) || $line[0] == '#' || $line[0] == ';') {
                continue;
            }
            
            // Explode the line into key and value
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Optional: Validate key and value here (e.g., non-empty, expected format)
            
            // Add to array
            $configArray[$key] = $value;
        }
        
        return $configArray;
    }
}