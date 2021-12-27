<?php

$url = 'https://mensa.jp/exam/'; 
$filename = '/home/pi/Desktop/Mensa-exam-judge.txt'; 

$input = file_get_contents($url);
$prv = file_get_contents($filename);

$match1 = mb_strstr($input, '関東地方</h3>');
$match1 = str_replace(array(" ", "　"), "", $match1);
$match1_1 = mb_strstr($match1, '</p>', true);
//var_dump($match1_1);

$match2 = mb_strstr($prv, '関東地方</h3> ');
$match2 = str_replace(array(" ", "　"), "", $match2);
$match2_1 = mb_strstr($match2, '</p>', true);
//var_dump($match2_1);

if($match1_1 != $match2_1){

echo "NO";

define('LINE_API_URL'  ,'https://notify-api.line.me/api/notify');
define('LINE_API_TOKEN','***');

function post_message($message){

    $data = http_build_query( [ 'message' => $message ], '', '&');

    $options = [
        'http'=> [
            'method'=>'POST',
            'header'=>'Authorization: Bearer ' . LINE_API_TOKEN . "\r\n"
                    . "Content-Type: application/x-www-form-urlencoded\r\n"
                    . 'Content-Length: ' . strlen($data)  . "\r\n" ,
            'content' => $data,
            ]
        ];

    $context = stream_context_create($options);
    $resultJson = file_get_contents(LINE_API_URL, false, $context);
    $resultArray = json_decode($resultJson, true);
    if($resultArray['status'] != 200)  {
        return false;
    }
    return true;
}
post_message("Mensa Exam!+ ${url}");

$fp = fopen($filename, 'w');
fwrite($fp, $input);
fclose($fp);


}else {

echo "YES";

}
?>
