<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Services\API\ApiCurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @var ApiCurrencyService
     */
    protected ApiCurrencyService $apiCurrencyService;

    /**
     * CurrencyController constructor.
     * @param ApiCurrencyService $apiCurrencyService
     */
    public function __construct(ApiCurrencyService $apiCurrencyService)
    {
        $this->middleware('auth');

        $this->apiCurrencyService = $apiCurrencyService;

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('currency.index', ['currencies' => $this->listCurrency()]);
    }

    /**
     * @param CurrencyRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function convertCurrency(CurrencyRequest $request)
    {
        try {
            $param = $request->input('currency');
            $this->apiCurrencyService->getRateCurrency($param);
            $currencyApi = $this->apiCurrencyService->getRateCurrency($param);
            return view('currency.index', [
                'currentCurrency' => array_key_first($currencyApi),
                'currentRate' => array_shift($currencyApi),
                'currencies' => $this->listCurrency()]);

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }

    }

    /**
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function listCurrency()
    {
        try {
            return $this->apiCurrencyService->getCurrencies();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function currencyHistory(Request $request)
    {
        try {
            $currentCurrency = $request->input('currentCurrency');
            $historyRates = $this->apiCurrencyService->getHistoryCurrency($request->input('period'), $currentCurrency);
            if (isset($historyRates)) {
                ksort($historyRates);
            }
            return view('currency.index', [
                'historyRates' => $historyRates,
                'currencies' => $this->listCurrency(),
                'currentCurrency' => $currentCurrency
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }

    }

}
