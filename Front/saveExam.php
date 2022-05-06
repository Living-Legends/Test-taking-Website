<?php
    session_start();
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
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

    $examID = $_POST["examID"];
    $user = $_POST["student"];
    $teach = $_GET["user"];
    $score = $_POST["totalScore"];
    $released = $_POST["Released"];

    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "LoadQuiz",
        'Username' => $user,
        'ExamID' => $examID
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);

    $response = json_decode($result, true);
    $xmlString = $response["RequestResponse"]["StudentAnswers"];
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xmlString);
    $xml->loadXML($xmlString);

    $counter = 0;
    $table = 0;
    $tScore = 0;
    foreach($xml->getElementsbyTagName('Q') as $q) {
        $q->getElementsbyTagName('QID')->item(0)->nodeValue = $_POST["qID"][$counter];
        $qtn = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $_POST["question"][$counter]);
        $q->getElementsbyTagName('Question')->item(0)->nodeValue = $qtn;
        $q->getElementsbyTagName('StudentAnswer')->item(0)->nodeValue = $_POST["studentAnswer"][$counter];
        $qScore = 0;
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+0];
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+0];
        if(is_numeric($_POST["scrAdj"][$table+0])) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+0];
            $qScore = $qScore + $_POST["scrAdj"][$table+0];
        } else {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
            $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
        }
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+1];
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+1];
        if(is_numeric($_POST["scrAdj"][$table+1])) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+1];
            $qScore = $qScore + $_POST["scrAdj"][$table+1];
        } else {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
            $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
        }
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+2];
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+2];
        if(is_numeric($_POST["scrAdj"][$table+2])) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+2];
            $qScore = $qScore + $_POST["scrAdj"][$table+2];
        } else {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
            $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
        }
        $inc = 3;
        if(count($_POST["actual"]) - 1 >= $table + 3 && $_POST["actual"][$table+3] != "" && $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->length != 0) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+3];
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+3];
            if(is_numeric($_POST["scrAdj"][$table+3])) {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+3];
                $qScore = $qScore + $_POST["scrAdj"][$table+3];
            } else {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
                $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
            }
            $inc = 4;
        }
        if(count($_POST["actual"]) - 1 >= $table + 4 && $_POST["actual"][$table+4] != "" && $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->length != 0) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+4];
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+4];
            if(is_numeric($_POST["scrAdj"][$table+4])) {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+4];
                $qScore = $qScore + $_POST["scrAdj"][$table+4];
            } else {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
                $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
            }
            $inc = 5;
        }
        if(count($_POST["actual"]) - 1 >= $table + 5 && $_POST["actual"][$table+5] != "" && $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->length != 0) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue = $_POST["actual"][$table+5];
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue = $_POST["given"][$table+5];
            if(is_numeric($_POST["scrAdj"][$table+5])) {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["scrAdj"][$table+5];
                $qScore = $qScore + $_POST["scrAdj"][$table+5];
            } else {
                $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
                $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
            }
            $inc = 6;
        }
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('Type')->item(0)->nodeValue = $_POST["type"][$counter];
        $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('Pass')->item(0)->nodeValue = $_POST["pass"][$counter];
        if(is_numeric($_POST["conScr"][$counter])) {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = $_POST["conScr"][$counter];
            $qScore = $qScore + $_POST["conScr"][$counter];
        } else {
            $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue = "--";
            if($_POST["type"][$counter] != "None") {
                $qScore = $qScore + $q->getElementsbyTagName('Grading')->item(0)->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue;
            }
        }
        $q->getElementsbyTagName('Comments')->item(0)->nodeValue = $_POST["comments"][$counter];
        $q->getElementsbyTagName('Score')->item(0)->nodeValue = $qScore;
        $tScore = $tScore + $qScore;
        $counter++;
        $table = $table + $inc;
    }

    $xml->formatOutput = true;
    
    $xmlString = $xml->saveXML();
    $jsonData = array(
        'RequestType' => "SaveQuiz",
        'Username' => $user,
        'ExamID' => $examID,
        'Score' => $tScore,
        'Released' => $released,
        'StudentAnswers' => $xmlString
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);

    curl_close($curl);

    header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/viewStudent.php?user=".$teach."&student=" . $user);
    exit();
?>