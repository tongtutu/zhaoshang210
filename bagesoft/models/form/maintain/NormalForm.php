<?php
namespace bagesoft\models\form\maintain;

class NormalForm extends \yii\base\Model
{

    public $typeid;
    public $content;
    public $steps;
    public $bt_demand;
    public $remind_time;
    public $prove_file;

    // 添加规则验证

    public function rules()
    {
        return [
            [['content', 'prove_file', 'steps'], 'required'],
            [['steps', 'bt_demand', 'typeid'], 'integer'],
            [['content', 'prove_file'], 'string'],
            [['prove_file'], 'string', 'max' => 200],
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
            'content' => '情况描述',
            'steps' => '项目阶段',
            'bt_demand' => '招投标需求',
            'remind_time' => '下次跟进提醒',
            'prove_file' => '证明资料',
        ];
    }


}
