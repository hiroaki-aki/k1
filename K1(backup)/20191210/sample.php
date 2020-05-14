<?php
/* 基本操作 */

// 変数宣言　　
$hensu = ""; 
$array = array();
$array_ren = array(key=>val,key2=>val2);
$array_ren = array(array(key=>val),"a");

// 文字列連結　htmlタグ作成 
echo $hensu , "string" , "<br>";
$array[] = $hensu . "string" . "<br>";
$print   = "<table><tr></tr></tbody>";

// 型変換 sort
$su = (int)($moji);
$sort_type($data,$sort_flug); 

// 各種文法（if,switch,for,foreach,while)
if($su == 0){
}
switch($c){
    case "$c" <= 50:
      echo "50以下です（加算）<br> $a + $b = " , $a + $b ;
      break;
    case "$c" <= 0:
      echo "0以下です（乗算）<br> $a * $b = " , $a * $b ;
      break;
    default:
      echo "その他です（除算-50以下です <br> ）<br> $a / $b = " , $a / $b ;
}
for($i=0;$i<count($array);$i++){
    $array[$i] = rand(1,100);
    $sum += $array[$i];
}
foreach($array as $key => $val){
    echo $key , ":" , $val , "<br>";
}
while (true){
    break;
}

// 判定関数各種
$func = array('is_array()','is_bool()','is_float()','is_int()','is_null()','is_empty()',
              'is_numeric()','is_string()','is_object()','is_scalar()');

//初期変数作成コーナー
$pflag    = false;
$err      = array();
$msg      = "";
// \"

// 変調可能なセレクト or チェックボックスの配列作成コーナー
// 注意：各ボックス配列は、HTMLの表示順に作成すること。
$month = array(1,2,3,4,5,6,7,8,9,10,11,12);
$test = array("a","b","c","d","e","f","g","h","i","j");


/* HTMLタグ作成系 */

// 引数：0,(global)$pflag 1,タイプ(String) 2,id名(String) 3,name名(String) 3,サイズ値(Int) 4,value値(変数) 5,placeholder値(String) 
// 処理：引数を基にしたinputタグを作成（post時の再表示機能付き）
// 戻値：上記inputタグ
// 備考：使用時はechoすること。
function create_input($type,$id,$name,$size,$val,$example){
    global $pflag;
    $input_tag = "";
    $input_tag .= "<input type=\"{$type}\"" ;
    $input_tag .= "name=\"{$name}\" size=\"{$size}\"";
    if($pflag && !empty($val)){
		$input_tag .= "value=\"{$val}\"";
	}
    $input_tag .= "placeholder=\"例){$example}\">";
    return $input_tag;
}

