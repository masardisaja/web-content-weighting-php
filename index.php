<?php
include('simple_html_dom.php');
?>
<html>
<head>
	<title>Latihan API</title>
</head>
<body>
<?php
date_default_timezone_set("Asia/Jakarta");

echo "<center>Latihan API</center>";

$domain = 'detik.com';
$apikey = 'your_newsapi.org_API_Key';

$i = 0;

$date=date_create(date('Y-m-d'));
$finalArray = new ArrayObject();

for($i=1;$i<=1;$i++){
	date_sub($date,date_interval_create_from_date_string("1 days"));
	$tgl = date_format($date,"Y-m-d");
	$url = 'https://newsapi.org/v2/everything?domains='.$domain.'&from='.$tgl.'&to='.$tgl.'&sortBy=popularity&apiKey='.$apikey;
	echo($url.'</br>');
	$cURLConnection = curl_init();
	
	curl_setopt($cURLConnection, CURLOPT_URL, $url);
	
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	$newsList = curl_exec($cURLConnection);
	curl_close($cURLConnection);
	
	$jsonArrayResponse[$i] = json_decode($newsList)->articles;
	$articles = json_decode($newsList)->articles;
	foreach($articles as $a){
		$finalArray->append($a);
	}
}
	
foreach ($finalArray as $a){
	$url = $a->url;
	$html = file_get_html($url);
	if(substr($url,0,23)=='https://news.detik.com/'){
		$div = '.detail__body-text';
	}else{
		$div = '#detikdetailtext';
	}
	$code = $html->find($div, 0);
	$text = $code->outertext;
	$text = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $text);
	$text = strip_tags($text);
	$a->content_full = $text;
	$text = sendToGataFrameworkTextMining(2,$text);
	$a->content_gtfw_t2 = $text;
	$text = sendToGataFrameworkTextMining(3,$text);
	$a->content_gtfw_t3 = $text;
	$text = sendToGataFrameworkTextMining(7,$text);
	$a->content_gtfw_t7 = $text;
	$text = sendToGataFrameworkTextMining(8,$text);
	$a->content_gtfw_t8 = $text;
	$text = sendToGataFrameworkTextMining(9,$text);
	$a->content_gtfw_t9 = $text;
	$text = sendToGataFrameworkTextMining(10,$text);
	$a->content_gtfw_t10 = $text;
	$text = sendToGataFrameworkTextMining(11,$text);
	$a->content_gtfw_t11 = $text;
	$text = sendToGataFrameworkTextMining(12,$text);
	$a->content_gtfw_t12 = $text;
	$text = sendToGataFrameworkTextMining(13,$text);
	$a->content_gtfw_t13 = $text;
	$text = sendToGataFrameworkTextMining(14,$text);
	$a->content_gtfw_t14 = $text;
}

echo('<pre>');
print_r($finalArray);
echo('</pre>');

function sendToGataFrameworkTextMining($teknik, $text){
   //hapus "#"   
   $text = str_replace("#",  "", $text);
   //hapus "&"   
   $text = str_replace("&",  "", $text);
   //hapus "?"
   $text = str_replace("?", "", $text);
   //rubah spasi ke %20
   $text = str_replace(" ", "%20", $text);
   //rubah tab jadi space
   $text = str_replace("\t", "%20", $text);
    //rubah return jadi space
   $text = str_replace("\r", "%20", $text);
    //rubah enter jadi space
   $text = str_replace("\n", "%20", $text);
   
   $url = "http://www.gataframework.com/textmining/index.php?model=transaction_text&action=processTextPublic&techniques=".$teknik."&textbefore=".$text;

   
   $textresult = file_get_contents($url);
   $textresult = str_replace("﻿", "", $textresult);

   return  $textresult;
	/*
	Kode teknik	Teknik	Usable
	2	Indonesian Stopword	usable
	3	Indonesia Stemming	usable
	7	Remove URL	usable
	8	Remove Tokenize Regex	usable
	9	Remove Annotation	usable
	10	Slank	usable
	11	Acromim	usable
	12	Emoticon	usable
	13	Not Model	usable
	14	Jumlah kata	usable
	*/
}

function terbilang($x) {
  $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

  if ($x < 12)
    return " " . $angka[$x];
  elseif ($x < 20)
    return terbilang($x - 10) . " belas";
  elseif ($x < 100)
    return terbilang($x / 10) . " puluh" . terbilang($x % 10);
  elseif ($x < 200)
    return "seratus" . terbilang($x - 100);
  elseif ($x < 1000)
    return terbilang($x / 100) . " ratus" . terbilang($x % 100);
  elseif ($x < 2000)
    return "seribu" . terbilang($x - 1000);
  elseif ($x < 1000000)
    return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
  elseif ($x < 1000000000)
    return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
}
?>
</body>
</html>
