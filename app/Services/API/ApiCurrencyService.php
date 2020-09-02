<?php


namespace App\Services\API;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApiCurrencyService
{
    const URL_API = 'https://api.exchangeratesapi.io/';

    private static function connectApi(string $action, string $symbol, string $startDate = '', string $endDate = ''): object
    {
        return Http::get(self::URL_API . $action, ['start_at' => $startDate, 'end_at' => $endDate, 'symbols' => $symbol]);
    }

    public function getCurrencies(): array
    {
        $request = self::connectApi('latest', '');
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get currencies.');
        }
        return array_keys(json_decode($request->body(), true)['rates']);
    }

    public function getRateCurrency(string $currency)
    {
        $request = self::connectApi('latest', $currency);
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get rate for current currency.');
        }
        return json_decode($request->body(), true)['rates'];
    }

    public function getHistoryCurrency(string $period, string $symbol): ?array
    {
        $date = $this->getDateForCurrency($period);
        $request = self::connectApi('history', $symbol, $date, Carbon::now()->toDateString());
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get history for currency.');
        }
        return json_decode($request->body(), true)['rates'];
    }

    private function getDateForCurrency(string $period): string
    {
        $todayDate = Carbon::now();
        switch ($period) {
            case 'day':
                return $todayDate->subDay()->toDateString();
            case 'week':
                return Carbon::now()->subDays($todayDate->dayOfWeek + 1)->subWeek()->toDateString();
        }
    }


}
