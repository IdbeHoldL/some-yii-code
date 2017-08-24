<?php

namespace common\models;

use common\api\components\ApiActiveRecordInterface;
use common\api\components\ApiActiveRecordTrait;
use common\models\notification\NotificationCommentReply;
use common\models\notification\NotificationFriendsBlacklist;
use common\models\notification\NotificationFriendsInbox;
use common\models\notification\NotificationFriendsLeave;
use common\models\notification\NotificationFriendsNew;
use common\models\notification\NotificationMatchCreated;
use common\models\notification\NotificationMatchStarted;
use Yii;
use \common\models\base\Notification as BaseNotification;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "notification".
 *
 * @property string $image
 * @property string $url
 */
class Notification extends BaseNotification implements ActiveRecordSingleTableInheritanceInterface, ApiActiveRecordInterface
{
    use ActiveRecordSingleTableInheritanceTrait {
        beforeSave as public beforeSaveInheritanceTrait;
    }

    use ApiActiveRecordTrait;

    /**
     * Notification types
     */
    // friends
    const TYPE_USER_FRIENDS_INBOX     = 'friends.inbox';        // new inbox friends request
    const TYPE_USER_FRIENDS_NEW       = 'friends.new';          // new friend
    const TYPE_USER_FRIENDS_LEAVE     = 'friends.leave';        // friend leave
    const TYPE_USER_FRIENDS_BLACKLIST = 'friends.blacklist';    // friend leave
    // comments
    const TYPE_COMMENTS_REPLY      = 'comments.reply';      // Someone reply user comment
    const TYPE_COMMENTS_REPLY_TREE = 'comments.reply_tree'; // Someone add comment to user comment_tree
    const TYPE_COMMENTS_LIKED      = 'comments.liked';      // Someone liked user comment
    // matches
    const TYPE_MATCHES_CREATED = 'matches.created'; // Match created
    const TYPE_MATCHES_STARTED = 'matches.started'; // Match started


    /**
     * @var array
     */
    public $parameters = [];

    public static function exposeFields()
    {
        return [
            'id'           => ['type' => 'integer', 'description' => 'id'],
            'type'         => ['type' => 'string', 'description' => 'Type'],
            'message'      => ['type' => 'string', 'description' => 'Message'],
            'image'        => ['type' => 'string', 'description' => 'Image'],
            'url'          => ['type' => 'string', 'description' => 'Target object url'],
            'reference_id' => ['type' => 'integer', 'description' => 'Reference object id (can be null)'],
            'is_active'    => ['type' => 'boolean', 'description' => 'Activity flag'],
            'created_at'   => ['type' => 'string', 'description' => 'Create date '],
        ];
    }

    /**
     * @return array
     */
    public static function getDiscriminatorMap()
    {
        return [
            self::TYPE_USER_FRIENDS_INBOX     => NotificationFriendsInbox::class,
            self::TYPE_USER_FRIENDS_NEW       => NotificationFriendsNew::class,
            self::TYPE_USER_FRIENDS_LEAVE     => NotificationFriendsLeave::class,
            self::TYPE_USER_FRIENDS_BLACKLIST => NotificationFriendsBlacklist::class,
            self::TYPE_COMMENTS_REPLY         => NotificationCommentReply::class,
            self::TYPE_MATCHES_STARTED        => NotificationMatchCreated::class,
            self::TYPE_MATCHES_CREATED        => NotificationMatchStarted::class,
        ];
    }

    /**
     * @return string
     */
    public static function getDiscriminatorColumnName()
    {
        return 'type';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return '';
    }

    /**
     * Add user notification
     * @param string  $type        Notify type
     * @param integer $recipientId Recipient Steam User ID
     * @param integer $referenceId Reference object Id
     * @param array   $params      Additional notify data
     * @return NotificationFriendsBlacklist|NotificationFriendsInbox|NotificationFriendsLeave|NotificationFriendsNew
     */
    public static function add($type, $recipientId, $referenceId = null, $params = [])
    {
        $notify = self::create($type, $recipientId, $referenceId, $params);
        $notify->save();

        return $notify;
    }


    /**
     * Fabric method for notifications
     * @param string  $type        Notify type
     * @param integer $recipientId Recipient Steam User ID
     * @param integer $referenceId Reference object Id
     * @param array   $params      Additional notify data
     * @return NotificationFriendsBlacklist|NotificationFriendsInbox|NotificationFriendsLeave|NotificationFriendsNew
     * @throws \Exception
     */
    public static function create($type, $recipientId, $referenceId = null, $params = [])
    {
        switch ($type) {
            case self::TYPE_USER_FRIENDS_INBOX    :
                $notify = new NotificationFriendsInbox();
                break;
            case self::TYPE_USER_FRIENDS_NEW      :
                $notify = new NotificationFriendsNew();
                break;
            case self::TYPE_USER_FRIENDS_LEAVE    :
                $notify = new NotificationFriendsLeave();
                break;
            case self::TYPE_USER_FRIENDS_BLACKLIST:
                $notify = new NotificationFriendsBlacklist();
                break;
            case self::TYPE_COMMENTS_REPLY:
                $notify = new NotificationCommentReply();
                break;
            case self::TYPE_MATCHES_CREATED:
                $notify = new NotificationMatchCreated();
                break;
            case self::TYPE_MATCHES_STARTED:
                $notify = new NotificationMatchStarted();
                break;
            default:
                throw new \Exception("Unknown notify type : {$type}");
        }

        $notify->recipient_id = $recipientId;
        $notify->reference_id = $referenceId;
        $notify->parameters   = $params;

        return $notify;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['params', 'validateParams'],
        ]);
    }

    /**
     * Get Validate Rules for parameters array (params json-field)
     * @return array
     */
    public function getParamRules()
    {
        return [];
    }


    /**
     * Get params json-field allowed field names
     * @return array
     */
    public function getParamNames()
    {
        return [];
    }

    /**
     * Validate parameters (params json-field)
     */
    public function validateParams()
    {

        $dynamicModel = new DynamicModel($this->getParamNames());

        foreach ($this->getParamRules() as $paramsRule) {
            $dynamicModel->addRule($paramsRule[0], $paramsRule[1], (isset($paramsRule[2]) ? $paramsRule[2] : []));
        }

        $dynamicModel->setAttributes($this->parameters);

        if (!$dynamicModel->validate()) {
            $errors = $dynamicModel->getFirstErrors();
            $this->addError('params', reset($errors));
        }
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->parameters = json_decode($this->params, true);
    }

    /**
     * @param bool $insert
     * @return mixed
     */
    public function beforeSave($insert)
    {
        $this->params = json_encode($this->parameters);

        return $this->beforeSaveInheritanceTrait($insert);
    }


    /**
     * @param      $name
     * @param null $default
     * @return null
     */
    public function getParam($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     *
     */
    public function init()
    {

        if ($this->isNewRecord) {
            $this->params = '{}';
            $this->type   = static::getType();
        }

        parent::init();
    }

    /**
     * @return []
     */

    function getMessage()
    {
        return 'no message';
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

}
