<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\nexmo;

use Yii;
use yii\base\Action;

/**
 * Class InboundCallbackAction handles inbound message requests.
 *
 * @see https://docs.nexmo.com/index.php/sms-api/handle-inbound-message
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class InboundCallbackAction extends Action
{
    /**
     * Event name
     */
    const EVENT_MESSAGE_RECEIVED = 'messageReceived';

    /**
     * Action run method
     */
    public function run()
    {
        // The request parameters are sent via a GET
        $data = Yii::$app->request->getQueryParams();

        if (isset($data['type']) && isset($data['messageId']) && $data['to']) {
            $message = new InboundMessage($data);

            $event = new InboundCallbackEvent(['message' => $message]);

            $this->trigger(self::EVENT_MESSAGE_RECEIVED, $event);
        }
    }
}