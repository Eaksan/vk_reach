<?php

$start = date('d-m H:i:s');// время начала
set_time_limit(1300);//секунд на выполнение
require_once('db.php');//файл доступа к БД


$list=explode("\r\n",$_POST['id']);
$date = $_POST['date'];
$count = $_POST['count'];
$token = file_get_contents('token.txt');
$m = 0;
$post_ids = array();
$reach_a = array();


function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}



//формирование запроса
foreach ($list as $group_id) {
    $posts = file_get_contents("https://api.vk.com/method/wall.get?owner_id=-" . $group_id . "&count=".$count."&access_token=" . $token);  //Отправляем запрос

    $posts = json_decode($posts); // Преобразуем JSON-строку в массив
    //echo "<br><br><br>";
    $posts = $posts->response;

    foreach ($posts as $post) {
        /*echo "<br>Day " . $group->day . "<br>";
         echo "Reach " . $group->reach . "<br>";
         echo "Members: " . $group->members_count . "<br>";
         echo "Verified: " . $group->verified . "<br>";
         echo "Activity: " . $group->activity . "<br>";
         echo "Site: " . $group->site . "<br>";
         echo "Type: " . $group->type . "<br>";
         echo "country: " . $group->country . "<br>";
         echo "city: " . $group->city . "<br>";*/
        $id = $post->id;

        array_push($post_ids, $id);

    };
    unset($post_ids[0]);
    foreach ($post_ids as $post_id) {
a:
        sleep(0.5);
        $stats = file_get_contents("https://api.vk.com/method/stats.getPostReach?owner_id=-" . $group_id . "&post_id=" . $post_id . "&access_token=" . $token);
        //echo "https://api.vk.com/method/stats.getPostReach?owner_id=-" . $group_id . "&post_id=" . $post_id . "&access_token=" . $token.'<br>';
        if(strpos($stats, "Too many requests per second")){print_r($stats);goto a;}
        $stats = json_decode($stats);
        $stats = $stats->response;

        foreach ($stats as $stat) {
            $reach = $stat->reach_subscribers;
            array_push($reach_a, $reach);

        }
    }
    echo '<br>';
    $reach_all = array_sum($reach_a);
    $reach_sum = count($reach_a);
    $reach_everege = array_sum($reach_a) / count($reach_a);
    $post_stats[$group_id] = $reach_everege;
    $reach_everege = 0;
    unset($reach_a);
    $reach_a = array();
    /*while(mysql_query("INSERT INTO lpvk (day,subscribed,unsubscribed)
                VALUES ('$day','$subscribed','$unsubscribed')
                ")==='FALSE')
    $m++;// запись в БД
    */
}
$check = $db->query("SELECT date FROM reach WHERE `date`='$date'");
if ( $check->num_rows < 1 ) {
    while ($db->query("INSERT INTO reach (date)
               VALUES ('$date')
                ") === 'FALSE') {
    }
}
foreach ($post_stats as $key => $name) {
    $db->query("UPDATE reach SET
                                  `$key` = '$name'

                                  WHERE `date` = '$date'
                 ");
}
echo '<br><br>';

$db->close();
$end = date('d-m H:i:s');


echo 'Начало:' . $start . "<br>Окончание:" . $end;
echo '<br>Записано: ' . $m;



?>
