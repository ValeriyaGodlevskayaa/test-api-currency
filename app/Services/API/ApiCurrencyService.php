<?php


namespace App\Services\API;

use Illuminate\Support\Facades\Http;

class ApiCurrencyService
{
    const URL_API_CURRENCY = 'http://data.fixer.io/api/';
    const KEY = '9e3f61b200994b0eae1926b978a314bc';

    private $startDate;
    private $endDate;

    private static function connect($action)
    {
        return Http::get(self::URL_API_CURRENCY.$action.'?access_key='.self::KEY);
    }

    public function listCurency()
    {
        return json_decode(self::connect('symbols')->body())->symbols;
    }

    public function getCurrentCurrency(string $fromCurrency='EUR', string $toCurrency='USD')
    {
        $request = Http::get(self::URL_API_CURRENCY.'latest?access_key='.self::KEY .'&symbols='.$toCurrency);
        var_dump($request->body());die();
        $rates = json_decode($request->body());
        return $rates->$toCurrency;
    }


}
