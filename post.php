<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="post.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
    <title>入力フォーム</title>
</head>
<body>
<h1>
    筋トレ記録フォーム
</h1>
<?php
     $date = date("y/m/d");
    echo "今日の日付 ".$date
?>
<div class = "form">
    <form id="add" method="">
        <label>種目追加：</label><br>
        <input type="text" name = "Training" placeholder="追加したい種目を入力">
        <input type="submit" id="submission" value="追加">
    </form>
    <span id="list"></span><br>

    <form action="write.php" method="post">
    <p>今日の種目数を選んでください。</p>
    <select class = "numSyumoku" name = "numSyumoku">種目
    </select><br>
    <span class = "space"></span>
    <span class = "button"></span>	
	</form>
    <button class = "show">フォーム表示</button><br>
    <a href = "read.php"><p　class="post">戻る</p></a>
</div>
</body>

<script type="text/javascript">
        //種目の追加
        $("#submission").on("click", function(){
                if(typeof localStorage.syumoku === 'undefined'){
                    var syumoku = []
                    syumoku.push($("#add [name=Training]").val())
                    let syumokujson = JSON.stringify(syumoku, undefined, 1)
                    localStorage.setItem("syumoku", syumokujson)
                }
                else{
                    var syumokujson_add = localStorage.getItem("syumoku")
                    let syumoku_add = JSON.parse(syumokujson_add)
                    syumoku_add.push($("#add [name=Training]").val())
                    var syumokujson_add2 = JSON.stringify(syumoku_add, undefined, 1)
                    localStorage.syumoku = syumokujson_add2
                }
            })
            var syumoku_json = localStorage.getItem("syumoku")
            var syumoku_ = JSON.parse(syumoku_json)
            $("#list").html(`登録済みの種目：${syumoku_}`)

        //numSyumokuのOption追加
        for(let i=0; i<10; i++){    
            $(".numSyumoku").append(`<option value = "${i}">${i}</option>`)
        }
 
        //表示ボタンを押した後に、種目数だけ追加
            $(".show").on("click", function(){
                $(".show").hide()
                for(let i=0; i<$(".numSyumoku").val(); i++){
                    $(".space").append(`
                    <table class = ${i}>
                        <tr>
                            <th>種目</th>
                            <td><select class="syumoku${i}" name="syumoku${i}">
                            </td>
                        </tr>		
                        <tr>
                            <th>セット数</th>
                            <td><select class="set${i}" name="set${i}">
                                <option value = "0">0</option>
                                <option value = "1">1</option>
                                <option value = "2">2</option>
                                <option value = "3">3</option>
                                <option value = "4">4</option>
                                <option value = "5">5</option>
                                <option value = "6">6</option>
                                <option value = "7">7</option>
                                <option value = "8">8</option>
                                <option value = "9">9</option></select>
                            </td>
                        </tr>
                        <tr>
                            <th>最大重量</th>
                            <td><input type="text" size="3" name="maxWeight${i}">kg</input></td>
                        </tr>
                        <tr>
                            <th>最小重量</th>
                            <td><input type="text" size="3" name="minWeight${i}">kg</input></td>
                        </tr>
                        <th>平均Rep数</th>
                            <td><select class="rep${i}" name="rep${i}">
                            </td>
                        <tr><th>メモ</th><td><textarea name="memo${i}"></textarea></td></tr>
                        </table>

                    `)
                    for(let j=0; j<21; j++){
                    $(`.rep${i}`).append(`<option value = "${j}">${j}</option>`)
                    }
                    $(`.rep${i}`).append("</select>")
                    //種目セレクトボックスの作成
                    for(let j=0; j<syumoku_.length; j++){
                    $(`.syumoku${i}`).append(`<option value = "${syumoku_[j]}">${syumoku_[j]}</option>`)
                    }
                    $(`.syumoku${i}`).append("</select>")
                }
                //保存ボタンの追加
                $(".button").html('<input class="submit-btn" type="submit" value="保存">')

            
                //rep数の追加
                for(let i=0; i<21; i++){
                    $(".rep").append(`<option value = "${i}">${i}</option>`)
                }
            })
            //保存ボタンを押したら、種目を保存
            // $(".submit-btn").on("click", function(){
            //     const numSyumoku = $(".numSyumoku").val()
            //     const syumoku = [] 
            //     for(let i=0; i<numSyumoku; i++){
            //         syumoku.push($(`input:radio[name="syumoku${i}"]:checked`).val())
            //         // $(".syumokuList").append(`<label><input type="radio" name="syumoku${i}" value="${i}">${syumoku_[i]}</label>`)
            //     }
            //     const syumokuArray = [numSyumoku, syumoku]
            //     fetch("write.php",{
            //         method: "POST",
            //         headers: { 'Content-Type': 'application/json' }, // jsonを指定
            //         body: JSON.stringify(syumokuArray)
            //     })
            //     .then(response => response.json()) // 返ってきたレスポンスをjsonで受け取って次のthenへ渡す
            //     .then(res => {
            //         console.log(res); // 返ってきたデータ
            //     });
            // })

</script>

</html>