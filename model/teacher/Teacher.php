<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Teacher
 *
 * @author islam.mohamed
 */
class Teacher {
    //put your code here
    public $id=-1;
    public $name="";
    public $email="";
    public $password="";
    public $imageUrl="";
    public $phoneNumber="+022255443";
    public $mobileNumber="+0201122754";
    public $timeZone="Asia/Kolkata";
    public $aboutTheTeacher="EA";
    public $canScheduleClass=false;
    public $isActive=false;
    
    
    
    function initFromJson($obj){
      $teacherObj= json_decode($obj);
      
      //echo $obj.' ea '.$teacherObj;
      $this->id=$teacherObj->id;
      $this->name=$teacherObj->name;
      $this->email=$teacherObj->email;
      $this->password=$teacherObj->password;
    }
    
    
    function getAsJson(){
        $teacherObj=new \stdClass();
        $teacherObj->id=$this->id;
        $teacherObj->name=$this->name;
        $teacherObj->email=$this->email;
        $teacherObj->password=$this->password;
        $teacherObj->imageUrl=$this->imageUrl;
        $teacherObj->phone_number=$this->phoneNumber;
        $teacherObj->mobile_number=$this->mobileNumber;
        $teacherObj->time_zone=$this->timeZone;
        $teacherObj->about_the_teacher=$this->aboutTheTeacher;
        $teacherObj->can_schedule_class=$this->canScheduleClass;
        $teacherObj->is_active=$this->isActive;
        
        
        return json_encode($teacherObj);
        
    }
}
