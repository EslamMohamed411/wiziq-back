<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RiziqTeacher
 *
 * @author islam.mohamed
 */
require_once __DIR__ . './../teacher/Teacher.php';
require_once 'AuthBase.php';
require_once 'RiziqStatic.php';

class RiziqTeacher {

    //put your code here

    public $teacher;

    function addTeacher() {


        $authBase = new AuthBase(RiziqStatic::secretAcessKey, RiziqStatic::access_key);
        $method = "add_teacher";

        $requestParameters["signature"] = $authBase->GenerateSignature($method, $requestParameters);

        $requestParameters["name"] = $this->teacher->name;
        $requestParameters["email"] = $this->teacher->email;
        $requestParameters["password"] = $this->teacher->password; //Required
        //$requestParameters["phone_number"] = $this->teacher->phoneNumber; //optional
        //$requestParameters["mobile_number"] = $this->teacher->mobileNumber; //optional
        //$requestParameters["time_zone"] = $this->teacher->timeZone; //optionalAsia/Kolkata
        $requestParameters["about_the_teacher"] = $this->teacher->aboutTheTeacher; //optional
        $requestParameters["can_schedule_class"] = $this->teacher->canScheduleClass; //optional
        $requestParameters["is_active"] = $this->teacher->isActive; //optional


        $httpRequest = new HttpRequest();
        try {
            $XMLReturn = $httpRequest->wiziq_do_post_request(RiziqStatic::webServiceUrl . '?method=' . $method, http_build_query($requestParameters, '', '&'));
        } catch (Exception $e) {
            return $e->getMessage();
        }


        if (!empty($XMLReturn)) {
            try {
                $objDOM = new DOMDocument();
                $objDOM->loadXML($XMLReturn);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $status = $objDOM->getElementsByTagName("rsp")->item(0);
            $attribNode = $status->getAttribute("status");
            if ($attribNode == "ok") {
                $teacherId = $objDOM->getElementsByTagName("teacher_id")->item(0)->nodeValue;
                $this->teacher->id = $teacherId;
                return $result;
            } else if ($attribNode == "fail") {
                $result = "";
                $error = $objDOM->getElementsByTagName("error")->item(0);
                $result .= "<br>errorcode=" . $errorcode = $error->getAttribute("code");
                $result .= "<br>errormsg=" . $errormsg = $error->getAttribute("msg");
                return $result;
            }
        }
    }

    function getAllTeacher() {


        $authBase = new AuthBase(RiziqStatic::secretAcessKey, RiziqStatic::access_key);
        $method = "get_teacher_details";

        $requestParameters["signature"] = $authBase->GenerateSignature($method, $requestParameters);

        // $requestParameters["teacher_id"] = "228154";

        $httpRequest = new HttpRequest();
        try {
            $XMLReturn = $httpRequest->wiziq_do_post_request(RiziqStatic::webServiceUrl . '?method=' . $method, http_build_query($requestParameters, '', '&'));
        } catch (Exception $e) {
            return $e->getMessage();
        }


        if (!empty($XMLReturn)) {
            try {
                $objDOM = new DOMDocument();
                $objDOM->loadXML($XMLReturn);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $status = $objDOM->getElementsByTagName("rsp")->item(0);
            $attribNode = $status->getAttribute("status");
            if ($attribNode == "ok") {
                $teacherDetailsList = $objDOM->getElementsByTagName("teacher_id");
                $teacherArr=array();
                for ($i = 0; $i < $teacherDetailsList->length; $i++) {

                    $teacher=new Teacher();
                    $teacher->id=$objDOM->getElementsByTagName("teacher_id")->item($i)->nodeValue;
                    $teacher->name=$objDOM->getElementsByTagName("name")->item($i)->nodeValue;
                    $teacher->email=$objDOM->getElementsByTagName("email")->item($i)->nodeValue;
                    $teacher->password=$objDOM->getElementsByTagName("password")->item($i)->nodeValue;
                    $teacher->phoneNumber=$objDOM->getElementsByTagName("phone_number")->item($i)->nodeValue;
                    $teacher->mobileNumber=$objDOM->getElementsByTagName("mobile_number")->item($i)->nodeValue;
                    $teacher->aboutTheTeacher=$objDOM->getElementsByTagName("about_the_teacher")->item($i)->nodeValue;
                    $teacher->imageUrl=$objDOM->getElementsByTagName("image")->item($i)->nodeValue;
                    $teacher->timeZone=$objDOM->getElementsByTagName("time_zone")->item($i)->nodeValue;
                    $teacher->canScheduleClass=$objDOM->getElementsByTagName("can_schedule_class")->item($i)->nodeValue;
                    $teacher->isActive=$objDOM->getElementsByTagName("is_active")->item($i)->nodeValue;
                    $teacherArr[$i]=$teacher;
                    
                   /* $teacherDetails = $teacherDetailsList->item($i);
                    //for ($j = 0; $j < 11; $j++) {
                        $teacherName = $teacherDetails->childNodes[0];
                        echo $i . '-' . $teacherName->nodeValue . '<br>';
                    //}*/
                }
                //$result = "xx";
                return $teacherArr;
            } else if ($attribNode == "fail") {
                $result = "";
                $error = $objDOM->getElementsByTagName("error")->item(0);
                $result .= "<br>errorcode=" . $errorcode = $error->getAttribute("code");
                $result .= "<br>errormsg=" . $errormsg = $error->getAttribute("msg");
                return $result;
            }
        }
    }

}
