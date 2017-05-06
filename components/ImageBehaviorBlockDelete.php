<?php

namespace app\components;

use Yii;
use maxmirazh33\image\Behavior;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\base\InvalidCallException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * Поведение в котором отключено удаление изображений у удаляемых объектов
 * @see maxmirazh33\image\Behavior
 */
class ImageBehaviorBlockDelete extends Behavior {
    
    /**
     * function for EVENT_BEFORE_DELETE
     */
    public function beforeDelete() {
        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttribute($attr, $options);
            // blocked
//            $this->deleteFiles($attr);
        }
    }
    
    /**
     * function for EVENT_BEFORE_INSERT and EVENT_BEFORE_UPDATE
     */
    public function beforeSave()
    {
        /* @var $model ActiveRecord */
        $model = $this->owner;
        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttribute($attr, $options);
            if ($file = UploadedFile::getInstance($model, $attr)) {
                $this->createDirIfNotExists($attr);
                // blocked
//                if (!$model->isNewRecord) {
//                    $this->deleteFiles($attr);
//                }
                
                $fileName = uniqid() . '.' . $file->extension;
                if ($this->needCrop($attr)) {
                    $coords = $this->getCoords($attr);
                    if ($coords === false) {
                        throw new InvalidCallException();
                    }
                    $image = $this->crop($file, $coords, $options);
                    $image->save($this->getSavePath($attr) . $fileName);
                } else {
                    $image = $this->processImage($file->tempName, $options);
                    $image->save($this->getSavePath($attr) . $fileName);
                }
                $model->{$attr} = $fileName;

                if ($this->issetThumbnails($attr)) {
                    $thumbnails = $this->attributes[$attr]['thumbnails'];
                    foreach ($thumbnails as $name => $options) {
                        $this->ensureAttribute($name, $options);
                        $tmbFileName = $name . '_' . $fileName;
                        $image = $this->processImage($this->getSavePath($attr) . $fileName, $options);
                        $image->save($this->getSavePath($attr) . $tmbFileName);
                    }
                }
            } elseif (isset($model->oldAttributes[$attr])) {
                $model->{$attr} = $model->oldAttributes[$attr];
            }
        }
    }
    
    /**
     * @param string $attr name of attribute
     */
    private function createDirIfNotExists($attr)
    {
        $dir = $this->getSavePath($attr);
        if (!is_dir($dir)) {
            FileHelper::createDirectory($dir);
        }
    }
    
    /**
     * @param string $attr name of attribute
     * @param bool|string $tmb name of thumbnail
     * @return string save path
     */
    private function getSavePath($attr, $tmb = false)
    {
        if ($tmb !== false) {
            if (isset($this->attributes[$attr]['thumbnails'][$tmb]['savePathAlias'])) {
                return rtrim(Yii::getAlias($this->attributes[$attr]['thumbnails'][$tmb]['savePathAlias']), '\/') . DIRECTORY_SEPARATOR;
            }
        }

        if (isset($this->attributes[$attr]['savePathAlias'])) {
            return rtrim(Yii::getAlias($this->attributes[$attr]['savePathAlias']), '\/') . DIRECTORY_SEPARATOR;
        }
        if (isset($this->savePathAlias)) {
            return rtrim(Yii::getAlias($this->savePathAlias), '\/') . DIRECTORY_SEPARATOR;
        }

        if (isset(Yii::$aliases['@frontend'])) {
            return Yii::getAlias('@frontend/web/images/' . $this->getShortClassName($this->owner)) . DIRECTORY_SEPARATOR;
        }

        return Yii::getAlias('@app/web/images/' . $this->getShortClassName($this->owner)) . DIRECTORY_SEPARATOR;
    }
    
    /**
     * @param string $original path to original image
     * @param array $options with width and height
     * @return \Imagine\Image\ImageInterface
     */
    private function processImage($original, $options)
    {
        list($imageWidth, $imageHeight) = getimagesize($original);
        $image = Image::getImagine()->open($original);
        if (isset($options['width']) && !isset($options['height'])) {
            $width = $options['width'];
            $height = $options['width'] * $imageHeight / $imageWidth;
            $image->resize(new Box($width, $height));
        } elseif (!isset($options['width']) && isset($options['height'])) {
            $width = $options['height'] * $imageWidth / $imageHeight;
            $height = $options['height'];
            $image->resize(new Box($width, $height));
        } elseif (isset($options['width']) && isset($options['height'])) {
            $width = $options['width'];
            $height = $options['height'];
            if ($width / $height > $imageWidth / $imageHeight) {
                $resizeHeight = $width * $imageHeight / $imageWidth;
                $image->resize(new Box($width, $resizeHeight))
                    ->crop(new Point(0, ($resizeHeight - $height) / 2), new Box($width, $height));
            } else {
                $resizeWidth = $height * $imageWidth / $imageHeight;
                $image->resize(new Box($resizeWidth, $height))
                    ->crop(new Point(($resizeWidth - $width) / 2, 0), new Box($width, $height));
            }
        }

        return $image;
    }

    /**
     * @param string $attr name of attribute
     * @return bool isset thumbnails or not
     */
    private function issetThumbnails($attr)
    {
        return isset($this->attributes[$attr]['thumbnails']) && is_array($this->attributes[$attr]['thumbnails']);
    }
    
}
