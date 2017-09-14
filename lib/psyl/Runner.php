<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 17:40
 */

namespace O876\Psyl;

require_once 'Modules/Math.php';

use O876\Psyl\Modules\Math;

/**
 * Class Runner
 * @package O876\Psyl
 * @class Runner
 *
 * a runner will parse and evaluate a source.
 * if an error occurs, the runner is able to display an error message
 *
 */
class Runner {

    protected $_evaluator;
    protected $_parser;

    public function exec($sSource) {
        try {
            if (!$this->_parser) {
                $this->_parser = new Parser();
            }
            if (!$this->_evaluator) {
                $this->_evaluator = new Evaluator();
                $this->_evaluator->module(new Math());
            }
            $aSource = $this->_parser->parse($sSource);
            return $this->_evaluator->evaluate($aSource);
        } catch (EvaluationException $e) {
            $nChar = (int) array_pop(explode(':', $e->getMessage()));
            $nStart = mb_strrpos(mb_substr($sSource, 0, $nChar), "\n");
            print '////' . $nStart . '----';
            if ($nStart === false) {
                $nStart = 0;
            }
            $nEnd = mb_strpos(mb_substr($sSource, $nChar), "\n");
            if ($nEnd === false) {
                $nEnd = mb_strlen($sSource);
            }
            $nLen = $nEnd - $nStart;
            throw new EvaluationException($e->getMessage() . "\n***$nChar $nStart $nEnd" . mb_substr($sSource, $nStart, $nLen) . " $nLen***");
        }
    }
}