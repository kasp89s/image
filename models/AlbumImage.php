<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "album_image".
 *
 * @property integer $albumId
 * @property integer $imageId
 *
 * @property UserAlbum $album
 * @property UserImage $image
 */
class AlbumImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['albumId', 'imageId'], 'required'],
            [['albumId', 'imageId'], 'integer'],
            [['albumId', 'imageId'], 'unique', 'targetAttribute' => ['albumId', 'imageId'], 'message' => 'The combination of Album ID and Image ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'albumId' => 'Album ID',
            'imageId' => 'Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(UserAlbum::className(), ['id' => 'albumId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(UserImage::className(), ['id' => 'imageId']);
    }
}