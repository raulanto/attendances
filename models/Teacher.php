<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property int $tea_id
 * @property string $tea_name
 * @property string $tea_paternal
 * @property string $tea_maternal
 * @property string $tea_mail
 * @property string $tea_phone
 * @property int $tea_fkdegree
 * @property int|null $tea_fkuser
 *
 * @property Group[] $groups
 * @property Question[] $questions
 * @property Degree $teaFkdegree
 * @property User $teaFkuser
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tea_name', 'tea_paternal', 'tea_maternal', 'tea_mail', 'tea_phone', 'tea_fkdegree'], 'required'],
            [['tea_fkdegree', 'tea_fkuser'], 'integer'],
            [['tea_name', 'tea_paternal', 'tea_maternal'], 'string', 'max' => 50],
            [['tea_mail'], 'string', 'max' => 100],
            [['tea_phone'], 'string', 'max' => 10],
            [['tea_fkdegree'], 'exist', 'skipOnError' => true, 'targetClass' => Degree::class, 'targetAttribute' => ['tea_fkdegree' => 'deg_id']],
            [['tea_fkuser'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['tea_fkuser' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tea_id' => 'Tea ID',
            'tea_name' => 'Tea Name',
            'tea_paternal' => 'Tea Paternal',
            'tea_maternal' => 'Tea Maternal',
            'tea_mail' => 'Tea Mail',
            'tea_phone' => 'Tea Phone',
            'tea_fkdegree' => 'Tea Fkdegree',
            'tea_fkuser' => 'Tea Fkuser',
        ];
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['gro_fkteacher' => 'tea_id']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['que_fkteacher' => 'tea_id']);
    }

    /**
     * Gets query for [[TeaFkdegree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeaFkdegree()
    {
        return $this->hasOne(Degree::class, ['deg_id' => 'tea_fkdegree']);
    }

    /**
     * Gets query for [[TeaFkuser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeaFkuser()
    {
        return $this->hasOne(User::class, ['id' => 'tea_fkuser']);
    }
}
