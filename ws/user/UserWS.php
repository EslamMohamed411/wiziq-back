<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserWS
 *
 * @author islam.mohamed
 */
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once __DIR__ .'./../../model/user/AdminUser.php';
require_once __DIR__ .'./../../model/user/User.php';

class UserWS {

    //put your code here

    public $adminUser;
    public $method;

    function __construct() {
        $this->adminUser=new AdminUser();
        //$x=$_POST['email'];
        $method=$_GET['method'];
        
        if($method=="login"){
            $email=$_POST['email'];
            $pass=$_POST['pass'];
            $this->login($email, $pass);
        }
        else if($method=="uploadImg"){
           $this->uploadUserProfileImg($_FILES["fileToUpload"],$_POST["imgName"],$_POST['userId']);
        }
        
        else if($method=="editUser"){
            //echo "editUser";
            //echo $_POST["userJson"];
            $this->editUser($_POST["userJson"]);
        }
        
         else if($method=="updateUserPassword"){
            //echo "editUser";
            //echo $_POST["userJson"];
            $this->editUserPassword($_POST["oldPass"],$_POST["newPass"],$_POST["userId"]);
        }
        
        
      // updateUserPassword($oldPass, $newPass, $userId)
    }
    
    
    function login($email , $pass){
        $this->adminUser->loginUser($email,$pass);
    }
    
    function uploadUserProfileImg($file,$imgName, $userId){
        $this->adminUser->uploadProfileImg($file,$imgName,$userId);
    }
    
    function editUser($userJson){
        
        $this->adminUser->initFromJson($userJson);
        $this->adminUser->editUser();
    }
    
     function editUserPassword($oldPass,$newPass,$userId){

        $this->adminUser->updateUserPassword($oldPass,$newPass,$userId);
    }

}


$userWs=new UserWS();