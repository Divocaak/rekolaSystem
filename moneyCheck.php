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

    <title>Rekola sys - Rozpočet</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="row mx-3">
        <div class="col-12 d-flex justify-content-center">
            <a href="index.php" class="btn btn-primary pink-primary mt-3">Zpět</a>
        </div>
        <div class="col-12">
            <h2 class="pt-5">Hlídání rozpočtu<h2>
        </div>
        <div class="col-6">
            <label for="table_month" class="form-label">Měsíc</label>
            <input type="number" class="form-control" name="table_month" id="table_month">
        </div>
        <div class="col-6">
            <label for="table_year" class="form-label">Rok</label>
            <input type="number" class="form-control" name="table_year" id="table_year">
        </div>
        <div class="col-12">
            <label for="table_money" class="form-label">Rozpočet (v Kč)</label>
            <input type="number" class="form-control" name="table_money" id="table_money">
        </div>
        <div class="col-12 pt-3 d-flex justify-content-center">
            <a id="writeTableBtn" class="btn btn-primary pink-primary">Vypsat</a>
        </div>
        <div class="col-xs-12 col-md-4" id="writeTableCont">
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#writeTableBtn').click(function() {
            var month = $("#table_month").val();
            var year = $("#table_year").val();
            var money = $("#table_money").val();
            
            if (month && year && money) {
                $.ajax({
                    url: 'scripts/moneyCheckScript.php',
                    type: 'post',
                    data: {
                        table_month: month,
                        table_year: year,
                        table_money: money,
                    },
                    success: function(response) {
                        $("#writeTableCont").html(response);
                    }
                });
            } else {
                alert("Zadejte všechny údaje");
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>