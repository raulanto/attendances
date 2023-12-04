<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grade".
 *
 * @property int $gra_id
 * @property string $gra_type
 * @property float $gra_score
 * @property string $gra_date
 * @property string $gra_time
 * @property string $gra_commit
 * @property int $gra_fkgroup
 * @property int $gra_fkperson
 *
 * @property Group $graFkgroup
 * @property Person $graFkperson
 */
class Grade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gra_type', 'gra_score', 'gra_date', 'gra_time', 'gra_commit', 'gra_fkgroup', 'gra_fkperson'], 'required'],
            [['gra_score'], 'number'],
            [['gra_date', 'gra_time'], 'safe'],
            [['gra_commit'], 'string'],
            [['gra_fkgroup', 'gra_fkperson'], 'integer'],
            [['gra_type'], 'string', 'max' => 50],
            [['gra_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['gra_fkgroup' => 'gro_id']],
            [['gra_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['gra_fkperson' => 'per_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gra_id' => 'Gra ID',
            'gra_type' => 'Gra Type',
            'gra_score' => 'Gra Score',
            'gra_date' => 'Gra Date',
            'gra_time' => 'Gra Time',
            'gra_commit' => 'Gra Commit',
            'gra_fkgroup' => 'Gra Fkgroup',
            'gra_fkperson' => 'Gra Fkperson',
        ];
    }

    /**
     * Gets query for [[GraFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'gra_fkgroup']);
    }

    /**
     * Gets query for [[GraFkperson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraFkperson()
    {
        return $this->hasOne(Person::class, ['per_id' => 'gra_fkperson']);
    }

    public function extraFields(){
        return[
            'person' => function($item){
                return $item->graFkperson->per_name;
            },
            'group' => function($item){
                return $item->graFkgroup->gro_code;
            }
        ];
    }
}
