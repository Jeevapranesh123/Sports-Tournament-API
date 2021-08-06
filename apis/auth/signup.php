<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/Signup.Class.php');


${basename(__FILE__,'.php')}=function(){

    if(($this->get_request_method()=="POST")){
        if(isset($this->_request['username']) and isset($this->_request['password']) and isset($this->_request['email'])){

                $username =$this->_request['username'];
                $email =$this->_request['email'];
                $password =$this->_request['password'];

            try {
                $a=new Signup($username,$email,$password);
                $data=[
                    'Status'=>'Signup Success',
                    'User'=>$a->username,
                    'Email'=>$a->email,
                ];
                $this->response($this->json($data),200);
            }
            
            catch(Exception $e){
                $data=[
                    'status'=>'Signup Failed',
                    'Message'=>$e->getmessage(),
                ];
                $this->response($this->json($data),409);
            }

        }else{
            $data=[
                'Status'=>'Insufficient Data',
                'Message'=>'Provide All Data',
            ];
            $this->response($this->json($data),400);    
        }

    }else{
        $data=[
            'Message'=>'Method Not Allowed',
        ];
        $this->response($this->json($data),405);
    }
};