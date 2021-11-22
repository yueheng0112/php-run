<?php

namespace app;

use core\Controller;
use core\Request;

/**
 * Class Index
 * @package app\index
 * @example
 * php run index/index/good a=3 b=2
 */
class Index extends Controller
{
    public function good(Request $request)
    {
        dump($request,'index-app');
    }
}