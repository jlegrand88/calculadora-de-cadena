<?php

namespace App\Services;

use App\Interfaces\ICalculatorService;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class CalculatorService implements ICalculatorService
{
    public function sumar(?string $summationString) : int {
        $numbers = $this->filterPositiveNumbers($summationString);
        $result = array_sum($numbers);
        $this->writeJson($summationString, $result);
        return $result;
    }

    private function writeJson(?string $argument, int $result) {
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

    public function findNegativeNumbers(string $summationString = '') {
        $numbers = $this->filterNumbers($summationString);
        $negativeNumbers = Arr::where($numbers, function ($value, $key) {
            if($value < 0) {
                return $value;
            }
        });
        if($negativeNumbers){
            return collect($negativeNumbers)->implode(', ');
        }
        return false;
    }
    private function filterPositiveNumbers(?string $summationString) {
        $numbers = $this->filterNumbers($summationString);
        $positiveNumbers = Arr::where($numbers, function ($value, $key) {
            if($value > 0 && $value < 1000) {
                return $value;
            }
        });
        return $positiveNumbers;
    }

    private function filterNumbers(?string $summationString) {
        if (Str::startsWith($summationString, '//')) {
            $data = preg_split('/[\s]+/', $summationString);
            $rawSeparators = str_replace('//', '', $data[0]);
            $rawData = $data[1];
            //verify multiple separators
            $multiSeparators = preg_match_all('/\[(.*?)\]/', $rawSeparators, $output);
            if(count($output[1])) {
                $escapedSeparators = array_map(function($value) {
                    if($value){
                        return preg_quote($value);
                    } else {
                        return preg_quote('\s|,');
                    }
                }, $output[1]);
                $separators = collect($escapedSeparators)->implode('|');
            } else {
                if($rawSeparators){
                    $separators = preg_quote($rawSeparators[0]);
                } else {
                    $separators = ';';
                }
            }
        }else {
            $rawData = $summationString;
            $separators = false;
        }

        if($separators){
            $numbers = preg_split('/('.$separators.')|,|\s+/', $rawData);
        } else {
            $numbers = preg_split('/[\s,]+/', $rawData);
        }
        return $numbers;
    }
}
