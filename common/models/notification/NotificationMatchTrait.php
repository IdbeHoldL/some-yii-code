<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 04.07.2017
 * Time: 12:54
 */

namespace common\models\notification;

use common\components\FrontendRouteHelper;
use common\models\Match;

/**
 * Class NotificationFriendsTrait
 * @package common\models\notification
 *
 * @property Match $match
 */
trait NotificationMatchTrait
{

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return Match::find()->where(['id' => $this->getParam('match_id')]);
    }

    /**
     * @return array
     */
    public function getParamRules()
    {
        return [
            ['match_id', 'required'],
            ['match_id', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function getParamNames()
    {
        return [
            'match_id',
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return FrontendRouteHelper::buildUrl(Match::class, $this->match->id);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return 'match.png';
    }
}