<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 04.07.2017
 * Time: 12:54
 */

namespace common\models\notification;


use common\models\Notification;

/**
 * Class NotificationFriendsInbox
 * @package common\models\notification
 */
class NotificationFriendsInbox extends Notification
{
    use NotificationFriendsTrait;

    /**
     * @return string
     */
    public static function getType()
    {
        return Notification::TYPE_USER_FRIENDS_INBOX;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return \Yii::t('app', 'Пользователь {user_profile_link} хочет добавить вас в друзья', [
            'user_profile_link' => $this->getFriendUserProfileLink(),
        ]);
    }
}