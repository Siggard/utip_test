<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $users_id
 * @property int $files_id
 * @property timestamp $created_at
 */
class UserFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%users_files}}';
    }

    public function setFileId($id)
    {
        $this->files_id = $id;
        return $this;
    }

    public function setUserId()
    {
        $this->users_id = \Yii::$app->user->id;
        return $this;
    }

    public static function findByIds($fileId)
    {
        return static::find()
            ->where(['files_id' => $fileId])
            ->andWhere(['users_id' => \Yii::$app->user->id])
            ->one();
    }
}