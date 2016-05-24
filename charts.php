<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <script src="js\Chart.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/theme.css">
    
</head>
<body>
    
<form method="get" action="charts.php">
    <label>Выбор группы</label><br>


    Группа:<select name="group" rows="10">
            <option value=85624519>Беларусь</option>
            <option value=57187411>Минск</option>
            <option value=86126586>Брест Live</option>
            <option value=85637573>Гродно Live</option>
            <option value=56650337>Могилев Live</option>
            <option value=86061420>Гомель Live</option>
            <option value=86131228>Украина Live</option>
            <option value=86117351>Киев Live</option>
            <option value=86047615>Львов Live</option>
            <option value=48866685>Россия Live</option>
            <option value=47636806>Москва Live</option>
            <option value=2709>Санкт-Петербург Live</option>
            <option value=86121794>Екатеринбург</option>
            <option value=39903627>Новосибирск Live</option>
            <option value=82089357>Казань Live</option>
            <option value=82034326>Нижний Новгород Live</option>
            <option value=31566493>Самара Live</option>
            <option value=82037285>Челябинск Live</option>
            <option value=81028911>Омск Live</option>
            <option value=81130210>Ростов-на-Дону Live</option>
            <option value=30861472>Типичная Уфа</option>
            <option value=82053191>Пермь Live</option>
            </select><br><br>


    <input type="submit" value="Показать">

</form>



<canvas id="myChart" width</canvas>





<?php
$group_name = $_GET['group'];
require_once('db.php');//файл доступа к БД
$sql = "SELECT date,`$group_name` FROM subscribers";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        $dates[] = $row["date"];
        $data[] = $row["$group_name"];
    }
} else {
    echo "0 results";
}
?>


    
<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: <?php echo json_encode([
        'labels'    => $dates,
        'datasets'  => [
            ['label'=> 'Прирост подписчиков в '.$group_name, 'data' => $data]
        ] 
                                  ]) ?>,
    option: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>



</body>
</html>