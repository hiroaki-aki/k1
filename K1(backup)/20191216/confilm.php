<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：会員登録情報確認ページ
番号：③

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）

// ここで使う変数の初期化。
$user_array = array();
// $last_name = $first_name = $last_name_kana = $first_name_kana = $birth_date = $sex =
// $postalcode_1 = $postalcode_2 = $address_1 = $address_2 = $address_3 = 
// $mail = $id = $pw = $sq_array = $seaqret_q = $seaqret_a = "";
$sq_array     = array();
$colomn_array = array();
// 【要削除】検証用
// var_dump($_COOKIE);
//var_dump($_SESSION);
//var_dump($_POST);

/* --------------------------------------------------------------------------------------- */

sql("select","3","form1","form1","","","");
$sq_array = $sql_output_form_sq_array;
sql("select","3","form2","form2","","","");
$colomn_array = $sql_output_form_colomn_array;
var_dump($colomn_array);
foreach($colomn_array as $key => $val){
    $user_array[$val] = "";
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $pflag = true;
    foreach($_POST["user"] as $key => $val){
        $user_array[$key] = htmlspecialchars($_POST["user"][$key],ENT_QUOTES);
    }
    echo $user_array["user_id"];
    $check = sql("select","3","confilm1","confilm1",$user_array["user_id"],"","");
	if($check){
        sql("insert","3","confilm2","confilm2","","",$_POST['user']);
        header("Location:login.php");
		exit;
    }else{	
        $err_msg = $err_array["confilm1"];
    }
}else{
    session_user_check($_SESSION["user"]);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $head_common_tag ?>
	<script>
        window.onload = function(){
            var input_tags = document.getElementsByTagName("input");
            for(var i = 0 ; i < input_tags.length ; i++){
                input_tags[i].readOnly == true;
            }
            for(var i = 0 ; i < sessionStorage.length ; i++){
                console.log(sessionStorage.key(i));
                if(sessionStorage.getItem(sessionStorage.key(i)) != ""){ 
                    document.getElementById(sessionStorage.key(i)).value = sessionStorage.getItem(sessionStorage.key(i));
                }
            }
        }
    </script>
    
	<title>会員情報確認画面</title>
</head>

<body>
<header>
	<h1>会員情報確認画面</h1>
</header>
<main>
	<div>
        <header>
            <h2>以下の内容で登録します。</h2>
        </header>
        <span id="err"><?= $err_msg ?></span>
        <form id="form_user" action="confilm.php" method="POST">
            <table>
            	<caption id="customer">お客様情報</caption>
                <tr>
                    <td>氏名</td>
                    <td>
                    	<?= create_input("text","user_last_name","user[user_last_name]","10",$user_array["user_last_name"],"maxlength","30","例）南") ?>
                    	<?= create_input("text","user_first_name","user[user_first_name]","10",$user_array["user_first_name"],"maxlength","30","例）太郎") ?>
                    </td>
                </tr>
                <tr>
                    <td>ﾌﾘｶﾞﾅ</td>
                    <td>
                    	<?= create_input("text","user_last_name_kana","user[user_last_name_kana]","10",$user_array["user_last_name_kana"],"maxlength","30","例）ﾐﾅﾐ") ?>
                    	<?= create_input("text","user_first_name_kana","user[user_first_name_kana]","10",$user_array["user_first_name_kana"],"maxlength","30","例）ﾀﾛｳ") ?>
                    </td>
                </tr>
                <tr>
                    <td>生年月日</td>
                    <td><?= create_input("date","user_birth_date","user[user_birth_date]","20",$user_array["user_birth_date"],"maxlength","8","例）1989/08/04") ?></td>
                </tr>
                <tr>
                    <td>性別</td>
                    <td>
                        <?= create_input("text","user_sex","user[user_sex]","20",$user_array["user_sex"],"","","") ?>
                        <?= create_input("hidden","man","man","20","","","","") ?>
                        <?= create_input("hidden","woman","woman","20","","","","") ?>
                        <?= create_input("hidden","other","other","20","","","","") ?>
                    </td>
                    <!-- <td>
                    	<input type="radio" name="user_sex" id="man" value="男" checked>
		                    <label for="man" form="form_user">男</label>
                		<input type="radio" name="user_sex" id="woman" value="女">
                    		<label for="woman" form="form_user">女</label>
                		<input type="radio" name="user_sex" id="other" value="その他">
                    		<label for="other" form="form_user">その他</label>
                    </td> -->
                </tr>
                <tr>
                	<td rowspan="5">住所</td>
                </tr>
      			<tr>
      				<td>
                        郵便番号
                        <?= create_input("text","user_postalcode","user[user_postalcode]","20",$user_array["user_postalcode"],"maxlength","8","例)1234567") ?> 
              		</td>
              	</tr>
              	<tr>
                    <td>都道府県
                        <?= create_input("text","user_address_1","user[user_address_1]","",$user_array["user_address_1"],"","","")?></td>
                	<!-- <td>都道府県
          				<select id="user_address_1" name="user_address_1" >
                 			<option value="" selected>都道府県を選択</option>
                  			<optgroup label="北海道地方">
                    			<option value="北海道">北海道</option>
                    		<optgroup label="東北地方">
                    			<option value="青森県">青森県</option><option value="岩手県">岩手県</option><option value="宮城県">宮城県</option>
                    			<option value="秋田県">秋田県</option><option value="山形県">山形県</option><option value="福島県">福島県</option>
                  			<optgroup label="関東地方">
                   				<option value="茨城県">茨城県</option><option value="栃木県">栃木県</option><option value="群馬県">群馬県</option>
                   				<option value="埼玉県">埼玉県</option><option value="千葉県">千葉県</option><option value="東京都">東京都</option>
                    			<option value="神奈川県">神奈川県</option>
                  			<optgroup label="中部地方">   
                    			<option value="新潟県">新潟県</option><option value="富山県">富山県</option><option value="石川県">石川県</option>
                    			<option value="福井県">福井県</option><option value="山梨県">山梨県</option><option value="長野県">長野県</option>
                   				<option value="岐阜県">岐阜県</option><option value="静岡県">静岡県</option><option value="愛知県">愛知県</option>
                  			<optgroup label="近畿地方">
                    			<option value="三重県">三重県</option><option value="滋賀県">滋賀県</option><option value="京都府">京都府</option>
                    			<option value="大阪府">大阪府</option><option value="兵庫県">兵庫県</option><option value="奈良県">奈良県</option>
                   				<option value="和歌山県">和歌山県</option>
                  			<optgroup label="中国地方">
                   				<option value="鳥取県">鳥取県</option><option value="島根県">島根県</option><option value="岡山県">岡山県</option>
                   				<option value="広島県">広島県</option><option value="山口県">山口県</option>
                			<optgroup label="四国地方">
                    			<option value="徳島県">徳島県</option><option value="香川県">香川県</option><option value="愛媛県">愛媛県</option><option value="高知県">高知県</option>
                  			<optgroup label="九州地方">
                   				<option value="福岡県">福岡県</option><option value="佐賀県">佐賀県</option><option value="長崎県">長崎県</option>
                   				<option value="熊本県">熊本県</option><option value="大分県">大分県</option><option value="宮崎県">宮崎県</option>
                   				<option value="鹿児島県">鹿児島県</option><option value="沖縄県">沖縄県</option>
          				</select>
          			</td> -->
          		</tr>
          		<tr>
          			<td>市区町村<?= create_input("text","user_address_2","user[user_address_2]","20",$user_array["user_address_2"],"maxlength","20","例）和泉市テクノステージ") ?></td>
          		</tr>
          		<tr>
          			<td>番地<?= create_input("text","user_address_3","user[user_address_3]","20",$user_array["user_address_3"],"maxlength","20","例）2-3-5") ?></td>
                </tr>
                <tr>
      				<td>電話番号</td>
                    <td><?= create_input("text","user_tel","user[user_tel]","20",$user_array["user_tel"],"maxlength","11","例)123456789") ?></td>
              	</tr>
            </table>
            <table>
            	<caption>ログイン情報</caption>
                <tr>
                	<td>mail</td>
                	<td><?= create_input("email","user_mail","user[user_mail]","20",$user_array["user_mail"],"","","例）minami@email.com") ?></td>
                </tr>
            	<tr>
                	<td>ID(半角英数字6文字以内)</td>
                    <td>
                        <?= create_input("text","user_id","user[user_id]","20",$user_array["user_id"],"maxlength","6","例）minami" ) ?>
                        <?php
                            //echo "<form action=\"form.php\" method=\"post\">";
                            //echo create_input("text","user_id","user_id","20",$id,"maxlength","6","例）minami") , "<br>";
                            echo create_input("hidden","id_check","id_check","20","確認","onclick","check_id()","");
                            //echo "</form>";
                        ?>
                	</td>
                </tr>
                <tr>
                	<td>パスワード</td>
                    <td>
                        <?= create_input("password","user_pw","user[user_pw]","20",$user_array["user_pw"],"minlength","6","例）任意のパスワード" ) ?>
                        <?= create_input("hidden","user_pw2","user_pw2","20","","","","") ?>
                    </td>
                </tr>
            	<!-- <tr>
                	<td>パスワード<br>(入力確認用)</td>
                    <td>< ?= create_input("password","user_pw2","user_pw2","20",$pw,"minlength","6","例）任意のパスワード" ) ?></td>
                </tr> -->
                <tr>
                	<td>秘密の質問</td>
                	<td><?= create_input("text","user_secret_q","user[user_secret_q]","",$user_array["user_secret_q"],"","","")?></td>
            	</tr>
            	<tr>
                	<td>秘密の質問の答え</td>
                	<td><?= create_input("text","user_secret_a","user[user_secret_a]","30",$user_array["user_secret_a"],"maxlength","20","20文字以内で記載下さい") ?></td>
                </tr>
            </table>
            <!-- <table>
            	<caption>各種規約について</caption>
	            <tr><td><textarea rows="10" cols="20">会員規約（未規定です）</textarea></td></tr>
	            <tr><td><a href="perspnal.html" target="_blank">プライバシーポリシー</a>について</td></tr>
	            <tr><td><label><input id="pribasy_agree" type="checkbox" value="agree">上記に同意する</label></td></tr>
            </table> -->
            <?= create_input("hidden","pribasy_agree","pribasy_agree","20","","","","") ?>
            <?= create_input("hidden","confilm","confilm","20","","","","") ?>
            <input type="submit" id="submit" name="submit" value="登録する">
        </form>
	</div>
</main>
</body>
</html>

