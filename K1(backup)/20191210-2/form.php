<?php

include 'config.php';
//session_destroy();
//setcookie("logmiss", "", time()-60);


$pflag = false;
// JS で使う事になるPHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）
$logcheck = "false";
$id = $pw = "";

var_dump($_COOKIE);
var_dump($_SESSION);
if(isset($_COOKIE["logmiss"]) && $_COOKIE["logmiss"] == "NG" ){
    $_SESSION["logmiss"] = 0;
    $logcheck = true;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$pflag = true;	
	if(!($_POST["user_id"] == "" && $_POST["user_pw"] == "")){
        if($p==0){
            // pdo 処理
        }else{
            if(isset($_SESSION["logmiss"])){
                $_SESSION["logmiss"]++;
            }else{
                $_SESSION["logmiss"] = 1;
            }
            $err_msg = $err["index2"]."(".$_SESSION["logmiss"]."回目)";
            if($_SESSION["logmiss"] == 5){
                setcookie("logmiss","NG",time()+20);
                $_SESSION["logmiss"] = 0;
                $logcheck = true;
            }
        }
        $id = htmlspecialchars($_POST["user_id"],ENT_QUOTES);
        $pw = htmlspecialchars($_POST["user_pw"],ENT_QUOTES);
	}else{
        $err_msg = $err["index2"];
    }
    
}else{
    // GET 時は全てのクッキーを破壊する。
    $_SESSION = array();
        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(),'',time()-43200,'/');
        }
    session_destroy();
}
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
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<script>
        window.onload = function(){
            var btn_tag = document.getElementById("btn");
            console.log(<?= $logcheck ?>);
            if(<?= $logcheck ?>){
                btn_tag.disabled = true;
            }
            var span_tag = document.getElementById("err");
            span_tag.innerText = "<?= $err_msg ?>";
        }

	</script>
	<title>ログイン画面</title>
</head>
<body>
<header>
	<h1>みんなのクイズ</h1>
</header>
<main>
	<div>
        <header>
            <h2>ログイン画面</h2>
        </header>
        <form action="index.php" method="POST">
            <span id="err"></span>
            <table>
                <tr>
                    <td>会員ID</td>
                    <td><?= create_input("text","user_id","user_id","20",$id,"会員ID") ?><td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td><?= create_input("password","user_pw","user_pw","20",$pw,"パスワード") ?></td>
                </tr>
            </table>
            <input type="submit" id="btn" name="btn" value="ログイン">
        </form>
	</div>
</main>
	<p id="err">
	<?php 
	foreach ($err as $val){
		//echo $val , "<br>";
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

