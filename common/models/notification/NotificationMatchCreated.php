<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 05.07.2017
 * Time: 0:18
 */

namespace common\models\notification;


use common\models\Notification;

class NotificationMatchCreated extends Notification
{
    use NotificationMatchTrait;

    /**
     * @return string
     */
    public static function getType()
    {
        return Notification::TYPE_MATCHES_CREATED;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return \Yii::t('app', 'Матч создан!');
    }
}