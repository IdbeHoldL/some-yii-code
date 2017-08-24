<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 04.07.2017
 * Time: 12:54
 */

namespace common\models\notification;

use common\components\FrontendRouteHelper;
use common\models\SteamUser;

/**
 * Class NotificationFriendsTrait
 * @package common\models\notification
 *
 * @property SteamUser $friendUser
 */
trait NotificationFriendsTrait
{

    /**
     * @return mixed
     */
    public function getFriendUser()
    {
        return SteamUser::find()->where(['id' => $this->getParam('friend_user_id')]);
    }

    /**
     * @deprecated don't need anymore (link available by getUrl method and adds to api response by default. No html links in notification messages!)
     * @return string
     */
    public function getFriendUserProfileLink()
    {
        return sprintf('<a href="%s">%s</a>',
            FrontendRouteHelper::buildUrl(SteamUser::class, $this->friendUser->id),
            $this->friendUser->username
        );
    }

    /**
     * @return array
     */
    public function getParamRules()
    {
        return [
            ['friend_user_id', 'required'],
            ['friend_user_id', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function getParamNames()
    {
        return [
            'friend_user_id',
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return FrontendRouteHelper::buildUrl(SteamUser::class, $this->friendUser->id);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->friendUser->avatar;
    }
}
