<?php

$start = date('d-m H:i:s');// время начала
set_time_limit(1300);//секунд на выполнение
require_once('db.php');//файл доступа к БД

$list=explode("\r\n",$_POST['id']);
$date_from = $_POST['date'];
$date_to = $_POST['date'];
$token = file_get_contents('token.txt');
$m = 0;//по сколько групп в завпросе

function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}



//формирование запроса

foreach ($list as $group_id) {
    $wall = file_get_contents("https://api.vk.com/method/stats.get?group_id=" . $group_id . "&date_from=" . $date_from . "&date_to=" . $date_to . "&access_token=" . $token);  //Отправляем запрос
    
    $wall = json_decode($wall); // Преобразуем JSON-строку в массив
    $wall = $wall->response;

    foreach ($wall as $group) {

        $day = $group->day;
        $subscribed = $group->subscribed;
        $unsubscribed = $group->unsubscribed;
        $s = $subscribed - $unsubscribed;
        $group_names .= $group_id.', ';
        $group_name[$group_id] = $s;


        $m++;// запись в БД
        //}


    }
}
unset($numbers);
$numbers = '';

foreach ($group_name as $key => $name) {
    $str .= "$key='$name', ";
};
$str = substr($str, 0, -2);
while ($db->query("INSERT INTO subscribers (date)
            VALUES ('$day')
            ") === 'FALSE') {

    //  echo "Members: " . $group->members_count . "<br>";
    // echo "Verified: " . $group->verified . "<br>";
    //echo "Activity: " . $group->activity . "<br>";
//        echo "Site: " . $group->site . "<br>";
//        echo "Type: " . $group->type . "<br>";
//        echo "country: " . $group->country . "<br>";
//        echo "city: " . $group->city . "<br>";
//        echo 'Счетчик = ' . $m . "<br>";
};
foreach ($group_name as $key => $name) {
$db->query("UPDATE subscribers SET
                                  `$key` = '$name'

                                  WHERE `date` = '$day'
                 ");
}

echo '<br><br>';
$db->close();
$end = date('d-m H:i:s');

echo 'Начало:' . $start . "<br>Окончание:" . $end;
echo '<br>Записано: ' . $m;



?>
