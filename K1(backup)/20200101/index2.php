<?php

$pflag = "";
include "config2.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$pflag = true;
	//echo $_POST["user_id"];

	// IDの種類毎にセッションのタイプを変更。
	if($_POST["user_id"] == "admin"){
		header("Location:ex.php");
		exit;
	}else{
		echo "NG";
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<style>
		body > header{
			margin: auto;
			max-width: 600px;
		}
		body > header > a{
			float: right;
		}
		div{
			margin: auto;
			text-align:center;
			max-width: 600px;
		}
		.form1 {
			padding: 20px 0px;
			border: 2px solid mediumblue;
			border-radius: 10px ;
			box-shadow: 4px 4px 6px gray;
		}
		.form1 table{
			margin:auto;
			text-align:center;
		}
		.form1 table input{
			margin: 5px 0px;
		}
		.form2 {
			text-align:left;
			color: gray;
		}
		.form2 input[type="submit"],
		.form2 input[type="button"]{
			text-align:left;
			background:none;
			background-color:none;
			margin: 5px auto 0px;
			width: 50%;
			border:none;
			border-radius: 0px;
			background-color: none;
			box-shadow: none;
			font-size: 17px;
			color: blue;
			text-decoration: underline;
			transition: all 0.8s ease;
		}
		.form2 input[type="submit"]:hover,
		.form2 input[type="button"]:hover{
			background:none;
			background-color:none;
			border: none;
			font-size: 20px;
			font-style: bold;
			font-weight: none;
			color: blue;
			text-decoration: underline;
			opacity: none;
			transition: all 0.5s ease;
		}
	</style>
	<title>みんなのクイズ（トップページ）</title>
</head>
<body>
	<header>
		<h1><img src="./image/mi_1.png" width="50px" alt="み">んなのクイズ</h1>
	</header>
	<main>
		<div>
			<header>
				<h2>ログイン画面</h2>
			</header>
			<br>
			<form class="form1" action="index2.php" method="POST">
				<table>
					<tr>
						<td>会員ID</td>
						<td>
							<input name="user_id">
						</td>
					</tr>
				</table>
				<input type="submit" id="btn" name="btn" value="ログイン">
			</form>
		</div>
	</main>
	<footer>

	</footer>
</body>
</html>

