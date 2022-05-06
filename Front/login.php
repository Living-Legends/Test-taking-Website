<?php
    session_start();
    $_SESSION['id'] = '123';
    // File to send json credentials to Middle End
    //error reporting code
    //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
    
    ini_set('display_errors' , 1);
    //Recieve Form Data Through Post
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hpassword = hash('md5', $password);
    //Initialiaze Curl and Create JSON payload
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "LoginCheck",
        'Username' => $username,
        'Password' => $hpassword
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));
    //Set Options of Curl
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //Execute Curl and Close Connection
    $result = curl_exec($curl);

    curl_close($curl);
    //Decoding JSON Response 
    $postData = json_decode($result, true);
    //Extracting Data From JSON payload and Hashing Password/

    $access = $postData["RequestResponse"]["accessLevel"];
    $isValid = $postData["RequestResponse"]["isValid"];
    //Checks Data and Redirects Based On Values
    if ($isValid == "False"){
        $_SESSION["login"] = "false";
        header ("refresh: 1, url=loginForm.php");
        exit();
    } else {
        if ($access == "STUDENT"){
            $_SESSION["login"] = "true";
            $_SESSION["level"] = "student";
            header ("refresh: 1, url=student_login.php?user=" . $username);
            exit();    
        }
        else if ($access == "TEACHER"){
            $_SESSION["login"] = "true";
            $_SESSION["level"] = "teacher";
            header ("refresh: 1, url=teacher_login.php?user=" . $username);
            exit();  
        }
        
    }
?>