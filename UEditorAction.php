<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\ueditor;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\helpers\FileHelper;

/**
 * UEditorAction class file.
 */
class UEditorAction extends Action
{
    /**
     * @var string file input name.
     */
    public $inputName = 'upfile';

    /**
     * @var string the directory to store temporary files during conversion. You may use path alias here.
     * If not set, it will use the "plupload" subdirectory under the application runtime path.
     */
    public $tempPath = '@runtime/ueditor';

    /**
     * @var integer the permission to be set for newly created cache files.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     */
    public $fileMode;

    /**
     * @var integer the permission to be set for newly created directories.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner and group,
     * but read-only for other users.
     */
    public $dirMode = 0775;

    /**
     * @var callable success callback with signature: `function($filename, $params)`
     */
    public $onComplete;

    /**
     * Initializes the action and ensures the temp path exists.
     */
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->tempPath = Yii::getAlias($this->tempPath);
        if (!is_dir($this->tempPath)) {
            FileHelper::createDirectory($this->tempPath, $this->dirMode, true);
        }
    }

    /**
     * Runs the action.
     * This method displays the view requested by the user.
     * @throws HttpException if the view is invalid
     */
    public function run()
    {
        $uploadedFile = UploadedFile::getInstanceByName($this->inputName);
        $params = Yii::$app->request->getBodyParams();
        $filename = $this->getUnusedPath($this->tempPath . DIRECTORY_SEPARATOR . $uploadedFile->name);
        $isUploadComplete = ChunkUploader::process($uploadedFile, $filename);
        if ($isUploadComplete) {
            if ($this->onComplete) {
                return call_user_func($this->onComplete, $filename, $params);
            } else {
                return [
                    'filename' => $filename,
                    'params' => $params,
                ];
            }
        }
        return null;
    }
}