<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property int $gro_id Unique identifier of table group
 * @property string $gro_code Code of the group
 * @property int $gro_fksubject Foreign key of the subject
 * @property int $gro_fkteacher Foreign key of the teacher
 * @property int $gro_fkclassroom Foreign key of the classroom
 * @property string $gro_date Group creation date
 * @property string $gro_time Group creation time
 *
 * @property Code[] $codes
 * @property Grade[] $grades
 * @property Classroom $groFkclassroom
 * @property Subject $groFksubject
 * @property Teacher $groFkteacher
 * @property Library[] $libraries
 * @property Listg[] $listgs
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gro_code', 'gro_fksubject', 'gro_fkteacher', 'gro_fkclassroom', 'gro_date', 'gro_time'], 'required'],
            [['gro_fksubject', 'gro_fkteacher', 'gro_fkclassroom'], 'integer'],
            [['gro_date', 'gro_time'], 'safe'],
            [['gro_code'], 'string', 'max' => 10],
            [['gro_fkclassroom'], 'exist', 'skipOnError' => true, 'targetClass' => Classroom::class, 'targetAttribute' => ['gro_fkclassroom' => 'clas_id']],
            [['gro_fksubject'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['gro_fksubject' => 'sub_id']],
            [['gro_fkteacher'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['gro_fkteacher' => 'tea_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gro_id' => 'Unique identifier of table group',
            'gro_code' => 'Code of the group',
            'gro_fksubject' => 'Foreign key of the subject',
            'gro_fkteacher' => 'Foreign key of the teacher',
            'gro_fkclassroom' => 'Foreign key of the classroom',
            'gro_date' => 'Group creation date',
            'gro_time' => 'Group creation time',
        ];
    }

    /**
     * Gets query for [[Codes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodes()
    {
        return $this->hasMany(Code::class, ['cod_fkgroup' => 'gro_id']);
    }

    /**
     * Gets query for [[Grades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrades()
    {
        return $this->hasMany(Grade::class, ['gra_fkgroup' => 'gro_id']);
    }

    /**
     * Gets query for [[GroFkclassroom]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroFkclassroom()
    {
        return $this->hasOne(Classroom::class, ['clas_id' => 'gro_fkclassroom']);
    }

    /**
     * Gets query for [[GroFksubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroFksubject()
    {
        return $this->hasOne(Subject::class, ['sub_id' => 'gro_fksubject']);
    }

    /**
     * Gets query for [[GroFkteacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroFkteacher()
    {
        return $this->hasOne(Teacher::class, ['tea_id' => 'gro_fkteacher']);
    }

    /**
     * Gets query for [[Libraries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibraries()
    {
        return $this->hasMany(Library::class, ['lib_fkgroup' => 'gro_id']);
    }

    /**
     * Gets query for [[Listgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListgs()
    {
        return $this->hasMany(Listg::class, ['list_fkgroup' => 'gro_id']);
    }

    public function extraFields(){
        return[
            'teacher' => function($item){
                return $item->groFkteacher->tea_name;
            },
            'subject' => function($item){
                return $item->groFksubject->sub_name;
            },
            'classroom' => function($item){
                return $item->groFkclassroom->clas_name;
            }
        ];
    }
}
