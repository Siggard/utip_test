<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $descr
 * @property string hash
 * @property string ext
 */
class File extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%files}}';
    }

    public function rules()
    {
        return [
            [['title', 'descr', 'hash', 'ext'], 'safe'],
        ];
    }

    public static function findByHash($hash)
    {
        return static::findOne(['hash' => $hash]);
    }
}