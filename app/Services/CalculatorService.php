<?php

namespace App\Services;

use App\Interfaces\ICalculatorService;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class CalculatorService implements ICalculatorService
{
    public function sumar(string $summationString) : int {
        if (Str::startsWith($summationString, '//')) {
            $data = preg_split('/[\s]+/', $summationString);
            $separators = str_replace('//', '', $data[0]);
            // dd($separators);
            // $numbers = preg_split('/['.$separators.']+/', $data[1]);
            $rawData = $data[1];
        }else {
            // $numbers = preg_split('/[\s,]+/', $summationString);
            $separators = ';';
            $rawData = $summationString;
        }
        $numbers = preg_split('/['.$separators.']+/', $rawData);
        $result = array_sum($numbers);
        $this->writeJson($summationString, $result);
        return $result;
    }

    private function writeJson(string $argument, int $result) {
        if(!Storage::disk('local')->exists('calculator-log.json')) {
            Storage::disk('local')->put('calculator-log.json', '');
        } else {
            $data = $this->getResultsHistory();
        }
        $data[] = [
            'uuid' => Str::uuid(),
            'date' => Carbon::now()->format('d-m-Y H:i'),
            'argument' => $argument,
            'result' => $result
        ];
        // Write File
        $newJsonString = json_encode($data);
        Storage::disk('local')->put('calculator-log.json', $newJsonString);
    }

    public function getResultsHistory() : mixed {
        // Read File
        $jsonString = Storage::disk('local')->get('calculator-log.json');
        $data = json_decode($jsonString);
        return $data;
    }
}
