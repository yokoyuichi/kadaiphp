<?php
    $c = ",";
    // $raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
    // $data = json_decode($raw); // json形式をphp変数に変換
    // $syumoku = $data; // やりたい処理
    // echo $syumoku;
    $date = date("y/m/d");
    $syumoku = [];
    $set = [];
    $maxWeight = [];
    $minWeight = [];
    $rep = [];
    $memo = [];
    $numSyumoku = $_POST["numSyumoku"];

      for($i = 0; $i<$numSyumoku; $i++){
          $syumoku[] = $_POST["syumoku".$i]; 
          $set[] = $_POST["set".$i];
          $maxWeight[] = $_POST["maxWeight".$i];
          $minWeight[] = $_POST["minWeight".$i];
          $rep[] = $_POST["rep".$i];
          $memo[] = $_POST["memo".$i];
       }
    
     for($i = 0; $i<$numSyumoku; $i++){
         $data = $date.$c.$syumoku[$i].$c.$set[$i].$c.$maxWeight[$i].$c.$minWeight[$i].$c.$rep[$i].$c.$memo[$i];
         $dataMod = mb_convert_encoding($data, "UTF-8");
         $file = fopen("trainingdata.txt","a");
         fwrite($file, $dataMod."\n");
         fclose($file);
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>保存画面</title>
</head>
<body>
    <a href = "read.php"><p>戻る</p></a>
</body>
</html>
<script type="text/javascript">
    alert("保存しました！")
</script>

