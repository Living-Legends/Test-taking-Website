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
   
    
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "LoadQuestions"
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($result, true);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://afsaccess4.njit.edu/~vs653/ProjCand/teacher_login.php?user=<?php echo $_GET["user"];?>">Back To Landing Page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container py-3"></div>
        <div class='container py-3'>
            <header>
                <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                    <h1 class="display-4 fw-normal">View Questions and Make Exams</h1>
                    <p class="fs-5 text-muted">View all Questions in Database. Add Questions To Make An Exam</p>
                </div>
            </header>
            <main>
                <div class='row'>
                    <div class='col-lg-6'>
                        <form action="https://afsaccess4.njit.edu/~vs653/ProjCand/makeExam.php?user=<?php echo $_GET["user"];?>" method="post">
                            <div class='form-group row'>
                                <label class="col-sm-2 col-form-label" for='topicL'>Exam Name:</label>
                                <div class='col-sm-6'>
                                    <input type='text' name='examName' class='form-control' id='topicL' required></input>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label" for='tP'>Total Points:</label>
                                <div class='col-sm-6'>
                                    <input type='number' name='points' class='form-control' id='tP' required></input>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label" for='status'>Choose an exam status:</label>
                                <div class='col-sm-3'>
                                    <select name="status" class='form-control' id="status" required>
                                        <option value="Active">Active</option>
                                        <option value="Hidden">Hidden</option>
                                        <option value="Finished">Finished</option>
                                    </select>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label" for='questL'>Questions and Point Values:</label>
                                <div class='col-sm-9'>
                                    <table class='table table-border table-striped'>
                                        <tr>
                                            <th>Question ID</th>
                                            <th>Point Values</th>
                                        </tr>
                                        <tr>
                                            <td><input type="number" id='qID_A' name="qID[]" placeholder="Question ID" readonly='true'></input></td>
                                            <td><input type="text" name="pV[]" placeholder="Point Value"></input></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" id='qID_B' name="qID[]" placeholder="Question ID" readonly='true'></input></td>
                                            <td><input type="text" name="pV[]" placeholder="Point Value"></input></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" id='qID_C' name="qID[]" placeholder="Question ID" readonly='true'></input></td>
                                            <td><input type="text" name="pV[]" placeholder="Point Value"></input></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" id='qID_D' name="qID[]" placeholder="Question ID" readonly='true'></input></td>
                                            <td><input type="text" name="pV[]" placeholder="Point Value"></input></td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" id='qID_E' name="qID[]" placeholder="Question ID" readonly='true'></input></td>
                                            <td><input type="text" name="pV[]" placeholder="Point Value"></input></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <input type='submit' class='form-control' value='Make Exam'></input>
                        </form>
                    </div>
                    <div class='col-lg-6'>
                        <form>
                            <div class='form-group row'>
                                <div class='col-sm-4'>
                                    <input type="text" id="q_search" onkeyup="search()" placeholder="Question Search..." class="form-control">
                                </div>
                                <div class='col-sm-4'>
                                    <input type="text" id="t_search" onkeyup="search()" placeholder="Topic.." class="form-control">
                                </div>
                                <div class='col-sm-4'>
                                    <input type="text" id="d_search" onkeyup="search()" placeholder="Difficulty.." class="form-control">
                                </div>
                            </div>
                            <div class='form-group row'>
                                <table id='Q' class="table table-border table-striped">
                                    <tr>
                                        <th>Question ID</th>
                                        <th>Topic</th>
                                        <th>Difficulty</th>
                                        <th>Question</th>
                                        <th>Constraint</th>
                                        <th>Test Cases</th>
                                        <th>Select</th>
                                    </tr>
                                    <?php
                                        for($i = 0; $i < count($response["RequestResponse"]); $i++) {
                                            echo "<tr>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["Question ID"] . "</td>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["Topic"] . "</td>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["Difficulty"] . "</td>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["Question"] . "</td>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["CSTRAINT"] . "</td>";
                                            echo "<td align='center'>" . $response["RequestResponse"][$i]["Test Cases"] . "</td>";
                                            echo "<td><input type='button' onclick='addQ(".$response["RequestResponse"][$i]["Question ID"].")' value='Add Question'></input></td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        <script>
            function addQ(qID) {
                if(document.getElementById("qID_A").value == '') {
                    document.getElementById("qID_A").value = qID;
                } else if(document.getElementById("qID_B").value == '') {
                    document.getElementById("qID_B").value = qID;
                } else if(document.getElementById("qID_C").value == '') {
                    document.getElementById("qID_C").value = qID;
                } else if(document.getElementById("qID_D").value == '') {
                    document.getElementById("qID_D").value = qID;
                } else if(document.getElementById("qID_E").value == '') {
                    document.getElementById("qID_E").value = qID;
                } else {
                    console.alert('Max Amount Of Questions Added');
                }
            }
            function search() {
                var question, topic, diff, table, fQ, fT, fD, td, tr, td_Q, td_T, td_D, td_check, td_P, num_questions;
                num_questions = 0;
                question = document.getElementById("q_search");
                topic = document.getElementById("t_search");
                diff = document.getElementById("d_search");
                fQ = question.value.toUpperCase();
                fT = topic.value.toUpperCase();
                fD = diff.value.toUpperCase();
                table = document.getElementById("Q");
                tr = table.getElementsByTagName("tr");

                for (let i = 1; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td");
                    
                    
                    if (td[3]){
                        td_Q = td[3].textContent || td[3].innerText;
                    }
                    if (td[1]){
                        td_T = td[1].textContent || td[1].innerText;
                    }
                    if (td[2]){
                        td_D = td[2].textContent || td[2].innerText;
                    }

                    if (td[4].checked){
                        if (num_questions >= 5){
                            console.log("Test cases limit reached");
                            exit();
                        }
                        
                        td_left[0].innerText = td_Q;
                        num_questions+=1;
                        
                    }
                
    
                    if (td_Q.toUpperCase().indexOf(fQ) > -1 && td_T.toUpperCase().indexOf(fT) > -1 && td_D.toUpperCase().indexOf(fD) > -1) {
                        tr[i].style.display = "";
                    } else 
                        tr[i].style.display = "none";
                    
                }
            }
        </script>
    </body>
</html>