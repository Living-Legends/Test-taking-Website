<?php
    session_start();
    $_SESSION['id'] = '123';
    /*Things To Do For This File:
      Redirect Back To Landing Page
    */
    if(!isset($_SESSION["login"])) {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    } elseif($_SESSION["login"] == "false") {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    } elseif($_SESSION["level"] != "student") {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    }
    $examID = $_GET["examID"];
    $user = $_POST["user"];
    $examName = $_GET["examName"];


    $xmlString = "<Exam><ExamName>" . $examName . "</ExamName><ExamID>" . $examID . "</ExamID>";

    for($i = 0; $i < count($_POST["qNum"]); $i++) {
        $questionString = "<Q><QID>" . $_POST["qNum"][$i] . "</QID><Question>" . $_POST["qtn"][$i] . "</Question><Points>" . $_POST["pV"][$i] . "</Points><StudentAnswer>" . $_POST["answer"][$i] . "</StudentAnswer><Score>--</Score><Grading><PPM></PPM><Name><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></Name><TC1><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC1><TC2><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC2><TC3><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC3><TC4><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC4><TC5><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC5><Constraint><Type></Type><Pass></Pass><AGScore></AGScore><HScore></HScore></Constraint></Grading><Comments></Comments></Q>";
        $xmlString = $xmlString . "" . $questionString;
    }
    $xmlString = $xmlString . "</Exam>";
    $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xmlString);
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "SubmitQuiz",
        'ExamID' => $examID,
        'Username' => $user,
        'StudentAnswers' => $xmlString
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);
    curl_close($curl);
    header ("refresh: 1, url=student_login.php?user=" . $user);
?>