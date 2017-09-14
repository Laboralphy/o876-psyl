<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 10:15
 */

namespace O876\Psyl\Modules;

require_once 'Module.php';

use O876\Psyl\Exception;
use O876\Psyl\Evaluator;
use O876\Psyl\Atom;
use O876\Psyl\Modules\Module;


class Math implements Module {

    public function evaluateAllAsNumeric(Evaluator $evaluator, $aList) {
        $aOutput = array();
        foreach ($aList as $iParam => $value) {
            if ($value instanceof Atom) {
                $aOutput[] = $value->numeric();
            }
            $output = $evaluator->evaluate($value);
            if (!is_numeric($output)) {
                throw new Exception('argument #' . ($iParam + 1) . ' is not a numeric');
            }
            $aOutput[] = $output;
        }
        return $aOutput;
    }


    public function init(Evaluator $evaluator) {

        $evaluator->opcode('add', function() use ($evaluator) {
            return array_reduce($this->evaluateAllAsNumeric($evaluator, func_get_args()), function($prev, $curr) {
                return $curr + $prev;
            }, 0);
        });

        $evaluator->opcode('sub', function($a, $b) use ($evaluator) {
            $aOperands = $this->evaluateAllAsNumeric($evaluator, array($a, $b));
            return $aOperands[1] - $aOperands[2];
        });

        $evaluator->opcode('mul', function() use ($evaluator) {
            return array_reduce($this->evaluateAllAsNumeric($evaluator, func_get_args()), function($prev, $curr) {
                return $curr * $prev;
            }, 1);
        });

        $evaluator->opcode('div', function($a, $b) use ($evaluator) {
            $aOperands = $this->evaluateAllAsNumeric($evaluator, array($a, $b));
            return $aOperands[1] / $aOperands[2];
        });

        $evaluator->opcode('mod', function($a, $b) use ($evaluator) {
            $aOperands = $this->evaluateAllAsNumeric($evaluator, array($a, $b));
            return $aOperands[1] / $aOperands[2];
        });

    }
}