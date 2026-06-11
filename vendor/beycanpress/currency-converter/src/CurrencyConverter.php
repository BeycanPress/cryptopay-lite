<?php

namespace BeycanPress;

final class CurrencyConverter
{
    /**
     * @var string
     */
    private $api;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string|null
     */
    private $apiKey = null;

    /**
     * @var array|null
     */
    private $coinbaseRates = null;

    /**
     * @var array
     */
    private $needsApiKey = [
        'CoinMarketCap'
    ];

    /**
     * @var array
     */
    private $apis = [
        'CryptoCompare' => 'https://api.coinbase.com/v2/exchange-rates',
        'CoinMarketCap' => 'https://pro-api.coinmarketcap.com/v1/tools/price-conversion',
        'CoinGecko' => 'https://api.coingecko.com/api/v3/simple/price'
    ];

    /**
     * @var array
     */
    private $stableCoins = [
        'USDT',
        'USDC',
        'DAI',
        'BUSD',
        'UST',
        'TUSD'
    ];

    /**
     * @param string $api
     * @param string|null $apiKey
     * @throws Exception
     */
    public function __construct(string $api, ?string $apiKey = null)
    {
        if (!isset($this->apis[$api])) {
            throw new \Exception('Unsupported api!');
        }

        if (!extension_loaded('curl')) {
            throw new \Exception('Curl extension is not loaded!');
        }

        if (!extension_loaded('bcmath')) {
            throw new \Exception('Bcmath extension is not loaded!');
        }

        if (!function_exists('file_get_contents')) {
            throw new \Exception('file_get_contents function is not available!');
        }

        $this->client = new Client();

        $this->api = $api;
        $this->apiKey = $apiKey;
        $this->apiUrl = $this->apis[$api];
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float|null
     * @throws Exception
     */
    public function convert(string $from, string $to, float $amount) : ?float
    {
        if (in_array($this->api, $this->needsApiKey) && $this->apiKey == null) {
            throw new \Exception("The key of the api selected to be used has not been entered.");
        }

        if ($this->isStableCoin($from, $to)) {
            return floatval($amount);
        }

        if ($this->isSameCurrency($from, $to)) {
            return floatval($amount);
        }

        return call_user_func_array([$this, 'convertWith' . $this->api], [$from, $to, $amount]);
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float|null
     * @throws Exception
     */
    private function convertWithCoinGecko(string $from, string $to, float $amount) : ?float
    {   
        try {
            $tokenList = json_decode($this->cache(function() {
                $tokenList = $this->client->get('https://api.coingecko.com/api/v3/coins/list');

                // CoinGecko can return an error object (e.g. when rate-limited);
                // bail out so a bad response is never cached for 24h.
                if (!is_array($tokenList)) {
                    throw new \Exception('Unexpected CoinGecko coins/list response');
                }

                $usdId = array_search('usd', array_column($tokenList, 'symbol'));
                $usdId2 = array_search('usd+', array_column($tokenList, 'symbol'));
                if (isset($tokenList[$usdId])) unset($tokenList[$usdId]);
                if (isset($tokenList[$usdId2])) unset($tokenList[$usdId2]);

                $tokenList[] = (object) [
                    'id' => 'usd',
                    'symbol' => 'usd',
                    'name' => 'USD'
                ];

                return json_encode(array_values($tokenList));
            }, 'token-list', (3600*24))->content);
        } catch (\Throwable $e) {
            // Transient upstream failure (rate limit, network) -> no conversion.
            return null;
        }

        if (!is_array($tokenList)) {
            return null;
        }

        $fromId = array_search(strtolower($from), array_column($tokenList, 'symbol'));
        $toId = array_search(strtolower($to), array_column($tokenList, 'symbol'));
        
        // if token not found
        if (!$toId) {
            return null;
        }

        if (!$fromId) {
            $cgFrom = strtolower($from);
        } else {
            $cgFrom = $tokenList[$fromId]->id;
        }

        $cgTo = $tokenList[$toId]->id;
        $key = $cgFrom.$cgTo;

        $cgPriceFile = dirname(__DIR__) . '/cache/cg-price.json';
        if (file_exists($cgPriceFile) && time() - 30 < filemtime($cgPriceFile)) {
            $cgPrice = json_decode(file_get_contents(dirname(__DIR__) . '/cache/cg-price.json'));
        } else {
            $cgPrice = (object) [];
        }

        if (!isset($cgPrice->$key)) {
            $parameters = [
                'ids' => urlencode(implode(',', [$cgTo])),
                'vs_currencies' => urlencode(implode(',', [$cgFrom]))
            ];

            $qs = http_build_query($parameters);
            $request = "{$this->apiUrl}?{$qs}";

            $response = $this->client
                ->addHeader('Content-Type', 'application/json')
                ->get($request);

            if (isset($response->{$cgTo}->{$cgFrom})) {
                $result = $response->{$cgTo}->{$cgFrom};
            } else {
                $result = null;
            }

            $cgPrice->$key = $result;

            file_put_contents($cgPriceFile, json_encode($cgPrice));
        }

        if (is_null($cgPrice->$key)) {
            return null;
        }

        return ($amount / $cgPrice->$key);
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float|null
     * @throws Exception
     */
    private function convertWithCoinMarketCap(string $from, string $to, float $amount) : ?float
    {
        $parameters = [
            'amount' => $amount,
            'symbol' => $from,
            'convert' => $to
        ];

        $qs = http_build_query($parameters);
        $request = "{$this->apiUrl}?{$qs}";

        $response = $this->client
            ->addHeader('Accepts', 'application/json')
            ->addHeader('X-CMC_PRO_API_KEY', (string) $this->apiKey)
            ->get($request);

        if (isset($response->data)) {
            return $response->data->quote->{strtoupper($to)}->price;
        }

        return null;
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float|null
     * @throws Exception
     */
    private function convertWithCryptoCompare(string $from, string $to, float $amount) : ?float
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        if ($from === $to) {
            return floatval($amount);
        }

        $rates = $this->getCoinbaseRates();

        // rates[X] = amount of X per 1 USD. USD is the base, so its rate is 1.
        $fromRate = ($from === 'USD') ? 1.0 : (isset($rates[$from]) ? (float) $rates[$from] : null);
        $toRate   = ($to === 'USD')   ? 1.0 : (isset($rates[$to])   ? (float) $rates[$to]   : null);

        if (is_null($fromRate) || is_null($toRate) || $fromRate <= 0) {
            return null;
        }

        // amount(from) -> USD -> to
        return $amount * ($toRate / $fromRate);
    }

    /**
     * @return array
     */
    private function getCoinbaseRates() : array
    {
        if (is_array($this->coinbaseRates)) {
            return $this->coinbaseRates;
        }

        $file = dirname(__DIR__) . '/cache/coinbase-rates.json';

        // Only use the cache when it is fresh and non-empty (don't let an empty fetch poison it)
        if (file_exists($file) && (time() - filemtime($file) < 30)) {
            $cached = json_decode((string) file_get_contents($file), true);
            if (is_array($cached) && !empty($cached)) {
                return $this->coinbaseRates = $cached;
            }
        }

        // Single USD-based call: all fiat + crypto rates
        $response = $this->client->get($this->apiUrl . '?currency=USD');

        $rates = [];
        if (isset($response->data->rates)) {
            $rates = (array) $response->data->rates;
        }

        // Only persist a successful (non-empty) fetch to disk
        if (!empty($rates)) {
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file), 0775, true);
            }
            file_put_contents($file, json_encode($rates));
        }

        return $this->coinbaseRates = $rates;
    }

