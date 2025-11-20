<?php
namespace bagesoft\models\form\maintain;

class BtMgrForm extends \yii\base\Model
{
    public $steps;
    public $typeid;
    public $remind_time;

    // 添加规则验证

    public function rules()
    {
        return [
            [['steps', 'typeid'], 'integer'],
            [['remind_time'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'typeid' => '维护类型',
            'steps' => '项目阶段',
            'remind_time' => '下次跟进提醒',
        ];
    }

}
