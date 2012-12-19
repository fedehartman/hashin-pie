<?php

class HTML {
    private $js = array();

   
    function shortenUrls($data) {
        $data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class($this), '_fetchTinyUrl'), $data);
        return $data;
    }

    private function _fetchTinyUrl($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url[0]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return '<a href="'.$data.'" target = "_blank" >'.$data.'</a>';
    }

    function sanitize($data) {
        return mysql_real_escape_string($data);
    }

    /**
     * Funcion que crea la etiqueta <a> </a> de html.
     * @param string $text texto del link
     * @param string $path ruta a donde dirige el link
     * @param boolean $prompt si necesita confirmacion o no
     * @param string $confirmMessage mensaje de confirmacion
     * @return string $data devuelve la etiqueta <a> armada
     */
    function link($text,$path,$prompt = null,$confirmMessage = "Are you sure?",$class = "") {
        $path = str_replace(' ','-',$path);
        if ($prompt) {
            $data = '<a href="javascript:void(0);" onclick="javascript:jumpTo(\''.BASE_PATH.'/'.$path.'\',\''.$confirmMessage.'\')" class="'.$class.'" >'.$text.'</a>';
        } else {
            $data = '<a href="'.BASE_PATH.'/'.$path.'" class="'.$class.'" >'.$text.'</a>';
        }
        return $data;
    }
    
    function linkConfirm($text,$path,$prompt = null,$confirmMessage = "Estas seguro?") {
        $path = str_replace(' ','-',$path);
        if ($prompt) {
            $data = '<a href="'.BASE_PATH.'/'.$path.'" onclick="return confirm(\''.$confirmMessage.'\')" >'.$text.'</a>';
        } else {
            $data = '<a href="'.BASE_PATH.'/'.$path.'">'.$text.'</a>';
        }
        return $data;
    }

    /**
     * Funcion que crea la etiqueta <img></img> de html.
     * @param string $file_name ruta de la imagen
     * @param string $title titulo de la imagen
     * @param string $class clase de la etiqueta
     * @param string $id id de la etiqueta
     * @return string $data devuelve la etiqueta <img> armada
     */
    function image($file_name,$title,$class='',$id) {
    	if ($id) $id = "id = '".$id."'"; 
    	$data = '<img src="'.BASE_PATH.'/img/'.$file_name.'" title = "'.$title.'" border="0" align="absmiddle" '.$id.' class="'.$class.'" />';
        return $data;
    }


    function select(array $opciones, array $campos, $name, $id, $selected_val = '', $class = '', $emptyOption = ''){
        $out = "<select name='$name' id='$id' class='$class' >";
        
        if ($emptyOption){
            $out .= "<option value=''>$emptyOption</option>";
        }
        if (!empty($opciones)) {
            foreach ($opciones as $opcion) {
            	$sel = '';
            	if ($selected_val == $opcion[$campos['modelo']][$campos['value']]) $sel = 'selected = "selected"';
                $out .= "<option value='".$opcion[$campos['modelo']][$campos['value']]."' $sel>".utf8_encode($opcion[$campos['modelo']][$campos['texto']])."</option>";
            }
        }
        $out .= "</select>";
        return $out;
    }

    /**
     * Funcion que inserta la etiqueta <form> de html. Solo la abre.
     * @param string $name nombre del formulario
     * @param string $id id del formulario
     * @param string $action accion del formulario
     * @param string $method metodo del formulario
     * @param string $enctype tipo de documento en formato MIME.
     * @return string $out devuelve la etiqueta <form> armada
     */
    function startForm($name, $id, $action, $method ='', $enctype="", $class=""){
        if (!$method)  $method = 'POST';
        $out = "<form name='$name' id='$id' action='".BASE_PATH . $action ."' method='$method' enctype='$enctype' class='$class' >";
        return $out;
    }

    /**
     * Funcion que inserta la etiqueta </form> de html. Cierra el formulario.
     * @param boolean $send coloca el boton par el submit del form
     * @return $out
     */
    function endForm($send){
        $out = '';
        if ($send)  $out .= '<input type="submit" value="'.$send.'" name="submit" >';
        $out .= "</form>";
        return $out;
    }

    
    function enumToCombo(Itertor $it, $name, $id, $selected_val = '', $class = '', $emptyOption = '', $emptyOptionValue = ''){
    	$out = "<select name='$name' id='$id' class='$class' >";
		if ($emptyOption){
            $out .= "<option value='$emptyOptionValue'>$emptyOption</option>";
        }
        $i=0;
    	foreach ($it as $elem){
        	$sel = '';
        	if ($selected_val == $elem) $sel = 'selected = "selected"';
    		$out .= "<option value='".$elem."' $sel>".$elem->getName()."</option>";
    		$i++;
    	}
        $out .= "</select>";
        return $out;
    }
    /**
     * Funcion que crea el include de un javascript de html
     * @param string $fileName nombre del archivo a incluir
     * @return $data devuelve la estructura del js para incluir
     */
    function includeJs($fileName) {
        $data = '<script src="'.BASE_PATH.'/js/'.$fileName.'.js"></script>';
        return $data;
    }

    /**
     * Funcion que crear el include de un css
     * @param string $fileName nombre del css a incluir
     * @return string $data devuelve la estructura del css para incluir
     */
    function includeCss($fileName) {
        $data = '<link type="text/css" rel="Stylesheet" href="'.BASE_PATH.'/public/css/'.$fileName.'.css" />';
        return $data;
    }
}