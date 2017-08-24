<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 05.06.2017
 * Time: 23:48
 */

namespace common\api\components;


/**
 * Interface ApiActiveRecordInterface
 * @package common\api\components
 */
interface ApiActiveRecordInterface
{

    const SCOPE_DEFAULT = 'default';
    const SCOPE_SHORT   = 'short';
    const SCOPE_DETAIL  = 'detail';


    /**
     * Return array model expose fields data
     * @return array
     */
    public static function exposeFields();

    /**
     * Return array of fieldNames by scopes
     * @return array
     */
    public static function scopes();


    /**
     * Return array of fields
     * @return array
     */
    public function fields();

    /**
     * Set current model scope for serializer
     * @param integer $scope
     * @return mixed
     */
    public static function setScope($scope);

    /**
     * Set current model scope for serializer
     * @return integer
     */
    public static function getScope();

    /**
     * Get available scope names
     * @return mixed
     */
    public static function getScopeNames();
}