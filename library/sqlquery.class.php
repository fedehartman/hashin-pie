<?php

class SQLQuery
{

    protected $_dbHandle;
    protected $_result;
    protected $_query;
    protected $_table;
    protected $_describe = array();
    protected $_orderBy;
    protected $_orderBy2;
    protected $_order;
    protected $_order2;
    protected $_orderByHM;
    protected $_orderHM;
    protected $_orderModelHM;
    protected $_extraConditions;
    protected $_hO;
    protected $_hM;
    protected $_hMABTM;
    protected $_page;
    protected $_limit;
    protected $_setUpdatedDate = true;
    protected $_fields = array();
    protected $_ignore;

    /** Connects to database * */
    function connect($address, $account, $pwd, $name)
    {
        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->_dbHandle != 0)
        {
            if (mysql_select_db($name, $this->_dbHandle))
            {
                return 1;
            } else
            {
                return 0;
            }
        } else
        {
            return 0;
        }
    }

    /** Disconnects from database * */
    function disconnect()
    {
        if (@mysql_close($this->_dbHandle) != 0)
        {
            return 1;
        } else
        {
            return 0;
        }
    }

    /** Select Query * */

    /**
     * Inserta el where a la consulta. Se puede utilizar infinitas veces la funcion pero una sola comparacion por vez.
     * @param string $field tabla en la base de datos por la cual comparar
     * @param string $value valor a comparar
     * @param string $operator operador para comparar
     */
    function where($field, $value, $operator = '')
    {
        if ($operator != '')
        {
            if ($operator != 'IN')
            {
                $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` ' . $operator . ' \'' . mysql_real_escape_string($value) . '\' AND ';
            } else
            {
                $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` ' . $operator . ' (' . mysql_real_escape_string($value) . ') AND ';
            }
        } else
        {
            $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` = \'' . mysql_real_escape_string($value) . '\' AND ';
        }
    }

    /**
     * Funcion que permite ingresar un where completo a la consulta.
     * Completo quiere decir colocar toda la estructura de comparacion, ej: "destacado=1".
     * @param string $where where completo de la consulta.
     */
    function whereCustom($where)
    {
        $this->_extraConditions .= $where . ' AND ';
    }

    function like($field, $value)
    {
        $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` LIKE \'%' . mysql_real_escape_string($value) . '%\' AND ';
    }

    function whereNull($field, $not = '')
    {
        $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` IS ' . $not . ' NULL AND ';
    }

    /**
     * Funcion que permite seleccionar tambien el relacionado de la tabla(category_id). Para que esto tenga efecto hay que setearlo
     * en el modelo primero, ej: "var $hasOne = array('Category' => 'Category');". Devuelve 1 solo valor.
     */
    function showHasOne()
    {
        $this->_hO = 1;
    }

    /**
     * Funcion que permite seleccionar tambien el relacionado de la tabla(category_id). Para que esto tenga efecto hay que setearlo
     * en el modelo primero, ej: "var $hasMany = array('Category' => 'Category');". Devuelve muchos valores.
     */
    function showHasMany()
    {
        $this->_hM = 1;
    }

    function showHMABTM()
    {
        $this->_hMABTM = 1;
    }

    /**
     * Inserta limite a la consulta
     * @param int $limit Límite para la consulta, pasar "All" para obtener todos.
     */
    function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    function insertIgnore($val)
    {
        $this->_ignore = $val;
    }

    /**
     * Indica la pagina que estoy mostrando
     * @param int $page
     */
    function setPage($page)
    {
        $this->_page = $page;
    }

    function getPage()
    {
        return $this->_page;
    }

    /**
     * Inserta el orden a la consulta.
     * @param string $orderBy tabla de la base de datos por cual ordenar. Si recibe "random" ordena aleatoriamente.
     * @param string $order tipo del orden ascendento o descente
     */
    function orderBy($orderBy, $order = 'ASC')
    {
        if ($orderBy == "random")
        {
            $this->_orderBy = "Rand()";
        } else
        {
            $this->_orderBy = $orderBy;
            $this->_order = $order;
        }
    }

    /**
     * Inserta el orden a la consulta. Se utiliza si hay mas 1 un orden en la consulta.
     * @param string $orderBy tabla de la base de datos por cual ordenar. Si recibe "random" ordena aleatoriamente.
     * @param string $order tipo del orden ascendento o descente
     */
    function orderBy2($orderBy, $order = 'ASC')
    {
        if ($orderBy == "random")
        {
            $this->_orderBy2 = "Rand()";
        } else
        {
            $this->_orderBy2 = $orderBy;
            $this->_order2 = $order;
        }
    }

    function getConditions()
    {
        return $this->_extraConditions;
    }

    /**
     * Funcion para ordenar los relacionados.
     * @param string $orderBy tabla de la base de datos por cual ordenar.
     * @param string $model nombre del modelo relacionado.
     * @param string $order tipo del orden ascendente o descente.
     */
    function orderByHasMany($orderBy, $model, $order = 'ASC')
    {
        $this->_orderByHM = $orderBy;
        $this->_orderHM = $order;
        $this->_orderModelHM = $model;
    }

    function doSetUpdatedDate($doUpdate)
    {
        $this->_setUpdatedDate = $doUpdate;
    }

    function setFields(array $fields)
    {
        $this->_fields = $fields;
    }

    /**
     * Funcion que ejecuta la consulta a la base de datos
     * @global <type> $inflect
     * @return array $result devuelve el resultado a la consulta de la base de datos
     */
    function search()
    {

        global $inflect;

        $from = '`' . $this->_table . '` as `' . $this->_model . '` ';
        $conditions = '\'1\'=\'1\' AND ';
        $conditionsChild = '';
        $fromChild = '';
        if ($this->_hO == 1 && isset($this->hasOne))
        {

            foreach ($this->hasOne as $alias => $model)
            {
                $table = strtolower($inflect->pluralize($model));
                $singularAlias = strtolower($alias);
                $from .= 'LEFT JOIN `' . $table . '` as `' . $alias . '` ';
                $from .= 'ON `' . $this->_model . '`.`' . $singularAlias . '_id` = `' . $alias . '`.`id`  ';
            }
        }

        if ($this->id)
        {
            $conditions .= '`' . $this->_model . '`.`id` = \'' . mysql_real_escape_string($this->id) . '\' AND ';
        }

        if ($this->_extraConditions)
        {
            $conditions .= $this->_extraConditions;
        }

        $conditions = substr($conditions, 0, -4);

        if (isset($this->_orderBy))
        {
            //Si esta seteado random en order by cargo esta condicion para que funcione la consulta
            if ($this->_orderBy == "Rand()")
            {
                $conditions .= ' ORDER BY ' . $this->_orderBy;
            }
            //Sino hago todo como estaba antes
            else
            {

                if (isset($this->_orderBy2))
                {
                    $conditions .= ' ORDER BY `' . $this->_model . '`.`' . $this->_orderBy . '` ' . $this->_order . ',`' . $this->_model . '`.`' . $this->_orderBy2 . '` ' . $this->_order2;
                } else
                {
                    $conditions .= ' ORDER BY `' . $this->_model . '`.`' . $this->_orderBy . '` ' . $this->_order;
                }
            }
        }

        if (isset($this->_page))
        {
            $offset = ($this->_page - 1) * $this->_limit;
            if ($offset >= 0)
            {
                $conditions .= ' LIMIT ' . $this->_limit . ' OFFSET ' . $offset;
            }
        } else
        {
            if (isset($this->_limit) && $this->_limit != "All")
            {
                $conditions .= ' LIMIT ' . $this->_limit;
            } else
            {
                $conditions .= "";
            }
        }

        if (!empty($this->_fields))
            $fields = implode(',', $this->_fields);
        else
            $fields = '*';
        $this->_query = 'SELECT ' . $fields . ' FROM ' . $from . ' WHERE ' . $conditions;
        //echo($this->_query ."<br>");die();
        //echo '<!--'.$this->_query.'-->';

        $this->_result = mysql_query($this->_query, $this->_dbHandle);

        if ($this->_result == 0)
        {
            /** Error Generation * */
            throw new DBQueryError($this->_query, mysql_errno($this->_dbHandle), $this->getError());
        }

        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();
        $numOfFields = mysql_num_fields($this->_result);
        for ($i = 0; $i < $numOfFields; ++$i)
        {
            array_push($table, mysql_field_table($this->_result, $i));
            array_push($field, mysql_field_name($this->_result, $i));
        }
        if (mysql_num_rows($this->_result) > 0)
        {
            while ($row = mysql_fetch_row($this->_result))
            {
                for ($i = 0; $i < $numOfFields; ++$i)
                {
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }

                if ($this->_hM == 1 && isset($this->hasMany))
                {
                    foreach ($this->hasMany as $aliasChild => $modelChild)
                    {
                        $queryChild = '';
                        $conditionsChild = '';
                        $fromChild = '';

                        $tableChild = strtolower($inflect->pluralize($modelChild));
                        $pluralAliasChild = strtolower($inflect->pluralize($aliasChild));
                        $singularAliasChild = strtolower($aliasChild);

                        $fromChild .= '`' . $tableChild . '` as `' . $aliasChild . '`';

                        $conditionsChild .= '`' . $aliasChild . '`.`' . strtolower($this->_model) . '_id` = \'' . $tempResults[$this->_model]['id'] . '\'';

                        if (isset($this->_orderByHM))
                        {
                            $conditionsChild .= ' ORDER BY `' . $this->_orderModelHM . '`.`' . $this->_orderByHM . '` ' . $this->_orderHM;
                        }

                        $queryChild = 'SELECT * FROM ' . $fromChild . ' WHERE ' . $conditionsChild;
                        //echo $queryChild;
                        #echo '<!--'.$queryChild.'-->';
                        $resultChild = mysql_query($queryChild, $this->_dbHandle);

                        if ($resultChild == 0)
                        {
                            /** Error Generation * */
                            throw new DBQueryError($resultChild, mysql_errno($this->_dbHandle), $this->getError());
                        }

                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();

                        if (mysql_num_rows($resultChild) > 0)
                        {
                            $numOfFieldsChild = mysql_num_fields($resultChild);
                            for ($j = 0; $j < $numOfFieldsChild; ++$j)
                            {
                                array_push($tableChild, mysql_field_table($resultChild, $j));
                                array_push($fieldChild, mysql_field_name($resultChild, $j));
                            }

                            while ($rowChild = mysql_fetch_row($resultChild))
                            {
                                for ($j = 0; $j < $numOfFieldsChild; ++$j)
                                {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }
                        }

                        $tempResults[$aliasChild] = $resultsChild;

                        mysql_free_result($resultChild);
                    }
                }


                if ($this->_hMABTM == 1 && isset($this->hasManyAndBelongsToMany))
                {
                    foreach ($this->hasManyAndBelongsToMany as $aliasChild => $tableChild)
                    {
                        $queryChild = '';
                        $conditionsChild = '';
                        $fromChild = '';

                        $tableChild = strtolower($inflect->pluralize($tableChild));
                        $pluralAliasChild = strtolower($inflect->pluralize($aliasChild));
                        $singularAliasChild = strtolower($aliasChild);

                        $sortTables = array($this->_table, $pluralAliasChild);
                        sort($sortTables);
                        $joinTable = implode('_', $sortTables);

                        $fromChild .= '`' . $tableChild . '` as `' . $aliasChild . '`,';
                        $fromChild .= '`' . $joinTable . '`,';

                        $conditionsChild .= '`' . $joinTable . '`.`' . $singularAliasChild . '_id` = `' . $aliasChild . '`.`id` AND ';
                        $conditionsChild .= '`' . $joinTable . '`.`' . strtolower($this->_model) . '_id` = \'' . $tempResults[$this->_model]['id'] . '\'';
                        $fromChild = substr($fromChild, 0, -1);

                        $queryChild = 'SELECT * FROM ' . $fromChild . ' WHERE ' . $conditionsChild;
                        #echo '<!--'.$queryChild.'-->';
                        $resultChild = mysql_query($queryChild, $this->_dbHandle);

                        if ($resultChild == 0)
                        {
                            /** Error Generation * */
                            throw new DBQueryError($queryChild, mysql_errno($this->_dbHandle), $this->getError());
                        }


                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();

                        if (mysql_num_rows($resultChild) > 0)
                        {
                            $numOfFieldsChild = mysql_num_fields($resultChild);
                            for ($j = 0; $j < $numOfFieldsChild; ++$j)
                            {
                                array_push($tableChild, mysql_field_table($resultChild, $j));
                                array_push($fieldChild, mysql_field_name($resultChild, $j));
                            }

                            while ($rowChild = mysql_fetch_row($resultChild))
                            {
                                for ($j = 0; $j < $numOfFieldsChild; ++$j)
                                {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }
                        }

                        $tempResults[$aliasChild] = $resultsChild;
                        mysql_free_result($resultChild);
                    }
                }

                array_push($result, $tempResults);
            }

            if (mysql_num_rows($this->_result) == 1 && $this->id != null)
            {
                mysql_free_result($this->_result);
                $this->clear();
                return($result[0]);
            } else
            {
                mysql_free_result($this->_result);
                $this->clear();
                return($result);
            }
        } else
        {
            mysql_free_result($this->_result);
            $this->clear();
            return $result;
        }
    }

    /** Custom SQL Query * */
    function custom($query)
    {
        //die($query);
        //echo $query;
        global $inflect;

        $this->_query = $query;
        
        $this->_result = mysql_query($query, $this->_dbHandle);
        
        if ($this->_result == 0)
        {
            /** Error Generation * */
            throw new DBQueryError($query, mysql_errno($this->_dbHandle), $this->getError());
        }

        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();

        if (substr_count(strtoupper($query), "SELECT") > 0)
        {
            if (mysql_num_rows($this->_result) > 0)
            {
                $numOfFields = mysql_num_fields($this->_result);
                for ($i = 0; $i < $numOfFields; ++$i)
                {
                    array_push($table, mysql_field_table($this->_result, $i));
                    array_push($field, mysql_field_name($this->_result, $i));
                }
                while ($row = mysql_fetch_row($this->_result))
                {
                    for ($i = 0; $i < $numOfFields; ++$i)
                    {
                        $table[$i] = ucfirst($inflect->singularize($table[$i]));
                        $tempResults[$table[$i]][$field[$i]] = $row[$i];
                    }
                    array_push($result, $tempResults);
                }
            }
            mysql_free_result($this->_result);
        }
        $this->clear();
        return($result);
    }

    /** Describes a Table * */

    /**
     * Funcion que permite ver la estructura de una tabla.
     * @global <type> $cache
     */
    protected function _describe()
    {
        global $cache;

        $this->_describe = $cache->get('describe' . $this->_table);

        if (!$this->_describe)
        {
            $this->_describe = array();
            $query = 'DESCRIBE `' . $this->_table . '`';
            $this->_result = mysql_query($query, $this->_dbHandle);
            while ($row = mysql_fetch_row($this->_result))
            {
                array_push($this->_describe, $row[0]);
            }

            mysql_free_result($this->_result);
            $cache->set('describe' . $this->_table, $this->_describe);
        }

        foreach ($this->_describe as $field)
        {
            $this->$field = null;
        }
    }

    /**
     * Funcion que ejecuta el borrado de una determinada tupla de la base de datos.
     * Delete an Object
     * @return string -1 devuelve esto si no pudo ejecutar la consulta, sino no devuelve nada.
     */
    function delete()
    {
        if ($this->id)
        {
            $query = 'DELETE FROM `' . $this->_table . '` WHERE `id`=\'' . mysql_real_escape_string($this->id) . '\'';
            $this->_result = mysql_query($query, $this->_dbHandle);
            $this->clear();
            if ($this->_result == 0)
            {
                /** Error Generation * */
                return -1;
            }
        } else
        {
            /** Error Generation * */
            return -1;
        }
    }

    /**
     * Funcion que ejecuta el borrado de una relacion de un determinado id
     * Delete an Object
     * @return string -1 devuelve esto si no pudo ejecutar la consulta, sino no devuelve nada.
     */
    function deleteRelation($field, $id)
    {
        if ($id)
        {
            $query = 'DELETE FROM `' . $this->_table . '` WHERE ' . $field . '=\'' . mysql_real_escape_string($id) . '\'';
            $this->_result = mysql_query($query, $this->_dbHandle);
            $this->clear();
            if ($this->_result == 0)
            {
                /** Error Generation * */
                return -1;
            }
        } else
        {
            /** Error Generation * */
            return -1;
        }
    }

    /**
     * Funcion que guarda o inserta una tupla en la base de datos
     * Saves an Object i.e. Updates/Inserts Query
     */
    function save()
    {
        $query = '';
              
        if (isset($this->id))
        {
            $updates = '';
            foreach ($this->_describe as $field)
            {
                if (isset($this->$field))
                {
                    $value = $this->$field;
                    if ($field === 'password')
                    {
                        $value = getPasswordHash(getPasswordSalt(), $value);
                    }
                    $updates .= '`' . $field . '` = \'' . mysql_real_escape_string($value) . '\',';
                } else if ($field === 'updated_on' && $this->_setUpdatedDate)
                {
                    $updates .= '`' . $field . '` = \'NOW()\',';
                }
            }

            $updates = substr($updates, 0, -1);

            $query = 'UPDATE `' . $this->_table . '` SET ' . $updates . ' WHERE `id`=\'' . mysql_real_escape_string($this->id) . '\'';
        } else
        {
            $fields = '';
            $values = '';
            $ignore = '';
            foreach ($this->_describe as $field)
            {

                if (isset($this->$field))
                {

                    $fields .= '`' . $field . '`,';
                    $value = $this->$field;
                    if ($field === 'password')
                    {
                        $value = getPasswordHash(getPasswordSalt(), $value);
                    }
                    $values .= '\'' . mysql_real_escape_string($value) . '\',';
                } else if ($field === 'created_on')
                {
                    $fields .= '`' . $field . '`,';
                    $values .= 'date_add(NOW(), INTERVAL ' . AJUSTE_FECHA . ' HOUR),';
                }
            }
            $values = substr($values, 0, -1);
            $fields = substr($fields, 0, -1);

            if ($this->_ignore == true)
            {
                $ignore = "IGNORE";
            }

            $query = 'INSERT `' . $ignore . '` INTO ' . $this->_table . ' (' . $fields . ') VALUES (' . $values . ')';
        }
        // die($query);
        $this->_result = mysql_query($query, $this->_dbHandle);

        if (!isset($this->id))
        {
            $this->id = mysql_insert_id($this->_dbHandle);
        }

        //$this->clear();
        if ($this->_result == 0)
        {
            /** Error Generation * */
            throw new DBQueryError($query, mysql_errno($this->_dbHandle), $this->getError());
        }
    }

    protected function columnExists($column_name)
    {
        return in_array($column_name, $this->_describe);
    }

    protected function loadFromRow($row)
    {
        if (is_array($row))
        {
            // para cada campo
            foreach ($row as $k => $v)
            {
                // si la columna existe
                if ($this->columnExists($k))
                {
                    $this->$k = $v;
                }
            } // foreach

            return true;
        } else
        {// if
            return null;
        }

        // Error...
        throw new Exception("Error al cargar objeto desde base de datos: loadFromRow");
    }

// loadFromRow

    /**
     * Funcion que borra todas las variables seteadas de la clase..
     * Clear All Variables
     */
    function clear()
    {
        foreach ($this->_describe as $field)
        {
            $this->$field = null;
        }

        $this->_orderby = null;
        $this->_extraConditions = null;
        $this->_hO = null;
        $this->_hM = null;
        $this->_hMABTM = null;
        $this->_orderByHM = null;
        $this->_orderHM = null;
        $this->_orderModelHM = null;
        $this->_page = null;
        $this->_order = null;
        $this->_setUpdatedDate = true;
        $this->_fields = array();
    }
    
    /**
     * Funcion que retorna el count.
     * @param string $where where del count
     * @return int $totalCount total de tuplas.
     */
    function countResult($where)
    {
            $countQuery = "SELECT COUNT(*) FROM `" . $this->_table . "` WHERE ".$where."";
            
            $this->_result = mysql_query($countQuery, $this->_dbHandle);
            $count = mysql_fetch_row($this->_result);

            $totalCount = ceil($count[0]);

            return $totalCount;
    }

    /**
     * Funcion que retorna la cantidad de paginas.
     * Pagination Count
     * @return int $totalPages o -1 retorna -1 si no ejecuta nada, sino retorna el total de paginas.
     */
    function totalPages()
    {
        if ($this->_query && $this->_limit)
        {

            $pattern = '/SELECT (.*?) FROM (.*)LIMIT(.*)/i';
            $replacement = 'SELECT COUNT(*) FROM $2';
            $countQuery = preg_replace($pattern, $replacement, $this->_query);


            //ACA CAMBIE COSAS [MARTIN]
            //$countQuery=str_replace('*','COUNT(*)',$this->_query);
            //echo $countQuery.'<br/>';
            //-------------------------------------------------

            $this->_result = mysql_query($countQuery, $this->_dbHandle);
            $count = mysql_fetch_row($this->_result);

            $totalPages = ceil($count[0] / $this->_limit);

            return $totalPages;
        } else
        {
            /* Error Generation Code Here */
            return -1;
        }
    }

    /** Get error string * */
    function getError()
    {
        return mysql_error($this->_dbHandle);
    }

}
