<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：会員登録ページ
番号：②

----------------------------------------------------------------------------------------- */



/* ----- 共通処理 ----------------------------------------------------------------------- */

include 'config.php';

// JS で使う事になるログイン制御の変数。（k1_config.php → config.php → 当該ファイル）
// PHP変数初期値は文字列じゃないとうまく機能しない。（初期値以外は可）

// ここで使う変数の初期化。
$last_name = $first_name = $last_name_kana = $first_name_kana = $birth_date = 
$postalcode = $address_1 = $address_2 = $address_3 = $tel =
$mail = $id = $pw = $sq_array = $secret_a = "";
$sq_array     = array();
$colomn_array = array();
$id_check     = "false";
$pflag        = "false";

// 【要削除】検証用
var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_POST);

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
    if(true){   //【余裕があれば作る。】会員ID確認処理（POST送信される）用の切り分け（）
        foreach($_POST["user"] as $key => $val){
            $user_array[$key] = htmlspecialchars($_POST["user"][$key],ENT_QUOTES);
        }
	    if($_POST["user_id"] != "" || preg_match("/^[a-zA-Z0-9]+$/",$_POST["user_id"])){
            $err_msg = $err_array["form1"];
        }else{
            $id = htmlspecialchars($_POST["user_id"],ENT_QUOTES);
            $id_check = sql("select","3","form3","form3",$id,"","");
        }
    }
}else{
    session_user_check($_SESSION["user"]);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $head_common_tag ?>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
	<script>
        window.onload = function(){
            if(<?= $pflag ?>){
                var id_tag = document.getElementById("user_id");
                id_tag.readOnly == true;
            }

            // 郵便番号入力時に自動で住所を表示（要ネット接続）
            const postalcode_tag = document.getElementById("user_postalcode");
            postalcode_tag.setAttribute("onKeyUp","AjaxZip3.zip2addr(this,'','user_address_1','user_address_2')");
            
            // 動的な brタグの作成
            const br_tag  = document.createElement("br");
            
            //　【動作検証中】ID使用可能有無
            if( <?= $pflag ?> ){
                const msg_tag = document.createElement("span");
                const user_id_parent  = document.getElementById("user_id").parentNode;
                if( <?= $id_check ?> ){
                    msg_tag.innerText   = "使用可能なIDです。";
                    parent.appendChild(br_tag);
                    parent.appendChild(msg_tag);
                }else{
                    msg_tag.style.color = "red";
                    msg_tag.innerText   = "当該IDは使用できません。";
                    parent.appendChild(br_tag);
                    parent.appendChild(msg_tag);
                }
            }
            
            // PW確認用との一致チェック（不一致ならエラーメッセージを表示)
            const pw_tag         = document.getElementById("user_pw");
            const pw2_tag        = document.getElementById("user_pw2");
            const err_tag        = document.createElement("span");
            err_tag.style.color  = "red";
            err_tag.innerText    = "確認用PWと一致していません。";
            var pw_check_func = function(){
                var pw_val  = pw_tag.value;
                var pw2_val = pw2_tag.value;
                var parent  = pw2_tag.parentNode;
                if(pw_val != pw2_val){
                    parent.appendChild(br_tag);
                    parent.appendChild(err_tag);
                }else{
                    parent.removeChild(err_tag);
                }
            }
            pw2_tag.onkeyup = pw_check_func;
            pw_tag.onkeyup = pw_check_func;
            
            //「規約に同意する」ボタンのチェック確認（チェックしてなけば確認ボタンを無効化）
            const check_tag      = document.getElementById("pribasy_agree");
            const confilm_tag    = document.getElementById("confilm");
            confilm_tag.disabled = true;
            check_tag.onchange =function(){
                if(!this.checked == true){
                    confilm_tag.disabled = true;
                }else{
                    confilm_tag.disabled = false;
                }
            }
            confilm_tag.onclick = form_check;
        }
        
        // 入力内容の確認
        function form_check(){
            var check              = true;
            
            // inputタグの値の取得（取り敢えず入力箇所以外も全て取得）
            var input_val_array    = {};
            var input_tags         = document.getElementsByTagName("input");
            var select_tag_address = document.getElementById("user_address_1");
            var select_tag_secretq = document.getElementById("user_secret_q");
            for(var i = 0 ; i < input_tags.length ; i++){
                if(input_tags[i].type == "radio" && input_tags[i].checked){
                    input_val_array[input_tags[i].name] = input_tags[i].value;
                }else{
                    input_val_array[input_tags[i].id] = input_tags[i].value;
                }
            }
            input_val_array[select_tag_address.id] = select_tag_address.value;
            input_val_array[select_tag_secretq.id] = select_tag_secretq.value;

            // 正規表現チェック & エラー箇所表示
            const h2_tag   = document.getElementById("h2");
            const err2_tag = document.getElementById("err2");
            err2_tag.innerHTML = "";
            if(input_val_array["user_last_name"] == ""){
                document.querySelector("#user_last_name").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_last_name").classList.remove("err");
            }
            if(input_val_array["user_first_name"] == ""){
                document.querySelector("#user_first_name").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_first_name").classList.remove("err");
            }
            if(input_val_array["user_last_name_kana"].match(/[^ｦ-ﾟ]/) ||
               input_val_array["user_last_name_kana"] == ""){
                document.querySelector("#user_last_name_kana").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_last_name_kana").classList.remove("err");
            }
            if(input_val_array["user_first_name_kana"].match(/[^ｦ-ﾟ]/) ||
               input_val_array["user_first_name_kana"] == ""){
                document.querySelector("#user_first_name_kana").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_first_name_kana").classList.remove("err");
            }
            // 32〜39日とか閏年の制限はしてない。
            console.log(input_val_array["user_birth_date"].match(/[^d]/) );
            var falseval = false;
            console.log(falseval);
            if(input_val_array["user_birth_date"].match(/\d/) ||
              //input_val_array["user_birth_date"].match(/^\d{8}/) ||
              //input_val_array["user_birth_date"].match(/^\d{4}[0|1]\d[0-3]\d/) ||
              //input_val_array["user_birth_date"] > new Date() ||
              input_val_array["user_birth_date"] == ""){
                document.querySelector("#user_birth_date").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_birth_date").classList.remove("err");
            }
            if(input_val_array["user_postalcode"].match(/^\d{8}/) ||
              input_val_array["user_postalcode"] == ""){
                document.querySelector("#user_postalcode").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_postalcode").classList.remove("err");
            }
            if(input_val_array["user_address_1"] == ""){
                document.querySelector("#user_address_1").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_address_1").classList.remove("err");
            }
            if(input_val_array["user_address_2"] == ""){
                document.querySelector("#user_address_2").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_address_2").classList.remove("err");
            }
            if(input_val_array["user_address_3"] == ""){
                document.querySelector("#user_address_3").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_address_3").classList.remove("err");
            }
            if(input_val_array["user_tel"].match(/^\d{.10}/) ||
              input_val_array["user_tel"] == ""){
                document.querySelector("#user_tel").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_tel").classList.remove("err");
            }
            if(input_val_array["user_mail"].match(/^[\w.\-]+@[\w\-]+\.[\w.\-]+/) ||
              input_val_array["user_mail"] == ""){
                document.querySelector("#user_mail").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_mail").classList.remove("err");
            }
            if(input_val_array["user_id"].match(/^([a-zA-Z0-9]{6})$/) ||
              input_val_array["user_id"] == ""){
                document.querySelector("#user_id").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_id").classList.remove("err");
            }
            if(input_val_array["user_pw"].match(/^([a-zA-Z0-9]{6,})$/) ||
              input_val_array["user_pw"] != input_val_array["user_pw2"] ||
              input_val_array["user_pw"] == "" ){
                document.querySelector("#user_pw").classList.add("err");
                document.querySelector("#user_pw2").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_pw").classList.remove("err");
                document.querySelector("#user_pw2").classList.remove("err");
            }
            if(input_val_array["user_secret_a"] == ""){
                document.querySelector("#user_secret_a").classList.add("err");
                h2_tag.scrollIntoView({behavior:'smooth',block:'start'});
                check = false;
            }else{
                document.querySelector("#user_secret_a").classList.remove("err");
            }
            
            if(check){
                for(key in input_val_array){
                    console.log('key:' + key + ' value:' + input_val_array[key]);
                    sessionStorage.setItem(key,input_val_array[key]);
                }
                location.href = "confilm.php";
            }else{
                const err_tag       = document.createElement("span");
                err_tag.style.color = "red";
                err_tag.style.fontSize = 15;
                err_tag.innerText   = "赤枠の項目に誤りがあります。";
                err2_tag.appendChild(err_tag);
            }
            
        }
    </script>
	<title>会員登録フォーム</title>
</head>
<body>
<header>
	<h1>会員登録フォーム</h1>
</header>
<main>
	<div>
        <header>
            <h2 id="h2">ログイン画面</h2>
        </header>
        <span id="err"><?= $err_msg ?></span>
        <span id="err2"></span>
        <form id="form_user" action="confilm.php" method="GET">
            <table>
            	<caption id="customer">お客様情報</caption>
                <tr>
                    <td>氏名</td>
                    <td>
                    	<?= create_input("text","user_last_name","user_last_name","10",$last_name,"maxlength","30","例）南") ?>
                    	<?= create_input("text","user_first_name","user_first_name","10",$first_name,"maxlength","30","例）太郎") ?>
                    </td>
                </tr>
                <tr>
                    <td>ﾌﾘｶﾞﾅ</td>
                    <td>
                    	<?= create_input("text","user_last_name_kana","user_last_name_kana","10",$last_name_kana,"maxlength","30","例）ﾐﾅﾐ") ?>
                    	<?= create_input("text","user_first_name_kana","user_first_name_kana","10",$first_name_kana,"maxlength","30","例）ﾀﾛｳ") ?>
                    </td>
                </tr>
                <tr>
                    <td>生年月日</td>
                    <td><?= create_input("date","user_birth_date","user_birth_date","20",$birth_date,"maxlength","8","例）1989/08/04") ?></td>
                </tr>
                <tr>
                    <td>性別</td>
                    <td>
                    	<input type="radio" name="user_sex" id="man" value="男" checked>
		                    <label for="man" form="form_user">男</label>
                		<input type="radio" name="user_sex" id="woman" value="女">
                    		<label for="woman" form="form_user">女</label>
                		<input type="radio" name="user_sex" id="other" value="その他">
                    		<label for="other" form="form_user">その他</label>
                    </td>
                </tr>
                <tr>
                	<td rowspan="5">住所</td>
                </tr>
      			<tr>
      				<td>
                        郵便番号
                        <?= create_input("text","user_postalcode","user_postalcode","20",$postalcode,"maxlength","8","例)1234567") ?>
                        <!-- ?= create_input("text","user_postalcode_2","user_postalcode_2","8",$postalcode_1,"maxlength","4","例)4567") ?--> 
              		</td>
              	</tr>
              	<tr>
                	<td>都道府県
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
          			</td>
          		</tr>
          		<tr>
          			<td>市区町村<?= create_input("text","user_address_2","user_address_2","20",$address_2,"maxlength","20","例）和泉市テクノステージ") ?></td>
          		</tr>
          		<tr>
          			<td>番地<?= create_input("text","user_address_3","user_address_3","20",$address_3,"maxlength","20","例）2-3-5") ?></td>
                </tr>
                <tr>
      				<td>電話番号</td>
                    <td><?= create_input("text","user_tel","user_tel","20",$tel,"maxlength","11","例)123456789") ?></td>
              	</tr>
            </table>
            <table>
            	<caption>ログイン情報</caption>
                <tr>
                	<td>mail</td>
                	<td><?= create_input("email","user_mail","user_mail","20",$mail,"","","例）minami@email.com") ?></td>
                </tr>
            	<tr>
                	<td>ID(半角英数字6文字以内)</td>
                    <td>
                        <?= create_input("text","user_id","user_id","20",$id,"maxlength","6","例）minami" ) ?>
                        <?php
                            //echo "<form action=\"form.php\" method=\"post\">";
                            //echo create_input("text","user_id","user_id","20",$id,"maxlength","6","例）minami") , "<br>";
                            //echo create_input("submit","id_check","id_check","20","確認","","",""),"確認する";
                            //echo "</form>";
                        ?>
                	</td>
                </tr>
                <tr>
                	<td>パスワード(半角英数字6文字以上)</td>
                    <td><?= create_input("password","user_pw","user_pw","20",$pw,"minlength","6","例）任意のパスワード" ) ?></td>
                </tr>
            	<tr>
                	<td>パスワード<br>(入力確認用)</td>
                    <td><?= create_input("password","user_pw2","user_pw2","20",$pw,"minlength","6","例）任意のパスワード" ) ?></td>
                </tr>
                <tr>
                	<td>秘密の質問</td>
                	<td><?= create_box("selectbox","user_secret_q","user_secret_q",$sq_array) ?></td>
            	</tr>
            	<tr>
                	<td>秘密の質問の答え</td>
                	<td><?= create_input("text","user_secret_a","user_secret_a","30",$secret_a,"maxlength","20","20文字以内で記載下さい") ?></td>
                </tr>
            </table>
            <table>
            	<caption>各種規約について</caption>
	            <tr><td><textarea rows="10" cols="20">会員規約（未規定です）</textarea></td></tr>
	            <tr><td><a href="perspnal.html" target="_blank">プライバシーポリシー</a>について</td></tr>
	            <tr><td><label><input id="pribasy_agree" type="checkbox" value="agree">上記に同意する</label></td></tr>
	        </table>
	        
            <input type="button" id="confilm" name="confilm" value="確認する">
        </form>
	</div>
</main>
</body>
</html>

