<?php


namespace App\Services\API;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApiCurrencyService
{
    const URL_API_CURRENCIES = 'http://data.fixer.io/api/';
    const URL_API_HISTORY = 'https://api.exchangeratesapi.io/history';
    const KEY = '9e3f61b200994b0eae1926b978a314bc';

    private static function connect(string $action, string $symbols=''): object
    {
        return Http::get(self::URL_API_CURRENCIES.$action.'?access_key='.self::KEY.'&symbols='.$symbols);
    }

    private static function connectApiHistory(string $symbol, string $startDate, string $endDate): object
    {
        return Http::get(self::URL_API_HISTORY,['start_at' => $startDate,'end_at' => $endDate,'symbols' => $symbol]);
    }

    public function listCurency(): object
    {
        return json_decode(self::connect('symbols')->body())->symbols;
    }

    public function getCurrentCurrency(string $currency='USD'): object
    {
        $request = self::connect('latest', $currency)->body();
        return json_decode($request)->rates;
    }

    public function getHistoryCurrency(string $period, string $symbol): ?array
    {
       $date = $this->getDateForCurrency($period);
       $request = self::connectApiHistory($symbol, $date, Carbon::now()->toDateString());
        $result = null;
       if ($request->successful()){
           $result = json_decode($request->body(), true)['rates'];
       }
       return $result;
    }

    private function getDateForCurrency(string $period): string
    {
        $todayDate = Carbon::now();
        switch ($period){
            case 'day':
                return $todayDate->subDay()->toDateString();
            case 'week':
                return Carbon::now()->subDays($todayDate->dayOfWeek + 1)->subWeek()->toDateString();
        }
    }


}
