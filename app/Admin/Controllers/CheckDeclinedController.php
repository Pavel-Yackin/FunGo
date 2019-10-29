<?php


namespace App\Admin\Controllers;


use App\Check;

class CheckDeclinedController extends CheckBaseController
{
    protected $status = Check::STATUS_DECLINED;
}
