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
        } else {
            return false;
        } 
    }
    function pythongrader(){
        $pyout = shell_exec('python student.py');
        return $pyout;
    }
    $xmlString = "<Exam><ExamName>2Qs2</ExamName><ExamID>12</ExamID><Q><QID>1</QID><Question>Write a function named operation that takes three arguments:
    1) op, a string, e.g., '+','-','*','/'
    2) a &amp; b, two ints
    Function operation should perform the operation specified
    by op on the two operands a and b and return the correct
    result.
    For example, operation('+', 5, 3) should return 8.</Question><Points>10</Points><StudentAnswer>def operation(op,a,b):
       return 8</StudentAnswer><Score>--</Score><Grading><Name><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></Name><TC1><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC1><TC2><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC2><TC3><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC3><TC4><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC4><TC5><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC5><Constraint><Type></Type><Pass></Pass><AGScore></AGScore><HScore></HScore></Constraint></Grading><Comments></Comments></Q><Q><QID>4</QID><Question>Write a function named addbyloop that takes three parameters:
    1) num, an int with value to be added to
    2) itr, the number of iterations of the loop
    3) inc, the amount to add to num each iteration
    
    addbyloop(5,2,3) = 11</Question><Points>10</Points><StudentAnswer>def addbyloop(num,itr,inc):
       return num + (itr * inc)</StudentAnswer><Score>--</Score><Grading><Name><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></Name><TC1><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC1><TC2><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC2><TC3><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC3><TC4><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC4><TC5><FCall></FCall><Actual></Actual><Given></Given><AGScore></AGScore><HScore></HScore></TC5><Constraint><Type></Type><Pass></Pass><AGScore></AGScore><HScore></HScore></Constraint></Grading><Comments></Comments></Q></Exam>";
    $examid = '12';
    $jsondata = array(
        'RequestType' => 'TestCases',
        'ExamID' => $examid
    );
    echo $examid;
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
    print_r($resultvar);
    curl_close($ch);
    //---------------------------------------
    $qarray = json_decode($resultvar,true);
    print_r($qarray);
    $questionIDs= $qarray["RequestResponse"]["QuestionIDs"];
    $testcases= $qarray["RequestResponse"]["TestCases"];
    $constraintcase = $qarray["RequestResponse"]["CSTRAINTs"];
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->loadXML($xmlString);
    print_r($questionIDs);
    print_r($testcases);
    print_r($constraintcase);

    foreach($xml->getElementsbyTagName('Q') as $q){
        print_r($q->getElementsbyTagName('QID')->item(0)->nodeValue);
        echo "Next\n";
    }
?>
