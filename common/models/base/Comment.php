<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "comment".
 *
 * @property integer $id
 * @property integer $steam_user_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property boolean $is_active
 *
 * @property \common\models\SteamUser $steamUser
 * @property \common\models\CommentTree[] $commentTrees
 * @property \common\models\CommentTree $commentTree
 * @property \common\models\Comment[] $descendants
 * @property \common\models\Comment[] $ancestors
 * @property string $aliasModel
 */
abstract class Comment extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['steam_user_id', 'content'], 'required'],
            [['steam_user_id'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'boolean'],
            [['steam_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\SteamUser::className(), 'targetAttribute' => ['steam_user_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'steam_user_id' => Yii::t('app', 'Steam User ID'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteamUser()
    {
        return $this->hasOne(\common\models\SteamUser::className(), ['id' => 'steam_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentTrees()
    {
        return $this->hasMany(\common\models\CommentTree::className(), ['ancestor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentTree()
    {
        return $this->hasOne(\common\models\CommentTree::className(), ['descendant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescendants()
    {
        return $this->hasMany(\common\models\Comment::className(), ['id' => 'descendant_id'])->viaTable('comment_tree', ['ancestor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAncestors()
    {
        return $this->hasMany(\common\models\Comment::className(), ['id' => 'ancestor_id'])->viaTable('comment_tree', ['descendant_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \common\models\query\CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CommentQuery(get_called_class());
    }


}