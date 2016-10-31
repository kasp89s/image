<?php

namespace app\controllers;

use app\models\CommentReply;
use app\models\UserComment;
use Yii;
use yii\data\Pagination;
class CommentController extends AbstractController
{
    public $layout = 'main';

    public $title = 'Комментарии';

    /**
     * Список номеров.
     *
     * @return string
     */
    public function actionIndex()
    {
        // выполняем запрос
        $query = UserComment::find();
        // делаем копию выборки
        $countQuery = clone $query;
        // подключаем класс Pagination, выводим по 10 пунктов на страницу
        $commentsPages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 30]);
        // приводим параметры в ссылке к ЧПУ
        $commentsPages->pageSizeParam = false;
        $comments = $query->offset($commentsPages->offset)
            ->limit($commentsPages->limit)
            ->orderBy('id desc')
            ->all();

        // выполняем запрос
        $query = CommentReply::find();
        // делаем копию выборки
        $countQuery = clone $query;
        // подключаем класс Pagination, выводим по 10 пунктов на страницу
        $repliesPages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 30]);
        // приводим параметры в ссылке к ЧПУ
        $repliesPages->pageSizeParam = false;
        $replies = $query->offset($repliesPages->offset)
            ->limit($repliesPages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render('index',
            [
                'comments' => $comments,
                'replies' => $replies,
                'commentsPages' => $commentsPages,
                'repliesPages' => $repliesPages,
                'title' => $this->title,
            ]
        );
    }

    /**
     * Удалить.
     *
     * @param $id
     */
    public function actionRemoveComment($id)
    {
        $model = UserComment::findOne($id);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Удалить.
     *
     * @param $id
     */
    public function actionRemoveReply($id)
    {
        $model = CommentReply::findOne($id);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }
}
