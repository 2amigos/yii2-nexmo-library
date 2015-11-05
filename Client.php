<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\nexmo;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Client is the base class of Components that access the API and handles the calls to the API base end point.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class Client extends Component
{
    /**
     * @var string response format. Can be json or xml.
     */
    public $format = 'json';
    /**
     * @var string API endpoint
     */
    public $api = 'https://rest.nexmo.com';
    /**
     * @var string your API key
     */
    public $key;
    /**
     * @var string your API secret
     */
    public $secret;
    /**
     * @var string the formed uri by child classes
     */
    protected $uri;
    /**
     * @var \Guzzle\Http\Client a client to make requests to the API
     */
    private $_guzzle;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (empty($this->key) || empty($this->secret)) {
            throw new InvalidConfigException('"key" and "secret" cannot be empty.');
        }
    }

    /**
     * Makes a Url request and returns its response
     * @param string $url the uri to call
     * @param array $params the parameters to be bound to the call
     * @param array $options the options to be attached to the client
     * @return mixed|null
     */
    protected function request($url, $params = [], $options = [])
    {
        try {

            $params = ArrayHelper::merge($params, ['api_key' => $this->key, 'api_secret' => $this->secret]);

            $response = $this->getGuzzleClient()->get($url, ArrayHelper::merge(['query' => $params], $options));

            return $this->format == 'xml'
                ? $response->xml()
                : $response->json();

        } catch (RequestException $e) {
            return $e;
        }
    }

    /**
     * Returns the guzzle client
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleClient()
    {
        if ($this->_guzzle == null) {
            $this->_guzzle = new HttpClient();
        }
        return $this->_guzzle;
    }

    /**
     * Validates whether the value is UTF8 encoded and if not, it encodes it.
     * From Nexmo Docs: All requests are submitted through the HTTP POST or GET method using UTF-8 encoding and URL
     * encoded values.
     * @param string $value
     * @return string
     */
    protected function validateUTF8($value)
    {
        return (!mb_check_encoding($value, 'UTF-8')) ? utf8_encode($value) : $value;
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