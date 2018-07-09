<?php

$check='SELECT chat_id FROM udetails WHERE chat_id='.$chat_id;
   $result=$conn->query($check);
 if ($result->num_rows==0){ 

$text="
Please Select your language
";
$text=urlencode($text);

$inline_button1 = array("text"=>"ðŸ‡¬ðŸ‡§ English ","callback_data"=>'en');
$inline_button2 = array("text"=>"ðŸ‡µðŸ‡¹ PortuguÃªs","callback_data"=>'pt');
$inline_button3 = array("text"=>"ðŸ‡«ðŸ‡· FranÃ§ais ","callback_data"=>'fr');
$inline_button4 = array("text"=>"ðŸ‡µðŸ‡± Polski","callback_data"=>'pl');
$inline_button5 = array("text"=>"ðŸ‡®ðŸ‡¹ Italiano","callback_data"=>'it');
$inline_button6 = array("text"=>"ðŸ‡ªðŸ‡¸ EspaÃ±ol","callback_data"=>'es');
$inline_button7 = array("text"=>"ðŸ‡·ðŸ‡º PÑƒÌÑÑÐºÐ¸Ð¹","callback_data"=>'ru');
$inline_button8 = array("text"=>"ðŸ‡©ðŸ‡ª Deutsch","callback_data"=>'de');


    $inline_keyboard = [[$inline_button1,$inline_button2],[$inline_button3,$inline_button4],
	[$inline_button5,$inline_button6],[$inline_button7,$inline_button8]];


/*$inline_keyboard = [[$inline_button1],[$inline_button2],[$inline_button3],[$inline_button4],
        [$inline_button5],[$inline_button6],[$inline_button7],[$inline_button8]];

*/

// $inline_keyboard = [[$inline_button1]];
    $keyboard=array("inline_keyboard"=>$inline_keyboard);
    $inkeyboard = json_encode($keyboard);
	file_get_contents($url."sendmessage?chat_id=".$chat_id."&parse_mode=markdown&reply_markup=$inkeyboard&text=$text");
	

 $insert = "INSERT INTO udetails(chat_id,referrer,uname,time) VALUES($chat_id,$refcode,\"$u_name\",now())";
                       $conn->query($insert);
                   $insert = "INSERT INTO referral(chat_id,ref_1) VALUES($chat_id,$refcode)";
                       $conn->query($insert);
					   $insert = "INSERT INTO balance(chat_id) VALUES($chat_id)";
                       $conn->query($insert);

$conn->query("INSERT INTO pending(chat_id,refcode) VALUES($chat_id,$refcode)");


function get_address($coin){
    $public_key='e26214af9459e4f47e07968abd3fb9739b1db3fe35fcd43b30873c0d18df4a98';
    $cmd='get_callback_address';
    $req['version'] = 1; 
    $req['cmd'] = $cmd; 
	$req['currency']=$coin;
    $req['key'] = $public_key; 
  $req['format'] = 'json';
    $post_data = http_build_query($req, '', '&'); 
$private_key='49da04Aa222FC744721b6Cbc59418c3e7fBc28e316a6BE1E6D58944869c0c641';
    $hmac = hash_hmac('sha512', $post_data, $private_key); 
     
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.coinpayments.net/api.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "version=1&cmd=$cmd&currency=$coin&key=$public_key&format=json");
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = "Content-Type: application/x-www-form-urlencoded";
$headers[] = "Hmac:$hmac";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$result=json_decode($result);
$address=$result->result->address;
return $address;
}

 $btca=get_address('btc');
						  $ltca=get_address('ltct');
						  $bcasha=get_address('bch');
						  $etha=get_address('eth');
$insert = "UPDATE udetails SET btc_address=\"$btca\",ltc_address=\"$ltca\",bcash_address=\"$bcasha\",eth_address=\"$etha\" WHERE chat_id=$chat_id";  
$conn->query($insert);

$sql="SELECT id from udetails WHERE chat_id=$chat_id";
 $result2=$conn->query($sql);
 $row2 = $result2->fetch_assoc();
$c=intval($row2['id']);
$sql="UPDATE referral SET code=$c WHERE chat_id=$chat_id";
$result2=$conn->query($sql);
$sql="UPDATE balance SET code=$c WHERE chat_id=$chat_id";
$result2=$conn->query($sql);


if($refcode!=999999){
$i=2;
while($i<5){
                           $sql="SELECT referrer FROM udetails WHERE id=$refcode";
                  $result2=$conn->query($sql);
                   $row2 = $result2->fetch_assoc();
               $refcode=intval($row2['referrer']);
            if($refcode!=999999) {$sql='UPDATE referral SET ref_'.$i.'='.$refcode.' WHERE chat_id='.$chat_id;
$conn->query($sql);
$i=$i+1;
                                 }
if($refcode==999999) exit();
}
}

}

else {


require "menu/$lang/start.php";

}
?>
