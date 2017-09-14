<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 02:24
 */

use O876\Psyl\Evaluator;

class EvaluatorTest extends PHPUnit_Framework_TestCase
{
    function testSimpleEvaluate() {
        $os = new Evaluator();
        $os->opcode('sum', function($a, $b) {
            return $a + $b;
        });
        $this->assertEquals(23, $os->evaluate(array('sum', 10, 13)));
        $os->opcode('mul', function($a, $b) {
            return $a * $b;
        });
        $os->opcode('concat', function() {
            return implode('', func_get_args());
        });
        $this->assertEquals('20---15', $os->evaluate(
            array('concat',
                array('sum', 50, -30),
                "---",
                array('mul', 3, 5)
            )));

    }

}
