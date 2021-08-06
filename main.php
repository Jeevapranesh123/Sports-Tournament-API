<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/Signup.Class.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/Auth.Class.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/Dbfetch.php');

    class API extends REST {

        public $data = "";

        const DB_SERVER = "localhost";
        const DB_USER = "root";
        const DB_PASSWORD = "";
        const DB = "apis";

        private $db = NULL;
        private $current_call;
        private $auth=NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            $this->dbConnect();                    // Initiate Database connection
        }

        /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */

        public function processApi(){
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
            if((int)method_exists($this,$func) > 0){
                // echo "Found Without Namespace";
                $this->$func();
            }
            else{

                if(isset($_GET['namespace'])){
                    // echo 'Checking Namespace';

                    $dir=__DIR__."/apis"."/".$_GET['namespace'];
                    $apis=scandir($dir);

                    foreach($apis as $api){
                        if($api=="." or $api==".."){
                                continue;
                        }

                        $apiname=basename($api,'.php');

                        if($apiname==$func){
                            include $dir."/".$api;
                            // echo "API Found in Namespace";
                            $this->current_call = Closure::bind(${$apiname}, $this, get_class());
                            $this->$apiname();
                        }
                            
                    }
                    // echo $this->current_call();
                }
                else{
                    // If the method not exist with in this class, response would be "Page not found".
                    $this->response('',404);
                }
            }
           
        }

        public function __call($method,$args){
            if(is_callable($this->current_call)){
                // echo "Successfully Called $method()";
                // echo time();
                return call_user_func_array($this->current_call, $args);
            } 
            else {
                // echo time();
                // echo "Something is wrong";
            }
        }


        public function auth(){
            $headers = getallheaders();
            if(isset($headers['Authorization'])){
                $token = explode(' ', $headers['Authorization']);
                // print_r ($token);
                $this->auth=new Auth($token[1]);
                
            }
        }

        public function isAuthenticaed(){
            if($this->auth==NULL){
                return FALSE;
            }if($this->auth->authenticate() and isset($_SESSION['username'])){
                return TRUE;

            }else{
                return FALSE;
            }
        }

        public function isAdmin(){

        }

        public function errors($e){
            $data = [
                "error" => $e->getMessage()
            ];
            // print_r($data);
            $response_code = 400;
            
            if($e->getMessage() == "Expired Token"){
                
                $response_code = 403;
            }
            if($e->getMessage() == "Unauthorized"){
                $response_code = 401;
            }
            
            if($e->getMessage() == "Invalid Token"){

                $response_code = 401;
            }
    
            if($e->getMessage() == "Not found"){
                $response_code = 404;
            }
            $data = $this->json($data);
            $this->response($data,$response_code);
        }

        /*************API SPACE START*******************/

        private function about(){

            if($this->get_request_method() != "POST"){
                $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
                $error = $this->json($error);
                $this->response($error,406);
            }
            $data = array('Release' => 'Beta', 'Team' => 'This API is created by Zuva Technologies Pvt. Ltd.','Usecase'=>'This API Can be used for Managing Backend for Sports Torunament');
            $data = $this->json($data);
            $this->response($data,200);

        }


        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,200);
        }

        private function get_current_user(){
            $username = $this->is_logged_in();
            if($username){
                $data = [
                    "username"=> $username
                ];
                $this->response($this->json($data), 200);
            } else {
                $data = [
                    "error"=> "unauthorized"
                ];
                $this->response($this->json($data), 403);
            }
        }

        /*************API SPACE END*********************/

        /*
            Encode array into JSON
        */
        private function json($data){
            if(is_array($data)){
                return json_encode($data, JSON_PRETTY_PRINT);
            } else {
                return "{}";
            }
        }

    }
    // Initiiate Library
    $api = new API;
    try{
        $api->auth();
        $api->processApi();
    }catch(Exception $e){
        // echo 'Caught 1';
        $api->errors($e);
    }
    
    
  
?>