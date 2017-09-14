<?php
/**
 * Created by PhpStorm.
 * User: ralphy
 * Date: 14/09/17
 * Time: 17:57
 */

namespace O876\Psyl\Modules;

use \O876\Psyl\Evaluator;



interface Module {
    public function init(Evaluator $evaluator);
}