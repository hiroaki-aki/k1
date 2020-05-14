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

// 【要削除】検証用
var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_POST);

// 共通で使う変数（初期値つき）
$pflag   = false;
$err_msg = "";
$sql_output_form_sq_array     = array();
$sql_output_form_colomn_array = array();
$sql_output_main_colomn_array = array();
$sql_output_main_user_array	  = array();
$sql_output_test_qno_array    = array();
$sql_output_question_q_array  = array();
$ans_test_status			  = "false";
$ans_test_highscore			  = "";

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
$head_common_tag .= "<meta charset=\"UTF-8\">";											// charset=utf8。常識やけど。
$head_common_tag .= "<meta name=\"robots\" content=\"noindex,follow\">";				// SEO対策（インデックス:×、クロール:〇）
$head_common_tag .= "<meta name=\"format-detection\" content=\"telephone=no\">";		// IOS対策（電話番号表示を電話リンク化しない）
$head_common_tag .= "<link rel=\"icon\" type=\"image/png\" href=\"./image/mi.png\" >";	// ファビコンのリンク
$head_common_tag .= "<link rel=\"stylesheet\" href=\"./css/default.css\" >";			// 共通CSSのリンク
$head_common_tag .= "<link href=\"https://use.fontawesome.com/releases/v5.6.1/css/all.css\" rel=\"stylesheet\">"; // fontawesomeの読み込み

// 共有のheader内のタグ
$header_common_tag  = "";
$header_common_tag .= "<h1>みんなのクイズ</h1>";
if(isset($_SESSION["user"]["name"])){
	if($_SESSION["user"]["name"] != "pre"){
		if($_SESSION["user"]["name"] == "guest"){
			$header_common_tag .= "<p>ゲスト 様</p>";
		}else{
			$header_common_tag .= "<p>{$_SESSION["user"]["name"]} 様</p>";
		}
		$header_common_tag .= "<a id=\"a1\" href=\"index.php\">ログアウト</a>";
	}else{
		$header_common_tag .= "<a id=\"a1\" href=\"index.php\">トップ（ログイン画面）に戻る</a>";
	}
}

