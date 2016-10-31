<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Tags;
use app\models\UserPost;
use app\models\PostTags;
use app\models\PostImage;
use app\models\UserImage;
use app\models\UserAlbum;
use app\models\UserComment;
use app\models\CommentReply;
use app\models\UserFavorite;
use app\models\AlbumImage;
use yii\web\User;

class AjaxController extends AbstractController
{
	private $_post;
	
	public function init()
	{
        parent::init();

        if (!Yii::$app->request->isAjax) {
			throw new \yii\web\NotFoundHttpException();
		}
		
		$this->_post = Yii::$app->request->post();
	}

	public function actionFindTags()
	{
		$tags = Tags::find()->andFilterWhere(['like', 'title', $this->_post['title']])
		->asArray()->all();
		
		if (!empty($tags)) {
			echo \yii\helpers\BaseJson::encode($tags);
			Yii::$app->end();
		}
	}

	public function actionChangeAbout()
	{
		if (empty($this->user))
			throw new \yii\web\NotFoundHttpException();

		$this->user->about = $this->_post['about'];
		$this->user->save();

		echo \yii\helpers\BaseJson::encode([]);
		Yii::$app->end();
	}

	public function actionSendComment()
	{
		if (empty($this->user) || empty($this->_post['id']) && empty($this->_post['message']))
			throw new \yii\web\NotFoundHttpException();
		
		$comment = new UserComment();
		$comment->userId = $this->user->id;
		$comment->postId = $this->_post['id'];
		$comment->message = $this->_post['UserComment']['message'];
		if ($comment->validate()) {
			$comment->save();
			$comment->user->commentCount+=1;
			$comment->user->save();
			$comment->post->commentCount+=1;
			$comment->post->save();
		}
		
		echo \yii\helpers\BaseJson::encode($comment->getErrors());
		Yii::$app->end();
	}

	public function actionSendCommentReply()
	{
		if (empty($this->user) || empty($this->_post['id']) && empty($this->_post['message']))
			throw new \yii\web\NotFoundHttpException();
		
		$reply = new CommentReply();
		$reply->userId = $this->user->id;
		$reply->commentId = $this->_post['id'];
		$reply->message = $this->_post['CommentReply']['message'];
		if ($reply->validate())
			$reply->save();
		/*
			$reply->user->commentCount+=1;
			$reply->user->save();
			$reply->comment->image->commentCount+=1;
			$reply->comment->image->save();
		*/
		
		echo \yii\helpers\BaseJson::encode($reply->getErrors());
		Yii::$app->end();
	}
 
	public function actionFavoryte()
	{
		if (empty($this->user) || empty($this->_post['id']))
			throw new \yii\web\NotFoundHttpException();
		
		$favorites = $this->session->get('favorites');
		if (empty($favorites))
			$favorites = [];
		if (!in_array($this->_post['id'], $favorites)) {
			$model = new UserFavorite();
			$model->userId = $this->user->id;
			$model->postId = $this->_post['id'];
			$model->save();
			$favorites[] = $this->_post['id'];
			$this->session->set('favorites', $favorites);
			echo \yii\helpers\BaseJson::encode([]);
		}
		Yii::$app->end();
	}

	public function actionLike()
	{
        if (\Yii::$app->user->isGuest) {
            echo \yii\helpers\BaseJson::encode(['guest' => true]);
            Yii::$app->end();
        }
        
		if (empty($this->user) || empty($this->_post['id']) && empty($this->_post['type']))
			throw new \yii\web\NotFoundHttpException();

		$likes = $this->session->get('likePost');
		if (empty($likes))
			$likes = [];

		//$type = array_search($this->_post['id'], $likes)
		
		if (
		//Нашол лайк но его тип не равен запрашиваемому.
			(!empty($likes[$this->_post['id']]) && $likes[$this->_post['id']] != $this->_post['type']) ||
		// Не нашол лайк.
			empty($likes[$this->_post['id']])
		) {
			$post = UserPost::findOne($this->_post['id']);
			if (empty($post))
				Yii::$app->end();
			
			$post->likeCount = ($this->_post['type'] == 'inc') ? $post->likeCount + 1 : $post->likeCount - 1;
			$post->save();
			// Не нашол лайк.
			if (empty($likes[$this->_post['id']])) {
				$likes[$this->_post['id']] = $this->_post['type'];
				$this->user->likeCount+= 1;
			}
			//Нашол лайк но его тип не равен запрашиваемому.
			if (!empty($likes[$this->_post['id']]) && $likes[$this->_post['id']] != $this->_post['type']) {
				unset($likes[$this->_post['id']]);
				$this->user->likeCount-= 1;
			}
			
			$this->session->set('likePost', $likes);
			$this->user->likePost = json_encode($likes);
			$this->user->save();
			echo \yii\helpers\BaseJson::encode(['likeCount' => $post->likeCount]);
		}
		/*
		if ((!in_array($this->_post['id'], $likes) && $this->_post['type'] == 'inc')|| (in_array($this->_post['id'], $likes) && $this->_post['type'] == 'dec')) {
			$post = UserPost::findOne($this->_post['id']);
			if (empty($post))
				throw new \yii\web\NotFoundHttpException();
			
			$post->likeCount = ($this->_post['type'] == 'inc') ? $post->likeCount + 1 : $post->likeCount - 1;
			$post->save();
			if ($this->_post['type'] == 'inc') {
				$likes[] = $this->_post['id'];
				$this->user->likeCount+= 1;
			} else {
				$key = array_search($this->_post['id'], $likes);
				$this->user->likeCount-= 1;
				unset($likes[$key]);
			}

			$this->session->set('likePost', $likes);
			$this->user->likePost = json_encode($likes);
			$this->user->save();
			echo \yii\helpers\BaseJson::encode(['likeCount' => $post->likeCount]);
		}
*/
		Yii::$app->end();
	}

