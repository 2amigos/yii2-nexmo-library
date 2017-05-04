<?php
/**
 * @link https://github.com/2amigos/yii2-nexmo-library
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\nexmo;


use yii\base\Event;

/**
 * Class InboundCallbackEvent is the [[yii\base\Event]] object that is sent as a parameter when a
 * [[InboundCallbackAction]] is used as a CallBack Url to handle Inbound Messages. This Event holds an
 * [[InboundMessage]] object that is initialized with the parameters received.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class InboundCallbackEvent extends Event
{
    /**
     * @var InboundMessage $message
     */
    public $message;
}
