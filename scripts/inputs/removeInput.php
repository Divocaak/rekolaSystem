<?php
require_once "../config.php";

$sql = "DELETE FROM inputs WHERE id=" . $_GET["inputId"] . ";";

if (mysqli_query($link, $sql)) {
  $return = "Záznam odstraněn";
} else {
  $return =  "Error při mazání záznamu: " . mysqli_error($link);
}
mysqli_close($link);
?>

<!doctype html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link rel="stylesheet" href="../../style.css">
</head>

<body>
    <div class="row mx-3">
        <div class="col-12">
            <p><?php echo $return; ?></p>
        </div>
        <div class="col-12 pt-3 d-flex justify-content-center">
            <a href="../../index.php" class="btn btn-primary pink-primary">Zpět</a>
        </div>
    </div>
</body>

</html>