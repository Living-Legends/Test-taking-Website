<?php
    function constraints($constraint,$methodname, $sA){
        if($constraint == 'While'){
            if(preg_match('/(while)/', $sA) == 1){
                return true;
            }
        } elseif($constraint == 'For'){
            if(preg_match('/(for)/', $sA) == 1){
                return true;
            }
        } elseif($constraint == 'Recursion'){
            $pattern = '/('.$methodname.')/';
            $counter = preg_match_all($pattern, $sA);           
            if($counter > 1) {
                return true;
            }
        } else
            return false; 
    }

    function pythongrader(){
       $pyout = exec('python student.py');
       return $pyout;
    }

    
    $postdata = file_get_contents('php://input');
    $request = json_decode($postdata, true);

    if($request["Request"]["RequestType"] == "Autograde") {
        $xml_string = $request["Request"]["XMLString"];
        $user = $request["Request"]["Username"];
        $pyfilename = "student.py";

        $examid = $request["Request"]["ExamID"];

        $jsondata = array(
            'RequestType' => 'TestCases',
            'ExamID' => $examid
        );

        $jsonpayload = json_encode(array('Request' => $jsondata));
        $ch = curl_init(); //initialize curl
        $beurl = "https://afsaccess4.njit.edu/~vs653/ProjCand/auth.php"; //url to back end .php

        //set up url in curl 
        curl_setopt($ch, CURLOPT_URL, $beurl);
        //setting the return transfer to new
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Post in curl
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonpayload);
        
        $resultvar = curl_exec($ch);
        curl_close($ch);
        //---------------------------------------
        $qarray = json_decode($resultvar,true);
        $xml_data = new DOMDocument('1.0', 'UTF-8');
        $xml_data->loadXML($xml_string);

        $questionIDs= $qarray["RequestResponse"]["QuestionIDs"];
        $testcases= $qarray["RequestResponse"]["TestCases"];
        $constraintcase = $qarray["RequestResponse"]["CSTRAINTs"];
        $tScore = 0;
        $xmlString = "<Exam><ExamName>" . $xml_data->getElementsbyTagName('ExamName')->item(0)->nodeValue . "</ExamName><ExamID>" . $xml_data->getElementsbyTagName('ExamID')->item(0)->nodeValue . "</ExamID>";  
        foreach($xml_data->getElementsbyTagName('Q') as $q) {
            $i = 0;
            while($questionIDs[$i] != $q->getElementsbyTagName('QID')->item(0)->nodeValue) {
                $i++;       
            }
            $questionString = "<Q><QID>" . $q->getElementsbyTagName('QID')->item(0)->nodeValue . "</QID><Question>" . $q->getElementsbyTagName('Question')->item(0)->nodeValue . "</Question><Points>" . $q->getElementsbyTagName('Points')->item(0)->nodeValue . "</Points><StudentAnswer>" . $q->getElementsbyTagName('StudentAnswer')->item(0)->nodeValue . "</StudentAnswer><Grading>";
            $totalpoints = $q->getElementsbyTagName('Points')->item(0)->nodeValue;
            
            $studentanswer = $q->getElementsbyTagName('StudentAnswer')->item(0)->nodeValue;
            $trueanswer = $testcases[$i]; //result expected
            $split = preg_split("/[=;]/", $trueanswer);
            $idx = strpos($split[0], '(');
            $sAidx = strpos($studentanswer, '(');
            $cDef = substr($trueanswer,0,$idx);
            $sDef = substr($studentanswer,4,$sAidx-4);
            $marks = 1 + ((count($split)-1)/2);
            if($constraintcase[$i] != 'None') {
                $marks = $marks + 1;
            }
            $pV = $totalpoints / $marks;
            $questionString = $questionString . "<PPM>" . $pV . "</PPM>";
            $count = 0;
        //----------------------------------------------------//
        
    
        //----------------------------------------// do this for each test case
            $questionString = $questionString . "<Name><Actual>" . $cDef . "</Actual><Given>" . $sDef . "</Given><AGScore>";
            if($cDef == $sDef) {
                $questionString = $questionString . $pV . "</AGScore><HScore></HScore></Name>";
                $count = $count + 1;
            } else {
                $questionString = $questionString . "0</AGScore><HScore></HScore></Name>";
            }
            $studentanswer = substr($studentanswer,0,4) . $cDef . substr($studentanswer,$sAidx);
            for ($j = 0; $j < count($split)-1; $j= $j+2) {
                $method = $split[$j]; 
                $answer = $split[$j+1];
                $ansmethod = $studentanswer."\nprint(". $method. ")";
                file_put_contents($pyfilename, $ansmethod);
                // ------------- put the contents in the file
                $pygradeop = pythongrader();
                //---- gives full points
                if($j / 2 == 0) {
                    $questionString = $questionString . "<TC1><FCall>" . $method . "</FCall><Actual>" . $answer . "</Actual><Given>" . $pygradeop . "</Given><AGScore>";
                    if($pygradeop == $answer) {
                        $questionString = $questionString . $pV . "</AGScore><HScore></HScore></TC1>";
                        $count = $count + 1;
                    } else {
                        $questionString = $questionString . "0</AGScore><HScore></HScore></TC1>";
                    }
                } elseif($j / 2 == 1) {
                    $questionString = $questionString . "<TC2><FCall>" . $method . "</FCall><Actual>" . $answer . "</Actual><Given>" . $pygradeop . "</Given><AGScore>";
                    if($pygradeop == $answer) {
                        $questionString = $questionString . $pV . "</AGScore><HScore></HScore></TC2>";
                        $count = $count + 1;
                    } else {
                        $questionString = $questionString . "0</AGScore><HScore></HScore></TC2>";
                    }
                } elseif($j / 2 == 2) {
                    $questionString = $questionString . "<TC3><FCall>" . $method . "</FCall><Actual>" . $answer . "</Actual><Given>" . $pygradeop . "</Given><AGScore>";
                    if($pygradeop == $answer) {
                        $questionString = $questionString . $pV . "</AGScore><HScore></HScore></TC3>";
                        $count = $count + 1;
                    } else {
                        $questionString = $questionString . "0</AGScore><HScore></HScore></TC3>";
                    }
                } elseif($j / 2 == 3) {
                    $questionString = $questionString . "<TC4><FCall>" . $method . "</FCall><Actual>" . $answer . "</Actual><Given>" . $pygradeop . "</Given><AGScore>";
                    if($pygradeop == $answer) {
                        $questionString = $questionString . $pV . "</AGScore><HScore></HScore></TC4>";
                        $count = $count + 1;
                    } else {
                        $questionString = $questionString . "0</AGScore><HScore></HScore></TC4>";
                    }
                } elseif($j / 2 == 4) {
                    $questionString = $questionString . "<TC5><FCall>" . $method . "</FCall><Actual>" . $answer . "</Actual><Given>" . $pygradeop . "</Given><AGScore>";
                    if($pygradeop == $answer) {
                        $questionString = $questionString . $pV . "</AGScore><HScore></HScore></TC5>";
                        $count = $count + 1;
                    } else {
                        $questionString = $questionString . "0</AGScore><HScore></HScore></TC5>";
                    }
                }
            }
            $questionString = $questionString . "<Constraint><Type>" . $constraintcase[$i] . "</Type>";
            if($constraintcase[$i] != 'None') {
                $pass = constraints($constraintcase[$i], $cDef, $studentanswer);
                if($pass) {
                    $questionString = $questionString . "<Pass>Pass</Pass><AGScore>" . $pV . "</AGScore><HScore></HScore></Constraint></Grading>";
                    $count = $count + 1;
                } else {
                    $questionString = $questionString . "<Pass>Fail</Pass><AGScore>0</AGScore><HScore></HScore></Constraint></Grading>";
                }
            } else {
                $questionString = $questionString . "<Pass>--</Pass><AGScore>--</AGScore><HScore></HScore></Constraint></Grading>";
            }
            $score = $pV * $count;
            $questionString = $questionString . "<Score>" . $score . "</Score><Comments>--</Comments></Q>";
            $xmlString = $xmlString . "" . $questionString;
            $tScore = $tScore + $score;
        }
        $xmlString = $xmlString . "</Exam>";
        $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xmlString);
        $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/auth.php";
        $curl = curl_init($url);
        $jsonData = array(
            'RequestType' => 'SaveQuiz',
            'Username' => $user,
            'ExamID' => $examid,
            'Score' => $tScore,
            'StudentAnswers' => $xmlString,
            'Released' => 'No'
        );
    
        $jsonPayload = json_encode(array('Request' => $jsonData));
    
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        //Execute Curl and Close Connection
        $result = curl_exec($curl);
        curl_close($curl);
        $json_payload = json_encode(array('XML' => $xmlString));
        echo $json_payload;
    } else {
        $ch = curl_init(); //initialize curl
        $beurl = "https://afsaccess4.njit.edu/~vs653/ProjCand/auth.php"; //url to back end .php

        //set up url in curl 
        curl_setopt($ch, CURLOPT_URL, $beurl);
        //setting the return transfer to new
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Post in curl
        curl_setopt($ch, CURLOPT_POST, true);
            
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $reply = curl_exec($ch);
        
        curl_close($ch);
        
        echo $reply;
    }
?>