// 引数：0,(global)$pflag 1,タイプ(String) 2,id名(String) 3,name名(String) 3,サイズ値(Int) 4,value値(変数) 5,placeholder値(String)
// 処理：引数を基にしたinputタグを作成（post時の再表示機能付き）
// 戻値：上記inputタグ
// 備考：使用時はechoすること。
function create_input($type,$id,$name,$size,$val,$attribute,$attr_val,$placeholder){
	global $pflag;
	$input_tag = "";
	$input_tag .= "<input type=\"{$type}\"" ;
	$input_tag .= "id=\"{$id}\" name=\"{$name}\" size=\"{$size}\"";
	if($type == "hidden" || $type == "button" || $type == "submit" ){
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

// 引数：0,(global)$pflag 1,タイプ(String) 2,id名(String) 3,name名(String) 
//		4,element_array(配列変数):配列の内容(引数1でselectbox指定時は連想配列も可)
//		5,chose_no(推奨：変数)：POST送信再表示用に前画面で選択した番号or値
//		6,val_type(true/false)：valueの値（true：引数4で指定した$element_arrayがそのまま入る、false:0から採番したものが入る）
// 処理：引数を基にしたcheck/selectboxタグを作成（post時の再表示機能付き）
// 戻値：上記boxタグ(各boxのvalue値は引数4の添字、表示は引数4のデータ値)
// 備考：引数１はcheckbox または selectbox を指定のコト。使用時はechoすること。
//		selectbox の時は引数4に連想配列を指定するとkeyがoptgroupとして生成される。
function create_box($type,$id,$name,$element_array,$chose_no,$val_type){
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
		if(array_values($element_array) === $element_array){
			// $element_array が普通の配列の時の処理
			for($i=0;$i<count($element_array);$i++){
				if($val_type == true){
					$result .=  "<option value=\"{$element_array[$i]}\"";
				}else{
					$result .=  "<option value=\"{$i}\"";
				}
				if($pflag){
					if(is_array($chose_no) && $i == $chose_no[0]){
						$result .= "selected";
					}else{
						if($i == $chose_no){
							$result .= "selected";
						}
					}
				}
				$result .= ">$element_array[$i]</option>";
			}
			$result .= "</select>";
		}else{
			// $element_array が連想配列の時の処理（optgroup タグ作成)
			$i = 0;
			foreach($element_array as $key => $val){
				if($val === reset($key)){
					if($val_typa == true){
						$result .= "<optgroup label=\"{$key}\">";	
						$result .= "<option value=\"{$val}\"";
					}else{
						$result .= "<optgroup label=\"{$key}\">";	
						$result .= "<option value=\"{$i}\"";
					}
				}else{
					if($val_typa == true){
						$result .= "<option value=\"{$val}\"";
					}else{
						$result .= "<option value=\"{$i}\"";
					}
				}
				if($pflag){
					if(is_array($chose_no) && $i == $chose_no[0]){
						$result .= "selected";
					}else{
						if($i == $chose_no){
							$result .= "selected";
						}
					}
				}
				$result .= ">$val </option>";
			}
			$result .= "</select>";
		}
	}
	return $result;
}

// ミスって作ってしまって運用してしまったやつ
// form.phpの「秘密の質問」で使ってる。時間ないので特別に残す。
function create_box_sq($type,$id,$name,$element_array){
	global $pflag;
	$result = "";
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
		"index1"		=> "select user_id,user_pw from k1_user where user_id = '${val}' and user_pw = '${val2}' and user_delete = 0 and user_freeze = 0",
		"index2"		=> "insert into k1_log set log_id = '${val}' , log_log = now()",
		"form1"			=> "select * from k1_secret_q",
		"form2"			=> "select column_name from information_schema.columns where table_schema = 'web1902' and table_name = 'k1_user';",
		"form3"			=> "select user_id from k1_user",
		"confilm1"		=> "select user_id from k1_user",
		"confilm2"		=> "insert into k1_user set ",
		"confilm3"		=> "insert into k1_grades (gr_id,gr_type,gr_su) values('${val}',0,0),('${val}',1,0),('${val}',2,0)",
		"confilm4"		=> "update k1_user set ",
		"main1"			=> "select column_name from information_schema.columns where table_schema = 'web1902' and table_name = 'k1_user';",
		"main2"			=> "select * from k1_user where user_id = '${val}' ",
		"main3"			=> "update k1_user set user_delete = '1' where user_id = '${val}' ",
		"que_test1"		=> "select qu_no from k1_question where qu_status = 1 and qu_delete = 1 order by RAND() limit 10",
		"que_test2"		=> "select qu_no from k1_question,k1_answer where qu_no = an_no and qu_status = 1 and qu_delete = 1 order by an_su_c asc , qu_no desc limit 10",
		"que_test3"		=> "select qu_no from k1_question,k1_answer where qu_no = an_no and qu_status = 1 and qu_delete = 1 order by an_evaluation desc , qu_no desc limit 10",
		"question1"		=> "select qu_no,qu_title,qu_id,qu_question,qu_answer_1,
							qu_answer_2,qu_answer_3,qu_answer_4,qu_answer_correct,
							qu_time_limit,qu_explanation from k1_question where qu_no = '${val}' ",
		"question2"		=> "update k1_answer set an_su_a = an_su_a + 1,an_su_c = an_su_c + 1 where an_no = '${val}' ",
		"question3"		=> "update k1_answer set an_su_a = an_su_a + 1 where an_no = '${val}' ",
		"ans_test1"		=> "select gr_score,gr_time from k1_grades where gr_id = '${val2}' and gr_type = '${val_array}' ",
		"ans_test2"		=> "update k1_grades set gr_score = '${val}',gr_time = now(),gr_su = gr_su + 1 where gr_id = '${val2}' and gr_type = '${val_array}'",
		"ans_test3"		=> "update k1_grades set gr_su = gr_su + 1  where gr_id = '${val2}' and gr_type = '${val_array}'",
		"que_create1"	=> "select column_name from information_schema.columns where table_schema = 'web1902' and table_name = 'k1_question' ",
		"answer1"		=> "update k1_answer set an_evaluation = an_evaluation + '${val}' , an_su_e = an_su_e + 1 where an_no = '${val2}' ",
		"answer2"		=> "insert into k1_question set qu_id = '${val}',",
		//"answer3"		=> "insert into k1_question set qu_id = '${val}' ,",
		"answer4"		=> "update k1_question set qu_status = 1 , qu_reason = '${val}' where qu_no = '${val2}' ",
		"answer5"		=> "update k1_question set qu_status = 2 , qu_reason = '${val}' where qu_no = '${val2}' "
	);

	if($sqlno == "confilm2"){
		// 会員登録時の処理。
		// 大量の入力値を配列にしてココに送ってるので、配列を分解してwhere文に組み込む処理。
		foreach($val_array as $key => $val){
			// 正直最後の要素の処理を分ける必要なかったけど、なんかで使えるかも。
			if($val === end($val_array)) {
				$sql_array[$sqlno] .= $key . "='" . $val ."',";	
			}else{
				$sql_array[$sqlno] .= $key . "='" . $val . "',";
			}
		}
		// 最後に現在日時（登録日時）をSQL文に加える。
		$sql_array[$sqlno] .= "user_registration = now()";
	}
	if($sqlno == "confilm4"){
		// 会員修正時の処理。
		// 大量の入力値を配列にしてココに送ってるので、配列を分解してwhere文に組み込む処理。
		foreach($val_array as $key => $val){
			if($key == "user_registration" ||  $key == "user_miss" ||
			  $key == "user_freeze_time" || $key == "user_delete" ||
			  $key == "user_freeze" || $key == "user_mod_date"){
				//echo "<br>",$key,"aaaaaaaaa<br>";
			}else{
				$sql_array[$sqlno] .= $key . "='" . $val . "' ,";
			}
		}
		$sql_array[$sqlno] .= "user_mod_date = now() ";
		$sql_array[$sqlno] .= "where user_id = '{$val_array["user_id"]}' ";
	}
	if($sqlno == "answer2"){
		// 問題新規登録時の処理。
		// 大量の入力値を配列にしてココに送ってるので、配列を分解してwhere文に組み込む処理。
		foreach($val_array as $key => $val){
			$sql_array[$sqlno] .= $key . "='" . $val . "',";
		}
		// 最後に会員IDと現在日時（登録/修正日時）をSQL文に加える。
		$sql_array[$sqlno] .= " qu_create_date = now() ,";
		$sql_array[$sqlno] .= " qu_mod_date    = now()";
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

		// 【要削除】検証用
		//var_dump($sql);

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
		echo "<br><br>";
		var_dump($count);
		echo "<br><br>";
		if($count !== FALSE){
			if($count == 0){
				echo "err2:" , $sql;
				return false;
			}else{
				$rows = $result->fetchall(PDO::FETCH_ASSOC);
				foreach($rows as $row){

					// 各ページに合わせて関数処理。内容は下記参照のコト。
					$check = sql_func($row,$funcno,$check,$val,$val2);

				}
			}
		}else{
			echo "err3:" , $sql;
			return false;
		}
		$db = NULL;
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
	global $sql_output_form_sq_array,
	$sql_output_form_colomn_array,
	$sql_output_main_colomn_array,
	$sql_output_main_user_array,
	$sql_output_test_qno_array,
	$sql_output_question_q_array,
	$same,
	$ans_test_status,
	$ans_test_highscore;

	switch ($funcno){
		case "index1":
			if($val === $row["user_id"]){
				if($val2 === $row["user_pw"]){
					$check = true;
					//【検証用】echo '1';
					break;
				}
				//【検証用】echo '2';
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
					//【検証用】echo '6';
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

		case "main1":
			$sql_output_main_colomn_array[] = $row["column_name"];
			break;
		case "main2":
			foreach($sql_output_main_colomn_array as $val){
				$sql_output_main_user_array[$val] = $row[$val];
			}
			break;

		case "que_test1":
		case "que_test2":
		case "que_test3":
			$sql_output_test_qno_array[] = $row["qu_no"];
			$check = true;
			break;

		case "question1":
			$sql_output_question_q_array["qu_no"] 				= $row["qu_no"];
			$sql_output_question_q_array["qu_title"] 			= $row["qu_title"];
			$sql_output_question_q_array["qu_id"] 				= $row["qu_id"];
			$sql_output_question_q_array["qu_question"] 		= $row["qu_question"];
			$sql_output_question_q_array["qu_answer_1"] 		= $row["qu_answer_1"];
			$sql_output_question_q_array["qu_answer_2"] 		= $row["qu_answer_2"];
			$sql_output_question_q_array["qu_answer_3"] 		= $row["qu_answer_3"];
			$sql_output_question_q_array["qu_answer_4"] 		= $row["qu_answer_4"];
			$sql_output_question_q_array["qu_answer_correct"] 	= $row["qu_answer_correct"];
			$sql_output_question_q_array["qu_time_limit"] 		= $row["qu_time_limit"];
			$sql_output_question_q_array["qu_explanation"] 		= $row["qu_explanation"];
			$check = true;
			break;

		case "ans_test1":
			$ans_test_highscore = $row["gr_score"];
			if($row["gr_score"] == ""){
				echo '7';
				$ans_test_status = "first_time";
				$check = true;
				break;
			}
			if($val > $row["gr_score"]){
				echo '8';
				$ans_test_status = "new_record";
				$check = true;
			}else{
				if($val == $row["gr_score"]){
					echo '9';
					$ans_test_status = "same_record";
					$check = true;
				}else{
					echo '10';
					$check = false;
				}
			}
			break;

		case "que_create1":
			$sql_output_form_colomn_array[] = $row["column_name"];
			break;

		default:
			$check = true;
	}
	return $check;
}


?>    