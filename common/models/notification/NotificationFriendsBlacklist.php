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
 * Class NotificationFriendsBlacklist
 * @package common\models\notification
 */
class NotificationFriendsBlacklist extends Notification
{
    use NotificationFriendsTrait;

    /**
     * @return string
     */
    public static function getType()
    {
        return Notification::TYPE_USER_FRIENDS_BLACKLIST;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return \Yii::t('app', 'Пользователь {user_profile_link} добавил вас в черный список', [
            'user_profile_link' => $this->getFriendUserProfileLink(),
        ]);
    }
}