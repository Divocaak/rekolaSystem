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

    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="charts/lib/js/chartphp.css">
    <script src="charts/lib/js/jquery.min.js"></script>
    <script src="charts/lib/js/chartphp.js"></script>
</head>

<body>
    <div class="row m-3">
        <div class="col-xs-12 col-md-4">
            <div class="container">
                <?php echo "<p data-user-id=" . $_SESSION["id"] . " id='userIdHolder'>Přihlášen jako <b>" . $_SESSION["fName"] . " " . $_SESSION["lName"] . "</b></p>";?>
            </div>
            <div class="row">
                <div class="col-6">
                    <a href="changePass.php" class="btn btn-warning pink-warning">Změnit heslo</a>
                </div>
                <div class="col-6">
                    <a href="scripts/logout.php" class="btn btn-danger pink-danger">Odhlásit</a>
                </div>
                <?php
                    if($_SESSION["isAdmin"] == true){
                        echo '<div class="row pt-5">';
                        echo '<div class="col-6">';
                        echo '<h4>Rozpočet</h4>';
                        echo '</div>';
                        echo '<div class="col-6">';
                        echo '<a href="moneyCheck.php" class="btn btn-primary pink-primary">Zobrazit</a>';
                        echo '</div>';
                        echo '</div>';
                        
                        echo '<div class="row pt-5">';
                        echo '<div class="col-6">';
                        echo '<h4>Správa GPS</h4>';
                        echo '</div>';
                        echo '<div class="col-6">';
                        echo '<a href="" class="btn btn-primary pink-primary">Zobrazit</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <h4 class="pt-5">Výpis<h4>
                    </div>
                    <?php
                        if($_SESSION["isAdmin"] == true){
                            echo '<div class="col-12">';
                            echo '<p>Pracovník</p>';
                            echo '<select name="table_user" id="table_user" class="form-select" 
                            aria-label="Default select example">';
                            
                            $sql = 'SELECT id, fName, lName FROM users;';
                            if ($result = mysqli_query($link, $sql)) {
                                if(mysqli_num_rows($result) > 0){
                                    while ($row = mysqli_fetch_row($result)) {
                                        echo '<option value="' . intval($row[0]) .'">' . $row[1] . ' ' . $row[2] .'</option>';
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
                    <div class="col-6">
                        <label for="table_month" class="form-label">Měsíc</label>
                        <input type="number" class="form-control" name="table_month" id="table_month">
                    </div>
                    <div class="col-6">
                        <label for="table_year" class="form-label">Rok</label>
                        <input type="number" class="form-control" name="table_year" id="table_year">
                    </div>
                    <div class="col-12 pt-3 d-flex justify-content-center">
                        <a id="writeTableBtn" class="btn btn-primary pink-primary">Vypsat</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <h4 class="pt-5">Přidat zápis<h4>
                </div>
                <div class="form-check pt-3">
                    <input class="form-check-input pink-primary" type="radio" name="flexRadioDefault"
                        id="flexRadioDefault1" checked>
                    <label class="form-check-label" for="flexRadioDefault1">
                        Přidat z hodnot
                    </label>
                </div>
                <div class="form-check pt-3">
                    <input class="form-check-input pink-primary" type="radio" name="flexRadioDefault"
                        id="flexRadioDefault2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Přidat z textu
                    </label>
                </div>

                <div id="addFromValues" class="pt-3">
                    <form>
                        <div class="col">
                            <label for="input_day" class="form-label">Den:</label>
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
                        <div class="col collapse" id="distanceDriven">
                            <label for="input_distance" class="form-label">Naježděno (zaokrouhleno na celé km)</label>
                            <input type="number" class="form-control" name="distance" id="input_distance">
                        </div>
                        <div class="col pt-3 d-flex justify-content-center">
                            <button id="addInputFromVals" class="btn btn-primary pink-primary">Přidat zápis</button>
                        </div>
                    </form>
                </div>

                <div id="addFromText" class="collapse pt-3">
                    <p>
                        Hodiny zapisujte tímto způsobem: </br>
                        měsíc (číslem)/rok</br>
                        .den) od do práce | od do práce</br></br>
                        příklad:</br>
                        6/2021</br>
                        .3) 10:50 12:30 t | 12:30 13:45 d</br>
                        .5) 11:50 13:40 d | 18:00 20:55 s !36!</br>
                        ...</br></br>
                        n = nespecifikováno</br>
                        t = terén</br>
                        d = dílna</br>
                        g = baterky/GPS</br>
                        s = svoz !kilometry!</br>
                        j = jiné
                    </p>
                    <form method="POST" action="scripts/addInputFromText.php">
                        <div class="col">
                            <textarea id="input_text" name="input_text" rows="15" cols="35"></textarea>
                        </div>
                        <div class="col pt-3 d-flex justify-content-center">
                            <button type="submit" id="addInputFromText" class="btn btn-primary pink-primary">Přidat zápis</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4" id="writeTableCont">
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#addFromValues').collapse('show');
        $('#addFromText').collapse('hide');
        $('#distanceDriven').collapse('hide');

        $('#input_type').change(function() {
            if ($(this).val() == 5) {
                $('#distanceDriven').collapse('show');
            } else {
                $('#distanceDriven').collapse('hide');
            }
        });

        $('#addInputFromVals').click(function() {
            var inputFrom = $("#input_from").val();
            var inputTo = $("#input_to").val();
            var inputDay = $("#input_day").val();
            var inputType = $("#input_type").val();

            var inputDistance = inputType == 5 ? $("#input_distance").val() : 0;

            if (inputFrom && inputTo && inputDay) {
                $.ajax({
                    url: 'scripts/addInputFromValues.php',
                    type: 'post',
                    data: {
                        input_from: inputFrom,
                        input_to: inputTo,
                        input_day: inputDay,
                        input_type: inputType,
                        input_distance: inputDistance
                    },
                    complete: function(response){
                        alert(response);
                    },
                });
            } else {
                alert("Zadejte všechny údaje");
            }
        });

        $("#writeTableBtn").click(function() {
            var month = $("#table_month").val();
            var year = $("#table_year").val();
            var user = $("#table_user").length ? $("#table_user").val() : $("#userIdHolder").data(
                'userId');

            $.ajax({
                url: 'scripts/writeTable.php',
                type: 'post',
                data: {
                    table_month: month,
                    table_year: year,
                    table_user: user,
                },
                success: function(response) {
                    $("#writeTableCont").html(response);
                }
            });
        });

        $('#flexRadioDefault1,#flexRadioDefault2').on('click', function(e) {
            e.stopPropagation();
            if (this.id == 'flexRadioDefault1') {
                $('#addFromValues').collapse('show');
                $('#addFromText').collapse('hide');
            } else if (this.id == 'flexRadioDefault2') {
                $('#addFromValues').collapse('hide');
                $('#addFromText').collapse('show');
            }
        })
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>