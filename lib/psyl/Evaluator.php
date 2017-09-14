<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 02:11
 */

namespace O876\Psyl;

require_once 'EvaluationException.php';


/**
 * Class Evaluator
 * This class evaluates a structure built by the parser, and calls back functions that matching all first atoms
 * @package O876\Psyl
 */
class Evaluator {

    protected $_opcodes = array();

    /**
     * Declares a new opcode callback
     * @param $sOpcode string
     * @param $pCallable callable
     */
    public function opcode($sOpcode, $pCallable) {
        $this->_opcodes[$sOpcode] = $pCallable;
    }

    public function module($m) {
        $m->init($this);
    }

    /**
     * Runs a function bound to an opcode
     * @param $sCommand string
     * @param $aParams array
     * @return mixed
     * @throws EvaluationException
     */
    public function invoke($oCommand, $aParams) {
        $sCommand = (string) $oCommand;
        if (array_key_exists($sCommand, $this->_opcodes)) {
            try {
                return call_user_func_array($this->_opcodes[$sCommand], $aParams);
            } catch (Exception $e) {
                throw new EvaluationException($sCommand . ' : ' . $e->getMessage() . ':' . $oCommand->index());
            }
        } else {
            throw new EvaluationException('undefined opcode : ' . $sCommand . ':' . $oCommand->index());
        }
    }

    /**
     * Evaluate an array.
     * @param $aSource array|string|integer|boolean
     * @param $bRecursive boolean
     * @return mixed
     */
    public function evaluate($aSource, $bRecursive = false) {
        if (is_array($aSource)) {
            if (count($aSource)) {
                $aParams = array_slice($aSource, 1);
                if ($bRecursive) {
                    $aParams = array_map(function($item) use ($bRecursive) {
                        return $this->evaluate($item, $bRecursive);
                    }, $aParams);
                }
                return $this->invoke(
                    $aSource[0],
                    $aParams
                );
            } else {
                return $aSource;
            }
        } else {
            return $aSource;
        }
    }
}