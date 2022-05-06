<?php
    session_start();
    $_SESSION['id'] = '123';
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
    $url = "https://afsaccess4.njit.edu/~vs653/ProjCand/mEnd.php";
    $curl = curl_init($url);
    $jsonData = array(
        'RequestType' => "LoadActiveQuizzes"
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
        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/pricing/">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://afsaccess4.njit.edu/~vs653/ProjCand/student_login.php?user=<?php echo $_GET["user"];?>">Back To Landing Page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container py-3"></div>
        <div class="container py-3">
            <header>
                <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                    <h1 class="display-4 fw-normal">Active Exams</h1>
                    <p class="fs-5 text-muted">View All Active Exams and Point Values. Enter Exam ID of Exam To Take.</p>
                </div>
            </header>
            <main class="form-control">
                <div class="row row-cols-1 row-cols-md-2 mb-3 text-center">
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Exams</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-border table-striped">
                                    <thead>
                                        <tr>
                                            <th>Exam ID</th>
                                            <th>Exam Name</th>
                                            <th>Total Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            for($i = 0; $i < count($response["RequestResponse"]); $i++) {
                                                echo "<tr>";
                                                echo "<td align='center'>" . $response["RequestResponse"][$i]["Exam ID"] . "</td>";
                                                echo "<td align='center'>" . $response["RequestResponse"][$i]["Exam Name"] . "</td>";
                                                echo "<td align='center'>" . $response["RequestResponse"][$i]["Total Points"] . "</td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Enter Exam ID</h4>
                            </div>
                            <div class="card-body">
                                <form action="https://afsaccess4.njit.edu/~vs653/ProjCand/takeExam.php?user=<?php echo $_GET["user"];?>" method="post">
                                    <label class="Instruction">Enter The Exam ID of Exam You Want To Take:</label><br>
                                    <input type="text" name="examID" placeholder="Enter Exam ID"></input><br>
                                    <input type="submit" name="submit"></input><br>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>