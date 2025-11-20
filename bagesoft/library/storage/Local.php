<?php
/**
 * 本地上传
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     (c) 2007-2099 cookman.cn. All rights reserved.
 * @package       BageCMS.Library
 * @license       http://www.cookman.cn/license
 *
 */
namespace bagesoft\library\storage;

use bagesoft\library\Image;
use Yii;

class Local extends \bagesoft\library\storage\Base
{
    //上传完成文件
    public $upload = null;
    //上传数量 one/multi
    public $uploadNum = 0;

    /**
     * 上传
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function upload($name = '')
    {
        $root = Yii::getAlias('@resource') . '/uploads';
        $files = parent::fileParse($name);
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                $fileinfo = parent::config($file)->move($root);
                if ($fileinfo) {
                    $this->upload[] = $this->_attrMap($fileinfo);
                }
            }
            $this->uploadNum = 'multi';
        } elseif (is_object($files)) {
            $fileinfo = parent::config($files)->move($root);
            if (false == $fileinfo) {
                return $files->getError();
            } else {
                $this->upload = $this->_attrMap($fileinfo);
            }
            $this->uploadNum = 'one';
        }
        return $this->upload;
    }

    /**
     * 属性
     * @param  [type] $file [description]
     * @return [type]       [description]
     *
     */
    private function _attrMap($file)
    {
        $ltrim = './';
        $fileFmt = str_replace('\\', '/', ltrim($file->getPathname(), $ltrim));

        $result = [
            'name' => $file->getInfo('name'),
            'type' => $file->getMime(),
            'size' => $file->getSize(),
            'ext' => $file->getExtension(),
            'savename' => $file->getFilename(),
            'savepath' => str_replace('\\', '/', ltrim($file->getPath(), $ltrim)),
            'file' => $fileFmt,
            'fileurl' => Yii::$app->params['res.url'] . '/uploads/' . $file->getSavename(),
        ];

        //Utils::dump($result);

        //缩略图
        if ($this->_config['thumb'] && in_array($file->getExtension(), ['gif', 'jpg', 'jpeg', 'bmp', 'png'])) {
            $thumbSize = $this->_config['thumbSize'] ? explode('x', $this->_config['thumbSize']) : [300, 300];
            $thumbEx = explode('.', $file->getBasename());

            $thumbName = $thumbEx[0] . '_s.' . $thumbEx[1];
            $thumbFile = str_replace($file->getBasename(), $thumbName, $file->getRealPath());
            $image = Image::open($file)->thumb($thumbSize[0], $thumbSize[1])->save($thumbFile, null, $this->_config['quality']);
            $result['thumb'] = str_replace($result['savename'], $thumbName, $result['file']);
            $result['thumburl'] = str_replace($result['savename'], $thumbName, $result['fileurl']);
        }
        //水印
        if ($this->_config['water']) {
            Image::open($file)->water($this->_config['waterFile'], $this->_config['waterPos'], $this->_config['waterAlpha'])->save($file->getRealPath(), null, $this->_config['quality']);
        }
        return $result;
    }

}
