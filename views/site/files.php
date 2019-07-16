<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\FileForm */

use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Files';
$this->params['breadcrumbs'][] = $this->title;

$id = 'fileform-image-fileupload';
$this->registerJsFile(Yii::getAlias('@web') . '/js/script.js', ['position' => \yii\web\View::POS_HEAD] );
?>

    <?= Html::tag('p', Html::input('test', 'search', null, ['id' => 'search', 'class' => 'form-control', 'onkeyup' => "searchFiles('$id', this.value)"])); ?>

<?
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