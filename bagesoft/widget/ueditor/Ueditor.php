<?php
/**
 * 百度编辑器
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Widget
 * @license       http://www.cookman.cn/license
 */

namespace bagesoft\widget\ueditor;

use bagesoft\widget\ueditor\UeditorAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

class Ueditor extends InputWidget
{
    /**
     * UE初始化目标ID
     * @var string
     */
    public $id;
    /**
     * UE默认值
     * @var string
     */
    public $value;
    /**
     * 表单字段名
     * @var string
     */
    public $name;
    /**
     * Tag/ScriptTag HtmlStyle
     * @var style
     */
    public $style;

    public $route;

    /**
     * 是否渲染Tag
     * @var string/bool
     */
    public $renderTag = true;
    /**
     * UE 参数
     * @var array
     */
    public $jsOptions = [];
    /**
     * UE.ready(function(){
     * //nothing
     * //alert('editor ready');
     * });
     * @var string
     */

    //默认参数
    private $_options;

    public $readyEvent;
    /**
     * 初始化widget
     */
    public function init()
    {
        parent::init();
        if (empty($this->id)) {
            $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        if (empty($this->name)) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }

        $attributeName = $this->attribute;
        if (empty($this->value) && $this->hasModel()) {
            $this->value = $this->model->$attributeName;
        }

        $this->_options = [
            'serverUrl' => Url::to([$this->route]),
            'initialFrameWidth' => '100%',
            'maximumWords' => 1000000,
            'pageBreakTag' => '~bagepagetag~',
            'initialFrameHeight' => '200',
            'lang' => (strtolower(Yii::$app->language) == 'en-us') ? 'en' : 'zh-cn',
            'autoHeightEnabled' => true, //是否自动长高，默认true
            'autoFloatEnabled' => false, //是否保持toolbar的位置不动，默认true
            'catchRemoteImageEnable' => false, //禁止抓取远程图片
        ];

        $this->jsOptions = ArrayHelper::merge($this->_options, $this->jsOptions);
    }

    /**
     * 渲染小部件
     */
    public function run()
    {
        UeditorAsset::register($this->view);
        $this->registerScripts();
        if ($this->renderTag) {
            echo $this->renderTag();
        }
        //保留原始表单功能
        /* if ($this->hasModel()) {
    return Html::activeTextarea($this->model, $this->attribute, ['id' => $this->id]);
    } else {
    return Html::textarea($this->id, $this->value, ['id' => $this->id]);
    } */
    }

    /**
     * 渲染标签
     * @return string
     */
    public function renderTag()
    {
        $id = $this->id;
        $content = $this->value;
        $name = $this->name;
        $style = $this->style ? " style=\"{$this->style}\"" : '';
        return <<<EOF
<script id="{$id}" name="{$name}"$style type="text/plain">{$content}</script>
EOF;
    }

    /**
     * 注册脚本
     */
    public function registerScripts()
    {
        $jsonOptions = Json::encode($this->jsOptions);
        $script = "UE.getEditor('{$this->id}', " . $jsonOptions . ")";
        if ($this->readyEvent) {
            $script .= ".ready(function(){{$this->readyEvent}})";
        }
        $script .= ';';
        $this->view->registerJs($script, View::POS_READY);
    }
}
