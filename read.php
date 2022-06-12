<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="read.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <title>表示画面</title>
</head>
<body>
<header>
        <h1>あなたの筋トレ記録サイト</h1>
        <h2>筋トレの全てがここに。</h2>
    </header>
    
        <div id="mainbg">
        <img src="Arnold.jpg" alt="Negger">
    </div>
    <div class = "link">
        <a href = "post.php"><p　class="post">メニュー記録へ</p></a>
    </div>
    <table class="table">

    </table>
    <canvas id="stage"></canvas>
    <canvas id="stage2"></canvas>
</body>
</html>


<?php
class data
{
    public $date;
    public $syumoku;
    public $set;
    public $max;
    public $min;
    public $rep;
    public $memo;
}
$filename = 'trainingdata.txt';

// fopenでファイルを開く（'r'は読み込みモードで開く）
$fp = fopen($filename, 'r');
$data = array();
// whileで行末までループ処理
while (!feof($fp)) {
  // fgetsでファイルを読み込み、変数に格納
  $trainingdata = fgets($fp);
  $dataLine = explode(",", $trainingdata);
//   $dataObjectLine = new data();
//   $dataObjectLine->date = $dataLine[0];
//   $dataObjectLine->syumoku = $dataLine[1];
//   $dataObjectLine->set = $dataLine[2];
//   $dataObjectLine->max = $dataLine[3];
//   $dataObjectLine->min = $dataLine[4];
//   $dataObjectLine->rep = $dataLine[5];
//   $dataObjectLine->memo = $dataLine[6];
//   $dataObject = array($dataObject,$dataObjectLine);
    $data[] = $dataLine;
};
// fcloseでファイルを閉じる
fclose($fp);
// ファイルを読み込んだ変数を出力
// var_dump($data);
$json_data = json_encode($data); 

?>

<script type="text/javascript">
    let js_data = <?php echo $json_data ?>;
    let dataLength = js_data.length-1
    let dataObject = {}
    dataObject.date = [js_data[0][0]]
    dataObject.syumoku = [js_data[0][1]]
    dataObject.set = [js_data[0][2]]
    dataObject.max = [js_data[0][3]]
    dataObject.min = [js_data[0][4]]
    dataObject.rep = [js_data[0][5]]
    dataObject.memo = [js_data[0][6]]
 
    for(let i=1; i<dataLength; i++){
             dataObject.date.push(js_data[i][0])
             dataObject.syumoku.push(js_data[i][1])
             dataObject.set.push(js_data[i][2])
             dataObject.max.push(js_data[i][3])
             dataObject.min.push(js_data[i][4])
             dataObject.rep.push(js_data[i][5])
             dataObject.memo.push(js_data[i][6])
     }
     console.log(dataObject)   
     //日付の重複の削除
     var date = dataObject.date.filter(function (x, i, self) {
            return self.indexOf(x) === i;
        });

    //Tableの作成
    $(".table").append(`
    <tr>
        <th>日付</th>
        <th>種目</th>
        <th>セット数</th>
        <th>最大重量</th>
        <th>最小重量</th>
        <th>Rep数</th>
        <th>メモ</th>
    </tr>
    `)
    for(let i=0; i<dataLength; i++){
        $(".table").append(`
        <tr>
            <td>${dataObject.date[i]}</td>
            <td>${dataObject.syumoku[i]}</td>
            <td>${dataObject.set[i]}</td>
            <td>${dataObject.max[i]}</td>
            <td>${dataObject.min[i]}</td>
            <td>${dataObject.rep[i]}</td>
            <td>${dataObject.memo[i]}</td>
        </tr>
    `)
    }
    var numSyumoku = [0]
    var numSet = [0]
    //日付ごとに集計
    for(let i=0; i<date.length; i++){
        numSyumoku[i] = 0
        numSet[i] = 0
        for(let j=0; j<dataLength; j++){
            if(dataObject.date[j] == date[i]){
                numSet[i] += Number(dataObject.set[j])
                numSyumoku[i] += 1
            }
        }
    }

    //Table sorter
    $(document).ready(function() {
    $(".table").tablesorter();
    });

    //グラフ
    //最大値とり
    var maxnumSet = numSet.reduce(function(a, b) {
        return Math.max(a, b);
        }, -Infinity);
    var maxnumSyumoku = numSyumoku.reduce(function(a, b) {
        return Math.max(a, b);
        }, -Infinity);
    
    
        var mydata = {
        labels: date,
        datasets: [
            {
            label: 'セット数',
            hoverBackgroundColor: "rgba(255,99,132,0.3)",
            data: numSet,
            }
        ]
        
    }  ;
    //「オプション設定」
        var options = {
        title: {    
            display: true,
            text: '1日当たりのセット数',
            fontSize: 16
        },
        scales: {                          //軸設定
            yAxes: [{                      //y軸設定
                display: true,             //表示設定
                scaleLabel: {              //軸ラベル設定
                   display: true,          //表示設定
                   fontSize: 12               //フォントサイズ
                },
                ticks: {                      //最大値最小値設定
                    min: 0,                   //最小値
                    max: maxnumSet,               //最大値
                    fontSize: 12,             //フォントサイズ
                    stepSize: 5               //軸間隔
                    },
                }],
        }
        };

    var canvas = document.getElementById('stage');
    var chart = new Chart(canvas, {

        type: 'bar',  //グラフの種類
        data: mydata,  //表示するデータ
        options: options  //オプション設定
    });

    var mydata = {
        labels: date,
        datasets: [
            {
            label: '種目数',
            hoverBackgroundColor: "rgba(255,99,132,0.3)",
            data: numSyumoku,
            }
        ]
        
    }  ;
    //「オプション設定」
        var options = {
        title: {    
            display: true,
            text: '1日当たりの種目数',
            fontSize: 16
        },
        scales: {                          //軸設定
            yAxes: [{                      //y軸設定
                display: true,             //表示設定
                scaleLabel: {              //軸ラベル設定
                   display: true,          //表示設定
                   fontSize: 12               //フォントサイズ
                },
                ticks: {                      //最大値最小値設定
                    min: 0,                   //最小値
                    max: maxnumSyumoku,                  //最大値
                    fontSize: 12,             //フォントサイズ
                    stepSize: 1              //軸間隔
                    },
                }],
        }
        };
        console.log(Math.max(numSyumoku))
    var canvas = document.getElementById('stage2');
    var chart = new Chart(canvas, {
        type: 'bar',  //グラフの種類
        data: mydata,  //表示するデータ
        options: options  //オプション設定
    });

</script>

