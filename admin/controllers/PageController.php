<?php

namespace app\controllers;

use app\models\Page;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use Yii;

class PageController extends AbstractController
{
    public $layout = 'main';

    public $title = 'Редакция статических материалов';

    /**
     * Список номеров.
     *
     * @return string
     */
    public function actionIndex()
    {
        $records = Page::find()->orderBy('id desc')->all();

        return $this->render('index',
            [
                'title' => $this->title,
                'records' => $records,
            ]
        );
    }

    /**
     * Редактировать.
     *
     * @return string
     */
    public function actionCreate()
    {
        $this->title = 'Добавить страницу';

        $model = new Page();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->save();
            Yii::$app->response->redirect(array('page/index'));
        }
        return $this->render('add', [
            'title' => $this->title,
            'model' => $model,
        ]);
    }

    /**
     * Редактировать.
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $this->title = 'Редактировать страницу';

        $model = Page::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->save();
            Yii::$app->response->redirect(array('page/index'));
        }
        return $this->render('edit', [
            'title' => $this->title,
            'model' => $model,
        ]);
    }

    /**
     * Удалить отель.
     *
     * @param $id
     */
    public function actionRemove($id)
    {
        $model = Page::findOne($id);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Утвердить.
     *
     * @param $id
     */
    public function actionApprove($id)
    {
        $model = Page::findOne($id);
        $model->active = 1;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }
}
