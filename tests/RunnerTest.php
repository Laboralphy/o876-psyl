<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 17:51
 */

use O876\Psyl\Runner;

class RunnerTest extends PHPUnit_Framework_TestCase {
    public function testRun() {
        $r = new Runner();
        $r->exec(<<<EOT

(add 1 2)



EOT
);
    }
}
