<?php

require_once($_SERVER['DOCUMENT_ROOT'].'\testapi\Lib\Auth.class.php');

${basename(__FILE__,'.php')}=function(){

    if(($this->get_request_method()=="POST")){
        if(isset($this->_request['email']) and isset($this->_request['password'])){
            $email=$_REQUEST['email'];
            $password=$_REQUEST['password'];
            try{
                $a=new Auth($email,$password);
                $data=[
                    "Message"=>"Login Success",
                    "Data"=>$a->getdata()
                ];
                $data = $this->json($data);
                $this->response($data,200);

            }
            catch(Exception $e){
                $data=[
                    "Error"=>$e->getMessage()
                ];
                $data = $this->json($data);
                $this->response($data,403);

            }
            
        }else{
            $data=[
                "Error"=>"Invalid Request"
            ];
            $data = $this->json($data);
                $this->response($data,400);
        }

    }else{
        $data=[
            "Error"=>"Method Not Accepted"
        ];
        $data = $this->json($data);
            $this->response($data,405);
    }

        
};


