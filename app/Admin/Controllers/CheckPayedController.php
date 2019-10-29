<?php


namespace App\Admin\Controllers;


use App\Check;

class CheckPayedController extends CheckBaseController
{
    protected $status = Check::STATUS_PAYED;
}
