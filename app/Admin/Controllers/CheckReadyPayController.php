<?php


namespace App\Admin\Controllers;


use App\Check;

class CheckReadyPayController extends CheckBaseController
{
    protected $status = Check::STATUS_READY_TO_PAY;
}
