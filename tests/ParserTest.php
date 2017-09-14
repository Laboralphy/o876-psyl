<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 01:56
 */

use O876\Psyl\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    function testSimpleParse() {
        $p = new Parser();
        $this->assertInstanceOf(Parser::class, $p, 'should an instance of Parser');
        $a = $p->parse(<<<EOT
(test 1 3 5 (abc x y z "this is an atom"))
    
EOT
);
        $this->assertEquals(array(
            'test', 1, 3, 5, array('abc', 'x', 'y', 'z', 'this is an atom')
        ), $a);
    }
}
