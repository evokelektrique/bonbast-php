<?php
namespace Bonbast;

final class Bonbast {

    /**
     * Global user agent
     *
     * @var string
     */
    private $user_agent;

    /**
     * List of prices`
     *
     * @var array
     */
    public $prices;

    /**
     * Setup global user agent & prices
     */
    public function __construct() {
        $this->user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36';
        $this->prices = $this->get_prices();
    }

    /**
     * Retrieve a pair list of sell and buy prices of given price
     *
     * @param string $price
     * @return array
     */
    public function get_formatted_price(string $price): array {
        $sell = $this->prices[$price . "1"];
        $buy = $this->prices[$price . "2"];

        return [
            "sell" => $sell,
            "buy" => $buy
        ];
    }

    private function get_prices(): array {
        // Fetch bonbast home page
        $bonbast_response = $this->get_main_page();

        // Extract the data token
        $token = $this->get_data($bonbast_response);

        // Get the result using cURL with given data
        $currencies = $this->get_currencies($token);

        return json_decode($currencies, true);
    }

    /**
     * Extract the token from the main page
     *
     * @param string $input
     * @return string
     */
    function get_data(string $input): string {
        // Evaluate regex on the input
        $regex = '#data:"[^"]*"#';
        preg_match($regex, $input, $match);

        // Sanitize the data
        $match = end($match);
        $match = explode(":", $match);
        $match = str_replace('"', "", $match[1]);

        return $match;
    }

    /**
     * Retrieve a list of prices in raw JSON format
     *
     * @param string $token
     * @return string
     */
    private function get_currencies(string $token): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://bonbast.com/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$token&webdriver=false");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: bonbast.com';
        $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
        $headers[] = 'Cookie: st_bb=0';
        $headers[] = 'Origin: https://bonbast.com';
        $headers[] = 'Referer: https://bonbast.com/';
        $headers[] = 'Sec-Ch-Ua: \"Not;A=Brand\";v=\"99\", \"Chromium\";v=\"106\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'User-Agent: ' . $this->user_agent;
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Fetch the main page HTML
     *
     * @return string
     */
    private function get_main_page() : string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://bonbast.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: bonbast.com';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Cookie: st_bb=0';
        $headers[] = 'Referer: https://bonbast.com/';
        $headers[] = 'Sec-Ch-Ua: \"Not;A=Brand\";v=\"99\", \"Chromium\";v=\"106\"';
        $headers[] = 'Sec-Ch-Ua-Arch: \"x86\"';
        $headers[] = 'Sec-Ch-Ua-Bitness: \"64\"';
        $headers[] = 'Sec-Ch-Ua-Full-Version: \"106.0.5249.119\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Model: \"\"';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Fetch-User: ?1';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: ' . $this->user_agent;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return "";
        }
        curl_close($ch);

        return $result;
    }
}
