<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：問題表示ページ
番号：⑨

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）

// ここで使う変数の初期化。
$pflag = "false";

// 【要削除】検証用
var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_POST);

/* --------------------------------------------------------------------------------------- */

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $pflag = true;
    sql("select","2","test1","test1","","","");
    foreach($sql_output_test_qno_array as $key => $val){
        $_SESSION["question"]["no"][] = $val;
    }
    var_dump($_SESSION["question"]["no"]);
    header("Location:question_question.php");
    exit;
}else{
    session_user_check($_SESSION["user"]);
    if(isset($_SESSION["question"]["type"])){
        switch($_SESSION["question"]["type"]){
            case "r_test":
                $test_type = "ランダムテスト";
                break;
            case "d_test":
                $test_type = "難問テスト";
                break;
            case "e_test":
                $test_type = "高評価問題テスト";
                break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $head_common_tag ?>
	<script>
        window.onload = function(){
            // ログアウト（トップに戻る）の確認処理。
            var a1_tag = document.getElementById("a1");
            a1_tag.addEventListener("click",function(){
                event.preventDefault();
                var confirm_result = confirm("「ログアウト」してログイン画面に戻りますか？");
                if(confirm_result){ 
                    location.href = "index.php";
                }
            });
        }
    </script>
    
	<title>テスト</title>
</head>

<body>
<header>
    <h1>テスト</h1>
    <p><?= $_SESSION["user"]["name"] ?>様</p>
    <a id="a1" href="index.php">ログアウト</a>
</header>
<main>
	<div>
        <header>
            <h2><?= $test_type ?></h2>
        </header>
        <span id="err"><?= $err_msg ?></span>
        <section>
            <ul>
                <li>「テストを始める」ボタンでテストが始まります。</li>
                <li>テストは連続10問出題されます。</li>
                <li>各問題で解答完了ボタンを押下、もしくは時間切れで次の問題に進みます。</li>
                <li>全問解答後に結果が表示されます。</li>
            </ul>
        </section>
        <form action="question_test.php" method="POST">
            <?= create_input("submit","btn","btn","10","テストを始める","","","") ?>
        </form>
	</div>
</main>
</body>
</html>