    /**
     * @param float $number
     * @param int $decimals
     * @return float
     */
    public function toFixed(float $number, int $decimals = 6) : float
    {
        return floatval(number_format($number, $decimals, '.', ""));
    }

    /**
     * @param $exp
     * @return void
     */
    public function expToStr($exp) : string
    {
        $parsedNumber = explode("e", strtolower($exp));

        if (count($parsedNumber) == 1) {
            return $exp;
        }

        list($mantissa, $exponent) = $parsedNumber;
        list($int, $dec) = explode(".", $mantissa);

        bcscale(abs($exponent-strlen($dec)));
        $result = bcmul($mantissa, bcpow("10", $exponent));

        if ($result > 1) {
            $result = rtrim($result, '.0');
        }

        return $result;
    }

    /**
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function isStableCoin(string $from, string $to) : bool
    {
        if (strtoupper($from) == 'USD' || strtoupper($to) == 'USD') {
            if (in_array(strtoupper($from), $this->stableCoins) || in_array(strtoupper($to), $this->stableCoins)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $from
     * @param string $to
     * @return boolean
     */
    public function isSameCurrency(string $from, string $to) : bool
    {
        return strtoupper($from) == strtoupper($to);
    }

    /**
     * @param array $symbols
     * @return void
     */
    public function addStableCoins(array $symbols) : void
    {
        $this->stableCoins = array_merge($this->stableCoins, $symbols);
    }

    /**
     * @param callable $function
     * @param string $name
     * @param int $time seconds
     * @return object
     */
    public function cache(callable $function, string $name, int $time = 600) : object
    {
        $path = dirname(__DIR__) . '/cache/';

        if (!file_exists($path)) {
            mkdir($path);       
        } 

        $file = $path . $name . '.json';

        if (file_exists($file) && time() - $time < filemtime($file)) {
            $content = file_get_contents($file);
        } else {
            if (file_exists($file)) {
                unlink($file);
            }

            $content = $function();

            $fp = fopen($file, 'w+');
            fwrite($fp, $content);
            fclose($fp);
        }

        return (object) compact('file', 'content');
    }

}
