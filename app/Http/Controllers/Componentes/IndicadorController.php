<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndicadorController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
}
