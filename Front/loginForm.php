<?php
    session_start();
    $_SESSION['id'] = '123';
    $failed = "true";
    if(isset($_SESSION["login"]) && $_SESSION["login"] == "false") {
        $failed = "false";
    } 
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <style>
            body {
                display: flex;
                justify-content: center;
            }
            main {
                width: 350px;
                height: 450px;
            }
        </style>
    </head>
    <body class="text-center">
        <main class="form-signin">
            <form action="login.php" method="post">
                <h1 class="h3 mb-3 fw-normal">Login</h1>
                <h2 class="hidden" hidden='<?php echo $failed;?>'>Bad Credentials. Please Try Again</h2>
                <div class="form-floating">
                    <input type='text' name="username" autocomplete="off" class="form-control" id="floatingInput">
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating">
                    <input type='password' name="password" class="form-control" id="floatingPassword" autocomplete="off">
                    <label for="floatingPassword">Password</label>
                </div>
                <input type='submit' class="w-100 btn btn-lg btn-primary" value='Press'>
            </form>
        </main>
    </body>
</html>

