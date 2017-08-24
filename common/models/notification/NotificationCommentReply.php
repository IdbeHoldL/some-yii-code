<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 03.08.2017
 * Time: 22:09
 */

namespace common\models\notification;


use common\models\Notification;

class NotificationCommentReply extends Notification
{

    use NotificationCommentTrait;

    /**
     * @return string
     */
    public static function getType()
    {
        return Notification::TYPE_COMMENTS_REPLY;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return \Yii::t('app', 'Пользователь {username} ответил на ваш комментарий', [
            'username' => $this->replyComment->steamUser->username,
        ]);
    }
}