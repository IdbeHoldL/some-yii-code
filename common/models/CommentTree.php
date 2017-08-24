<?php

namespace common\models;

use common\components\FrontendRouteHelper;
use Yii;
use \common\models\base\CommentTree as BaseCommentTree;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comment_tree".
 */
class CommentTree extends BaseCommentTree
{


    const TYPE_STEAM_USER = SteamUser::class;
    const TYPE_MATCH      = Match::class;
    const TYPE_ARTICLE    = Article::class;

    public static function getModelTypeList()
    {
        return [
            'steam-users' => self::TYPE_STEAM_USER,
            'matches'     => self::TYPE_MATCH,
        ];
    }

    public function getUrl()
    {
        return FrontendRouteHelper::buildUrl(self::getModelTypeList()[$this->model_type], $this->model_id) . '#comment-' . $this->descendant_id;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
