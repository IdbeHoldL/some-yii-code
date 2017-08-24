<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 15.04.2017
 * Time: 14:41
 */

namespace common\models;

use yii\db\ActiveRecordInterface;

trait ActiveRecordSingleTableInheritanceTrait
{

    /**
     * Get model type
     * @return integer|null
     */
    public static function getType()
    {
        return null;
    }

    /**
     * @param $attributes
     * @return ActiveRecordInterface
     */
    public static function instantiate($attributes)
    {
        $discriminatorMap        = self::getDiscriminatorMap();
        $discriminatorColumnName = self::getDiscriminatorColumnName();

        $type = isset($attributes[$discriminatorColumnName]) ? $attributes[$discriminatorColumnName] : null;
        if ($type && isset($discriminatorMap[$type])) {
            return new $discriminatorMap[$type];
        }

        throw new \RuntimeException("Cant find child model for type : $type");
    }

    /**
     * @param $insert
     * @return mixed
     */
    public function beforeSave($insert)
    {
        $discriminatorMap = self::getDiscriminatorMap();

        if (!isset($discriminatorMap[static::getType()])) {
            throw new \RuntimeException('Can\'t create instance of ' . __CLASS__ . '. Class name must be added to discriminator Map');
        }
        // make sure that we save valid model type
        $this->setAttribute(self::getDiscriminatorColumnName(), static::getType());

        return parent::beforeSave($insert);
    }
}