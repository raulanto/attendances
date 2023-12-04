<?php
//CONTROLADOR DE TABLA EXTRACURRICULAR_PERSON
namespace app\models;

use Yii;

/**
 * This is the model class for table "extra_person".
 *
 * @property int $extper_id
 * @property string $extper_commit
 * @property int $extper_fkextracurricular
 * @property int $extper_fkperson
 *
 * @property Extracurricular $extperFkextracurricular
 * @property Person $extperFkperson
 */
class ExtraPerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extra_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['extper_commit', 'extper_fkextracurricular', 'extper_fkperson'], 'required'],
            [['extper_commit'], 'string'],
            [['extper_fkextracurricular', 'extper_fkperson'], 'integer'],
            [['extper_fkextracurricular'], 'exist', 'skipOnError' => true, 'targetClass' => Extracurricular::class, 'targetAttribute' => ['extper_fkextracurricular' => 'ext_id']],
            [['extper_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['extper_fkperson' => 'per_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'extper_id' => 'Extper ID',
            'extper_commit' => 'Extper Commit',
            'extper_fkextracurricular' => 'Extper Fkextracurricular',
            'extper_fkperson' => 'Extper Fkperson',
        ];
    }

    /**
     * Gets query for [[ExtperFkextracurricular]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtperFkextracurricular()
    {
        return $this->hasOne(Extracurricular::class, ['ext_id' => 'extper_fkextracurricular']);
    }

    /**
     * Gets query for [[ExtperFkperson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtperFkperson()
    {
        return $this->hasOne(Person::class, ['per_id' => 'extper_fkperson']);
    }
}
