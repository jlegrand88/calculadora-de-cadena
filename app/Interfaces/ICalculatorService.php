<?php

namespace App\Interfaces;

interface ICalculatorService {

    public function sumar(string $string) : int;
    public function getResultsHistory() : mixed;
}
