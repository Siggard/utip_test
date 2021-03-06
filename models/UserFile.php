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

    public static function findUserFileById($fileId)
    {
        return static::findOne([
            'files_id' => $fileId,
            'users_id' => \Yii::$app->user->id
        ]);
    }

    public function getFile()
    {
        return $this->hasOne(File::class, ['id' => 'files_id']);
    }

    public static function loadFiles($userId, $like)
    {
        return static::find()
            ->where(['users_id' => $userId])
            ->joinWith(['file' => function($query) use ($like) {
                if (!empty($like)) {
                    $query->where(['like', 'title', $like])->orWhere(['like', 'descr', $like]);
                }
            }])
            ->asArray()
            ->all();
    }
}