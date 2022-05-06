<?php
    /* Things to Do:
        -Change it from Test Case 1-5 to actual method calls
        -Change it to have two columns for autograder grade and human grade
    */
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
    if(isset($_POST["examID"])) {
        $examID = $_POST["examID"];
    } else {
        $examID = $_GET["examID"];
    }
    if(isset($_POST["student"])) {
        $user = $_POST["student"];
    } else {
        $user = $_GET["student"];
    }
    if(isset($_POST["user"])) {
        $teach = $_POST["user"];
    } else {
        $teach = $_GET["user"];
    }

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
    curl_close($curl);
    $response = json_decode($result, true);
    $xmlString = $response["RequestResponse"]["StudentAnswers"];
    $xmlString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xmlString);
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->loadXML($xmlString);

    $totalPoints = 0;
    foreach($xml->getElementsByTagName('Q') as $q) {
        $score = $q->getElementsByTagName('Score')->item(0)->nodeValue;
        if(is_numeric($score)){
            $totalPoints = $totalPoints + $score;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <style>
            table, th, td {
                border: 1px solid;
            }
            td {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://afsaccess4.njit.edu/~vs653/ProjCand/viewStudent.php?user=<?php echo $teach;?>&student=<?php echo $user;?>">Back To Student's Exams</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container py-3"></div>
        <div class="container py-3">
            <header>
                <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                    <h1 class="display-4 fw-normal"> View Exam: <?php echo $response["RequestResponse"]["ExamName"];?></h1>
                    <p class="fs-5 text-muted">Review Student Exam, Change Individual Question Scores and Total Score, and View Autograder Results.</p>
                </div>
            </header>
            <main>
                <form action="saveExam.php?user=<?php echo $teach;?>&student=<?php echo $user;?>" method="post" id='viewExam'>
                    <input type="text" hidden='true' name='student' value='<?php echo $user;?>'></input>
                    <input type="text" hidden="true" name="user" value='<?php echo $_POST["user"];?>'></input>
                    <input type="text" hidden='true' name='examID' value='<?php echo $examID;?>'></input>
                    <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for='tScore'>Total Score:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="tScore" name='totalScore' placeholder='<?php echo $response["RequestResponse"]["TotalPoints"]; ?>' value='<?php echo $totalPoints; ?>' required></input>
                            </div>
                            <label class='col-sm-2 col-form-label'>/<?php echo $response["RequestResponse"]["TotalPoints"]; ?></label>
                            <label class="col-sm-2 col-form-label">Release Status:</label>
                            <div class="col-sm-2">
                                <select name="Released" class="form-control form-control-sm" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                    </div>
                    <?php
                        $counter = 1;
                        foreach($xml->getElementsbyTagName('Q') as $q) {
                            $qID = $q->getElementsbyTagName('QID')->item(0)->nodeValue;
                            $question = $q->getElementsbyTagName('Question')->item(0)->nodeValue;
                            $points = $q->getElementsbyTagName('Points')->item(0)->nodeValue;
                            $sA = $q->getElementsbyTagName('StudentAnswer')->item(0)->nodeValue;
                            $score = $q->getElementsbyTagName('Score')->item(0)->nodeValue;
                            $grading = $q->getElementsbyTagName('Grading')->item(0);
                            $comments = $q->getElementsbyTagName('Comments')->item(0)->nodeValue;
                            echo "<br><br><br>";
                            echo "<input type='text' name='qID[]' value='" . $qID . "' hidden='true'></input><br>";
                            echo "<div class='form-group row'>";
                            echo "<div class='col-sm-4'>";
                            echo "<label class='col-sm-4 col-form-label col-form-label-md'> Question #" . $counter . " </label>";
                            echo "</div>";
                            echo "<label class='col-sm-2 col-form-label col-form-label-md'>Score:</label>";
                            echo "<div class='col-sm-2'>";
                            echo "<input type='text' class='form-control' id='scr' name='score[]' value='" . $score . "' required></input>";
                            echo "</div>";
                            echo "<label class='col-sm-2 col-form-label col-form-label-md'>/".$points."</label>";
                            echo "</div>";
                            echo "<div class='form-group row'>";
                            echo "<div class='col-sm-12'>";
                            echo "<label for='question'> Question: </label><br>";
                            echo "<textarea form='viewExam'  id='question' class='form-control' name='question[]' readonly='true' rows='10'>" . $question . "</textarea>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='form-group row'>";
                            echo "<div class='col-sm-12'>";
                            echo "<label for='sA'> Student Answer: </label><br>";
                            echo "<textarea form='viewExam'  id='sA' class='form-control' name='studentAnswer[]' readonly='true' rows='10'>" . $sA . "</textarea>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='form-group row'>";
                            echo "<div class='col-sm-12'>";
                            echo "<label class='col-sm-2 col-form-label col-form-label-md' for='Grading'> Grading: </label>";
                            $ppm = $grading->getElementsbyTagName('PPM')->item(0)->nodeValue;
                            if(is_numeric($ppm)) {
                                echo "<table id='Grading' class='table table-border table-striped'><thead><tr><th></th><th>Correct Answer/Constraint Type</th><th>What Student Gave/Constraint Pass or Fail</th><th>Autograde Score Out of " . $ppm ." Points Each</th><th>Teacher Adjustment Out of " . $ppm ." Points Each</th><tr></thead>";
                            } else {
                                echo "<table id='Grading' class='table table-border table-striped'><thead><tr><th></th><th>Correct Answer/Constraint Type</th><th>What Student Gave/Constraint Pass or Fail</th><th>Autograde Score</th><th>Teacher Adjustment</th><tr></thead>";
                            }
                            echo "<tbody><tr><td>Name of Method</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('Name')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            echo "<tr><td>".$grading->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('FCall')->item(0)->nodeValue."</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('TC1')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            echo "<tr><td>".$grading->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('FCall')->item(0)->nodeValue."</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('TC2')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            if($grading->getElementsbyTagName('TC3')->length != 0) {
                                echo "<tr><td>".$grading->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('FCall')->item(0)->nodeValue."</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('TC3')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            }
                            if($grading->getElementsbyTagName('TC4')->length != 0) {
                                echo "<tr><td>".$grading->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('FCall')->item(0)->nodeValue."</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('TC4')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            }
                            if($grading->getElementsbyTagName('TC5')->length != 0) {
                                echo "<tr><td>".$grading->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('FCall')->item(0)->nodeValue."</td><td><input type='text' name='actual[]' required value='" . $grading->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('Actual')->item(0)->nodeValue . "'></input></td><td><input type='text' name='given[]' required value='" . $grading->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('Given')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='scrAdj[]' value='" . $grading->getElementsbyTagName('TC5')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            }
                            if($grading->getElementsbyTagName('Constraint')->length != 0) {
                                echo "<tr><td>Constraint</td><td><input type='text' name='type[]' required value='" . $grading->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('Type')->item(0)->nodeValue . "'></input></td><td><input type='text' name='pass[]' required value='" . $grading->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('Pass')->item(0)->nodeValue . "'></input></td><td>" . $grading->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('AGScore')->item(0)->nodeValue . "</td><td><input type='text' name='conScr[]' value='" . $grading->getElementsbyTagName('Constraint')->item(0)->getElementsbyTagName('HScore')->item(0)->nodeValue . "'></input></td></tr>";
                            }
                            echo "</tbody></table><br>";
                            echo "<div class='form-group row'>";
                            echo "<div class='col-sm-12'>";
                            echo "<label for='cm'> Comments: </label><br>";
                            echo "<textarea form='viewExam'  id='cm' class='form-control' name='comments[]' rows='3'>" . $comments . "</textarea>";
                            echo "</div>";
                            echo "</div>";
                            echo "<br><br>";
                            $counter++;
                        }
                    ?>
            <input type="submit" name="submit" value='Save Exam'></input><br>
        </form>
        <form action="https://afsaccess4.njit.edu/~vs653/ProjCand/autogradeH.php" method="post">
                <input type='number' name='examID' value='<?php echo $examID;?>' hidden='true'></input><br>
                <input type="text" hidden='true' name='user' value='<?php echo $user;?>'></input><br>
                <input type="text" hidden="true" name="teach" value='<?php echo $_POST["user"];?>'></input>
                <input type="submit" name = "autograde" value="Autograde"></input><br>
        </form>
    </body>
</html>