<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：確認用

【主な処理】

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）
// 0:不可 1:可
$config_login;

// ここで使う変数の初期化。


//var_dump($_POST);

/* --------------------------------------------------------------------------------------- */

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$pflag = true;
	switch($_POST["btn"]){
		case "メール送信":
			$mail = htmlspecialchars($_POST["mail"],ENT_QUOTES);
			$url_token1 = password_hash($_POST["mail"],PASSWORD_DEFAULT);
			break;
		default:
			break;
	}
}

?>
<?php 
	if(isset($_GET['hash'])){ 
		$hash = $_GET['hash']; 
	}
	if(isset($_GET['page'])){ 
		$page = $_GET['page']; 
	}
	if(isset($_GET['ex1'])){ 
		$ex1 = $_GET['ex1']; 
	}
	echo "送信されたハッシュ値：" , $hash ;
	echo "<br>";
	echo "送信されたページの値：" , $page ;
	echo "<br>";
	echo "送信された入力値：" , $ex1 ;
	echo "<br>";
	echo "ホスト名：" , $_SERVER["HTTP_HOST"];
	echo "<br>";
	echo "プロトコル名：" , (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<script></script>
	<style></style>
	<title>テスト２</title>
</head>
<body>
	<header>
		<h1><img src="./image/mi_1.png" width="50px" alt="み">んなのクイズ</h1>
	</header>
	<main>
		<div>
			<header>
				<h2>テスト確認用ページ２</h2>
			</header>
			<span id="err"><?= $err_msg ?></span>	
			<br>
			<br>
			<form action="ex.php" method="POST">
				<?php
					echo create_input("text","mail","mail","20",$mail,"","","メールアドレスを入力");
					echo "<br>";
					echo create_input("submit","btn","btn","20","メール送信","","","");
				?>
			</form>
		</div>
	</main>
	<footer>
		<?= $footer_common_tag ?>
	</footer>
</body>
</html>