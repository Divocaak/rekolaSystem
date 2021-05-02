<?php
require_once "config.php";

$return = "";
$sql = "SELECT user_id, t_from, t_to, activity FROM inputs WHERE user_id=" . $_POST["user"] . ";";
if ($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_row($result)) {
            $return .= '<tr>
                    <th scope="row">' . $row [1]. '</th>
                    <td>' . $row[2] . '</td>
                    <td>' . $row[3] . '</td>
                    <td>' . 'celkem' . '</td>
                </tr>';
        }
    }
    else{
        $return .= "<p>Error</p>";
    }
    mysqli_free_result($result);
}
mysqli_close($link);

echo '<p>Tabulka za MĚSÍC ROK pracovníka JMÉNO PŘÍJMENÍ</p>
<table class="table table-dark table-hover">
    <thead>
        <tr>
            <th scope="col">Od</th>
            <th scope="col">Do</th>
            <th scope="col">Činnost</th>
            <th scope="col">Celkem</th>
        </tr>
    </thead>
    <tbody>' . $return . '</tbody>
    </table>';
?>