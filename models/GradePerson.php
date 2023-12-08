<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grade_person".
 *
 * @property int $graper_id
 * @property float $graper_score
 * @property string $graper_commit
 * @property int $graper_fkperson
 * @property int $graper_fkgrade
 *
 * @property Grade $graperFkgrade
 * @property Person $graperFkperson
 */
class GradePerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grade_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['graper_score', 'graper_commit', 'graper_fkperson', 'graper_fkgrade'], 'required'],
            [['graper_score'], 'number'],
            [['graper_commit'], 'string'],
            [['graper_fkperson', 'graper_fkgrade'], 'integer'],
            [['graper_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['graper_fkperson' => 'per_id']],
            [['graper_fkgrade'], 'exist', 'skipOnError' => true, 'targetClass' => Grade::class, 'targetAttribute' => ['graper_fkgrade' => 'gra_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'graper_id' => 'Graper ID',
            'graper_score' => 'Graper Score',
            'graper_commit' => 'Graper Commit',
            'graper_fkperson' => 'Graper Fkperson',
            'graper_fkgrade' => 'Graper Fkgrade',
        ];
    }

    /**
     * Gets query for [[GraperFkgrade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraperFkgrade()
    {
        return $this->hasOne(Grade::class, ['gra_id' => 'graper_fkgrade']);
    }

    /**
     * Gets query for [[GraperFkperson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraperFkperson()
    {
        return $this->hasOne(Person::class, ['per_id' => 'graper_fkperson']);
    }

    public function extraFields(){
        return[
            'person' => function($item){
                return $item->graperFkperson->per_name;
            },
            'grade' => function($item){
                return $item->graperFkgrade->gra_type;
            }
        ];
    }
}