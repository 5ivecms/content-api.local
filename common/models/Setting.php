<?php

namespace common\models;

use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string|null $option
 * @property string|null $value
 * @property string|null $default
 * @property string|null $label
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'default', 'label'], 'string'],
            [['option'], 'string', 'max' => 255],
            [['option'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option' => 'Option',
            'value' => 'Value',
            'default' => 'Default',
            'label' => 'Label',
        ];
    }

    public static function getGroupSettings($group = '', $groupKey = true)
    {
        if (empty($group)) {
            return false;
        }

        $settings = Setting::find()->where(['like', 'option', $group . '.'])->asArray()->all();
        if (!$settings) {
            return false;
        }
        $settings = ArrayHelper::map($settings, 'option', 'value');
        foreach ($settings as $k => $setting) {
            if (self::isJson($setting)) {
                $settings[$k] = json_decode($setting);
            }
        }

        if (!$groupKey) {
            $result = [];
            foreach ($settings as $k => $setting) {
                $newKey = str_replace($group . '.', '', $k);
                $result[$newKey] = $setting;
            }
            $settings = $result;
        }

        return $settings;
    }

    public static function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function prepareSettingsForForms($settings)
    {
        if (!$settings) {
            return false;
        }
        $settings = ArrayHelper::index($settings, 'option');
        foreach ($settings as $k => $setting) {
            if (Setting::isJson($setting->value)) {
                $settings[$k]->value = json_decode($setting->value);
            }
        }

        return $settings;
    }

    public static function getCronToken()
    {
        return Setting::findOne(['option' => 'cron.token'])->value;
    }

    public static function getParserSettings()
    {
        $settings = Yii::$app->cache->get('settings.parser');
        if (!$settings) {
            $settings = Setting::getGroupSettings('parser', false);
            Yii::$app->cache->set('settings.parser', $settings, 0, new TagDependency([
                'tags' => ['settings.parser']
            ]));
        }

        return $settings;
    }

    public static function getProxySettings()
    {
        $settings = Yii::$app->cache->get('settings.proxy');
        if (!$settings) {
            $settings = Setting::getGroupSettings('proxy', false);
            Yii::$app->cache->set('settings.proxy', $settings, 0, new TagDependency([
                'tags' => ['settings.proxy']
            ]));
        }

        return $settings;
    }
}
