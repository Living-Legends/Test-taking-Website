<?php
    session_start();
    $_SESSION['id'] = '123';
    /*Things To Do For This File:
      Set Cookie Params Location On First Line of Code
      Set Action of Form To Correct File Location
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
    $user = $_GET['user'];

    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "TakeQuiz",
        'ExamID' => $examID
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($result, true);
    $examName = $response["RequestResponse"]["ExamName"];
    $questionIDs = $response["RequestResponse"]["QuestionIDs"];
    $pVs = $response["RequestResponse"]["PointValues"];
    $questions = $response["RequestResponse"]["Questions"];
    $constraints = $response["RequestResponse"]["CSTRAINTs"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <style>
            body {
                display: flex;
                justify-content: center;
            }
            main {
                display: flex;
                justify-content: center;
            }
            form {
                width: 750px;
            }
            #question1 {
                display: block;
            }
            #question2 {
                display: none;
            }
            #question3 {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="container py-3">
            <header>
                <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                    <h1 class="display-4 fw-normal">Exam: <?php echo $examName;?></h1>
                    <p class="fs-5 text-muted">Answer every question to the best of your ability. Follow All Guidelines and DO NOT CHEAT. Good Luck!</p>
                </div>
            </header>
            <main>
                <form action="https://afsaccess4.njit.edu/~vs653/ProjCand/submitQuiz.php?examID=<?php echo $examID;?>&examName=<?php echo $examName;?>" method="post" id="takeExam">
                    <input type='text' name='examName' hidden='true' value='<?php echo $$examName;?>'></input>
                    <input type='text' name='user' hidden='true' value='<?php echo $user;?>'></input>
                    <div id="Questions">
                        <?php
                            for($i = 0; $i < count($questionIDs); $i++) {
                                if($i == 0) {
                                    echo "<div id='question" . ($i+1) . "' display='block'>";
                                } else {
                                    echo "<div id='question" . ($i+1) . "' display='none'>";
                                }
                                echo "<div class='form-group row'>";
                                echo "<label class='col-sm-6 col-form-label col-form-label-lg'> Question #" . ($i+1) . "     -   Points: " . $pVs[$i] . "</label><input type='number' name='pV[]' value='" . $pVs[$i] . "' hidden='true'></input>";
                                echo "<input type='number' name='qNum[]' value='" . $questionIDs[$i] . "' hidden='true'></input>";
                                echo "<label class='col-sm-6 col-form-label col-form-label-lg'> Constraints (if any): " . $constraints[$i] . "</label><br>";
                                echo "</div>";
                                echo "<div class='form-group row'>";
                                echo "<div class='col-sm-12'>";
                                echo "<label for='question'> Question: </label><br>";
                                echo "<textarea form='takeExam'  id='question' class='form-control' name='qtn[]' readonly='true' rows='10'>" . $questions[$i] . "</textarea>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='form-group row'>";
                                echo "<div class='col-sm-12'>";
                                echo "<label for='answer'> Enter Answer: </label><br>";
                                echo "<textarea form='takeExam' onkeydown='insertTab(this,event);' class='form-control' name='answer[]' required rows='10' placeholder='Enter Answer Here'></textarea>";
                                echo "</div>";
                                echo "</div>";
                                if($i == 0) {
                                    echo "<button id='". ($i+1) . "' class='next btn btn-success' onclick='nextQ(".($i+1).")' type='button'>Next</button>";
                                } else if($i < count($questionIDs)-1) {
                                    echo "<button id='" . ($i+1) . "' class='previous btn btn-success' onclick='prevQ(".($i+1).")'type='button'>Previous</button>";
                                    echo "<button id='". ($i+1) . "' class='next btn btn-success' onclick='nextQ(".($i+1).")' type='button'>Next</button>";
                                } else {
                                    echo "<button id='" . ($i+1) . "' class='previous btn btn-success' onclick='prevQ(".($i+1).")' type='button'>Previous</button>";
                                }
                                echo "</div><br>";
                            }
                        ?>
                    </div>
                    <input type="submit" value='Submit Quiz'></input>
                </form>
            </main>
        </div>
    </body>
    <script>
        function nextQ(id) {
            var nid = id+1;
            var nq = document.getElementById('question' + nid);
            var oq = document.getElementById('question' + id);
            nq.style.display = "block";
            oq.style.display = "none";
        }
        function prevQ(id) {
            var nid = id-1;
            var nq = document.getElementById('question' + nid);
            var oq = document.getElementById('question' + id);
            nq.style.display = "block";
            oq.style.display = "none";
        }
        function insertTab(o, e) {		
            var kC = e.keyCode ? e.keyCode : e.charCode ? e.charCode : e.which;
            if (kC == 9 && !e.shiftKey && !e.ctrlKey && !e.altKey) {
                var oS = o.scrollTop;
                if (o.setSelectionRange) {
                    var sS = o.selectionStart;	
                    var sE = o.selectionEnd;
                    o.value = o.value.substring(0, sS) + "\t" + o.value.substr(sE);
                    o.setSelectionRange(sS + 1, sS + 1);
                    o.focus();
                } else if (o.createTextRange) {
                    document.selection.createRange().text = "\t";
                    e.returnValue = false;
                }
                o.scrollTop = oS;
                if (e.preventDefault) {
                    e.preventDefault();
                }
                return false;
            }
            return true;
        }
    </script>
</html>