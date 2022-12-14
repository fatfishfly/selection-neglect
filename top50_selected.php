<?php
// build up connection with mysql

$servername = "localhost";
$username = "root";
$password = "root";
$database = 'candidate_pool';
$conn = new mysqli($servername, $username, $password, $database);

// test the connection

if ($conn->connect_error) {
    die("failed: " . $conn->connect_error);
}
echo "success";

// randomly select all the data from mysql and save as the result

$query = "( SELECT * FROM `selected` ORDER BY RAND())";
$result= $conn->query($query);

// save all the results to the array table

$table = [];
while($row = $result -> fetch_assoc()) {
    $table[] = $row;
  #  echo "id: " . $row['worker_ID'] . ", degree level:" . $row['education'] . ", gender:" . $row['gender'] . "score:" . $row['score']. ", math:" .$row['quantitative']. "<br>";
}

#randomly get a pair from $table, make sure the scores are different, and drop the selected ones from $table in case of repeating

$output=array();
for ($i = 0; $i < 50; $i++) {
    $random_key=array_rand($table,2);
    $one_participant= $table[$random_key[0]];
    $another_participant=$table[$random_key[1]];
    #$another_participant= $table[array_rand($table)];
    if($one_participant['quantitative'] != $another_participant['quantitative'] and
    $one_participant['verbal'] != $another_participant['verbal'])
    {
        echo "ID 1: ". $one_participant['worker_ID'].", ID 2:".$another_participant['worker_ID'].", total score 1:".$one_participant['score'].", total score 2:" .$another_participant['score']. "verbal 1:" . $one_participant['verbal'] . ", verbal 2:" . $another_participant['verbal'] . ", quant 1:".$one_participant['quantitative'].", quant 2:".$another_participant['quantitative']. "<br>";
        $output[] = array(
        'one_participants' => $one_participant,
        'another_participants' => $another_participant);
        unset($table[$random_key[0]]);
        unset($table[$random_key[1]]);
    }
}

// select 10 pairs from the result as the final result
echo count($output);
$final = array_slice($output, 0, 10);

/*
// if you don't mind whether the pairs selected will be repeated

$output=array();
for ($i = 0; $i < 50; $i++) {
    $one_participant= $table[array_rand($table)];
    $another_participant=$table[array_rand($table)];
    if($one_participant['quantitative'] != $another_participant['quantitative'] and
        $one_participant['verbal'] != $another_participant['verbal'])
    {
        echo "ID 1: ". $one_participant['worker_ID'].", ID 2:".$another_participant['worker_ID'].", total score 1:".$one_participant['score'].", total score 2:" .$another_participant['score']. "verbal 1:" . $one_participant['verbal'] . ", verbal 2:" . $another_participant['verbal'] . ", quant 1:".$one_participant['quantitative'].", quant 2:".$another_participant['quantitative']. "<br>";
        $output[] = array(
            'one_participants' => $one_participant,
            'another_participants' => $another_participant);
    }
}
echo count($output);
$final = array_slice($output, 0, 10);

// if you don't care whether the pair selected will share same scores

shuffle($table);
$one_participants = array_slice($table, 0, 10);
$another_participants = array_slice($table, 11, 20);
*/

// output final results as json
echo json_encode($final);

$conn->close();
?>

