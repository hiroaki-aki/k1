<?php

// k1_config.phpの相対パス。
$config_path = "../k1_config.php";
$config_file = "k1_config.php";


$pflag = false;
$err   = array();


/* HTMLタグ作成系 */


// 共有のhead内のタグ
$head_common_tag = "";
$head_common_tag .= "<meta charset=\"UTF-8\">" ;                                    // charset=utf8。常識やけど。
$head_common_tag .= "<meta name=\"robots\" content=\"noindex,follow\">";            // SEO対策（インデックス:×、クロール:〇）
$head_common_tag .= "<meta name=\"format-detection\" content=\"telephone=no\">";    // IOS対策（電話番号表示を電話リンク化しない）
$head_common_tag .= "<link rel=\"icon\" type=\"image/png\" href=\"/image/mi.png\" sizes=\"32x32\">";     // ファビコンのリンク


// 引数：0,(global)$pflag 1,タイプ(String) 2,id名(String) 3,name名(String) 3,サイズ値(Int) 4,value値(変数) 5,placeholder値(String)
// 処理：引数を基にしたinputタグを作成（post時の再表示機能付き）
// 戻値：上記inputタグ
// 備考：使用時はechoすること。
function create_input($type,$id,$name,$size,$val,$example){
	global $pflag;
	$input_tag = "";
	$input_tag .= "<input type=\"{$type}\"" ;
	$input_tag .= "id=\"{$id}\" name=\"{$name}\" size=\"{$size}\"";
	if($pflag && !empty($val)){
		$input_tag .= "value=\"{$val}\"";
	}
	$input_tag .= "placeholder=\"{$example}\">";
	return $input_tag;
}



?>    