<?php
/**
 * @link https://github.com/2amigos/yii2-nexmo-library
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\nexmo;

/**
 * Class InboundMessage is the class that is initialized with all the parameters sent to a CallBack Url that is set to
 * handle inbound messages. In order to receive an [[InboundMessage]] object you need to set your CallBack Url with
 * the [[InboundCallbackAction]] which will trigger an [[InboundCallbackEvent]] with an [[InboundMessage]] object
 * initialized with the parameters sent.
 *
 * @see https://docs.nexmo.com/index.php/sms-api/handle-inbound-message
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class InboundMessage extends Object
{
    /**
     * @var string Expected values are: text, unicode (URL encoded, valid for standard GSM, Arabic, Chinese ...
     * characters) or binary
     */
    public $type;
    /**
     * @var string content of the message. Specific to text inbound type message.
     */
    public $text;
    /**
     * @var string Set to true if a MO concatenated message is detected. Long concatenated Inbound specific.
     */
    public $concat;
    /**
     * @var string Transaction reference, all message parts will shared the same transaction reference. Long
     * concatenated Inbound specific.
     */
    public $concatRef;
    /**
     * @var string The total number of parts in this concatenated message. set Long concatenated Inbound specific.
     */
    public $concatTotal;
    /**
     * @var string The part number of this message within the set (starts at 1). Long concatenated Inbound specific.
     */
    public $concatPart;
    /**
     * @var string Content of the message. Binary Inbound specific.
     */
    public $data;
    /**
     * @var string User Data Header (hex encoded). Binary Inbound specific.
     */
    public $udh;

}
