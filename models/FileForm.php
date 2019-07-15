<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileForm extends Model
{
    const SCENARIO_ADD = 'ADD';
    const SCENARIO_UPD = 'UPD';
    const SCENARIO_DEL = 'DEL';

    public $title;
    public $descr;
    public $image;

    public $ext;
    public $hash;
    private $size;

    public function rules()
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            [['image'], 'file', 'maxSize' => '104857600', 'extensions' => 'gif, jpg, jpeg, bmp, png', 'checkExtensionByMimeType' => false, 'maxFiles' => 10],
//            ['id', 'exist', 'targetClass' => File::class, 'targetAttribute' => 'id'],
            [['title', 'descr'], 'string']
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['title', 'descr', 'image'],
            self::SCENARIO_DEL => ['id'],
            self::SCENARIO_UPD => ['id', 'title', 'descr']
        ];
    }

    /**
     * @param UploadedFile $imageFile
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload(UploadedFile $imageFile)
    {
        $directory = \Yii::getAlias('@files');
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $this->hash = hash_file('md5', $imageFile->tempName);
            $this->ext = $imageFile->extension;
            $this->size = $imageFile->size;

            $oldFile = File::findByHash($this->hash);
            if ($oldFile) {
                $userFile = UserFile::findByIds($oldFile->id);
                if (!$userFile) {
                    $userFile = (new UserFile())
                        ->setFileId($oldFile->id)
                        ->setUserId();
                }

                return $userFile->save();
            } else {
                if ($imageFile->saveAs($this->getFullFilepath())) {
                    $file = new File();
                    $file->attributes = $this->getAttributes(['title', 'descr', 'hash', 'ext']);

                    if ($file->save()) {
                        return (new UserFile())
                            ->setFileId($file->id)
                            ->setUserId()
                            ->save();
                    }
                }
            }
        }

        return false;
    }

    public function getFilename()
    {
        return $this->hash . '.' . $this->ext;
    }

    public function getFullFilepath()
    {
        return \Yii::getAlias('@files') . '/' . $this->getFilename();
    }

    public function getFilepath()
    {
        return 'uploads/' . $this->getFilename();
    }

    public function getSize()
    {
        return $this->size;
    }
}