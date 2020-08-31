<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Services\API\ApiCurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $listCurrency = $this->apiCurrencyService->listCurency();
        return view('currency.index', ['currencies' => (array)$listCurrency]);
    }

    public function convertCurrency(CurrencyRequest $request)
    {
        $params = $request->all();
        $currencyApi = $this->apiCurrencyService->getCurrentCurrency($params['fromCurrency'], $params['toCurrency']);
        var_dump($currencyApi);die();
    }




}
