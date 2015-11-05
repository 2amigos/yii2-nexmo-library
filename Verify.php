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
 * Class Verify handles the methods and properties of sending a Verify message through your Nexmo account.
 *
 * @see https://docs.nexmo.com/api-ref/verify
 * @author Yurii Hetmanskii <jurets75@gmail.com>
 * @package dosamigos\nexmo
 */
class Verify extends Client
{
    public $api = 'https://api.nexmo.com';
    
    /**
    * @var string
    * @see https://docs.nexmo.com/api-ref/verify/verify/request
    */
    public $brand = 'NexmoVerifyTest';

    /**
     * @return string the api url call for Verify Request
     * @see https://docs.nexmo.com/api-ref/verify/verify/request
     */
    public function getUrlVerify()
    {
        $this->uri = '/verify/';
        return $this->api . $this->uri . $this->format;
    }

    /**
     * @return string the api url call for Verify Check Request
     * @see https://docs.nexmo.com/api-ref/verify/check/request
     */
    public function getUrlCheck()
    {
        $this->uri = '/verify/check';
        return $this->api . $this->uri . $this->format;
    }

    /**
     * Sends a Verify Request
     * @param string $number the mobile number in international format. Ex: 447525856424 or 00447525856424 to UK.
     * @param array $params optional parameters to be attached to the call.
     * @return mixed|null the request response
     * @see https://docs.nexmo.com/api-ref/verify/verify/request
     */
    public function sendVerify($number, $params = [])
    {
        $params = ArrayHelper::merge(
            ['number' => $number, 'brand' => $this->brand], //required parameters
            $params  //optional parameters
        );
        return $this->request($this->getUrlVerify(), $this->getEncodedParams($params));
    }

} 