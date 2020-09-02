<?php


namespace App\Services\API;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiCurrencyService
{
    const URL_API = 'https://api.exchangeratesapi.io/';

    public static function connectApi(string $action, string $symbol, string $startDate = '', string $endDate = ''): Response
    {
        return Http::get(self::URL_API . $action, ['start_at' => $startDate, 'end_at' => $endDate, 'symbols' => $symbol]);
    }

    public function getCurrencies(): ?array
    {
        $request = self::connectApi('latest', '');
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get currencies.');
        }
        $response = json_decode($request->body(), true);
        return $response['rates'] ? array_keys($response['rates']) : null;
    }

    public function getRateCurrency(string $currency): ?array
    {
        $request = self::connectApi('latest', $currency);
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get rate for current currency.');
        }
        $response = json_decode($request->body(), true);
        return $response['rates'] ?? null;
    }

    public function getHistoryCurrency(string $period, string $symbol): ?array
    {
        $date = $this->getDateForCurrency($period);
        $request = self::connectApi('history', $symbol, $date, Carbon::now()->toDateString());
        if ($request->failed()){
            throw new \Exception('Problem with currency api. Not get history for currency.');
        }
        $response = json_decode($request->body(), true);
        return $response['rates'] ?? null;
    }

    protected function getDateForCurrency(string $period): string
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
