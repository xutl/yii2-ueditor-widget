<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\umeditor;

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class UEditor
 * @package xutl\editormd
 */
class UEditor extends InputWidget
{

    /**
     * @var array the options for the Bootstrap date time picker JS plugin.
     * Please refer to the Bootstrap date time picker plugin Web page for possible options.
     * @see https://github.com/pandao/editor.md
     */
    public $clientOptions = [];

    /**
     * {@inheritDoc}
     * @see \Leaps\Base\Object::init()
     */
    public function init()
    {
        parent::init();
        if (!isset ($this->options ['id'])) {
            $this->options ['id'] = $this->getId();
        }

        $this->clientOptions = array_merge([
            'width' => "100%",
            'height' => 380,
            'watch' => false,
            'autoFocus' => false
        ], $this->clientOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $textarea = Html::activeTextArea($this->model, $this->attribute, $this->options);
        } else {
            $textarea = Html::textArea($this->name, $this->value, $this->options);
        }
        $assets = MarkdownEditorAsset::register($this->view);
        $this->clientOptions['path'] = new JsExpression("'{$assets->baseUrl}/lib/'");
        $options = empty ($this->clientOptions) ? '' : Json::htmlEncode($this->clientOptions);
        $varName = Inflector::classify('editor' . $this->id);
        $this->view->registerJs("var editor{$this->id} = new editormd(\"{$varName}\", {$options});");
        echo '<div id="' . $varName . '">' . $textarea . '</div>';
    }
}