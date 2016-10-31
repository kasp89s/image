<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_album".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property string $description
 * @property string $cover
 * @property string $type
 * @property string $language
 * @property string $date
 *
 * @property AlbumImage[] $albumImages
 * @property UserImage[] $images
 * @property User $user
 */
class UserAlbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['title'], 'required', 'message' => \Yii::t('app', 'Field can not be empty.')],
            [['userId'], 'integer'],
            [['description', 'type'], 'string'],
            [['date'], 'safe'],
            [['title', 'cover', 'url'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'url' => 'url',
            'title' => 'Title',
            'description' => 'Description',
            'cover' => 'Cover',
            'type' => 'Type',
            'language' => 'Language',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumImages()
    {
        return $this->hasMany(AlbumImage::className(), ['albumId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
		->viaTable('album_image', ['albumId' => 'id'])
		->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesTimeAsc()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
		->viaTable('album_image', ['albumId' => 'id'])
		->orderBy(['date' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesTimeDesc()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
		->viaTable('album_image', ['albumId' => 'id'])
		->orderBy(['date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesNameAsc()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
		->viaTable('album_image', ['albumId' => 'id'])
		->orderBy(['name' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesNameDesc()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
		->viaTable('album_image', ['albumId' => 'id'])
		->orderBy(['name' => SORT_DESC]);
    }

    public function getImagesCount()
    {
        return AlbumImage::find()->where(['albumId' => $this->id])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
	
	public static function generateUrl($length = 7)
	{
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
	}
}
