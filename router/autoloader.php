<?php  
  spl_autoload_register(function($class){
    $file = './' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.class.php';
    
    if (file_exists($file)) {
      include_once $file;
    } else {
      throw new Exception('Non-existing class: ' . $class);
    }
  });
