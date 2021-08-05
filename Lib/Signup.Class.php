<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/lib/database.php');


class Signup{

    public $username;
    public $password;
    public $email;

    function __construct($username, $email, $password){
        list($this->username,$this->email,$this->password)=$this->cleanInput($username,$email,$password);
        $this->db=getdbConnection();
        $this->password=$this->gethashPassword();
        $this->token=$this->Token();
        $query="INSERT INTO `users`(`username`,`email`,`password`) VALUES ('$this->username','$this->email','$this->password')";
        $result=mysqli_query($this->db,$query);
        // var_dump(mysqli_error($this->db));
        if(mysqli_error($this->db)=="Duplicate entry '$this->username' for key 'username'"){

            throw new Exception("Username already exists");
        }
        elseif(mysqli_error($this->db)=="Duplicate entry '$this->email' for key 'email'"){

            throw new Exception("Email already exists");
        }
        else{
            echo "Added";
        }


    }

    //To Generate Hashed Password
    private function gethashPassword($cost=16){
        $options=[
            'Cost'=>$cost,
        ];
        return password_hash($this->password, PASSWORD_BCRYPT,$options);

    } 

    //To prevent MQSQL Injection
    private function cleanInput($username, $email,$password){
        return array($username,$email,$password);

    }

    private function Token(){
        $bytes = random_bytes(16);
        $token = bin2hex($bytes);
        return $token;
    }


}