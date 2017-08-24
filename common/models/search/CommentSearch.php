<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 29.06.2017
 * Time: 14:23
 */

namespace common\models\search;


use common\models\Comment;
use yii\data\ActiveDataProvider;

class CommentSearch extends Comment
{

    private $pageSize = 5;

    /**
     * @param      $modelType
     * @param      $modelId
     * @param null $pageSize
     * @return ActiveDataProvider
     */
    public function search($modelType, $modelId, $pageSize = null)
    {

        $query = Comment::find()
                        ->subject($modelType, $modelId)
                        ->rootOnly();

        return new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize'     => (($pageSize) ? $pageSize : $this->pageSize),
                'validatePage' => false,
            ],
        ]);
    }

}