<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Notification]].
 *
 * @see \common\models\Notification
 */
class NotificationQuery extends \yii\db\ActiveQuery
{
    /**
     * Active only (not readed)
     * @return $this
     */
    public function notReaded()
    {
        $this->andWhere(['is_active' => true]);

        return $this;
    }

    /**
     * @param $recipientId
     * @return $this
     */
    public function recipient($recipientId)
    {
        $this->andWhere(['recipient_id' => $recipientId]);

        return $this;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function lastDays($days)
    {
        $days = (int)$days;
        $this->andWhere("created_at > current_timestamp - interval '{$days} days'");

        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\models\Notification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Notification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