	public function actionShare()
	{
		$postModel = new UserPost();
		$postModel->userId = $this->user->id;
		if ($postModel->load($this->_post) && $postModel->validate()) {
            $mature = !empty($this->_post['UserPost']['mature']) ? 1 : 0;
			$postModel->url = UserAlbum::generateUrl();
			$postModel->language = \Yii::$app->language;
			$postModel->mature = $mature;
            $postModel->active = true;
			$postModel->save();
			$mature = !empty($this->_post['UserPost']['mature']) ? 1 : 0;
			
			if (!empty($this->_post['UserAlbum']['id'])) {
				$album = UserAlbum::findOne($_POST['UserAlbum']['id']);
				if (empty($album))
					throw new \yii\web\NotFoundHttpException();
				if ($album->load(Yii::$app->request->post()) && $album->validate()) {
					$album->mature = $mature;
					$album->active = true;
					$album->save();
				}
				unset($this->_post['UserAlbum']);
			}

			unset($this->_post['_csrf']);
			if (!empty($this->_post['UserPost']['tags'])) {
				$tagsPost = $this->_post['UserPost']['tags'];
			}
            unset($this->_post['UserPost']);

			foreach ($this->_post as $key => $post) {
                $id = (int) $post["UserImage"]["id"];

				$image = UserImage::findOne($id);

				if ($image->load($post) && $image->validate()) {
					if (!empty($album)&& empty($album->cover)) {
						$album->cover = $image->thumb90;
						$album->save();
					}
					$image->active = 1;
					$image->mature = $mature;
					$image->lastUpdate = date('Y-m-d H:i:s', time());
					$image->save();

					$imageRelation = new PostImage();
					$imageRelation->postId = $postModel->id;
					$imageRelation->imageId = $image->id;
					$imageRelation->save();
				}
			}

			if (!empty($tagsPost)) {
				foreach ($tagsPost as $tag) {
					$Tags = Tags::find()->where(['title' => $tag])->one();
					if (empty($Tags)) {
						$Tags = new Tags();
						$Tags->title = $tag;
						$Tags->count = count($this->_post);
						$Tags->save();
					} else {
						$Tags->count+= count($this->_post);
					}
					
					$relation = new PostTags();
					$relation->tagId = $Tags->id;
					$relation->postId = $postModel->id;
					$relation->save();
				}
			}
			$this->session->remove('initial');
			$this->session->remove('images');
			echo \yii\helpers\BaseJson::encode(['post' => $postModel->url]);
			Yii::$app->end();
		}
		echo \yii\helpers\BaseJson::encode($postModel->getErrors());

		Yii::$app->end();
	}

    public function actionRemoveImages()
    {
        if (empty($this->user))
            throw new \yii\web\NotFoundHttpException();

        if (!empty($this->_post['images'])) {
            foreach ($this->_post['images'] as $imageId) {
                //@todo Включить удаление картинок.
                $image = UserImage::findOne($imageId);
                UserImage::remove($image);
            }
        }
        echo \yii\helpers\BaseJson::encode($this->_post['images']);

        Yii::$app->end();
    }

    public function actionRenderAlbumImages()
    {
        $response = false;
        if (!empty($this->_post['albumId'])) {
            $album = UserAlbum::findOne($this->_post['albumId']);

            $images = $album->imagesTimeAsc;
            if ($this->_post['order_value'] == 'name') {
                if ($this->_post['order_type'] == 'asc') {
                    $images = $album->imagesNameAsc;
                } elseif ($this->_post['order_type'] == 'desc') {
                    $images = $album->imagesNameDesc;
                }
            } elseif($this->_post['order_value'] == 'time') {
                if ($this->_post['order_type'] == 'asc') {
                    $images = $album->imagesTimeAsc;
                } elseif ($this->_post['order_type'] == 'desc') {
                    $images = $album->imagesTimeDesc;
                }
            }
            $response =  $this->renderPartial('albumImages', [
                    'images' => $images,
                ]);
        } elseif ($this->_post['albumId'] == 0) {
             $model = UserImage::find()
                ->joinWith('albumImages', false)
                ->select('album_image.albumId, user_image.*')
                ->andWhere('album_image.albumId IS NULL');

            if ($this->_post['order_value'] == 'name') {
                if ($this->_post['order_type'] == 'asc') {
                    $model->orderBy(['user_image.name' => 'asc']);
                } elseif ($this->_post['order_type'] == 'desc') {
                    $model->orderBy(['user_image.name' => 'desc']);
                }
            } elseif($this->_post['order_value'] == 'time') {
                if ($this->_post['order_type'] == 'asc') {
                    $model->orderBy(['user_image.date' => 'asc']);
                } elseif ($this->_post['order_type'] == 'desc') {
                    $model->orderBy(['user_image.date' => 'desc']);
                }
            }

            $nonAlbumImages = $model->all();
            $response =  $this->renderPartial('albumImages', [
                    'images' => $nonAlbumImages,
                ]);
        }

        echo \yii\helpers\BaseJson::encode(['html' => $response]);

        Yii::$app->end();
    }

