<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：トップページ（ログイン画面）
番号：①

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）
// 0:不可　1:可
$config_login;

// ここで使う変数の初期化。
$id = $pw = "";

// 【通常はコメントアウト】検証用
//var_dump($_COOKIE);
var_dump($_SESSION);
//var_dump($err_array);

// ログインを５回間違えた後に再アクセス時してもログインボタンを無効化する処理。
if(isset($_COOKIE["logmiss"]) && $_COOKIE["logmiss"] == "NG" ){
	$_SESSION["logmiss"] = 0;
	$config_login = 0;
}

/* --------------------------------------------------------------------------------------- */



if($_SERVER["REQUEST_METHOD"] == "POST"){
    $pflag = true;
    switch($_POST["btn"]){
        case "ログイン":
	        if(!($_POST["user_id"] == "" && $_POST["user_pw"] == "")){
		        $id = htmlspecialchars($_POST["user_id"],ENT_QUOTES);
		        $pw = htmlspecialchars($_POST["user_pw"],ENT_QUOTES);
                
                // IDとPWが一致するかどうかチェック。この段階では取り敢えずゲスト権限（２）で入る。
		        $sql_check1 = sql("select","2","index1","index1",$id,$pw,"");
		        if($sql_check1){
                    // ログインOKの場合の処理。
                    $_SESSION["user"]["name"] = $id;
                    if($id == "admin"){
                        // 管理者(admin)は「0」番
                        $_SESSION["user"]["type"] = "0";
                    }else{
                        // 一般ユーザ(各ID)は「1」番
		    	        $_SESSION["user"]["type"] = "1";
                    }
                    // ログイン失敗カウントをクリアする。クッキーの消去。
                    $_SESSION["logmiss"] = 0;
			        setcookie("logmiss","",time()-1);
			
    			    // ログイン履歴を会員ID毎に挿入。取り敢えずゲスト権限（２）で入る。
	    		    sql("insert","2","index2","index2",$id,"","");
		    	    header("Location:login.php");
			        exit;
    		    }else{
                    // ログインNGの場合の処理。
	    		    if(isset($_SESSION["logmiss"])){
		    		    $_SESSION["logmiss"]++;
			        }else{
				        $_SESSION["logmiss"] = 1;
    			    }
	    		    $err_msg = $err_array["index2"]."(".$_SESSION["logmiss"]."回目)";
		    	    if($_SESSION["logmiss"] == 5){
                        setcookie("logmiss","NG",time()+10);
                        $_SESSION["logmiss"] = 0;
    			        $config_login = 0;
	    	        }
		        }
		        $id = htmlspecialchars($_POST["user_id"],ENT_QUOTES);
		        $pw = htmlspecialchars($_POST["user_pw"],ENT_QUOTES);
	        }else{
		        $err_msg = $err_array["index1"];
            }
            break;
        case "パスワードを忘れた場合":
            $_SESSION["user"]["name"] = "pre";
			$_SESSION["user"]["type"] = "3";
            $_SESSION["msg_type"]     = "4";
            header("Location:repassword.php");
			exit;
            break;
        case "新規会員登録":
            $_SESSION["user"]["name"] = "pre";
			$_SESSION["user"]["type"] = "3";
            $_SESSION["msg_type"]     = "3";
            header("Location:form.php");
            break;
        case "ゲストでログイン":
            $_SESSION["user"]["name"] = "guest";
			$_SESSION["user"]["type"] = "2";
            $_SESSION["msg_type"]     = "2";
            header("Location:login.php");
            break;
    }

}else{
	// GET 時は全てのセッションを破壊する。
	$_SESSION = array();
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(),'',time()-43200,'/');
	}
	session_destroy();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $head_common_tag ?>
	<script>
        window.onload = function(){
            var btn_tag = document.getElementById("btn");
            console.log(<?= $config_login ?>);
            if(!<?= $config_login ?>){
                btn_tag.disabled = true;
            }
            // var span_tag = document.getElementById("err");
            // span_tag.innerText = "< ?= $err_msg ?>";
            
            // aタグでセッションいじろうとしたけど無理やった。PHP → jsは一通で、逆干渉はajax構築しなあかん。
            // var a1_tag = document.getElementById("a1");
            // a1_tag.addEventListener("click",function(){
            //     event.preventDefault();  
            //     < ?php 
            //         session_start();
			// 	    $_SESSION["user"]["name"] = "pre";
			// 	    $_SESSION["user"]["type"] = "3";
			// 	    $_SESSION["msg_type"]     = "4";
            //     ?>
            //     //location.href = "form.php";
            // });
            // var a2_tag = document.getElementById("a2");
            // a2_tag.addEventListener("click",function(){
            //     event.preventDefault();  
            //     < ?php 
			// 	    $_SESSION["user"]["name"] = "pre";
			// 	    $_SESSION["user"]["type"] = "3";
			// 	    $_SESSION["msg_type"]     = "3";
            //     ?>
            //     location.href = "form.php";
            // });
            // var a3_tag = document.getElementById("a3");
            // a3_tag.addEventListener("click",function(){
            //     event.preventDefault();  
            //     < ?php 
			// 	    $_SESSION["user"]["name"] = "guest";
			// 	    $_SESSION["user"]["type"] = "2";
			// 	    $_SESSION["msg_type"]     = "2";
            //     ?>
            //     //location.href = "form.php";
            // });
        }
	</script>
	<title>ログイン画</title>
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
            <span id="err"><?= $err_msg ?></span>
            <table>
                <tr>
                    <td>会員ID</td>
                    <td><?= create_input("text","user_id","user_id","20",$id,"","","会員IDを入力下さい") ?><td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td><?= create_input("password","user_pw","user_pw","20",$pw,"","","パスワードを入力下さい") ?></td>
                </tr>
            </table>
            <input type="submit" id="btn" name="btn" value="ログイン">
            <input type="submit" id="btn" name="btn" value="パスワードを忘れた場合">
            <input type="submit" id="btn" name="btn" value="新規会員登録">
            <input type="submit" id="btn" name="btn" value="ゲストでログイン">
        </form>
        <!-- a id="a1" href="repassword.php">パスワードを忘れた場合</a-->
    </div>
	<!--div>
		<a id="a2" href="form.php" >新規会員登録</a>
		<br>
		<a id="a3" href="repassword.php">ゲストユーザとして利用</a>
	</div-->
</main>

</body>
</html>

