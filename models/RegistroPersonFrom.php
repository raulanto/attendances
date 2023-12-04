<?php
namespace app\models;

use yii\base\Model;

class RegistroFrom extends Model
{
    public $username;
    public $password;
    public $per_name;
    public $per_paternal;
    public $per_maternal;
    public $per_fkdegree;
    public $per_mail;
    public $per_phone;

    
    public function rules() 
    {
        return [
            ['username', 'unique'],
            [['username', 'password','per_name', 'per_paternal', 'per_maternal', 'per_mail', 'per_phone', 'per_fkdegree'], 'required'],
            [['per_fkdegree'], 'integer'],
            [['per_name', 'per_paternal', 'per_maternal'], 'string', 'max' => 50],
            [['per_mail'], 'string', 'max' => 100],
            [['per_phone'], 'string', 'max' => 10],
        
        ];
    }
}

?>