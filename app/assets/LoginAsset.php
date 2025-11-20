<?php
namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    

    /**
     * 全局CSS
     * @var [type]
     */
    public $css = [
        'static/plugins/fontawesome-free/css/all.min.css',
        'static/app/css/adminlte.min.css',
        'static/app/css/site.css',
    ];

    /**
     * 全局JS
     * @var [type]
     */
    public $js = [
        //['static/plugins/jquery/jquery.min.js', 'position' => \yii\web\View::POS_HEAD],
        ['static/plugins/bootstrap/4.5.3/js/bootstrap.bundle.min.js', 'position' => \yii\web\View::POS_HEAD],
        'static/app/js/adminlte.min.js',
        'static/app/js/app.js',
    ];

    /**
     * 依赖
     * @var [type]
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

    /**
     * 初始化
     * @return [type] [description]
     */
    public function init()
    {
        parent::init();

        $this->baseUrl = Yii::$app->params['res.url'];
        if (is_array($this->css)) {
            $cssArr = $this->css;
            $this->css = [];
            foreach ($cssArr as $css) {
                if (is_array($css)) {
                    $this->css[] = [$css[0] . '?v=' . Yii::$app->params['res.ver'], 'position' => $css['position']];
                } else {
                    $this->css[] = $css . '?v=' . Yii::$app->params['res.ver'];
                }
            }
        }
        if (is_array($this->js)) {
            $jsArr = $this->js;
            $this->js = [];
            foreach ($jsArr as $js) {
                if (is_array($js)) {
                    $this->js[] = [$js[0] . '?v=' . Yii::$app->params['res.ver'], 'position' => $js['position']];
                } else {
                    $this->js[] = $js . '?v=' . Yii::$app->params['res.ver'];
                }
            }
        }
    }

    /**
     * 自定义script
     * @param object $view    视图
     * @param string $cssfile 文件
     */
    public static function addScript($view, $jsfile)
    {
        $view->registerJsFile($jsfile . '?v=' . Yii::$app->params['res.ver'], [AppAsset::class, 'depends' => 'app\assets\AppAsset']);
    }

    /**
     * 自定义css
     * @param object $view    视图
     * @param string $cssfile 文件
     */
    public static function addCss($view, $cssfile)
    {
        $view->registerCssFile($cssfile . '?v=' . Yii::$app->params['res.ver'], [AppAsset::class, 'depends' => 'app\assets\AppAsset']);
    }
}
