<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 29.06.2017
 * Time: 14:23
 */

namespace common\models\search;


use common\models\Notification;
use yii\data\ActiveDataProvider;

class NotificationSearch
{

    private $pageSize = 5;


    public function search($recipientId, $pageSize = null)
    {

        $query = Notification::find()
                             ->notReaded()
                             ->recipient($recipientId)
                             ->lastDays(3);

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