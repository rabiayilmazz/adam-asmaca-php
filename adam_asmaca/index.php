<?php
include "durum.php";

session_start();
$harfler = ["a", "b", "c", "ç", "d", "e", 
			"f", "g", "ğ", "h", "ı", "i", 
			"j" ,"k", "l", "m", "n", "o", 
			"ö", "p", "r", "s", "ş", "t", 
			"u", "ü", "v", "y", "z"];

$kelimeler = ["panda", "ağaç", "staj", "haber", "yazılım", "usishi", "buluthan", "bilgisayar", "kitap"];

$sembol = "_ ";

$_SESSION["kelime"]=$_SESSION["kelime"]??NULL;
$_SESSION["kelime_ekran"]=$_SESSION["kelime_ekran"]??NULL;
$_SESSION["hak"]=$_SESSION["hak"]??0;

if(isset($_GET["uret"])){
	$kelime =  kelime_sec($kelimeler);
	$_SESSION["kelime"] = harf($kelime);
	$_SESSION["kelime_ekran"] = bos_harf($kelime);
	$_SESSION["hak"] = 0;
}

if(isset($_GET["harf"])){
	hak_kontrol($_GET["harf"], $_SESSION["kelime"]);
	doldur($_GET["harf"]);
}

function kelime_sec($dizi){
	$r = rand(0,count($dizi)-1);
	return $dizi[$r];
}

function harf($kelime){
	$dizi_harf = [];
	$uzunluk = mb_strlen($kelime);

	for ($i=0; $i < $uzunluk ; $i++) { 
		$dizi_harf[] = mb_substr($kelime, $i,1);
	}

	return $dizi_harf;
}

function bos_harf($kelime){
	global $sembol;
	$dizi_harf = [];
	$uzunluk = mb_strlen($kelime);

	for ($i=0; $i < $uzunluk ; $i++) { 
		$dizi_harf[] = $sembol;
	}

	return $dizi_harf;
}

function kelime_goster($dizi){
	$metin = "";
	foreach ($dizi as $key => $deger) {
		$metin.=$deger;
	}
	return $metin;
}

function butonlar(){
	global $harfler;

	foreach ($harfler as $key => $deger) {
		echo '<a href="?harf='.$deger.'" class="btn btn-danger m-1">'.$deger.'</a>';
	}
}

function hak_kontrol($harf, $kelime){
	$kontrol = in_array($harf, $kelime);
	if($kontrol == false){
		$_SESSION["hak"]++;
	}
}

function doldur($harf){
	foreach ($_SESSION["kelime"] as $key => $value) {
		if($value == $harf){
			$_SESSION["kelime_ekran"][$key]=$value;
		}
	}
}

function durum(){
	global $adam;
	echo "<pre>";
	echo $adam[$_SESSION["hak"]];
	echo "</pre>";
}

function kazandiniz(){
	global $sembol;
	$sonuc = in_array($sembol, $_SESSION["kelime_ekran"]);
	return $sonuc;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>Adam Asmaca</title>
</head>
<body>
<div class="alert alert-primary text-center">
    <strong>ADAM ASMACA</strong> 
</div>

<div class="container" >
  <div class="row p-10">
    <div class="col-4 text-center">
	<a type="button" class="btn btn-success" href= "?uret=1">Kelime Değiştir</a> <br><br>
    </div>
    <div class="col-4" >
	<b>Toplam Hak: 5</b> </br>
	<b>Kullandığınız Hak: </b>
	<?php echo $_SESSION["hak"]; ?>
	<?php durum(); ?>
    </div>
    <div class="col-4 text-center">
		<div class="alert alert-danger">
			<b>Kelime:  </b>
			<?=kelime_goster($_SESSION["kelime_ekran"]);?>
		</div>
		<br>
		<?php 
		if($_SESSION["hak"]<6 && kazandiniz())
		butonlar();
		else if($_SESSION["hak"]<6 ){
			echo '<div class="alert alert-success" role="alert"><strong>Kazandınız!</strong></div>';
		}
		else{
			echo '<div class="alert alert-danger text-center">
			<strong>Aranan Kelime: '.kelime_goster($_SESSION["kelime"]).'</strong> </div>';
		}
		
	
		?>
    </div>
  </div>
</div>
</body>
</html>