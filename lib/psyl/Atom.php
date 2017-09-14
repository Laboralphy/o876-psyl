<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 15:25
 */

namespace O876\Psyl;


class Atom {
    protected $_atom;
    protected $_index;

    public function __toString() {
        return $this->_atom;
    }

    protected function _prop($s, $v = null) {
        if (is_null($v)) {
            return $this->$s;
        } else {
            $this->$s = $v;
            return $this;
        }
    }

    public function value($n = null) {
        return $this->_prop('_atom', $n);
    }
    public function index($n = null) {
        return $this->_prop('_index', $n);
    }

    public function numeric() {
        if (!is_numeric($this->_atom)) {
            throw new EvaluationException('this atom cannot be converted to numeric');
        }
        return 0 + $this->_atom;
    }
}