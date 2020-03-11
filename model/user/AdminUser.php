<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminUser
 *
 * @author islam.mohamed
 */
require_once __DIR__ . './User.php';
require_once __DIR__ . './../db/DBStatic.php';

class AdminUser extends User {

    //put your code here
    public function addUser($user) {
        
    }

    public function editUser() {
        $resultObj = new \stdClass();
        
        $conn = mysqli_connect(DBStatic::DB_HOST, DBStatic::DB_USER, DBStatic::DB_PASSWORD, DBStatic::DB_NAME);
        if (!$conn) {
            $arr = array(false, $this, "Could not connect to MySQL: " . mysqli_connect_error());
             $resultObj->status= false;
             $resultObj->msg= "خطأ فى التحديث";
             $resultObj->errNum=0;
             echo json_encode($resultObj);
            die('-1|Could not connect to MySQL: ' . mysqli_connect_error());
        } else {
            mysqli_set_charset($conn, "utf8");

            $sql = "UPDATE `user` SET "
                    . "`email` = '" . $this->email."'"
                    . ",`name` = '" . $this->name."'"
                    . ",`role` = '" . $this->userType."'"
                    . " WHERE `user`.`id` = " . $this->userId;

            if ($conn->query($sql) === TRUE) {
                $resultObj->status= true;
                $resultObj->msg= "تم التحديث بنجاح";
                $resultObj->name=$this->name;
            } else {
                //echo "Error updating record: " . $conn->error;
                $resultObj->status= false;
                $resultObj->msg= "خطأ فى التحديث";
                $resultObj->errNum=1;
                $resultObj->name=$this->name;
                $resultObj->sqlErr=$conn->error;
            }
        }
        
        echo json_encode($resultObj);
        
        
        $conn->close();
        
        
    }

    public function getUserDetails($userId) {
        
    }

    public function removeUser($userId) {
        
    }

  

    public function getAsJson() {
        
    }

    public function initFromJson($userJson) {
        
        //echo $userJson;
        $adminUser= json_decode($userJson);
        
        $this->userId=$adminUser->userId;
        $this->userType=$adminUser->userType;
        $this->name=$adminUser->name;
        $this->email=$adminUser->email;
        $this->imgProfileUrl=$adminUser->imgProfileUrl;
        $this->password=$adminUser->password;
 
    }

}
