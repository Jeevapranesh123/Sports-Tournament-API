<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/lib/database.php');

Class Auth {

    private $username;
    private $password;
    private $db;
    private $TokenAuth=FALSE;
    private $Tokens=NULL;
    public $usertoken=NULL;

    /**
     * Verification is Done With Email
     */

    public function __construct($email,$password=NULL){
        $this->db=getdbConnection();

        if($password==NULL){
            $this->usertoken=$email;
            $this->TokenAuth=TRUE;

        }else{
            //Password Auth
            $this->email=$email;
            $this->password=$password;

        }

        if ($this->TokenAuth==TRUE){
                $this->authenticate($this->usertoken);
        }

        else{
            $query="SELECT * FROM `users` WHERE email='$this->email'";
            $result=mysqli_query($this->db,$query);
            if($result){
                if(mysqli_num_rows($result)==1){
                    $val=mysqli_fetch_assoc($result);
                    
                    if(password_verify($this->password,$val['password'])){
                        $this->username=$val['username'];
                        $this->Tokens=$this->addSession();
                        
                            

                    }
                    else{
                        throw new Exception("Password Mismatch");
                    }
                }
                else{
                    throw new Exception("User Not found");
                }
            }else{
                echo mysqli_error();
            }

        }

    }

    private function addSession(){
        $u=$this->username;
        $query="SELECT * FROM `session` WHERE active =1 and username='$u';";
        $result=mysqli_query($this->db,$query);
        if($result){
            $row=mysqli_num_rows($result);
            $val=mysqli_fetch_assoc($result);
            if($row==1){
                $id=$val['id'];
                if($this->deactivatesession($id)){
                    // echo "Deactivation Success";
                    $token=$this->newsession($this->username);
                    return $token;
                }else{
                    echo "Unable to Deactivate";
                }
                

            }else{
                $token=$this->newsession($this->username);
                return $token;
            }

        }else{
            echo "No Result";
        }

    }


    private function newsession($username){

        $newtoken=$this->Token();
        // echo $this->username;
        // echo $newtoken;
        $time=time();
        $query="INSERT INTO `session`(`username`, `token`, `created_at`) VALUES ('$this->username','$newtoken','$time')";
        $result=mysqli_query($this->db,$query);
        if($result){
            return $newtoken;
        }else{
            echo mysqli_error($this->db);
            echo "Unable to Create Session";
        }

    }

    private function deactivatesession($id){
        $query="UPDATE `session` SET active= 0 WHERE id=$id;";
        $result=mysqli_query($this->db,$query);
        if($result){
            // echo "Succes";
            return TRUE;
        }else{
            echo "Unable to Delete Session";
        }
    }


    public function getdata(){
        $data=[
            "Username"=>$this->username,
            "Email"=>$this->email,
            "Token"=>$this->Tokens
        ];
        return $data;
    }

    private function Token(){
        $bytes = random_bytes(16);
        $token = bin2hex($bytes);
        return $token;
    }

    


    public function authenticate(){
        $token=$this->usertoken;

        $query="SELECT * FROM `session` WHERE token='$token'";
        $result=mysqli_query($this->db,$query);
        if($result){
            if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_assoc($result);
            if($row['active']==1){
                $created_at=$row['created_at'];
                $expiry=$created_at+86400;
                if(time()<$expiry){
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $this->username = $_SESSION['username'] = $row['username'];
                    $_SESSION['token'] = $this->usertoken;
                    return true;
                }
                else{
                    throw new Exception('Expired Token');
                }
            }else{
                throw new Exception('Expired Token');
            }
        }else{
            throw new Exception('Invalid Token');
        }
        }

    }



}




