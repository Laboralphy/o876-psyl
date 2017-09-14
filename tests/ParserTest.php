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
        $this->assertEquals('test', $a[0]);
        $this->assertEquals(1, $a[0]->index());

        $this->assertEquals('1', $a[1]);
        $this->assertEquals(6, $a[1]->index());

        $this->assertEquals('3', $a[2]);
        $this->assertEquals(8, $a[2]->index());

        $this->assertEquals('5', $a[3]);
        $this->assertEquals(10, $a[3]->index());

        $this->assertEquals('abc', $a[4][0]);
        $this->assertEquals('x', $a[4][1]);
        $this->assertEquals('y', $a[4][2]);
        $this->assertEquals('z', $a[4][3]);
        $this->assertEquals('this is an atom', $a[4][4]);
    }
}
