<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\nexmo;


use yii\base\Event;

/**
 * Class DeliveryCallbackEvent is the [[yii\base\Event]] object that is sent as a parameter when a
 * [[DeliveryCallbackAction]] is used as a CallBack Url. This Event holds a [[DeliveryReceipt]] object that is
 * initialized with the parameters received.
 *
 * @see https://docs.nexmo.com/index.php/sms-api/handle-delivery-receipt
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class DeliveryCallbackEvent extends Event
{
    /**
     * @var [[DeliveryReceipt]] $receipt
     */
    public $receipt;
} 