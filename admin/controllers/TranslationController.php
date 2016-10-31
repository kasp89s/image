<?php

namespace app\controllers;

use yii\helpers\BaseFileHelper;
use Yii;

class TranslationController extends AbstractController
{
    public $layout = 'main';

    public $title = 'Преводы';

    public $_filesDirectory = [
        'views', 'models', 'controllers', 'components', 'config'
    ];
    /**
     * Список номеров.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $translations = Yii::$app->request->post()['translations'];
            $this->rewriteTranslationsFile(realpath(__DIR__ . '/../../messages/ru/app.php'), $translations, true);
        } else {
            $translations = require_once(realpath(__DIR__ . '/../../messages/ru/app.php'));
        }

        return $this->render('index',
            [
                'title' => $this->title,
                'translations' => $translations,
            ]
        );
    }

    private function rewriteTranslationsFile($path, $all, $force = false)
    {
        $current = require_once($path);

        $php = "<?php return [\n";
        if ($force === false) {
            foreach ($all as $message) {
                if (!empty($current[$message])) {
                    $php.= "    \"{$message}\" => \"{$current[$message]}\",\n";
                } else {
                    $php.= "    \"{$message}\" => \"\",\n";
                }
            }
        } else {
            foreach ($all as $key => $message) {
                    $php.= "    \"{$key}\" => \"{$message}\",\n";
            }
        }

        $php.= "];";

        file_put_contents($path, $php);
    }

    /**
     * Редактировать.
     *
     * @return string
     */
    public function actionCreate()
    {
        $files = [];
        foreach ($this->_filesDirectory as $directory) {
            $find = \yii\helpers\FileHelper::findFiles(realpath(__DIR__ . '/../../' . $directory), ['only'=>['*.php']]);
            $files = array_merge($files, $find);
        }

        $messages = [];
        foreach ($files as $file) {
            $find = $this->extractMessages($file);
            $messages = array_merge_recursive($messages, $find);
        }
        $messages = array_unique($messages['app']);

        $this->rewriteTranslationsFile(realpath(__DIR__ . '/../../messages/ru/app.php'), $messages);

        return $this->redirect(Yii::$app->request->referrer);
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


    /**
     * Парсит файл возвращает массив сообщений, ключи категориии.
     *
     * Возвращает массив [ категория => [сообщения] ].
     *
     * @param string $fileName   Файл для парсинга.
     * @param string $translator Поисковая строка перевода.
     *
     * @return array
     */
    public function extractMessages($fileName, $translator = 'Yii::t')
    {
        $subject = file_get_contents($fileName);
        $messages = array();
        if (is_array($translator) === false) {
            $translator = array($translator);
        }

        foreach ($translator as $currentTranslator) {
            $pattern = '/\b' .
                $currentTranslator .
                '\s*\(\s*(\'[\w.\/]*?(?<!\.)\'|"[\w.]*?(?<!\.)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s';
            $countMatches = preg_match_all(
                $pattern,
                $subject,
                $matches,
                PREG_SET_ORDER
            );

            for ($i = 0; $i < $countMatches; ++$i) {
                if (($pos = strpos($matches[$i][1], '.')) !== false) {
                    $category = substr($matches[$i][1], $pos + 1, -1);
                } else {
                    $category = substr($matches[$i][1], 1, -1);
                }
                $message = $matches[$i][2];
                $messageUnquoted = $this->parseString($message);
                if ($messageUnquoted !== false) {
                    $messages[$category][] = $messageUnquoted;
                }

            }
        }
        return $messages;
    }

    /**
     * Парсит строку с помощью токенов, для обработки конкатенации.
     *
     * @param string $input Данные файла.
     *
     * @return string
     */
    public function parseString($input)
    {
        $tokenString = '';
        $tokens = token_get_all('<?php ' . $input . '?>');

        foreach ($tokens as $token) {
            switch ($token[0]) {
                case T_LNUMBER:
                    $tokenString .= $token[1];
                    break;
                case T_CONSTANT_ENCAPSED_STRING:
                    $tokenString .= stripcslashes(substr($token[1], 1, -1));
                    break;
            }
        }

        return $tokenString;
    }
}
