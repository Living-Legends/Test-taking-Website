<?php
    session_start();
    if(!isset($_SESSION["login"])) {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    } elseif($_SESSION["login"] == "false") {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    } elseif($_SESSION["level"] != "teacher") {
        header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
        exit();
    }    
    $examName = $_POST["examName"];
    $points = $_POST["points"];
    $status = $_POST["status"];
    print_r($_POST["qID_A"]);
    $questions = array();
    $pointValues = array();
    if(is_array($_POST["qID"])) {
        for($i = 0; $i < count($_POST["qID"]); $i++) {
            array_push($questions, $_POST["qID"][$i]);
            array_push($pointValues, $_POST["pV"][$i]);
        }
    } else {
        array_push($questions, $_POST["qID_A"]);
        array_push($pointValues, $_POST["pV"][0]);
    }

    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "AddExam",
        'ExamName' => $examName,
        'TotalPoints' => $points,
        'Status' => $status,
        'Questions' => $questions,
        'PointValues' => $pointValues
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
    $result = curl_exec($curl);
    
    header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/add-exams.php?user=".$_GET["user"]);
?>