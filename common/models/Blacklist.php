<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "blacklist".
 *
 * @property int $id
 * @property string|null $domain
 *
 * @property string|null $list
 */

class Blacklist extends \yii\db\ActiveRecord
{
    public $list;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blacklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain'], 'string', 'max' => 255],
            [['domain'], 'unique'],
            [['list'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Домен',
        ];
    }

    public static function getDomains()
    {
        return ArrayHelper::getColumn(self::find()->asArray()->all(), 'domain');
    }
}
