<?php

/* --------------------------------------------------------------------------------------

【基本情報】
作成：秋野浩朗（web1902)
概要：全てのファイルに影響する共通処理用のファイル

----------------------------------------------------------------------------------------- */


/* ----- 共通処理 ----------------------------------------------------------------------- */
// k1_config.phpのパスとファイル名を変数にしとく。
$config_path = "../";
$config_file = "k1_config.php";

// k1_config.phpをインクルード。
include($config_path.$config_file);
include("../k1_config.php");
// セッションスタート。
session_start();

// 共通で使う変数（初期値つき）
$pflag   = false;
$err_msg = "";
$sql_output_form_sq_array     = array();
$sql_output_form_colomn_array = array();
$sql_output_test_qno_array    = array();

// 各種ページで使用するエラー一覧。適当に使いまわす。
// pdo 処理でエラーが入ればsqlというキーのエラーが増える。（ユーザには見せないエラー）
$err_array = array(
		"all"      => "当サイトに直接ログインがあった為、トップ画面に戻ります。",
		"index1"   => "IDとPWの両方を入力してください。",
		"index2"   => "IDもしくはPWが間違っています。",
        "index3"   => "連続して一定回数の誤入力があった為、暫くログインできません。",
        "form1"    => "IDを半角英数字で入力して下さい。",
        "form2"    => "該当IDは既に他のユーザが使用済みです。",
        "confilm1" => "該当IDは別会員と重複している為、登録できません。",
		
		"アクセス不可のページです。ログイン画面に戻ります。",
		"登録可能なIDです（タイムスタンプ現在）",
		"該当IDは既に他のユーザが使用済みです。（タイムスタンプ現在）",
		"未登録のIDです。",
		"項目を入力して下さい。",
		"記載内容に誤りがあります。",
);

// 【sql()関数内の $sqlno 】
// 各種ページで使用するSQL文一覧。適当に使いまわす。

/* 【sql()関数内の $sqlfuncno 】
 PDO処理内で使うSQL個別ファンクションの説明。
 index1 : 入力されたユーザIDとPWがDBに存在するかどうか。/ 戻り値：ある場合true、ない場合false
 index2 :
 
 
/* --------------------------------------------------------------------------------------- */

/* ----- 不正アクセス処理系 ------------------------------------------------------------------ */

// index.php（トップページ）以外でGET送信があった時の処理。
// session["user"]がない ＝ index.phpを通過していない ＝ 不正アクセス。
// session["user"]はindex.phpのみで作成される。
function session_user_check($session_user){
    if(!isset($session_user)){
        header("Location:index.php");
		exit;
    }
}


/* --------------------------------------------------------------------------------------- */

/* ----- HTMLタグ作成系 ------------------------------------------------------------------ */

// 共有のhead内のタグ
$head_common_tag  = "";
$head_common_tag .= "<meta charset=\"UTF-8\">" ;                                    // charset=utf8。常識やけど。
$head_common_tag .= "<meta name=\"robots\" content=\"noindex,follow\">";            // SEO対策（インデックス:×、クロール:〇）
$head_common_tag .= "<meta name=\"format-detection\" content=\"telephone=no\">";    // IOS対策（電話番号表示を電話リンク化しない）
$head_common_tag .= "<link rel=\"icon\" type=\"image/png\" href=\"./image/mi.png\" >";           // ファビコンのリンク
$head_common_tag .= "<link rel=\"stylesheet\" href=\"./css/default.css\" >";    // 共通CSSのリンク

// 引数：0,(global)$pflag 1,タイプ(String) 2,id名(String) 3,name名(String) 3,サイズ値(Int) 4,value値(変数) 5,placeholder値(String)
// 処理：引数を基にしたinputタグを作成（post時の再表示機能付き）
// 戻値：上記inputタグ
// 備考：使用時はechoすること。
function create_input($type,$id,$name,$size,$val,$attribute,$attr_val,$placeholder){
	global $pflag;
	$input_tag = "";
	$input_tag .= "<input type=\"{$type}\"" ;
    $input_tag .= "id=\"{$id}\" name=\"{$name}\" size=\"{$size}\"";
    if($type == "button"){
        $input_tag .= "value=\"{$val}\"";
    }else{
        if($pflag && !empty($val)){
	    	$input_tag .= "value=\"{$val}\"";
        }
    }
    if(!empty($attribute)){
        $input_tag .= "$attribute=\"{$attr_val}\"";
    }
    if(!empty($placeholder)){
        $input_tag .= "placeholder=\"{$placeholder}\"";
    }
    $input_tag .= ">";
	return $input_tag;
}

