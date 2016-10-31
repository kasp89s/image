<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_image".
 *
 * @property integer $postId
 * @property integer $imageId
 *
 * @property UserImage $image
 * @property UserPost $post
 */
class PostImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'imageId'], 'required'],
            [['postId', 'imageId'], 'integer'],
            [['imageId'], 'exist', 'skipOnError' => true, 'targetClass' => UserImage::className(), 'targetAttribute' => ['imageId' => 'id']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => UserPost::className(), 'targetAttribute' => ['postId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postId' => 'Post ID',
            'imageId' => 'Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(UserImage::className(), ['id' => 'imageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'postId']);
    }
}