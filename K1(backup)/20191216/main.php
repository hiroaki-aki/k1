<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：メインページ
番号：⑦

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
    switch($_POST["btn"]){
        case "一問一答道場":
            $_SESSION["question"]["type"] = "dojo";
            header("Location:question_dojo.php");
            break;
        case "ランダムテスト":
            $_SESSION["question"]["type"] = "r_test";
            header("Location:question_test.php");
            break;
        case "難問テスト":
            $_SESSION["question"]["type"] = "d_test";
            header("Location:question_test.php");
            break;
        case "高評価問題テスト":
            $_SESSION["question"]["type"] = "e_test";
            header("Location:question_test.php");
            break;
    } 
}else{
    session_user_check($_SESSION["user"]);
}

$tab1  = "";
$tab1 .= "<section>";
$tab1 .= "<form action=\"main.php\" method=\"POST\">";
$tab1 .= create_input("hidden","dojo","dojo","10","dojo","","","");
$tab1 .= create_input("submit","btn","btn","10","一問一答道場","","","");
$tab1 .= "</form>";
$tab1 .= "<br>";
$tab1 .= "<form action=\"main.php\" method=\"POST\">";
$tab1 .= create_input("hidden","r_test","r_test","10","r_test","","","");
$tab1 .= create_input("submit","btn","btn","10","ランダムテスト","","","");
$tab1 .= "<br>";
$tab1 .= create_input("hidden","d_test","d_test","10","d_test","","","");
$tab1 .= create_input("submit","btn","btn","10","難問テスト","","","");
$tab1 .= "<br>";
$tab1 .= create_input("hidden","e_test","e_test","10","e_test","","","");
$tab1 .= create_input("submit","btn","btn","10","高評価問題テスト","","","");
$tab1 .= "</form>";
$tab1 .= "</section>";
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
            //var tab_body_tag = document.getElementById("tab_body");
            //< ?= $tab1 ?>;
            //tab_body_tag.appendChild(tab1);
            //switch()

        }
    </script>
    
	<title>メインメニュー</title>
</head>

<body>
<header>
    <h1>メインメニュー</h1>
    <p><?= $_SESSION["user"]["name"] ?>様</p>
    <a id="a1" href="index.php">ログアウト</a>
</header>
<main>
	<div>
        <header>
            <h2>メインメニュー</h2>
        </header>
        <span id="err"><?= $err_msg ?></span>
        <div id="tab_body">
            <?= $tab1 ?>

        </div>
	</div>
</main>
</body>
</html>

