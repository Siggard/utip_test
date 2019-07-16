<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\FileForm */

use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Files';
$this->params['breadcrumbs'][] = $this->title;

//$test = new \dosamigos\fileupload\actions\FileListAction();
//$test->

echo FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
    'url' => ['site/upload', 'id' => \Yii::$app->user->id],
    'gallery' => false,
    'load' => true,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 104857600
    ],
    // ...
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]);