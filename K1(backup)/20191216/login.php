<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：ログインページ
番号：⑥

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値でtrueかfalse使う時は文字列じゃないとうまく機能しない。0 or 1ならOK（初期値以外は可）

// ここで使う変数の初期化。
$pflag      = "false";
$msg   = array(
    0 => "管理者権限でログインしました。",
    1 => "{$_SESSION["user"]["name"]}様 こんにちは！",
    2 => "ゲスト様 はじめまして",
    3 => "会員登録が完了しました",
    4 => "PWの再登録を致しました"
);

// 【要削除】検証用
var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_POST);

/* --------------------------------------------------------------------------------------- */

if($_SERVER["REQUEST_METHOD"] == "POST"){
	echo "重大なエラー(ココはPOST送信されないページ)";
}else{
    session_user_check($_SESSION["user"]);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $head_common_tag ?>
	<title>ログイン画面</title>
</head>
<body>
<header>
	<h1>ログイン</h1>
</header>
<main>
	<div>
        <header>
            <h2>ログイン画面</h2>
        </header>
        <section>
            <?= $msg[$_SESSION["user"]["type"]] ?>
        </section>
        <a id="a1" href="main.php">メインメニューへ</a>
	</div>
</main>
</body>
</html>

