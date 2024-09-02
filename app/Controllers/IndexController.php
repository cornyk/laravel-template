<?php

namespace App\Controllers;

use App\Utils\RespUtil;

class IndexController extends BaseController
{
    public function index()
    {
        return RespUtil::sucJson();
    }
}
