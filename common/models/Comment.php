<?php

namespace common\models;

use common\api\components\ApiActiveRecordInterface;
use common\api\components\ApiActiveRecordTrait;
use Yii;
use \common\models\base\Comment as BaseComment;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comment".
 *
 *
 */
class Comment extends BaseComment implements ApiActiveRecordInterface
{
    use ApiActiveRecordTrait;

    public static function exposeFields()
    {
        return [
            'id'         => ['type' => 'integer', 'description' => 'id'],
            'content'    => ['type' => 'string', 'description' => 'Content'],
            'created_at' => ['type' => 'string', 'format' => 'date-time', 'description' => 'Create date'],

            'steamUser' => ['ref' => '#/definitions/SteamUser'],
            'children'  => [
                'description' => 'Sub comments',
                'type'        => 'array',
                'items'       => [
                    'ref' => '#/definitions/Comment',
                ],
            ],
        ];
    }

    public static function scopes()
    {
        return [
            self::SCOPE_DEFAULT => array_keys(self::exposeFields()),
            self::SCOPE_SHORT   => ['id', 'content', 'steamUser', 'created_at'],
        ];
    }


    /**
     * Add comment
     * @param  string         $modelType
     * @param         integer $modelId
     * @param         integer $steamUserId
     * @param         string  $content
     * @param Comment|null    $parent
     * @return Comment
     */
    public static function add($modelType, $modelId, $steamUserId, $content, Comment $parent = null)
    {
        $comment = new self([
            'steam_user_id' => $steamUserId,
            'content'       => $content,
        ]);

        $comment->save();

        $commentTree = new CommentTree([
            'descendant_id'       => $comment->id,
            'ancestor_id'         => ($parent) ? $parent->commentTree->ancestor_id : $comment->id,
            'nearest_ancestor_id' => ($parent) ? $parent->id : 0,
            'model_type'          => ($parent) ? $parent->commentTree->model_type : $modelType,
            'model_id'            => ($parent) ? $parent->commentTree->model_id : $modelId,
            'level'               => ($parent) ? $parent->commentTree->level + 1 : 0,
        ]);
        $commentTree->save();

        return $comment;
    }

    /**
     * Reply comment
     * @param $steamUserId
     * @param $content
     * @return Comment
     */
    public function reply($steamUserId, $content)
    {
        return self::add($modelName = null, $modelId = null, $steamUserId, $content, $this);
    }

    /**
     * Get Comment children tree
     * @return mixed
     */
    public function getChildren()
    {
        $buildTree = function ($flatArray) {

            $grouped = [];
            // Подготавливаем массив комментов, сгруппированных по nearest_ancestor_id
            foreach ($flatArray as $item) {
                $parentId             = ArrayHelper::getValue($item, "commentTree.nearest_ancestor_id");
                $grouped[$parentId][] = [
                    'id'         => $item['id'],
                    'content'    => $item['content'],
                    'created_at' => $item['created_at'],
                    'steamUser'  => SteamUser::findOne(['id' => $item['steam_user_id']]),
                ];
            }

            /**
             * Возвращает ветку комметнов
             * @param Comment[] $comments
             * @return mixed
             */
            $fnBuilder = function ($comments) use (&$fnBuilder, $grouped) {
                foreach ($comments as $k => $comment) {
                    $id = $comment['id'];
                    if (isset($grouped[$id])) {
                        $comment['children'] = $fnBuilder($grouped[$id]);
                    }
                    $comments[$k] = $comment;
                }

                return $comments;
            };

            return (isset($grouped[0])) ? $fnBuilder($grouped[0], 0) : [];
        };

        $descendants = self::find()->ancestor($this->id)->asArray()->all();
        $tree        = (count($descendants)) ? $buildTree($descendants) : [];

        return ArrayHelper::getValue($tree, '0.children', []);
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
