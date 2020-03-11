<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author islam.mohamed
 */
abstract class User {

    //put your code here
    const ADMIN_ACCOUNT_TYPE = 0;
    const SCHOOL_ACCOUNT_TYPE = 1;
    const TEACHER_ACCOUNT_TYPE = 2;
    const STUDENT_ACCOUNT_TYPE = 3;

    public $userId;
    public $userType;
    public $name;
    public $email;
    public $imgProfileUrl;
    public $password;

    function loginUser($userEmail, $userpassword) {

        $resultObj = new \stdClass();
        $conn = mysqli_connect(DBStatic::DB_HOST, DBStatic::DB_USER, DBStatic::DB_PASSWORD, DBStatic::DB_NAME);
        if (!$conn) {
            $arr = array(false, $this, "Could not connect to MySQL: " . mysqli_connect_error());
            return json_encode($arr);
            die('-1|Could not connect to MySQL: ' . mysqli_connect_error());
        } else {
            mysqli_set_charset($conn, "utf8");
            $query = "SELECT * FROM user WHERE "
                    . "email='" . $userEmail . "' "
                    . "AND password='" . $userpassword . "'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $adminUser = new AdminUser();
                $adminUser->userId = $row['id'];
                $adminUser->name = $row['name'];
                $adminUser->email = $row['email'];
                $adminUser->userType = $row['role'];
                $adminUser->imgProfileUrl = $row['profile_img_name'];

                $resultObj->status = true;
                $resultObj->msg = "تم تسجيل الدخول بنجاح";
                $resultObj->userJson = $adminUser;


                
            } else {
                $resultObj->status = false;
                $resultObj->msg = "خطأ في تسجيل الدخول";
                $resultObj->userJson = null;


                
            }
        }
        
        echo json_encode($resultObj);
    }

/////////////////////////////////////////////////////////////////


    function updateUserPassword($oldPass, $newPass, $userId) {

        $resultObj = new \stdClass();

        $conn = mysqli_connect(DBStatic::DB_HOST, DBStatic::DB_USER, DBStatic::DB_PASSWORD, DBStatic::DB_NAME);
        if (!$conn) {
            $arr = array(false, $this, "Could not connect to MySQL: " . mysqli_connect_error());
            $resultObj->status = false;
            $resultObj->msg = "خطأ في الاتصال";
            $resultObj->errNum = 0;
            die('-1|Could not connect to MySQL: ' . mysqli_connect_error());
        } else {
            mysqli_set_charset($conn, "utf8");

            $sql = "UPDATE `user` u SET `password`='" . $newPass . "' WHERE u.password='" . $oldPass . "' AND id=" . $userId;

            if ($conn->query($sql) === TRUE) {
                $resultObj->status = true;
                $resultObj->msg = "تم التحديث بنجاح";
                $resultObj->errNum = -1;
            } else {
                //echo "Error updating record: " . $conn->error;
                $resultObj->status = false;
                $resultObj->msg = "كلمة المرور الاصلية غير صحيحة";
                $resultObj->errNum = 1;
            }
        }
        $conn->close();
        
        echo json_encode($resultObj);
    }

    /*     * ************************************************************************* */

    function updateProfileUserDB($imgName, $userId) {
        $conn = mysqli_connect(DBStatic::DB_HOST, DBStatic::DB_USER, DBStatic::DB_PASSWORD, DBStatic::DB_NAME);
        if (!$conn) {
            $arr = array(false, $this, "Could not connect to MySQL: " . mysqli_connect_error());
            return false;
            die('-1|Could not connect to MySQL: ' . mysqli_connect_error());
        } else {
            mysqli_set_charset($conn, "utf8");

            $sql = "UPDATE `user` SET `profile_img_name` = '" . $imgName . "' WHERE `user`.`id` = " . $userId;

            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                //echo "Error updating record: " . $conn->error;
                return false;
            }
        }
        $conn->close();
    }

    function uploadProfileImg($file, $name, $userId) {
        $resultObj = new \stdClass();
        $target_dir = __DIR__ . "./../../res/imgs/";
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $imgName = basename($name) . '.' . $imageFileType;
        $target_file = $target_dir . $imgName;
        $uploadOk = 1;

// Check if image file is a actual image or fake image
        // if (isset($_POST["submit"])) 
        {
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $resultObj->msg = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
                $resultObj->status = true;
            } else {
                $resultObj->msg = "File is not an image.";
                $uploadOk = 0;
                $resultObj->status = false;
            }
        }
// Check if file already exists
        /* if (file_exists($target_file)) {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
          $resultObj->status = false;
          } */
// Check file size
        /* if ($file["size"] > 500000) {
          $resultObj->msg="Sorry, your file is too large.";
          $uploadOk = 0;
          $resultObj->status = false;
          } */
// Allow certain file formats
        /* if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
          $resultObj->msg="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
          } */

        if ($imageFileType != "png") {
            $resultObj->msg = "Sorry, only PNG files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $resultObj->msg = "Sorry, your file was not uploaded.";
            $resultObj->status = false;
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {

                if ($this->updateProfileUserDB($imgName, $userId)) {
                    $resultObj->msg = "The file " . $imgName . " has been uploaded.";
                    $resultObj->imgName = $imgName;
                    $resultObj->status = true;
                } else {
                    $resultObj->msg = "Sorry, there was an error uploading your file.";
                    $resultObj->status = false;
                    $resultObj->imgName = "";
                }
            } else {
                $resultObj->msg = "Sorry, there was an error uploading your file.";
                $resultObj->status = false;
                $resultObj->imgName = "";
            }
        }

        echo json_encode($resultObj);
    }

    abstract function addUser($user);

    abstract function editUser();

    abstract function removeUser($userId);

    abstract function getUserDetails($userId);

    abstract function initFromJson($userJson);

    abstract function getAsJson();
}
