<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whoogle".
 *
 * @property int $id
 * @property string|null $host
 * @property int|null $is_blocked
 * @property array $list
 */
class Whoogle extends \yii\db\ActiveRecord
{
    const IS_BLOCKED = 1;
    const IS_NOT_BLOCKED = 0;

    public $list;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'whoogle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_blocked'], 'integer'],
            [['host'], 'string', 'max' => 255],
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
            'host' => 'Хост',
            'is_blocked' => 'Заблокирован',
        ];
    }

    public static function setIsBlockedStatus($host)
    {
        $whoogle = self::find()->where(new \yii\db\Expression('host LIKE :term', [':term' => '%' . $host . '%']))->one();
        $whoogle->is_blocked = self::IS_BLOCKED;
        $whoogle->save();
    }
}
