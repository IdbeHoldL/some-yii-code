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
 * Class NotificationFriendsLeave
 * @package common\models\notification
 */
class NotificationFriendsLeave extends Notification
{
    use NotificationFriendsTrait;

    /**
     * @return string
     */
    public static function getType()
    {
        return Notification::TYPE_USER_FRIENDS_LEAVE;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return \Yii::t('app', 'Пользователь {user_profile_link} удалил вас из списка друзей', [
            'user_profile_link' => $this->getFriendUserProfileLink(),
        ]);
    }
}