// 引数：1,タイプ(String) 2,id名(String) 3,name名(String) 4,element_array:配列の内容
// 処理：引数を基にしたselect/check boxタグを作成（post時の再表示機能付き）
// 戻値：上記boxタグ(各boxのvalue値は引数4の添字、表示は引数4のデータ値)
// 備考：引数１はcheckbox / selectbox を指定のコト。使用時はechoすること。
function create_box($type,$id,$name,$element_array){
	global $pflag;
	$result = "";
	if($type == "checkbox"){
		for($i=0;$i<count($element_array);$i++){
			$result .= "<input type=\"checkbox\" id=\"{$id}\" name=\"{$name}[]\" >";
			$result .= "value=\"{$i}\"";
			if($pflag && isset($element_array[$i][1])){
				$result .= "\"checked\"";
			}
			$result .= "$element_array[$i]";
		}
	}
	if($type == "selectbox"){
		$result .= "<select size=\"1\" id=\"{$id}\" name=\"{$name}[]\" >";
		for($i=1;$i<count($element_array)+1;$i++){
			$result .=  "<option value=\"{$i}\"";
			if($pflag && isset($element_array[$i][1])){
				$result .= "\"selected\"";
			}
			$result .= ">$element_array[$i] </option>";
		}
		$result .= "</select>";
    }
    // セレクトタグ（グループ付き）生成処理
    if($type == "selectbox_optg"){
		$result .= "<select size=\"1\" id=\"{$id}\" name=\"{$name}[]\" >";
        foreach($element_array as $key => $val){
            if(isarray($val)){
                $result .=  "<optiongroup value=\"{$key}\">";
                foreach($val as $val2){
                    $result .=  "<option value=\"{$val2}\"";
                    // 要修正（POST送信時の処理として）
                    if($pflag && isset($element_array[$i][1])){
                        $result .= "\"selected\"";
                    }
                    $result .=  ">";
                }
            }
        }
		$result .= "</select>";
	}
	return $result;
}


/* --------------------------------------------------------------------------------------- */


/* ----- PDO処理系 ----------------------------------------------------------------------- */


// 引数：1,ユーザ種類(0-2) 2,$SQL文の添え字(int) 　2,id名(String) 3,name名(String) 4,element_array:配列の内容
// 処理：引数を基にしたPDO処理
// 戻値：
// 備考：
function sql($type,$userno,$sqlno,$funcno,$val,$val2,$val_array) {
	global $database_dsn,$database_user,$database_password,$err_array,$sql_array;
    $userno = "-1"; // 強制的に検証したい時に使う。通常はコメントアウトする行。
    $dsn   = $database_dsn;
	$user  = $database_user[$userno];
	$pass  = $database_password[$userno];
    $check = false;
    $same  = false;
    
	$sql_array = array(
		"index1"    => "select user_id,user_pw from k1_user where user_id='${val}' and user_pw='${val2}'",
        "index2"    => "insert into k1_log set log_id = '${val}' , log_log = now()",
        "form1"     => "select * from k1_secret_q",
        "form2"     => "select column_name from information_schema.columns where table_schema = 'web1902' and table_name = 'k1_user';",
        "form3"     => "select user_id from k1_user",
        "confilm1"  => "select user_id from k1_user",
        "confilm2"  => "insert into k1_user set ",
        "test1"     => "select qu_no from k1_question order by RAND() limit 10"
	);
    if($sqlno == "confilm2"){
        echo "<br>";
        foreach($val_array as $key => $val){
            if($val === end($val_array)) {
                $sql_array[$sqlno] .= $key . "='" . $val ."',";
                
            }else{
                $sql_array[$sqlno] .= $key . "='" . $val . "',";
            }
        } 
        $sql_array[$sqlno] .= "user_registration = now()";
    }

	try{
		$db = new PDO($dsn,$user,$pass);
		$db->exec("SET NAMES utf8");
		$db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		
		// sql文の選択。候補は上の$sql_array参照のこと。
		$sql = $sql_array[$sqlno];
		
		$result = $db->prepare($sql);
		if(!$result->execute()){
			echo "err1:" , $sql;
			return false;
		}
		var_dump($sql);
		// 文章のタイプで切り分け。
		switch($type){
			case "select":
				$count = $result->rowCount();
				echo 'select';
				break;
			case "update":
			case "insert":
				$count = $db->exec($sql);
				echo 'update or insert';
				break;
		}
		var_dump($count);
		if($count !== FALSE){
			if($count == 0){
				echo "err2:" , $sql;
				return false;
			}else{
				$rows = $result->fetchall(PDO::FETCH_ASSOC);
				foreach($rows as $row){
					
                    $check = sql_func($row,$funcno,$check,$val,$val2);

				}
			}
		}else{
			echo "err3:" , $sql;
			return false;
		}
		$db = NULL;
		var_dump($db);
	}
	catch (Exception $e){
		echo "MSG:" .$e->getMessage()."<br>";
		echo "CODE:".$e->getCode()."<br>";
		echo "LINE:".$e->getLine()."<br>";
		$db = NULL;
		return false;
	}
	if($check){
		return true;
	}else{
		return false;
	}
}


/* --------------------------------------------------------------------------------------- */


function sql_func($row,$funcno,$check,$val,$val2){
    global $sql_output_form_sq_array,$sql_output_form_colomn_array,
    $sql_output_test_qno_array,$same;
    switch ($funcno){
		case "index1":
			if($val === $row["user_id"]){
				if($val2 === $row["user_pw"]){
					$check = true;
					echo '1';
					break;
				}
				echo '2';
			}
			$check = false;
			break;
        case "form1":
            $sql_output_form_sq_array[$row["sq_id"]] = $row["sq_q"];
            break;
        case "form2":
            $sql_output_form_colomn_array[] = $row["column_name"];
            break;
        case "form3":
			if(!$same){
                if($val === $row["user_id"]){
	    			echo '6';
                    $check = false;
                    $same  = true;
				    break;
                }else{
                    $check = true;
                }
            }
            break;
        case "confilm1":
            if(!$same){
                if($val === $row["user_id"]){
	    			echo '6';
                    $check = false;
                    $same  = true;
				    break;
                }else{
                    $check = true;
                }
            }
            break;
        case "confilm2":
            $check = true;
            break;
        case "test1":
            $sql_output_test_qno_array[] = $row["qu_no"];
            break;
    }
	return $check;
}


?>    