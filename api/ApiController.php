<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
class ApiController extends Controller
{
    function setting(){
        return Setting::all();
    }
    function program(){
        return Program::all();
    }
    function program_filter($id){
        return Program::find($id);
    }
}
