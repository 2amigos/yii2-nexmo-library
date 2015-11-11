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
    * @var string - name of the company or App you are using Verify for
    * @see https://docs.nexmo.com/api-ref/verify/verify/request
    */
    public $brand = 'NexmoVerifyTest';

    /**
    * @var mixed - returned Reponse (json)
    * @see https://docs.nexmo.com/api-ref/verify/verify/response
    */
    public $response;
    
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
        $this->uri = '/verify/check/';
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
        $this->response = $this->request($this->getUrlVerify(), $this->getEncodedParams($params));
        return $this->response;
    }

    /**
     * Sends a Verify Check Request
     * @param string $request_id The identifier of the Verify Request to check
     * @param string $code The PIN code given by your user
     * @param array $params optional parameters to be attached to the call.
     * @return mixed|null the request response
     * @see https://docs.nexmo.com/api-ref/verify/check/request
     */
    public function sendVerifyCheck($request_id, $code, $params = [])
    {
        $params = ArrayHelper::merge(
            ['request_id' => $request_id, 'code' => $code], //required parameters
            $params  //optional parameters
        );
        $this->response = $this->request($this->getUrlCheck(), $this->getEncodedParams($params));
        return $this->response;
    }
    
} 