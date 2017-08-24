<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Comment]].
 *
 * @see \common\models\Comment
 */
class CommentQuery extends \yii\db\ActiveQuery
{
    /**
     * @param $modelType
     * @param $modelId
     * @return $this
     */
    public function subject($modelType, $modelId)
    {

        $this
            ->withTree()
            ->andWhere([
                'comment_tree.model_type' => $modelType,
                'comment_tree.model_id'   => $modelId,
            ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function rootOnly()
    {
        $this
            ->withTree()
            ->andWhere(['comment_tree.level' => 0]);

        return $this;
    }

    public function ancestor($ancestorId)
    {

        $this
            ->withTree()
            ->andWhere(['comment_tree.ancestor_id' => $ancestorId]);

        return $this;
    }

    /**
     * @return $this
     */
    public function withTree()
    {
        $this->joinWith('commentTree');

        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\models\Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
