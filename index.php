<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "scripts/config.php";
?>

<!doctype html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Rekola sys</title>
</head>

<body>
    <div class="row">
        <div class="col-4">
            <div class="container">
                <p><?php echo "Přihlášen jako <b>" . $_SESSION["fName"] . " " . $_SESSION["lName"] . "</b>";?>
            </div>
            <div class="row">
                <div class="col-6">
                    <a href="changePass.php" class="btn btn-warning">Změnit heslo</a>
                </div>
                <div class="col-6">
                    <a href="scripts/logout.php" class="btn btn-danger">Odhlásit</a>
                </div>
                <div class="row">
                    <div class="col">
                        <a id="writeTableBtn" class="btn btn-primary">Vypsat</a>
                    </div>
                    <div class="col">
                        <label for="table_month" class="form-label">Měsíc</label>
                        <input type="number" class="form-control" name="table_month" id="table_month">
                    </div>
                    <div class="col">
                        <label for="table_year" class="form-label">Rok</label>
                        <input type="number" class="form-control" name="table_year" id="table_year">
                    </div>
                    <?php
                        if($_SESSION["isAdmin"] == true){
                            echo '<div class="col" data-user-id=' . $_SESSION["id"] . ' id="userIdHolder">';
                            echo '<p>Pracovník</p>';
                            echo '<select name="table_user" id="table_user" class="form-select" 
                            aria-label="Default select example">';

                            $sql = 'SELECT id, fName, lName FROM users;';
                            if ($result = mysqli_query($link, $sql)) {
                                if(mysqli_num_rows($result) > 0){
                                    while ($row = mysqli_fetch_row($result)) {
                                        echo '<option value="' . $row[0] .'">' . $row[1] . ' ' . $row[2] .'</option>';
                                    }
                                }
                                else{
                                    echo '<option value="x">Error</option>';
                                }
                                mysqli_free_result($result);
                            }
                            mysqli_close($link);
                            echo '</select>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Přidat z hodnot
                        <br>
                        <form>
                            <div class="col">
                                <label for="input_from" class="form-label">Den:</label>
                                <input type="date" class="form-control" name="input_day" id="input_day">
                            </div>
                            <div class="col">
                                <label for="input_from" class="form-label">Od:</label>
                                <input type="time" class="form-control" name="input_from" id="input_from">
                            </div>
                            <div class="col">
                                <label for="input_to" class="form-label">Do:</label>
                                <input type="time" class="form-control" name="input_to" id="input_to">
                            </div>
                            <div class="col">
                                <p>Převládající činnost:</p>
                                <select name="input_type" id="input_type" class="form-select"
                                    aria-label="Default select example">
                                    <option value="1">Nespecifikováno</option>
                                    <option value="2">Terén</option>
                                    <option value="3">Dílna</option>
                                    <option value="4">Baterky/GPS</option>
                                    <option value="5">Svoz</option>
                                    <option value="6">Jiné</option>
                                </select>
                            </div>
                            <div class="col">
                                <button id="addInputFromVals" class="btn btn-primary">Přidat zápis</button>
                            </div>
                        </form>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Přidat z textu TODO
                    </label>
                </div>
            </div>
        </div>
        <div class="col-4">
        
                
        </div>


    </div>
    <script>
    $(document).ready(function() {
        $('#addInputFromVals').click(function() {
            var inputFrom = $("#input_from").val();
            var inputTo = $("#input_to").val();
            var inputDay = $("#input_day").val();
            var inputType = $("#input_type").val();

            if (inputFrom && inputTo && inputDay) {
                $.ajax({
                    url: 'scripts/addInputFromValues.php',
                    type: 'post',
                    data: {
                        input_from: inputFrom,
                        input_to: inputTo,
                        input_day: inputDay,
                        input_type: inputType
                    },
                    success: function(response) {
                        alert(response);
                    }
                });
            } else {
                alert("Zadejte všechny údaje");
            }
        });

        $("#writeTableBtn").click(function() {
            var month = $("#table_month").val();
            var year = $("#table_year").val();
            var user = $("#table_user").length() ? $("#table_user").val() : $("#userIdHolder").data(
                'userId');

            $.ajax({
                url: 'scripts/writeTable.php',
                type: 'post',
                data: {
                    table_month: month,
                    table_year: year,
                    table_user: user
                },
                success: function(response) {
                    alert(response);
                }
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>