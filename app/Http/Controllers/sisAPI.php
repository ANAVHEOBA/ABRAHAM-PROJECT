<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sisAPI extends Controller
{
    function getData()
    {
        return ["name" => "Abraham", "email" => "wisdomvolt@gmail.com"];
    }
}
