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
$pflag = "false";
$msg   = array(
		0 => "管理者権限でログインしました。",
		1 => "{$_SESSION["user"]["name"]}様 こんにちは！",
		2 => "ゲスト様 はじめまして",
		3 => "会員登録が完了しました",
		4 => "PWの再登録を致しました!"
);

/* --------------------------------------------------------------------------------------- */

if($_SERVER["REQUEST_METHOD"] == "POST"){
	// POST時の処理（何もないけど一応書いとく。）

}else{
	// GET時の不正アクセスの制御処理（直リンクアクセスはindex.phpにリダイレクト。詳細な処理は config.php を確認）
	session_user_check($_SESSION["user"]);
	if($_SESSION["user"]["name"] == "pre"){
		// echo "err";
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<script>
		sessionStorage.clear();
	</script>
	<style>
		body > header{
			margin: auto;
			max-width: 600px;
		}
		div{
			margin: auto;
			text-align:center;
			max-width: 600px;
		}
		section{
			text-align: center;
			height: 30%;
			padding-top:50px; 
			font-size: 20px;
		}
	</style>
	<title>ログイン確認</title>
</head>
<body>
	<header>
		<?= $header_common_tag ?>
	</header>
	<main>
		<div>
			<header>
				<h2>ログイン確認</h2>
			</header>
			<section>
				<p><?= $msg[$_SESSION["msg_type"]] ?></p>
				<a id="a1" href="main.php">メインメニューへ</a>
			</section>
		</div>
	</main>
	<footer>
		<?= $footer_common_tag ?>
	</footer>
</body>
</html>