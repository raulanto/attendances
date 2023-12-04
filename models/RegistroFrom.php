<?php
namespace app\models;

use yii\base\Model;

class RegistroFrom extends Model
{
    public $username;
    public $password;
    public $tea_name;
    public $tea_paternal;
    public $tea_maternal;
    public $tea_fkdegree;
    public $tea_mail;
    public $tea_phone;

    
    public function rules() 
    {
        return [
            ['username', 'unique'],
            [['username', 'password','tea_name', 'tea_paternal', 'tea_maternal', 'tea_mail', 'tea_phone', 'tea_fkdegree'], 'required'],
            [['tea_fkdegree'], 'integer'],
            [['tea_name', 'tea_paternal', 'tea_maternal'], 'string', 'max' => 50],
            [['tea_mail'], 'string', 'max' => 100],
            [['tea_phone'], 'string', 'max' => 10],
            [['tea_fkdegree'], 'exist', 'skipOnError' => true, 'targetClass' => Degree::class, 'targetAttribute' => ['tea_fkdegree' => 'deg_id']],
        ];
    }
}

?>