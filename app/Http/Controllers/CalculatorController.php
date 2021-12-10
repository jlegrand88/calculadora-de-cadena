<?php

namespace App\Http\Controllers;

use App\Interfaces\ICalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CalculatorController extends Controller
{
    public function sumar(Request $request, ICalculatorService $service) : int
    {
        $summationString =  $request->input('summationString');
        if($summationString === null) {
            return 0;
        }
        return $service->sumar($summationString);
    }

    public function getResultsHistory(Request $request, ICalculatorService $service) : mixed {
        return $service->getResultsHistory();
    }
}
