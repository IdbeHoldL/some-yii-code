<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 15.04.2017
 * Time: 14:32
 *
 */

namespace common\models;


/**
 * Interface ActiveRecordSingleTableInheritanceInterface
 * todo: docs...
 * @package common\models
 */
interface ActiveRecordSingleTableInheritanceInterface
{

    /**
     * Get discriminator column name for Single Table Inheritance
     * @return string
     */
    public static function getDiscriminatorColumnName();

    /**
     * Get discriminator map for Single Table Inheritance
     *
     * Array of model subclasses by type
     *  [
     *    'type_1' => ModelSubclassOne::class,
     *    'type_2' => ModelSubclassTwo::class,
     *      ...
     *    'type_n' => ModelSubclassAnother::class
     *  ];
     *
     * @return array
     */
    public static function getDiscriminatorMap();

    /**
     * Get model type
     * You need redeclare this method in child classes
     * @return mixed
     */
    public static function getType();

    /**
     * Get ActiveQuery object
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public static function find();

}