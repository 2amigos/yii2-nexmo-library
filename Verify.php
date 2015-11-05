<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
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
class Verify extends Client
{
    /**
     * @var string the sender address. It can be alphanumeric. Restrictions may apply, depending on the destination.
     * @see http://help.nexmo.com/categories/7470-faq
     */
    //public $from;
    public $api = 'https://api.nexmo.com';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        /*if (empty($this->from)) {
            throw new InvalidConfigException('"$from" cannot be empty.');
        }*/
        
        //$this->uri = '/verify/';
    }

    /**
     * @return string the api url call
     */
    public function getUrlVerify()
    {
        $this->uri = '/verify/';
        return $this->api . $this->uri . $this->format;
    }

    /**
     * @return string the api url call
     */
    public function getUrlCheck()
    {
        $this->uri = '/verify/check';
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
    public function sendVerify($to, /*$text, */$params = [])
    {
        /*$type = ArrayHelper::getValue($params, 'type');
        if (strtolower($type) != 'unicode') {
            $type = max(array_map('ord', str_split($text))) > 127
                ? 'unicode'
                : ($type != null ? $type : 'text');
        }*/

        $params = ArrayHelper::merge(
            $params,
            [
                //'from' => $this->getValidatedFrom(),
                'number' => $to,
                //'text' => $text,
                //'type' => $type
            ]
        );

        return $this->request($this->getUrlVerify(), $this->getEncodedParams($params));
    }



} 