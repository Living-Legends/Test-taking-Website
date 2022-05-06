<?php
// initalise all variables
    $examID = $_POST["examID"];
    $user = $_POST["user"];
    
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType'=> "LoadQuiz",
        'ExamID' => $examID,
        'Username' => $user
    );
    
    $jsonPayload = json_encode(array('Request' => $jsonData));
    //Set Options of Curl
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_POST, true);
    //Execute Curl and Close Connection
    $result = curl_exec($curl);
    curl_close($curl);
    $postData = json_decode($result,true);
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType'=> "Autograde",
        'XMLString' => $postData["RequestResponse"]["StudentAnswers"],
        'Username' => $user,
        'ExamID' => $examID
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));
    //Set Options of Curl
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);

    //Execute Curl and Close Connection
    $result = curl_exec($curl);
    curl_close($curl);
    header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/viewExam.php?examID=".$examID."&student=".$user."&user=".$_POST["teach"]);
    exit();
?>