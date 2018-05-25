<?php
/**
 * La clase Registro almacena las referencias de los objetos del sistema
 *
 */
class Registro {
    protected $_objects = array();

    function set($name, $object) {
        $this->_objects[$name] = $object;
    }

    function get($name) {
        return $this->_objects[$name];
    }
}
?>