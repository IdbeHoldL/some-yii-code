<?php
/**
 * Created by PhpStorm.
 * User: IdbeHoldL
 * Date: 08.06.2017
 * Time: 11:04
 */

namespace common\api\components;


/**
 * Class ApiActiveRecordTrait
 * @package common\api\components
 */
trait ApiActiveRecordTrait
{

    /**
     * @var integer Scope
     */
    private static $scope = self::SCOPE_DEFAULT;


    public static function scopes()
    {
        return [
            self::SCOPE_DEFAULT => array_keys(self::exposeFields()),
        ];
    }

    /**
     * Return array of fields
     * @param int $scope
     * @return array
     */
    public function fields($scope = null)
    {
        $scopes = self::scopes();

        $scope = ($scope !== null) ? $scope : self::getScope();

        return (isset($scopes[$scope])) ? $scopes[$scope] : [];
    }


    /**
     * Set model scope for serializer
     * @param integer $scope
     * @throws \Exception
     */
    public static function setScope($scope)
    {
        if (!in_array($scope, self::getScopeNames())) {
            throw new \Exception("Failed to set model scope '{$scope}'. You must add it scopes() method");
        }

        self::$scope = $scope;
    }


    /**
     * Get current model scope for serializer
     * @return integer
     */
    public static function getScope()
    {
        return self::$scope;
    }


    /**
     * Get available scope names
     * @return array
     */
    public static function getScopeNames()
    {
        return array_keys(self::scopes());
    }

}