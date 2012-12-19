<?php

  /**
  * Query error
  *
  * @version 1.0
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class NotImplementedError extends Error {
  	var $method_name;
    
    function __construct($method_name, $error_message) {
      parent::__construct($error_message);
      $this->method_name = $method_name;
    } // __construct
    
   
  
    
    function __toString(){
    	return $this->getErrorMessage() . "(".$this->method_name.")";
    }
  } // DBQueryError

?>