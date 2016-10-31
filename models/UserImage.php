<?php

namespace app\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "user_image".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $name
 * @property string $language
 * @property string $original
 * @property string $thumb160
 * @property string $thumb90
 * @property string $date
 * @property string $lastUpdate
 *
 * @property AlbumImage[] $albumImages
 * @property UserAlbum[] $albums
 * @property User $user
 */
class UserImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'url'], 'required'],
            [['userId'], 'integer'],
            [['date', 'lastUpdate', 'sort'], 'safe'],
            [['title', 'description', 'url', 'name', 'original', 'thumb160', 'thumb90'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
            [
                ['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['userId' => 'id']
            ],
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
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'name' => 'Name',
            'language' => 'Language',
            'original' => 'Original',
            'thumb160' => 'Thumb160',
            'thumb90' => 'Thumb90',
            'date' => 'Date',
            'lastUpdate' => 'Last Update',
            'sort' => 'sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumImages()
    {
        return $this->hasMany(AlbumImage::className(), ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(UserAlbum::className(), ['id' => 'albumId'])->viaTable('album_image', ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'postId'])->viaTable('post_image', ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(UserComment::className(), ['imageId' => 'id'])->orderBy(['date' => SORT_DESC]);;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(UserFavorite::className(), ['imageId' => 'id']);
    }

    public static function getRootPath($size, $name)
    {
        return realpath(__DIR__ . '/../' . \Yii::$app->params['imageUploadFolder']) . "/{$size}/" . $name;
    }

    public static function generateUrl($length = 8)
    {
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    public static function saveImage($source, $size, $name)
    {
        $img = Image::getImagine()->open($source);
        $img->save(self::getRootPath($size, $name));

        $response = self::sendRequest([
            'image_save' => true,
            'url' => 'http://' . Yii::$app->getRequest()->serverName . '/' . Yii::$app->params['imageUploadFolder'] . "/{$size}/" . $name,
            'type' => $size
        ]);

        if (empty($response)) {
            throw new \Exception('Curl fail');
        }

        $response = json_decode($response);

        if (empty($response->action)) {
            throw new \Exception('Save fail');
        }
        unlink(self::getRootPath('original', pathinfo($name, PATHINFO_BASENAME)));
        return $response->url;
    }

    public static function saveCustomImage($source, $size, $name, $resize)
    {
        $resize = explode('x', $resize);
        Image::thumbnail($source, $resize[0], $resize[1])
            ->save(self::getRootPath($size, $name));

        $response = self::sendRequest([
            'image_save' => true,
            'url' => 'http://' . Yii::$app->getRequest(
                )->serverName . '/' . Yii::$app->params['imageUploadFolder'] . "/{$size}/" . $name,
            'type' => $size
        ]);

        if (empty($response)) {
            throw new \Exception('Curl fail');
        }

        $response = json_decode($response);

        if (empty($response->action)) {
            throw new \Exception('Save fail');
        }
        unlink(self::getRootPath($size, pathinfo($name, PATHINFO_BASENAME)));
        return $response->url;
    }

    public static function saveThumbnail($source, $size, $name)
    {
        Image::thumbnail($source, (int) str_replace('thumb', '', $size), (int) str_replace('thumb', '', $size))
            ->save(UserImage::getRootPath($size, $name));

        $response = self::sendRequest([
            'image_save' => true,
            'url' => 'http://' . Yii::$app->getRequest(
                )->serverName . '/' . Yii::$app->params['imageUploadFolder'] . "/{$size}/" . $name,
            'type' => $size
        ]);

        if (empty($response)) {
            throw new \Exception('Curl fail');
        }

        $response = json_decode($response);

        if (empty($response->action)) {
            throw new \Exception('Save fail');
        }
        unlink(self::getRootPath($size, pathinfo($name, PATHINFO_BASENAME)));
        return $response->url;
    }

    public static function remove($image)
    {
        self::sendRequest([
            'image_remove' => true,
            'original' => $image->original,
            'thumb90' => $image->thumb90,
            'thumb160' => $image->thumb160,
        ]);

        $image->user->imageCount -= 1;
        $image->user->save();
        $image->delete();
    }

    public static function replace($image, $file)
    {
        $img = Image::getImagine()->open($file['tmp_name']);
        $img->save(self::getRootPath('original', pathinfo($image->original, PATHINFO_BASENAME)));

        $response = self::sendRequest([
            'image_save' => true,
            'url' => 'http://' . Yii::$app->getRequest(
                )->serverName . '/' . Yii::$app->params['imageUploadFolder'] . "/original/" . $image->original,
            'type' => 'original'
        ]);

        if (empty($response)) {
            throw new \Exception('Curl fail');
        }

        $response = json_decode($response);

        if (empty($response->action)) {
            throw new \Exception('Save fail');
        }
        unlink(self::getRootPath('original', pathinfo($image->original, PATHINFO_BASENAME)));
        return $response->url;
    }

    public static function resize($image, $width, $height)
    {
        Image::thumbnail(self::getRootPath('original', pathinfo($image->original, PATHINFO_BASENAME)), $width, $height)
            ->save(self::getRootPath('original', pathinfo($image->original, PATHINFO_BASENAME)));

        $response = self::sendRequest([
            'image_save' => true,
            'url' => 'http://' . Yii::$app->getRequest(
                )->serverName . '/' . Yii::$app->params['imageUploadFolder'] . "/original/" . $image->original,
            'type' => 'original'
        ]);

        if (empty($response)) {
            throw new \Exception('Curl fail');
        }

        $response = json_decode($response);

        if (empty($response->action)) {
            throw new \Exception('Save fail');
        }
        unlink(self::getRootPath('original', pathinfo($image->original, PATHINFO_BASENAME)));
        return $response->url;
    }

    public static function sendRequest($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['imageDefaultServer'] . '/api.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
}
