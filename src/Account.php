<?php
/**
 * @link https://github.com/2amigos/yii2-nexmo-library
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\nexmo;

use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

/**
 * Class Account handles interaction with your Nexmo account.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class Account extends Client
{
    /**
     *
     * @var array $api_commands the available api calls for accounts
     */
    private $_commands = [
        'get_balance' => '/account/get-balance',
        'get_pricing' => '/account/get-pricing/outbound',
        'update_settings' => '/account/settings',
        'top_up' => '/account/top-up',
        'get_numbers' => '/account/numbers',
        'search_numbers' => '/number/search',
        'buy_number' => '/number/buy',
        'cancel_number' => '/number/cancel',
        'update_number' => '/number/update',
        'search_message' => 'search/message',
        'search_messages' => '/search/messages',
        'search_rejections' => '/search/rejections'
    ];
    /**
     * @var array keeps cached responses with non-variant results
     */
    private $_cache = [];

    /**
     * Retrieve your current account balance.
     * @return mixed|null
     * @see https://docs.nexmo.com/index.php/developer-api/account-get-balance
     */
    public function getBalance()
    {
        return $this->request($this->getUrl('get_balance'), [], $this->getAcceptHeaderOption());
    }

    /**
     * Retrieve our outbound pricing for a given country.
     * @param string $country A 2 letter country code. Ex: CA
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/account-pricing
     */
    public function getPricing($country)
    {
        $code = strtoupper($country);

        if (!isset($this->_cache['pricing'][$code])) {
            $this->_cache['pricing'][$code] = $this->request(
                $this->getUrl('get_pricing'),
                ['country' => $country],
                $this->getAcceptHeaderOption()
            );
        }
        return $this->_cache['pricing'][$code];
    }

    /**
     * Get all inbound numbers associated with your Nexmo account.
     * @param int $index Page index (>0, default 1). Ex: 2
     * @param int $size Page size (max 100, default 10). Ex: 25
     * @param string $pattern Page size (max 100, default 10). Ex: 25
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/account-numbers
     */
    public function getNumbers($index = null, $size = null, $pattern = null)
    {
        if ($size > 100) {
            $size = 100;
        }
        $params = array_filter(['index' => $index, 'size' => $size, 'pattern' => $pattern]);
        $crc = sha1(serialize($params));
        if (!isset($this->_cache['numbers'][$crc])) {
            $this->_cache['numbers'][$crc] = $this->request(
                $this->getUrl('get_numbers'),
                $params,
                $this->getAcceptHeaderOption()
            );
        }

        return $this->_cache['numbers'][$crc];
    }

    /**
     * Get available inbound numbers for a given country.
     * @param string $country Country code. Ex: CA
     * @param string $pattern A matching pattern. Ex: 888
     * @param string $features Available features are SMS and VOICE, use a comma-separated values. Ex: SMS,VOICE
     * @param int $index Page index (>0, default 1). Ex: 2
     * @param int $size Page size (max 100, default 10). Ex: 25
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/number-search
     */
    public function searchNumbers($country, $pattern = null, $features = null, $index = null, $size = null)
    {
        if ($size > 100) {
            $size = 100;
        }

        $params = array_filter(
            [
                'country' => strtoupper($country),
                'pattern' => $pattern,
                'features' => $features,
                'index' => $index,
                'size' => $size
            ]
        );
        $crc = sha1(serialize($params));
        if (!isset($this->_cache['search_numbers'][$crc])) {
            $this->_cache['search_numbers'][$crc] = $this->request(
                $this->getUrl('search_numbers'),
                $params,
                $this->getAcceptHeaderOption()
            );
        }

        return $this->_cache['search_numbers'][$crc];
    }

    /**
     * Cancel a given inbound number subscription.
     * @param string $country Country code. Ex: CA
     * @param string $msisdn One of your inbound numbers Ex: 34911067000
     * @return string the status code. Possible values:
     * - 200 if successful cancellation
     * - 401 if wrong credentials
     * - 420 if wrong parameters
     * @see https://docs.nexmo.com/index.php/developer-api/number-cancel
     */
    public function cancelNumber($country, $msisdn)
    {
        $options = ArrayHelper::merge(
            [
                'query' => [
                    'api_key' => $this->key,
                    'api_secret' => $this->secret,
                    'country' => strtoupper($country),
                    'msisdn' => $msisdn,
                ]
            ],
            $this->getAcceptHeaderOption()
        );
        $url = $this->getUrl('cancel_number');
        $response = $this->getGuzzleClient()->post($url, $options);
        return $response->getStatusCode();
    }

    /**
     * Purchase a given inbound number.
     * @param string $country Country code. Ex: ES
     * @param string $msisdn An available inbound number Ex: 34911067000
     * @return string the status code. Possible values:
     * - 200 if successful purchase
     * - 401 if wrong credentials
     * - 420 if wrong parameters
     * @see https://docs.nexmo.com/index.php/developer-api/number-buy
     */
    public function buyNumber($country, $msisdn)
    {
        $options = ArrayHelper::merge(
            [
                'query' => [
                    'api_key' => $this->key,
                    'api_secret' => $this->secret,
                    'country' => strtoupper($country),
                    'msisdn' => $msisdn,
                ]
            ],
            $this->getAcceptHeaderOption()
        );
        $url = $this->getUrl('buy_number');
        $response = $this->getGuzzleClient()->post($url, $options);
        return $response->getStatusCode();
    }

    /**
     * Update your number callback.
     * @param string $country Country code. Ex: ES
     * @param string $msisdn One of your inbound numbers Ex: 34911067000
     * @param string $mHttpUrl The URL should be active to be taken into account Ex: http://mycallback.servername
     * @param string $moSmppSysType The associated system type for SMPP client only Ex: inbound
     * @param string $voiceCallbackType The voice callback type for SIP end point (sip), for a telephone number (tel),
     * for VoiceXML end point (vxml)
     * @param string $voiceCallbackValue The voice callback value based on the voiceCallbackType
     * @return string
     * @see https://docs.nexmo.com/index.php/developer-api/number-update
     */
    public function updateNumber(
        $country,
        $msisdn,
        $mHttpUrl = null,
        $moSmppSysType = null,
        $voiceCallbackType = null,
        $voiceCallbackValue = null
    ) {
        $options = ArrayHelper::merge(
            [
                'query' => array_filter(
                    [
                        'country' => $country,
                        'msisdn' => $msisdn,
                        'mHttpUrl' => $mHttpUrl,
                        'moSmppSysType' => $moSmppSysType,
                        'voiceCallbackType' => $voiceCallbackType,
                        'voiceCallbackValue' => $voiceCallbackValue
                    ]
                )
            ],
            $this->getAcceptHeaderOption()
        );
        $url = $this->getUrl('update_number');
        $response = $this->getGuzzleClient()->post($url, $options);

        return $response->getStatusCode();

    }

    /**
     * Update your account settings.
     * @param string $newSecret Your new API secret (8 characters max)
     * @param string $moCallBackUrl Inbound call back URL. The URL should be active to be taken into account
     * Ex: http://mycallback.servername
     * @param string $drCallBackUrl DLR call back URL. The URL should be active to be taken into account and
     * Ex: http://mycallback.servername
     * @return mixed|null
     * @throws \yii\base\InvalidParamException
     * @see https://docs.nexmo.com/index.php/developer-api/account-settings
     */
    public function updateSettings($newSecret = null, $moCallBackUrl = null, $drCallBackUrl = null)
    {
        if ($newSecret == null && $moCallBackUrl == null && $drCallBackUrl == null) {
            return null;
        }
        if (strlen($newSecret) > 8) {
            throw new InvalidParamException('"$newSecret" cannot be larger than 8 chars.');
        }
        $options = ArrayHelper::merge(
            [
                'query' => array_filter(
                    [
                        'api_key' => $this->key,
                        'api_secret' => $this->secret,
                        'newSecret' => $newSecret,
                        'moCallBackUrl' => $moCallBackUrl,
                        'drCallBackUrl' => $drCallBackUrl
                    ]
                )
            ],
            $this->getAcceptHeaderOption()
        );

        $url = $this->getUrl('update_settings');
        $response = $this->getGuzzleClient()->post($url, $options);

        return preg_match('/xml/i', $this->format) ? $response->xml() : $response->json();
    }

    /**
     * Top-up your account, only if you have turn-on the 'auto-reload' feature. The top-up amount is the one associated
     * with your 'auto-reload' transaction.
     * @param string $transactionId The transaction id associated with your **first** 'auto reload' top-up.
     * Ex: 00X123456Y7890123Z
     * @return string the status code. Possible responses:
     * - 200 if successful top-up
     * - 401 if not authorized to perform the request
     * - 420 if top-up failed
     * @see https://docs.nexmo.com/index.php/developer-api/account-top-up
     */
    public function topUp($transactionId)
    {
        $options = ArrayHelper::merge(
            [
                'query' => [
                    'api_key' => $this->key,
                    'api_secret' => $this->secret,
                    'trx' => $transactionId
                ]
            ],
            $this->getAcceptHeaderOption()
        );
        $response = $this->getGuzzleClient()->get($this->getUrl('top_up'), $options);

        return $response->getStatusCode();
    }

    /**
     * Search a previously sent message for a given message id. Please note a message become searchable a few minutes
     * after submission for real-time delivery notification implement our DLR call back.
     * @param string $id Your message id received at submission time Ex: 00A0B0C0
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/search-message
     */
    public function searchMessage($id)
    {
        if (!isset($this->_cache['message'][$id])) {
            $this->_cache['message'][$id] = $this->request(
                $this->getUrl('search_message'),
                ['id' => $id],
                $this->getAcceptHeaderOption()
            );
        }

        return $this->_cache['message'][$id];
    }

    /**
     * Search sent messages. Please note a message become searchable a few minutes after submission for real-time
     * delivery notification implement the DLR call back.
     * @param array $ids A list of message ids, up to 10 Ex: ['00A0B0C0','00A0B0C1','00A0B0C2']
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/search-messages
     */
    public function searchMessagesByIds($ids)
    {
        $ids = (array)$ids;
        $query = [
            "api_key=$this->key",
            "api_secret=$this->secret"
        ];

        foreach ($ids as $id) {
            $query[] = "ids=$id";
        }

        $crc = sha1(serialize($query));
        if (!isset($this->_cache['search_messages'][$crc])) {
            $url = $this->getUrl('search_messages') . '?' . implode('&', $query);
            $this->_cache['search_messages'][$crc] = $this->getGuzzleClient()->get(
                $url,
                $this->getAcceptHeaderOption()
            );
        }
        return $this->_cache['search_messages'][$crc];
    }

    /**
     * Search sent messages. Please note a message become searchable a few minutes after submission for real-time
     * delivery notification implement the DLR call back.
     * @param string $date Message date submission YYYY-MM-DD Ex: 2011-11-15
     * @param string $to A recipient number Ex: 1234567890
     * @return mixed
     * @see https://docs.nexmo.com/index.php/developer-api/search-messages
     */
    public function searchMessagesByDate($date, $to)
    {
        $params = [
            'date' => $date,
            'to' => $to
        ];

        $crc = sha1(serialize($params));
        if (!$this->_cache['search_messages'][$crc]) {
            $this->_cache['search_messages'][$crc] = $this->request(
                $this->getUrl('search_messages'),
                $params,
                $this->getAcceptHeaderOption()
            );
        }

        return $this->_cache['search_messages'][$crc];
    }

    /**
     * Search rejected messages. Please note a message become searchable a few minutes after submission.
     * @param string $date Message date submission YYYY-MM-DD Ex: 2011-11-15
     * @param string $to A recipient number Ex: 1234567890
     * @return mixed
     */
    public function searchRejections($date, $to = null)
    {
        $params = array_filter(
            [
                'date' => $date,
                'to' => $to
            ]
        );

        $crc = sha1(serialize($params));
        if (!$this->_cache['search_rejections'][$crc]) {
            $this->_cache['search_rejections'][$crc] = $this->request(
                $this->getUrl('search_rejections'),
                $params,
                $this->getAcceptHeaderOption()
            );
        }

        return $this->_cache['search_rejections'][$crc];
    }

    /**
     * Returns the formed api call
     * @param string $command the command key
     * @return string the api url call
     */
    protected function getUrl($command)
    {
        return $this->api . $this->_commands[$command];
    }

    /**
     * Returns the Accept header according to the format selected
     * @return array
     */
    protected function getAcceptHeaderOption()
    {
        return [
            'headers' => [
                'Accept' => (preg_match('/xml/i', $this->format) ? 'application/xml' : 'application/json')
            ]
        ];
    }
}
