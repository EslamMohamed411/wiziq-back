<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        require_once 'model/teacher/Teacher.php';
        require_once './model/riziq/RiziqTeacher.php';
        $riziqTeacher = new RiziqTeacher();

        $teacher = new Teacher();
        $teacher->initFromJson('{"id":35,"name":"ae","email":"ae@ea.com","password":"ae"}');


        //$riziqTeacher->teacher=$teacher;
        $result = $riziqTeacher->getAllTeacher();


        $myJSON = json_encode($result);

        echo $myJSON;
        // echo $teacher->getAsJson();
        //echo $result;
        ?>
    </body>
</html>
