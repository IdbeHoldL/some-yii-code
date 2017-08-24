<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 04.07.2017
 * Time: 12:54
 */

namespace common\models\notification;

use common\components\FrontendRouteHelper;
use common\models\Comment;

/**
 * Class NotificationCommentTrait
 * @package common\models\notification
 *
 * @property Comment $targetComment
 * @property Comment $replyComment
 */
trait NotificationCommentTrait
{

    public function getTargetComment()
    {
        return Comment::find()->where(['id' => $this->getParam('target_comment_id')]);
    }

    public function getReplyComment()
    {
        return Comment::find()->where(['id' => $this->getParam('reply_comment_id')]);
    }

    /**
     * @return array
     */
    public function getParamRules()
    {
        return [
            ['target_comment_id', 'required'],
            ['reply_comment_id', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function getParamNames()
    {
        return [
            'target_comment_id',
            'reply_comment_id',
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->replyComment->commentTree->getUrl();
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->replyComment->steamUser->avatar;
    }
}