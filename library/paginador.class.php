<?php

class Paginador
{

    protected $_controller;
    protected $_action;
    protected $pagina_actual;
    protected $_modelo;
    protected $cat;

    /**
     * Funcion que crea el paginado
     * @param string $controller controlador para el link del paginado
     * @param string $action accion para el link del paginado
     * @param string $modelo modelo para paginar
     * @param int $pagina pagina en la que estoy ubicado
     * @param <type> $cat
     */
    function __construct($controller, $action, $modelo, $pagina, $cat)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->pagina_actual = $pagina;
        $this->_modelo = $modelo;
        $this->cat = $cat;
    }

    /**
     * Funcion que muestra el paginado en el administrador
     * @param string $buscar filtro de busqueda
     * @param string $folder la carpeta luego del root_path, se usa ppalmente para el admin
     * @return int $paginado retorna la estructura del paginado
     */
    function mostrarPaginado($buscar='', $folder='admin')
    {

        $total = $this->_modelo->totalPages();
        $inicio = $this->pagina_actual;
        $pagina_actual = $this->pagina_actual;

        $query = "";
        if ($buscar != "")
        {
            $query = "/$buscar";
        }
        $paginado = '<div class="pagination">';
        if ($pagina_actual == 1)
        {
            $atras = 1;
            $paginado.= '<span class="disabled prev_page">« Previous</span>';
        } else
        {
            $atras = $pagina_actual - 1;
            $paginado.= '<a rel="prev" class="prev_page" href="' . BASE_PATH . '/' . $folder . '/' . $this->_controller . '/' . $this->_action . '/' . $atras . $query . '">« Previous</a>';
        }

        $paginado.='<select onchange="window.location=\'' . BASE_PATH . '/' . $folder . '/' . $this->_controller . '/' . $this->_action . '/\'+this.value+\'' . $query . '\'">';

        for ($i = 1; $i <= $total; $i++)
        {
            if ($i == $pagina_actual)
            {
                $paginado.='<option selected="selected"  value="' . $i . '" > ' . $i . ' </option>';
            } else
            {
                $paginado.='<option  value="' . $i . '" > ' . $i . ' </option>';
            }
        }
        $paginado.='</select>';

        if ($pagina_actual == $total)
        {
            $siguiente = $total;
            $paginado.='<span class="disabled next_page">Next »</span>';
        } else
        {
            $siguiente = $pagina_actual + 1;
            $paginado.= '<a rel="next" class="next_page" href="' . BASE_PATH . '/' . $folder . '/' . $this->_controller . '/' . $this->_action . '/' . $siguiente . $query . '">Next »</a>';
        }
        $paginado.= '</div>';
        return $paginado;
    }   
}
