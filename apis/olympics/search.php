<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/olymp.class.php');


${basename(__FILE__,'.php')}=function(){
    if($this->isAuthenticaed()){

        if(isset($_REQUEST['country'])){

            try{
            $a=new Olympics();
            $data=$a->search($_REQUEST['country']);
            $data = $this->json($data);
            $this->response($data,200);
        }catch(Exception $e){
            $data=[
                "Error"=>$e->getMessage()
            ];

            $data = $this->json($data);
            $this->response($data,409);
        }

        }else{
            $data=[
                "Error"=>"Invalid Request"
            ];
            $data = $this->json($data);
            $this->response($data,400);
        }

    }
    else{

        throw new Exception('Unauthorized');
    }
    
};