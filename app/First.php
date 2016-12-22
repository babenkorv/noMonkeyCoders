<?php

namespace app\folder;

use app\Interf\ExamplInterface;

class First implements ExamplInterface
{
    public function firstMethod()
    {
        return __CLASS__;
    }

    public function interfaceMethod()
    {
        // TODO: Implement interfaceMethod() method.
    }
}