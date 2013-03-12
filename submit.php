<?
function sendmail($subject,$from,$to,$body,$fromName="Diageo") {
	$headers = "From: <" . $from . ">\n";
	$headers .= "X-Sender: <" . $from . ">\n";
	$headers .= "X-Mailer: PHP\n";
	$headers .= "X-Priority: 1\n";
	$headers .= "Return-Path: <" . $from . ">\n";
	$headers .= "MIME-Version: 1.0\n";		
	$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
	mail($to, $subject, $body, $headers);
	return true;
}

$prize = "NULL";
if (!empty($_POST['prize'])){
	$prize = "'".$_POST['prize']."'";
	
	$id = rand(1000,100000);
	$coupon_code = dechex($_POST['carrier'].$id);
	
	$prizes = array(0=>"FREE Appy",1=>"Buy 1, Get 1, Pizza",2=>"FREE Guinness Glass",3=>"FREE Coke Bottle",4=>"FREE Guinness Prize");
	$file = "email_prize.html";
	$html = file_get_contents($file);
	$prize_id = str_replace("images/prize","",$_POST['prize']);
	$prize_id = str_replace(".jpg","",$prize_id);
	$prize_desc = $prizes[$prize_id];
	$html = str_replace("#PRIZE#",$prize_desc,$html);
	$html = str_replace("#PRIZEIMG#","http://www.localhost.com/scratch/".$_POST['prize'],$html);
	$html = str_replace("#YEAR#",date("Y"),$html);
	$html = str_replace("#ID#",date("Y"),$html);
	$expiry = mktime(0,0,0,date("m"),date("d")+7,date("Y"));
	$expiry = date("M dS, Y",$expiry);
	$html = str_replace("#EXPIRY#",$expiry,$html);
	$html = str_replace("#COUPON_CODE#",strtoupper($coupon_code),$html);
	$subject = "Congratulations! You won a ".$prize_desc." from Guinness";
	sendmail($subject,"admin@localhost.com",$_POST['email'],$html);
}
?>