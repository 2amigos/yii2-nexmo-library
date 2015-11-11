<?php
/**
 * @link https://github.com/2amigos/yii2-nexmo-library
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\nexmo;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Sms handles the methods and properties of sending a SMS message through your Nexmo account.
 *
 * @see https://docs.nexmo.com/index.php/sms-api/send-message
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class Sms extends Client
{
    /**
     * @var string the sender address. It can be alphanumeric. Restrictions may apply, depending on the destination.
     * @see http://help.nexmo.com/categories/7470-faq
     */
    public $from;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->from)) {
            throw new InvalidConfigException('"$from" cannot be empty.');
        }

        $this->uri = '/sms/';
    }

    /**
     * @return string the api url call
     */
    public function getUrl()
    {
        return $this->api . $this->uri . $this->format;
    }

    /**
     * Sends a text message
     * @param string $to the mobile number in international format. Ex: 447525856424 or 00447525856424 to UK.
     * @param string $text the body of the text message (with a maximum length of 3200 chars)
     * @param array $params optional parameters to be attached to the call.
     * @return mixed|null the request response
     * @see https://docs.nexmo.com/index.php/sms-api/send-message
     */
    public function sendText($to, $text, $params = [])
    {
        $type = ArrayHelper::getValue($params, 'type');
        if (strtolower($type) != 'unicode') {
            $type = max(array_map('ord', str_split($text))) > 127
                ? 'unicode'
                : ($type != null ? $type : 'text');
        }

        $params = ArrayHelper::merge(
            $params,
            [
                'from' => $this->getValidatedFrom(),
                'to' => $to,
                'text' => $text,
                'type' => $type
            ]
        );

        return $this->request($this->getUrl(), $this->getEncodedParams($params));
    }

    /**
     * Sends a Push WAP message
     * @param string $to the mobile number in international format. Ex: 447525856424 or 00447525856424 to UK.
     * @param string $url the WAP Push URL. Ex: url=http://www.mysite.com
     * @param string $title the title of WAP Push. Ex: title=MySite
     * @param integer $validity sets how long WAP Push is available in milliseconds.
     * Ex: validity=86400000 Default: 48 hours: 172800000
     * @return mixed|null
     * @see https://docs.nexmo.com/index.php/how-to/send-wap-push-message
     */
    public function sendPushWAP($to, $url, $title, $validity = null)
    {
        $params = [
            'from' => $this->getValidatedFrom(),
            'to' => $to,
            'type' => 'wappush',
            'url' => $url,
            'title' => $title,
            'validity' => $validity ? : 172800000
        ];

        return $this->request($this->getUrl(), $this->getEncodedParams($params));
    }

    /**
     * Sends a binary message
     * @param string $to the mobile number in international format. Ex: 447525856424 or 00447525856424 to UK.
     * @param string $body the content of the message.
     * @param string $udh the user data header. Ex: udh=06050415811581.
     * @return mixed|null
     * @see https://docs.nexmo.com/index.php/how-to/send-binary-message
     */
    public function sendBinary($to, $body, $udh)
    {
        $params = [
            'from' => $this->getValidatedFrom(),
            'to' => $to,
            'type' => 'binary',
            'body' => bin2hex($body),
            'udh' => bin2hex($udh)
        ];

        return $this->request($this->getUrl(), $this->getEncodedParams($params));
    }

    /**
     * If the originator ('from' field) is invalid, some networks may reject the network
     * whilst stinging you with the financial cost! While this cannot correct them, it
     * will try its best to correctly format them.
     * @return string the validated from
     */
    protected function getValidatedFrom()
    {
        $from = preg_replace('/[^a-zA-Z0-9]/', '', (string)$this->from);

        if (preg_match('/[a-zA-Z]/', $this->from))
            // Alphanumeric format so make sure it's < 11 chars
            $from = substr($from, 0, 11);
        else {
            if (substr($from, 0, 2) == '00') {
                $from = substr($from, 2);
                $from = substr($from, 0, 15);
            }
        }
        return (string)$from;
    }

    /**
     * All requests require UTF-8 encoding
     * @param array $params
     * @return array
     */
    protected function getEncodedParams($params)
    {
        $validated = [];
        foreach ((array)$params as $key => $param) {
            $validated[$key] = $this->validateUTF8($param);
        }
        return $validated;
    }

}
