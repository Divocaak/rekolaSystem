<?php
// Include config file
require_once "scripts/config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $discount = $lName = $fName = "";
$username_err = $password_err = $confirm_password_err = $lName_err = $fName_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Prosím, zadejte uživatelské jméno";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Toto uživatelské jméno je již registrováno.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Něco se nepovedlo, zkuste to prosím později.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    if(empty(trim($_POST["fName"]))){
        $fName_err = "Zadejte křestní jméno";
    }
    else{
        $fName = trim($_POST["fName"]);
    }

    if(empty(trim($_POST["lName"]))){
        $lName_err = "Zadejte příjmení";
    }
    else{
        $lName = trim($_POST["lName"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Prosím, zadejte heslo.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Heslo musí obsahovat minimálně 6 znaků.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Prosím, potvrďte heslo.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Hesla se neshodují.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($fName_err) && empty($lName_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, fName, lName) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_fName, $param_lName);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fName = $fName;
            $param_lName = $lName;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Registrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body>
    <div class="row mx-3">
        <div class="col-12 pt-5 d-flex justify-content-center">
            <h1>Rekola IS České Budějovice</h1>
        </div>
        <div class="col-12 pt-5 d-flex justify-content-center">
            <h2>Registrace</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group col-12 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Uživatelské jméno</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group col-12 <?php echo (!empty($fName_err)) ? 'has-error' : ''; ?>">
                <label>Křestní jméno</label>
                <input type="text" name="fName" class="form-control" value="<?php echo $fName; ?>">
                <span class="help-block"><?php echo $fName_err; ?></span>
            </div>
            <div class="form-group col-12 <?php echo (!empty($lName_err)) ? 'has-error' : ''; ?>">
                <label>Příjmení</label>
                <input type="text" name="lName" class="form-control" value="<?php echo $lName; ?>">
                <span class="help-block"><?php echo $lName_err; ?></span>
            </div>
            <div class="form-group col-12 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Heslo</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group col-12 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Heslo znovu</label>
                <input type="password" name="confirm_password" class="form-control"
                    value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="d-grid gap-2">
                <input type="submit" class="btn btn-primary" value="Registrovat se">
                <a href="login.php" class="btn btn-secondary">Přihlásit</a>
            </div>
        </form>
    </div>
</body>

</html>