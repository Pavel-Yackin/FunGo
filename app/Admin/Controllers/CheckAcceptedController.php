<?php


namespace App\Admin\Controllers;


use App\Check;

class CheckAcceptedController extends CheckBaseController
{
    protected $status = Check::STATUS_ACCEPTED;
}
