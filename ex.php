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
$select_array = array(
	"都道府県を選択","北海道","青森県","岩手県","秋田県","宮城県","福島県"
);
$select_array_1 = array(
	"北海道地方"	=> array("北海道"),
	"東北地方"		=> array("青森県","岩手県","秋田県","宮城県","福島県")
);
$select_array_2 = array(
	"都道府県を選択",
	"北海道地方"	=> array("北海道"),
	"東北地方"		=> array("青森県","岩手県","秋田県","宮城県","福島県")
);

$a = $b = $c = $d = $e = $f = "" ;
$val = $result = "";

$ex1 = "";
$mail_to   = "";
$mail_from = "akaikelabo@minami_gisen.jp";
$mail_url  = "";
$mail_url1 = "http://localhost/web/k1/ex.php";
$mail_url2 = "https://www.g096407.shop/2019/k1/ex2.php";
$mail_title = "【中古家電.com】ご本人様 確認メール";
$mail_text  = "";
$mail_text1 = "
	本メールは「中古家電.com」の新規登録におけるご本人様確認メールとなります。
	ご本人様確認の為、以下のリンクより弊社の会員登録ページにアクセス下さい。\n\n"
;
$mail_text2 = "
	※ メールが送信されてから3分が経過すると、上記URLは無効になります。
	※ URLが無効になった場合は、弊社WEBページよりメールを再送信下さい。
	※ 上記URLをクリックしても登録フォームに遷移しない場合は、お手数ですがURL全文をコピー&ペーストの上、WEBブラウザより直接アクセス下さい。
	※ 既にメールが送信されている場合は御了承下さい。
	※ 本メールに心当たりがない場合は本メールを破棄して下さい。
	※ 本メールは送信専用アドレスから送信しており、ご返信頂いても対応致しかねますので御了承下さい。\n
	南大阪義専 赤池班"
;
//var_dump($_POST);

/* --------------------------------------------------------------------------------------- */

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$pflag = true;
	switch($_POST["btn"]){
		case "県名送信":
			$a = $_POST["a"];
			$b = $_POST["b"];
			$c = $_POST["c"];
			$d = $_POST["d"];
			$e = $_POST["e"];
			break;
		case "メール送信":
			$mail_to = htmlspecialchars($_POST["mail"],ENT_QUOTES);
			$ex1     = htmlspecialchars($_POST["ex1"],ENT_QUOTES);
			$url_token1 =  hash('sha256',$mail_to);
			$url_token2 = "index";
			$mail_url2 .= '?hash='.$url_token1.'&page='.$url_token2.'&ex1='.$ex1;
			$mail_text .= $mail_text1 . $mail_url2 . "\n" . $mail_text2;
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");
			if(mb_send_mail($mail_to,$mail_title,$mail_text)){
				$result = "送信完了";
			}else{
				$result = "送信失敗";
			}
			// メール内でのURL記載方法 https://asumeru.net/url_note
			break;
		default:
			break;
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<script></script>
	<style></style>
	<title>テスト１</title>
</head>
<body>
	<header>
		<h1><img src="./image/mi_1.png" width="50px" alt="み">んなのクイズ</h1>
		<br>
		<?= $result ?>
	</header>
	<main>
		<div>
			<header>
				<h2>テスト確認用ページ１</h2>
			</header>
			<span id="err"><?= $err_msg ?></span>	
			<br>
			<form action="ex.php" method="POST">
				<?php
					echo hash('sha256',"aasdgkr=-1!ish2@fgs.loj");
					echo "<br>";
					echo hash('md5',"aasdgkr=-1!ish2@fgs.loj");
					echo "<br>";
					echo hash('fish_born',"aasdgkr=-1!ish2@fgs.loj");
					echo "<br>";
					// 表示○　POST×
					echo create_box("selectbox","a","a",$select_array_2,$a,true,true);
					echo "<br>";
					echo var_dump($a);
					echo "<br>";
					// 表示×　POST×
					echo create_box("checkbox","b","b",$select_array,$b,false,true);
					echo "<br>";
					echo var_dump($b);
					echo "<br>";
					echo create_input("submit","btn","btn","20","県名送信","","","");
					echo "<br>";
				?>
			</form>
			<br>
			<form action="ex.php" method="POST">
				<?php
					echo create_input("text","mail","mail","20",$mail_to,"","","メールアドレスを入力");
					echo "<br>";
					echo create_input("text","ex1","ex1","20",$ex1,"","","任意の値を入力");
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