    public function actionSortAlbums()
    {
        if (!empty($this->_post['item'])) {
            foreach ($_POST['item'] as $key => $item) {
                $model = UserAlbum::findOne($item);
                $model->sort = $key + 1;
                $model->save();
            }
            echo \yii\helpers\BaseJson::encode([]);
            Yii::$app->end();
        }
    }

    public function actionSortImages()
    {
        if (!empty($this->_post['item'])) {
            foreach ($_POST['item'] as $key => $item) {
                $model = UserImage::findOne($item);
                $model->sort = $key + 1;
                $model->save();
            }
            echo \yii\helpers\BaseJson::encode([]);
            Yii::$app->end();
        }
    }

    public function actionNewAlbum()
    {
        $model = new UserAlbum();
        $model->userId = $this->user->id;
        if ($model->load($this->_post) && $model->validate()) {
            $model->save();
        }

        echo \yii\helpers\BaseJson::encode([$model->getErrors()]);
        Yii::$app->end();
    }

    public function actionChangeAlbum()
    {
        $model = UserAlbum::find()->where(['id' => $_POST['UserAlbum']['id']])->one();
        if (empty($model))
            throw new \yii\web\NotFoundHttpException();


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            echo \yii\helpers\BaseJson::encode([]);
            Yii::$app->end();
        }
    }

    public function actionUpload()
    {
        $is = @getimagesize($_FILES['image']['tmp_name']);
        if (!$is)
            Yii::$app->end();

        $name = UserImage::generateUrl();
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        if ($this->_post['resize'] == 'auto') {
            $original = UserImage::saveImage($_FILES['image']['tmp_name'], 'original', $name . '.' . $extension);
        } else {
            $original = UserImage::saveCustomImage($_FILES['image']['tmp_name'], 'original', $name . '.' . $extension, $this->_post['resize']);
        }

        $thumb160 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb160', $name . '160.' . $extension);

        $thumb90 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb90', $name . '90.' . $extension);

        $model = new UserImage();
        $model->userId = $this->user->id;
        $model->url = $name;
        $model->language = Yii::$app->language;
        $model->name = $_FILES['image']['name'];
        $model->original = $original;
        $model->thumb160 = $thumb160;
        $model->thumb90 = $thumb90;
        $model->save();

        $model->user->imageCount += 1;
        if (!empty($this->_post['albumId'])) {
            $albumImage = new AlbumImage();
            $albumImage->albumId = $this->_post['albumId'];
            $albumImage->imageId = $model->id;
            $albumImage->save();
        }

        echo \yii\helpers\BaseJson::encode(['imageUrl' => $model->url]);
        Yii::$app->end();
    }

    public function actionSaveImage()
    {
        $src = explode('?', $this->_post['src']);
        $src = $src[0];
        $image = UserImage::find()->where(['original' => $src])->one();
        if (empty($image)) {
            throw new \yii\web\NotFoundHttpException();
        }

        UserImage::replace($image, $_FILES['image']);
        echo \yii\helpers\BaseJson::encode(['src' => $src]);
        Yii::$app->end();
    }

    public function actionResizeImage()
    {
        $src = explode('?', $this->_post['src']);
        $src = $src[0];
        $image = UserImage::find()->where(['original' => $src])->one();
        if (empty($image)) {
            throw new \yii\web\NotFoundHttpException();
        }

        UserImage::resize($image, $this->_post['width'], $this->_post['height']);
        echo \yii\helpers\BaseJson::encode(['src' => $src]);
        Yii::$app->end();
    }

    public function actionMoveImages()
    {
        if (!empty($this->_post['images']) && !empty($this->_post['albumId'])) {
            foreach ($this->_post['images'] as $imageId) {
                $image = UserImage::findOne($imageId);
                if ($image->post) {
                    echo \yii\helpers\BaseJson::encode(['error' => \Yii::t('app', 'Cannot move uploaded image')]);
                    Yii::$app->end();
                }

                // Если картинка уже в альбоме
                if ($image->album) {
                    $relation = AlbumImage::find()
                        ->where(['imageId' => $imageId])
                        ->where(['albumId' => $image->album->id])
                        ->one();
                    if ($relation) {
                        $relation->delete();
                    }
                }

                if ($this->_post['albumId'] != 0) {
                    $relation = new AlbumImage();
                    $relation->imageId = $imageId;
                    $relation->albumId = $this->_post['albumId'];
                    $relation->save();
                }
            }
        }
    }
}
