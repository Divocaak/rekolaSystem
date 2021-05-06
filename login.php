<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "scripts/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Prosím, zadejte uživatelské jméno.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Prosím, zadejte heslo.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, password, username, fName, lName, moneyRate, isAdmin, isDriver FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $hashed_password, $username, $fName, $lName, $moneyRate, $isAdmin, $isDriver);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fName"] = $fName;
                            $_SESSION["lName"] = $lName;
                            $_SESSION["moneyRate"] = $moneyRate;
                            $_SESSION["isAdmin"] = $isAdmin;
                            $_SESSION["isDriver"] = $isDriver;


                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            echo $password . ' ' . $hashed_password;
                            // Display an error message if password is not valid
                            $password_err = "Nesprávné heslo.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Neznámý e-mail.";
                }
            } else{
                echo "Něco se nepovedlo, zkuste to prosím později.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Přihlaste se</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="row mx-3">
        <div class="col-12 pt-5 d-flex justify-content-center">
            <h1>Rekola IS České Budějovice</h1>
        </div>
        <div class="col-12 pt-5 d-flex justify-content-center">
            <h2>Přihlaste se</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group col-12 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Uživatelské jméno</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group col-12 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Heslo</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="d-grid gap-2">
                <input type="submit" class="btn btn-primary pink-primary" value="Přihlásit se">
                <a href="register.php" class="btn btn-secondary pink-secondary">Registrovat</a>
            </div>
        </form>
    </div>
</body>

</html>