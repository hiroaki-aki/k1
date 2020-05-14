<?php
session_start();

for($i=0;$i<10;$i++){
	$_SESSION["question"]["type"] = "r_test";
	//$_SESSION["question"] = array("r_test" => $q_no);
	$_SESSION["question"]["no"][] = $i;
	$_SESSION["question"]["result"][] = $i;
	$_SESSION["question"]["su"] = $i;
	//$_SESSION["question"] = "dojo";
	$_SESSION["hin"][] = array(1 => $i);
	//$_SESSION["question"][] = array($i => $i*10);
}
//$_SESSION["question"]["no"]["result"] = 1;


var_dump($_SESSION);
session_destroy();
?>    