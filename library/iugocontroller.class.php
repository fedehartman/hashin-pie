<?php
session_start();
class IugoController {
	
	protected $_controller;
	protected $_action;
        
	function __construct($controller, $action) {
		
		global $inflect;

		$this->_controller = ucfirst($controller);
		$this->_action = $action;
		
		$model = ucfirst($inflect->singularize($controller));
		$this->$model =& new $model;

	}
        
        function __destruct() {
            
	}
		
}
