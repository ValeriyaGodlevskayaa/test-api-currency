<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Services\API\ApiCurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $apiCurrencyService;

    public function __construct(ApiCurrencyService $apiCurrencyService)
    {
        $this->middleware('auth');

        $this->apiCurrencyService = $apiCurrencyService;

    }
    public function index()
    {
        return view('currency.index', ['currencies' => $this->listCurrency()]);
    }

    public function convertCurrency(CurrencyRequest $request)
    {
        $params = $request->all();
        $currencyApi = (array)$this->apiCurrencyService->getCurrentCurrency($params['currency']);
        return view('currency.index', [
            'currentCurrency' => array_key_first($currencyApi),
            'currentRate' => array_shift($currencyApi),
            'currencies' => $this->listCurrency()]);
    }

    public function listCurrency()
    {
        return (array)$this->apiCurrencyService->listCurency();
    }

    public function currencyHistory(Request $request)
    {
        $currentCurrency = $request->input('currentCurrency');
        $historyRates = $this->apiCurrencyService->getHistoryCurrency($request->input('period'), $currentCurrency);
        if (isset($historyRates)){
            ksort($historyRates);
        }
        return view('currency.index', [
            'historyRates' => $historyRates,
            'currencies' => $this->listCurrency(),
            'currentCurrency' => $currentCurrency
        ]);
    }


}
