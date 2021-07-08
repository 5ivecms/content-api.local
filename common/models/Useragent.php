<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "useragent".
 *
 * @property int $id
 * @property string|null $useragent
 */
class Useragent extends \yii\db\ActiveRecord
{
    public $list;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'useragent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['useragent'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'useragent' => 'Юзерагент',
            'list' => 'Список юзерагентов'
        ];
    }
}
