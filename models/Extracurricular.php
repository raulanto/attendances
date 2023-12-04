<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extracurricular".
 *
 * @property int $ext_id
 * @property string $ext_name
 * @property string $ext_date
 * @property string $ext_opening
 * @property string $ext_closing
 * @property string $ext_description
 * @property string $ext_place
 * @property string $ext_code
 *
 * @property ExtraGroup[] $extraGroups
 */
class Extracurricular extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extracurricular';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ext_name', 'ext_date', 'ext_opening', 'ext_closing', 'ext_description', 'ext_place', 'ext_code'], 'required'],
            [['ext_date', 'ext_opening', 'ext_closing'], 'safe'],
            [['ext_description'], 'string'],
            [['ext_name'], 'string', 'max' => 50],
            [['ext_place'], 'string', 'max' => 100],
            [['ext_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ext_id' => 'Ext ID',
            'ext_name' => 'Ext Name',
            'ext_date' => 'Ext Date',
            'ext_opening' => 'Ext Opening',
            'ext_closing' => 'Ext Closing',
            'ext_description' => 'Ext Description',
            'ext_place' => 'Ext Place',
            'ext_code' => 'Ext Code',
        ];
    }

    /**
     * Gets query for [[ExtraGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraGroups()
    {
        return $this->hasMany(ExtraGroup::class, ['extgro_fkextracurricular' => 'ext_id']);
    }


}
