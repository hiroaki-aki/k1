<?php

include 'config.php';
session_start();

$pflag = false;
$err   = array();
$name = $age = "";

if(isset($_COOKIE["logmiss"]) && $_COOKIE["logmiss"] == 5){
	$logcheck = true;
}
$logcheck = "true";




// if($_SERVER["REQUEST_METHOD"] == "POST"){
// 	$pflag = true;	
// 	if($_POST["name"] == "" || $_POST["age"] == ""){
// 		$err[] = "名前と年齢の両方を入力してください。";
// 	}else{
// 		if(!is_numeric($_POST["age"])){
// 			$err[] = "年齢に数値以外が入力されています。";
// 		}else{
// 			if(isset($_SESSION["name"])){
// 				$_SESSION = array();
// 				if(isset($_COOKIE[session_name()])){
// 					setcookie(session_name(),'',time()-43200,'/');
// 				}
// 				session_destroy();
// 			}
// 			$_SESSION["name"] = htmlspecialchars($_POST["name"],ENT_QUOTES);
// 			$_SESSION["age"]  = htmlspecialchars($_POST["age"],ENT_QUOTES);
// 			header("Location: ex11_04.php");
// 			exit;
// 		}
// 	}
// }else{
// 	if(isset($_SESSION["name"])){ 
// 		$name = $_SESSION["name"];
// 	}
// 	if(isset($_SESSION["age"])){
// 		$age  = $_SESSION["age"];
// 	}
// }

?>

<!DOCTYPE html>
<html>
<head>
	<?= $head_common_tag ?>
	<script>
		if(<?= $logcheck ?> ){
			alert("a");
		}
	</script>
	<title>index.php</title>
</head>
<body>
	<header>
		<h1>みんなのクイズ</h1>
	</header>
	<main>
		<div>
			<h2>ログイン画面</h2>
			<?= create_input("text","user_id","user_id","10","値","会員IDを入力して下さい") ?>
		</div>
	</main>
	<p id="err">
	<?php 
	foreach ($err as $val){
		echo $val , "<br>";
	}
	?>
	</p>
</div>
<form action="ex11_03.php" method="POST" >
	<div>
<!-- 		<h1>ログイン画面</h1> >
		お名前：<input type="text" name="name" size="20" value="< ?php if($pflag) echo $name;?>" placeholder="例)上部 太郎" ><br>
		年齢：<input type="text" name="age" size="20" value="< ?php if($pflag) echo $age;?>" placeholder="例)30" ><br>
 		<input type="submit" name="btn" value="ログイン"> -->
	</div>
</form>
</body>
</html>

