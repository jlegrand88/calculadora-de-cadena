<?php

namespace App\Http\Controllers;

use App\Interfaces\ICalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CalculatorController extends Controller
{
    public function sumar(Request $request, ICalculatorService $service)
    {
        $summationString =  $request->input('summationString');
        if($summationString === null) {
            return response()->json([
                'success' => true,
                'result' => 0,
            ]);
        }
        if($service->findNegativeNumbers($summationString)) {
            return response()->json([
                'success' => false,
                'result' => $service->findNegativeNumbers($summationString),
            ]);
        }
        return response()->json([
            'success' => true,
            'result' => $service->sumar($summationString),
        ]);
    }

    public function getResultsHistory(Request $request, ICalculatorService $service) : mixed {
        return $service->getResultsHistory();
    }
}
