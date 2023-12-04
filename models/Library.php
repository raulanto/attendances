<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "library".
 *
 * @property int $lib_id Unique identifier of table library
 * @property string $lib_type Type of file
 * @property string $lib_title Title of file
 * @property string $lib_description Description of file
 * @property string $lib_file Link of file
 * @property int $lib_fkgroup Foreign key of the group
 *
 * @property Group $libFkgroup
 */
class Library extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lib_type', 'lib_title', 'lib_description', 'lib_file', 'lib_fkgroup'], 'required'],
            [['lib_fkgroup'], 'integer'],
            [['lib_type', 'lib_title', 'lib_description', 'lib_file'], 'string', 'max' => 255],
            [['lib_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['lib_fkgroup' => 'gro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lib_id' => 'Unique identifier of table library',
            'lib_type' => 'Type of file',
            'lib_title' => 'Title of file',
            'lib_description' => 'Description of file',
            'lib_file' => 'Link of file',
            'lib_fkgroup' => 'Foreign key of the group',
        ];
    }

    /**
     * Gets query for [[LibFkgroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibFkgroup()
    {
        return $this->hasOne(Group::class, ['gro_id' => 'lib_fkgroup']);
    }

    public function extraFields(){
        return[
            'group' => function($item){
                return $item->libFkgroup->gro_code;
            }
        ];
    }
}
