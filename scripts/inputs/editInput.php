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
    <div class="row" id="idHolder" data-input-id="<?php $_GET["inputId"];?>">
        <div id="addFromValues" class="pt-3">
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
                    <select name="input_type" id="input_type" class="form-select" aria-label="Default select example">
                        <option value="1">Nespecifikováno</option>
                        <option value="2">Terén</option>
                        <option value="3">Dílna</option>
                        <option value="4">Baterky/GPS</option>
                        <option value="5">Svoz</option>
                        <option value="6">Jiné</option>
                    </select>
                </div>
                <div class="col pt-3 d-flex justify-content-center">
                    <button id="addInputFromVals" class="btn btn-primary pink-primary">Upravit zápis</button>
                </div>
            </form>
        </div>

        <div class="col-12 pt-3 d-flex justify-content-center">
            <a href="../../index.php" class="btn btn-primary pink-primary">Zpět</a>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#addInputFromVals').click(function() {
            var inputFrom = $("#input_from").val();
            var inputTo = $("#input_to").val();
            var inputDay = $("#input_day").val();
            var inputType = $("#input_type").val();
            var inputId = $("#idHolder").data("inputId");

            if (inputFrom && inputTo && inputDay) {
                $.ajax({
                    url: 'scripts/editInputBack.php',
                    type: 'post',
                    data: {
                        input_from: inputFrom,
                        input_to: inputTo,
                        input_day: inputDay,
                        input_type: inputType,
                        input_id: inputId
                    },
                    success: function(response) {
                        alert(response);
                    }
                });
            } else {
                alert("Zadejte všechny údaje");
            }
        });
    });
    </script>
</body>

</html>