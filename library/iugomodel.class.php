<?php
/**
 * @author Nicolas Medeiros <nicolas@iugo.com.uy>
 */
class IugoModel extends SQLQuery {
	protected $_model;

	function __construct() {
		
		global $inflect;
				
		if ($this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)) {						
			$this->_limit = PAGINATE_LIMIT;
			$this->_model = get_class($this);
			$this->_table = strtolower($inflect->pluralize($this->_model));
			if (!isset($this->abstract)) {
				$this->_describe();
			}	
		} else {
			throw new DBConnectError(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		}
		
	}

	function __destruct() {
            
	}
	
        /**
         * example of basic @return usage
         * @return mixed
         */
	function getItemClass(){
		return ucfirst($this->_table);
	}

	/**
         * Funcion que devuelve todos los datos del modelo dado.
         * @param string $orderBy le paso la tabla por cual quiero ordenar, puede ser vacio si no tiene orden
         * @param string $order le paso el orden, si no esta definido lo ordena por ASC
         * @return array devuelve el arreglo del modelo ubicado
         */
        function getAll($orderBy='',$order='ASC') {
            $class = $this->_model;
            $item = new $class();
            if($orderBy!="")
            {
                $item->orderBy($orderBy,$order);
            }
            return $item->search();
	}
	
	
	function loadObject($row) {
          if($this->loadFromRow($row)) {
            return $this;
          } 

          return null;
	} 
	
}
