<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property int $per_id
 * @property string $per_name
 * @property string $per_paternal
 * @property string $per_maternal
 * @property string $per_mail
 * @property string $per_phone
 * @property int|null $per_fkuser
 *
 * @property Grade[] $grades
 * @property Listg[] $listgs
 * @property User $perFkuser
 * @property Question[] $questions
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_name', 'per_paternal', 'per_maternal', 'per_mail', 'per_phone'], 'required'],
            [['per_fkuser'], 'integer'],
            [['per_name', 'per_paternal', 'per_maternal'], 'string', 'max' => 50],
            [['per_mail'], 'string', 'max' => 100],
            [['per_phone'], 'string', 'max' => 10],
            [['per_fkuser'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['per_fkuser' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'per_id' => 'Per ID',
            'per_name' => 'Per Name',
            'per_paternal' => 'Per Paternal',
            'per_maternal' => 'Per Maternal',
            'per_mail' => 'Per Mail',
            'per_phone' => 'Per Phone',
            'per_fkuser' => 'Per Fkuser',
        ];
    }

    /**
     * Gets query for [[Grades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrades()
    {
        return $this->hasMany(Grade::class, ['gra_fkperson' => 'per_id']);
    }

    /**
     * Gets query for [[Listgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListgs()
    {
        return $this->hasMany(Listg::class, ['list_fkperson' => 'per_id']);
    }

    /**
     * Gets query for [[PerFkuser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerFkuser()
    {
        return $this->hasOne(User::class, ['id' => 'per_fkuser']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['que_fkperson' => 'per_id']);
    }

    //funcion que retorna el nombre Completo
    public function getCompleto()
    {
        return $this->per_name.' '.$this->per_paternal.' '.$this->per_maternal;
    }
}