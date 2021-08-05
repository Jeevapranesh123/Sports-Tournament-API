<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/olymp.class.php');


${basename(__FILE__,'.php')}=function(){
    if($this->isAuthenticaed()){
    $a=new Olympics();
    $data=$a->display();
    $data = $this->json($data);
    $this->response($data,200);

    }else{
        throw new Exception('Unauthorized');
    }

   

};
