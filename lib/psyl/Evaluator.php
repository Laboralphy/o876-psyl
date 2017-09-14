<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 02:11
 */

namespace O876\Psyl;


class Evaluator {

    protected $_opcodes = array();

    public function opcode($sOpcode, $pCallable) {
        $this->_opcodes[$sOpcode] = $pCallable;
    }

    public function invoke($sCommand, $aParams) {
        if (array_key_exists($sCommand, $this->_opcodes)) {
            return call_user_func_array($this->_opcodes[$sCommand], $aParams);
        } else {
            throw new Exception('undefined opcode : ', $sCommand);
        }
    }

    public function evaluate($aSource) {
        if (is_array($aSource)) {
            if (count($aSource)) {
                return $this->invoke(
                    $aSource[0],
                    array_map(
                        array($this, 'evaluate'),
                        array_slice($aSource, 1)
                    )
                );
            }
        } else {
            return $aSource;
        }
    }
}