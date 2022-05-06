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
        'RequestType' => "GetStudents"
    );

    $jsonPayload = json_encode(array('Request' => $jsonData));

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($result, true);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/album/">

        <!-- Bootstrap core CSS -->
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
        <main>
            <section class="py-5 text-center container">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">View Students</h1>
                        <p class="lead text-muted">View all Students and their grades.</p>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <?php
                            for($i = 0; $i < count($response["RequestResponse"]); $i++) {
                                echo("<div class='col'>");
                                echo("<div class='card shadow-sm'>");
                                echo("<div class='card-body'>");
                                echo("<p class='card-text'>View " . $response["RequestResponse"][$i] . "'s Exams and Grades.</p>");
                                echo("<div class='d-flex justify-content-between align-items-center'>");
                                echo("<div class='btn-group'>");
                                echo("<a href='https://afsaccess4.njit.edu/~vs653/ProjCand/viewStudent.php?user=". $_GET["user"] . "&student=" . $response['RequestResponse'][$i] . "' class='btn btn-primary my-2'>View</a>");
                                echo("</div>");
                                echo("</div>");
                                echo("</div>");
                                echo("</div>");
                                echo("</div>");
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>