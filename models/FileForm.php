<?php

namespace app\models;

use \yii\imagine\Image;
use Imagine\Image\Box;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileForm extends Model
{
    const SCENARIO_ADD = 'ADD';
    const SCENARIO_UPD = 'UPD';
    const SCENARIO_DEL = 'DEL';
    const SCENARIO_LOAD = 'LOAD';

    const SIZE_THUMBNAIL = 64;

    public $fileId;
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
            [['image'], 'file', 'skipOnEmpty' => false, 'maxSize' => '104857600', 'extensions' => 'gif, jpg, jpeg, bmp, png', 'checkExtensionByMimeType' => false, 'maxFiles' => 10],
//            ['fileId', 'exist', 'targetClass' => File::class, 'targetAttribute' => 'id'],
            [['title', 'descr'], 'string']
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['title', 'descr', 'image'],
            self::SCENARIO_DEL => ['fileId'],
            self::SCENARIO_UPD => ['fileId', 'title', 'descr'],
            self::SCENARIO_LOAD => ['fileId', 'title', 'descr', 'ext', 'hash']
        ];
    }

    /**
     * @param UploadedFile $imageFile
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload(UploadedFile $imageFile)
    {
        $this->image = $imageFile;
        if (!in_array(mb_strtolower($imageFile->extension), ['gif', 'jpg', 'jpeg', 'bmp', 'png'])) {
            throw new \Exception('File is not supported');
        }

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
                $this->fileId = $oldFile->id;

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

                    Image::thumbnail($this->getFullFilepath(), self::SIZE_THUMBNAIL, self::SIZE_THUMBNAIL)
                        ->resize(new Box(self::SIZE_THUMBNAIL, self::SIZE_THUMBNAIL))
                        ->save($this->getThumbFullFilepath());

                    if ($file->save()) {
                        $this->fileId = $file->id;

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

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete()
    {
        $userFile = UserFile::findUserFileById($this->fileId);
        if ($userFile) {
            $others = UserFile::find()
                ->where('users_id != :id', ['id' => \Yii::$app->user->id])
                ->andWhere(['files_id' => $this->fileId])
                ->count();

            // if not links
            if (!$others) {
                $file = $userFile->file;
                $this->hash = $file->hash;
                $this->ext = $file->ext;

                (File::findOne($this->fileId))->delete();

                if (is_file($this->getFullFilepath())) {
                    unlink($this->getFullFilepath());
                    unlink($this->getThumbFullFilepath());
                }
            }
        }
    }

    /**
     * @param $id
     * @param $like
     * @return array
     */
    public function loadFiles($id, $like)
    {
        $files = [];

        $records = UserFile::loadFiles($id, $like);
        foreach ($records as $record) {
            if ($record['file'] === null) {
                continue;
            }

            $this->setScenario(FileForm::SCENARIO_LOAD);
            $this->attributes = array_merge($record['file'], ['fileId' => $record['files_id']]);
            $files[] = $this->getFileData();
        }

        return $files;
    }

    public function getFilename()
    {
        return $this->hash . '.' . $this->ext;
    }

    public function getFullFilepath()
    {
        return \Yii::getAlias('@files') . '/' . $this->getFilename();
    }

    public function getThumbFullFilepath()
    {
        return \Yii::getAlias('@files') . '/' . self::SIZE_THUMBNAIL . '_' . $this->getFilename();
    }

    public function getFilepath()
    {
        return 'uploads/' . $this->getFilename();
    }

    public function getThumbFilepath()
    {
        return 'uploads/' . self::SIZE_THUMBNAIL  . '_' . $this->getFilename();
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getId()
    {
        return $this->fileId;
    }

    public function getFileData()
    {
        return [
            'name' => $this->getFilename(),
            'size' => 0,
            'url' => $this->getFilepath(),
            'thumbnailUrl' => $this->getThumbFilepath(),
            'deleteUrl' => 'delete?id=' . $this->getId(),
            'deleteType' => 'POST',
            'title' => $this->title,
            'descr' => $this->descr
        ];
    }
}