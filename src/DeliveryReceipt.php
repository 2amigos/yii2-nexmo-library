<?php
/**
 * @link https://github.com/2amigos/yii2-nexmo-library
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\nexmo;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class DeliveryReceipt is the class that holds all the parameters received when a CallBack Url is set for message
 * delivery callback. Nexmo calls your configured CallBack Url (that should be done using [[DeliveryCallbackAction]]
 * action) and sends a list of parameters that [[DeliveryReceipt]] is initialized with.
 *
 * @see https://docs.nexmo.com/index.php/sms-api/handle-delivery-receipt
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\nexmo
 */
class DeliveryReceipt extends Object
{
    /**
     * Message arrived to handset.
     */
    const STATUS_DELIVERED = 'delivered';
    /**
     * Message timed out after we waited 48h to receive status from mobile operator.
     */
    const STATUS_EXPIRED = 'expired';
    /**
     * Message failed to be delivered.
     */
    const STATUS_FAILED = 'failed';
    /**
     * Message has been accepted by the mobile operator.
     */
    const STATUS_ACCEPTED = 'accepted';
    /**
     * Message is being delivered.
     */
    const STATUS_BUFFERED = 'buffered';
    /**
     * Undocumented status from the mobile operator.
     */
    const STATUS_UNKNOWN = 'unknown';

    /**
     * @var string the status of the message
     */
    public $status;
    /**
     * @var string the status related error code
     */
    public $errCode;
    /**
     * @var string the message price
     */
    public $price;
    /**
     * @var string UTC time when notification is received from mobile network in the following format YYMMDDHHMM e.g.
     * 1101181426 is 2011 Jan 18th 14:26
     */
    public $scts;
    /**
     * @var string If you set a custom reference during your send request, this will return that value.
     */
    public $clientRef;
    /**
     * @var int the received message time. Converted from [[$scts]].
     */
    private $_received_time;
    /**
     * @var array holds the error codes description
     */
    private $_error_codes = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslations();
        $this->_error_codes = [
            Yii::t('dosamigos/nexmo', 'Delivered'),
            Yii::t('dosamigos/nexmo', 'Unknown'),
            Yii::t('dosamigos/nexmo', 'Absent Subscriber - Temporary'),
            Yii::t('dosamigos/nexmo', 'Absent Subscriber - Permanent'),
            Yii::t('dosamigos/nexmo', 'Call barred by user'),
            Yii::t('dosamigos/nexmo', 'Portability Error'),
            Yii::t('dosamigos/nexmo', 'Anti-Spam Rejection'),
            Yii::t('dosamigos/nexmo', 'Handset Busy'),
            Yii::t('dosamigos/nexmo', 'Network Error'),
            Yii::t('dosamigos/nexmo', 'Illegal Number'),
            Yii::t('dosamigos/nexmo', 'Invalid Message'),
            Yii::t('dosamigos/nexmo', 'Unroutable'),
            Yii::t('dosamigos/nexmo', 'Destination Un-Reachable'),
            99 => Yii::t('dosamigos/nexmo', 'General Error')
        ];
    }

    /**
     * Returns the time in milliseconds from [[$scts]]
     * @return int the received time
     */
    public function getReceivedTime()
    {
        if ($this->_received_time == null && $this->scts) {
            $dp = date_parse_from_format('ymdGi', $this->scts);
            $this->_received_time = mktime(
                $dp['hour'],
                $dp['minute'],
                $dp['second'],
                $dp['month'],
                $dp['day'],
                $dp['year']
            );
        }
        return $this->_received_time;
    }

    /**
     * @return string|null the error label
     */
    public function getErrorLabel()
    {
        return ArrayHelper::getValue($this->_error_codes, $this->errCode);
    }

    /**
     * Registers the translations
     */
    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['dosamigos/nexmo*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@common/extensions/nexmo/messages',
            'forceTranslation' => true
        ];
    }
}
