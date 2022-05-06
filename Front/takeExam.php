<?php
    session_start();
    /*Things To Do For This File:
      Set Cookie Params Location On First Line of Code
      Set Header URL To Correct File Location
    */
    $examID = $_POST["examID"];
    header("refresh: 2, url=studentExam.php?examID=".$examID."&user=".$_GET["user"]);
?>