<?php

require_once(dirname(__FILE__) . '/../lib/ComicException.php');

class Comic {
    /**
     * The identifier for this Comic object. Used for sorting.
     */
    public $id;
    
    public function __construct($info = array()) {
         if (!is_array($info)) { throw new ComicException("must be an array"); }
         
         foreach ($info as $key => $value) {
            if (is_string($key)) {
                switch ($key) {
                    case "date":
                        if (!is_numeric($value)) {
                            if (($value = strtotime($value)) === false) {
                                throw new ComicException("must be a valid time");
                            }
                        }
                        break;
                }
                $this->{$key} = $value;
            }
         }
    }
  
    /**
     * Return true if the Comic object is valid ($id is a non-boolean scalar with a length greater than 0)
     * @return boolean True if the object is valid;
     */
    public function is_valid() {
         return is_scalar($this->id) && !is_bool($this->id) && (strlen($this->id) > 0);
    }
  
    /**
     * Process a directory or an array of comic file info and generate Comic objects based on the data.
     */
    public static function process_directory($directory) {
        if (!is_array($directory) && !is_string($directory)) { throw new ComicException("must be an array or a string"); }
        
        $result = array();
        $found_entries = array();
        
        if (is_array($directory)) {    
            foreach ($directory as $filename) {
                $base = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                
                if (!isset($found_entries[$base])) {
                    $found_entries[$base] = array(); 
                }
                
                if (($time_result = strtotime($base)) !== false) {
                    $found_entries[$base]['date'] = $time_result; 
                }
                
                if (!isset($found_entries[$base]['title'])) {
                    $found_entries[$base]['title'] = $base; 
                }
                
                switch ($extension) {
                    case "txt":
                        $found_entries[$base]['copy'] = "";
                        break; 
                }
            }
        }
        
        foreach ($found_entries as $entry_info) {
            $result[] = new Comic($entry_info); 
        }
        
        return $result;
    } 
}

?>