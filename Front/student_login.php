<?php
    session_set_cookie_params(0,"https://afsaccess4.njit.edu/~vs653/download/");
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
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/features/">
        <style>
        </style>
    </head>
    <body>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                <div class="col-md-9"></div>
                <div class="col-md-3 text-end">
                    <form action="logout.php" method="post">
                        <input type="submit" value="Log Out" class="btn btn-outline-primary me-2"></input>
                    </form>
                </div>
            </header>
        </div>
        <div class="container px-4 py-5" id="featured-3">
            <h2 class="pb-2 border-bottom">Student Landing Page - Welcome <?php echo($_GET["user"]);?></h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-2">
                <div class="feature col">
                    <h2>View and Take Active Exams</h2>
                    <p>View Active Exams Assigned in Table. Enter Exam ID to take Exam.</p>
                    <a href="SExams.php?user=<?php echo($_GET["user"]); ?>" class="icon-link">
                        View and Take Exams
                    </a>
                </div>
                <div class="feature col">
                    <h2>View Graded Exams</h2>
                    <p>View all released and graded exams. Select individual exam by ID to view answers and grading.</p>
                    <a href="SGrades.php?user=<?php echo($_GET["user"]); ?>" class="icon-link">
                        View Graded Exams
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>