// 引数：1,タイプ(String) 2,name名(String) 3,element_array:配列の内容
// 処理：引数を基にしたselect/check boxタグを作成（post時の再表示機能付き）
// 戻値：上記boxタグ(各boxのvalue値は引数3の添字、表示は引数3のデータ値)
// 備考：引数１はcheckbox / selectbox を指定のコト。使用時はechoすること。
function create_box($type,$name,$element_array){
	global $pflag;
	$result = "";
    if($type == "checkbox"){
        for($i=0;$i<count($element_array);$i++){
            $result .= "<input type=\"checkbox\" name=\"{$name}[]\" >";
            $result .= "value=\"{$i}\"";
            if($pflag && isset($element_array[$i][1])){ 
                 $result .= "\"checked\"";
            }
            $result .= "$element_array[$i]";
        }
    }
    if($type == "selectbox"){
        $result .= "<select size=\"1\" name=\"{$name}[]\" >";
        for($i=0;$i<count($element_array);$i++){
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

function create_hidden($name,$element_array){
	for($i=0;$i<count($element_array);$i++){
		$result .= "<input type=\"hidden\" name=\"{$name}[]\" ";
		$result .= "value=\"$element_array[$i]\">";
	}
	return $result;
}

/* POST関連 */

// 本文：POST送信された時の処理。
// 備考：p_check関数の引数には、最初にBOX関連の物を表示順に入れるコト。
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$pflag = true;
	if(p_check_empty($_POST[" "],$_POST[" "])){
		$val1 = htmlspecialchars($_POST[" "],ENT_QUOTES );
		$val2 = htmlspecialchars($_POST[" "],ENT_QUOTES );
		// 必要な処理を書く。以上。
	}
}

// 引数：POST変数（じゃなくてもいいけど）
// 処理：空判定 → box系なら選択有無判定
// 戻値：true/false & (global)$err
function p_check_empty(){
	global $err ;
	$post_array = func_get_args();
	for($i = 0 ; $i < count($post_array) ; $i++){
		if(empty($post_array[$i])){
			$err[] = "文字を入力して下さい。";
			return false;
		}else{
			if(is_array($post_array[$i])){
				if(!isset($post_array[$i])){
					$err[] = "選択肢が選択されていません。";
					return false;
				}
			}
		}
	}
	return true;
}

// 引数：POST変数（じゃなくてもいいけど）
// 処理：空判定 → 数値判定
// 戻値：true/false & (global)$err
function p_check_numeric(){
	global $err ;
	$post_array = func_get_args();
	for($i = 0 ; $i < count($post_array) ; $i++){
		if(empty($post_array[$i])){
			$err[] = "文字を入力して下さい。";
			return false;
		}else{
			if(!is_numeric($post_array[$i])){
				$err[] = "数字で入力して下さい。";
				return false;
			}
		}
	}
	return true;
}

// 引数：1,POST変数 2,POST変数のボックスのデータ配列
// 処理：選択済み項目(引数2の添字)と、同じ値の引数2の配列要素に無理矢理trueを追加
// 戻値：なし（引数２を参照渡し）
function box_select_check($post_array,&$element_array){
    foreach($post_array as $key => $val){
        foreach($element_array as $key2 => $val2){
            // boxで選択されたタグ内のvalue（val）には、元データ配列の添字（key2）が設定されているのでマッチ判定。
            if($val == $key2){
                $elment_array[$key2][1] = true;
            }
        }
    }
}






/* File 操作関連の関数 */

// 引数：1,$_FILES[" "] 2,保管先のパス 3,拡張子の制限（複数記載可）
// 処理：引数１のファイルをチェックし問題なければ、引数２のパスに保管する。
// 戻値：なし & (global)$err
function upload_file(){
    $file_array = func_get_args();
	$fname      = $file_array[0]["name"];
	$ftname     = $file_array[0]["tmp_name"];
	$fsize      = $file_array[0]["size"];
    $finfo      = pathinfo($fname);
    $movepath   = $file_array[1];
    for($i=2;$i<count($file_array);$i++){ $ftype[] = $file_array[$i]; }
    $check = false;
    
    if (strlen($fname) <= 0) {
        $err[] = "ファイルが指定されていません。";
        return;
	}else{
		$ext = strtolower($finfo["extension"]);
        foreach($ftype as $val){
            if($ext == $val){
                $check = true;
                break;
            }
        }
        if(!$check){
            $err[] = "指定の拡張子のファイルのみアップしてください。";
            return;
        }
		if($fsize == 0){
            $err[] = "ファイルが空（0バイト）です。<br>";
            return;
		}else{
			$movepath .= mb_convert_encoding($fname,"SJIS","UTF-8");
			$moveok    = move_uploaded_file($ftname,$movepath);
			if($moveok){
				echo "<p><font color=\"red\">アップロード成功！</font><br>";
				echo $fname , "をアップしました。</p>";
				echo "<img src=\"../img/$fname\" width=\"50%\" height=\"50%\" alt=\"失敗\"><br>";
			}else{
                $err[] = "アップロードに失敗しました。";
                return;
			}
		}
	}
}


// 引数：1,$filename 
// 処理：引数１のファイルをチェックし問題なければ、
// 戻値： & (global)$err
function f_file($filename){
	global $err ;
	$check = false;
	if(file_exists($filename)){
		if(($fp = fopen($filename, "a+")) == false ){
			$err[] = "ファイル読込みエラー。";
		}else{
			flock($fp,LOCK_EX);
			while(!feof($fp)){
				$mojiarray[] = explode(",", fgets($fp));
			}
			foreach($mojiarray as $key => $val){
				if($val[0] == $no){
					$en    = $su * intval($val[2]);
					$name  = $val[1];
					$check = true;
					break;
				}
			}
			flock($fp,LOCK_UN);
			fclose($fp);
		}
	}
}

/* 月日関連のプログラム */

// 引数：タイムスタンプ(省略時は現在時刻)
// 処理：引数を日本文に変換
// 戻値：引数を日本文にした文字列 & (global)$err 
function time_print(){
    global $err;
    $time_stamp = func_get_arg(0);
    if(empty($time_stamp)){ $time_stamp = time(); }
    $dateday_array = array("日","月","火","水","木","金","土");
    // 曜日が英語でもいいならコレ→ date("Y年n月j日 H時i分s秒(l)" , $now);
    return date("Y年n月j日 H時i分s秒({$dateday_array[date("w",$time_stamp)]})",$time_stamp);
}

// 引数：年、月、日 (何れか省略時は現在の年月日を代入)
// 処理：指定日の月日が存在するかチェック（閏年判定もしてるヨ）
// 戻値：true/false & (global)$err
// 備考：参照引渡したければ引数に「&」つけて使えばおｋ 
function month_day_check($year,$month,$day){
    global $err;
    $now  = time();
    // 各月の上限日数
    $days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    // 引数が空なら現在の年月日を代入
    if(empty($year)) { $year  = date("Y",$now); }
    if(empty($month)){ $month = date("n",$now); }
    if(empty($day))  { $month = date("j",$now); }
    // 閏年の判定
    if(date('L', mktime(0,0,0,1,1,$year))){ $days[1] = "29"; }
    if(!0<$month || !$month<13){
        $err[] = "存在しない月です。";
        return false;
    }
    if($day > 0 && ($day <= $days[$month-1]) ){
		return true;
	}else{
		$err[] = "存在しない日付です。";
		return false;
	}
}

/* PDO(DB操作)関連のプログラム */

// 引数：1,テーブル名(string) 2,カラム名(string)
// 処理：引数を基にしたセレクト文の作成（条件指定なし）
// 戻値：なし & (global)$sql
// 備考：引数１は必須。引数２は複数記載可（引数３以降として追記可）。
function create_select(){
	global $sql;
	$column_array = func_get_args();
	$sql .= "select ";
	for($i=1;$i<count($column_array);$i++){
		if($i==count($column_array)-1){
			$sql .= $column_array[$i];
		}else{
			$sql .= $column_array[$i] . ",";
		}
	}
	$sql .= " from " . $column_array[0];
}

// 引数：1,カラム名(string) 2,条件(不等号記号) 3,条件(変数など)
// 処理：(global)$sql文に、引数を基にしたwhile文を追加。
// 戻値：なし & (global)$sql
// 備考：引数は３つ指定。異なる条件指定ができるように、作成可能なwhile文は一文のみ。
//       前処理として(global)$sqlに「 while 」の追記が必要。
//       複数条件とする場合は、前処理として(global)$sqlに「 while 」の追記が必要。
function create_select_while($column,$sign,$condition){
	global $sql;
	$sql .= $column." ".$sign." '".$condition."'";
}

// 引数：1,テーブル名(string) 2,カラム名(string) 3,カラムリスト(変数) 4,タイプ(String) 5,name名(String) 
// 処理：該当テーブルのカラム値(重複なし)をHTMLのbox化して出力
// 戻値：上記で作成されたHTMLのboxタグ & (global)$err
// 備考：ボックスの作成はcreate_box()を使用。使用時はechoすること。
function select_condition($table,$column,&$column_array,$box_type,$box_name){
	global $err,$dsn,$user,$pass;
	try{
		$db = new PDO($dsn,$user,$pass);
		$db->exec("SET NAMES utf8");
		$db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		$sql = "select distinct({$column}) from {$table}";
		$result = $db->prepare($sql);
		if(!$result->execute()){
			$err[] = "SQL ERROR:" . $sql;
		}
		$rows = $result->fetchall(PDO::FETCH_COLUMN);
		$column_array = $rows;
		$box_tag = create_box($box_type,$box_name,$rows);
		$db = NULL;
	}
	catch (Exception $e){
		echo "MSG:" .$e->getMessage()."<br>";
		echo "CODE:".$e->getCode()."<br>";
		echo "LINE:".$e->getLine()."<br>";
		$db = NULL;
	}
	return $box_tag;
}



?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>sample.php</title>
	<style>
		table{width:;}
		th{width:;}
		td{width:;}
		#err{color:red;}
	</style>
</head>
<body>
<div>
	<p id="err">
	<?php /*
        foreach ($err as $val){ echo $val , "<br>";}
        foreach ($msg as $val){ echo $val , "<br>";}
        */
	?>
	</p>
</div>
<form action="sample.php" method="POST" >
	<div>
		名前：
		<?php　create_input("text","name","20",$name,"akino"); ?>
		<br>
        <?php　create_box("checkbox","month",$month); ?>
        <br>
        <?php　create_box("selectbox","test",$test); ?>
        <br>
        <input type="submit" name="btn" value="送信">

	</div>
</form>
</body>
</html>

