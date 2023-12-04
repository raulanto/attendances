<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extra_group".
 *
 * @property int $extgro_id
 * @property string $extgro_commit
 * @property int $extgro_fkextracurricular
 * @property int $extgro_fkgroup
 *
 * @property Extracurricular $extgroFkextracurricular
 * @property Group $extgroFkgroup
 */
class ExtraGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extra_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['extgro_commit', 'extgro_fkextracurricular', 'extgro_fkgroup'], 'required'],
            [['extgro_commit'], 'string'],
            [['extgro_fkextracurricular', 'extgro_fkgroup'], 'integer'],
            [['extgro_fkextracurricular'], 'exist', 'skipOnError' => true, 'targetClass' => Extracurricular::class, 'targetAttribute' => ['extgro_fkextracurricular' => 'ext_id']],
            [['extgro_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['extgro_fkgroup' => 'gro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'extgro_id' => 'Extgro ID',
            'extgro_commit' => 'Extgro Commit',
            'extgro_fkextracurricular' => 'Extgro Fkextracurricular',
            'extgro_fkgroup' => 'Extgro Fkgroup',
        ];
    }

    /**
     * Gets query for [[ExtgroFkextracurricular]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtgroFkextracurricular()
    {
        return $this->hasOne(Extracurricular::class, ['ext_id' => 'extgro_fkextracurricular']);
    }

    /**
     * Gets query for [[ExtgroFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtgroFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'extgro_fkgroup']);
    }

    public function extraFields(){
        return[
            'extracurricular' => function($item){
                return $item->extgroFkextracurricular->ext_name;
            },
            'group' => function($item){
                return $item->extgroFkgroup->gro_code;
            }
        ];
    }
}
