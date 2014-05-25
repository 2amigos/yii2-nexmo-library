<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\nexmo;

use Yii;
use yii\helpers\Inflector;

/**
 * Class Object is the base calls for [[DeliveryReceipt]] and [[InboundMessage]] objects.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class Object extends \yii\base\Object
{
    /**
     * @var string the sender id of the message
     */
    public $to;
    /**
     * @var string the optional identifier of a mobile network MCCMNC.
     * @see http://en.wikipedia.org/wiki/Mobile_Network_Code
     */
    public $networkCode;
    /**
     * @var string the message ID.
     */
    public $messageId;
    /**
     * @var string the number message was delivered to.
     */
    public $msisdn;
    /**
     * @var string When Nexmo started to push the receipt to your callback URL in the following format
     * YYYY-MM-DD HH:MM:SS e.g. 2012-04-05 09:22:57
     */
    public $messageTimestamp;

    /**
     * Constructor override. Required to transform the parameter names to variable names.
     *
     * From [[yii\base\Object]]:
     * The default implementation does two things:
     *
     * - Initializes the object with the given configuration `$config`.
     * - Call [[init()]].
     *
     * If this method is overridden in a child class, it is recommended that
     *
     * - the last parameter of the constructor is a configuration array, like `$config` here.
     * - call the parent implementation at the end of the constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            $parsedConfig = [];
            foreach ($config as $k => $value) {
                $parsedConfig[Inflector::variablize($k)] = $value;
            }
            Yii::configure($this, $parsedConfig);
        }
        $this->init();
    }
} 