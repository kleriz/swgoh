<html>
 <title>
	SWGOH - GEAR FINDER
</title>
<body>
<?php
session_start();

if (isset($_POST["ally_code"])) {
	$_SESSION["ally_code"]=str_replace("-","",$_POST["ally_code"]);
	//$_SESSION["gear_page"]=$_POST["gear_page"];
	$_SESSION["character_page"]=$_POST["character_page"];
}

$_SESSION["gear_page"]="https://swgoh.gg".$_GET["get_gear_page"];

?>

<center>

<h1>SWGOH - GEAR FINDER</h1>

<form method="post">
	<table style="border: 1px solid #F5F8FA !important;">
		<tr>
			<td>
				Ally code: 
			</td>
			<td>
				<input type="text" name="ally_code" value="<?php echo $_SESSION["ally_code"];?>" size="20">
			</td>
		</tr>
		<tr>
			<td>
				Character's gear page: 
			</td>
			<td>
				<input type="text" name="character_page" value="<?php echo $_SESSION["character_page"];?>" size="20">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br>
				<center>
					<input type="submit" value="List gears">
				</center>
				<br><br>
			</td>
		</tr>
</form>

<?php
$html = file_get_contents('https://swgoh.gg/p/'.$_SESSION["ally_code"].'/characters/');
$html = htmlspecialchars($html);

//print_r($html);
$pieces = explode("player-char-portrait char-portrait-full char-portrait-full-gear-t", $html);
for ($i=1;$i<count($pieces);$i++) {
	$pieces[$i] =  substr($pieces[$i], 0, 100);
	$pieces_small_seged = explode ("characters/", $pieces[$i]);
	
	$pos = strpos($pieces_small_seged[1], "&quot;");
	$pieces_small_seged[1]=substr($pieces_small_seged[1],0,$pos);
	$pos = strpos($pieces_small_seged[0], " ");
	$pieces_small_seged[0]=substr($pieces_small_seged[0],0,$pos-16);
	
	// user karakterei, és gear szintjeik
	$character_name_of_user[$i]=$pieces_small_seged[1];
	$character_gear_of_user[$i]=$pieces_small_seged[0];
	//echo $character_name[$i]." - ".$character_gear[$i]."<br><br>";
}


unset($html);
unset($pieces);


$html = file_get_contents($_SESSION['gear_page']);
$html = htmlspecialchars($html);


$darab=0;


echo "<tr><td><b>Results:<br><br></b></td></tr>";

//print_r($html);
$pieces = explode("Gear Level ", $html);
for ($i=1;$i<count($pieces);$i++) {
	//echo $pieces[$i]."<br><br>";
	$pieces_gear_seged =  substr($pieces[$i], 0, 20);
	$pos = strpos($pieces_gear_seged, '&quot');
	$pieces_gear_seged=substr($pieces_gear_seged,0,$pos);
	
	$pieces_chars = explode("characters/", $pieces[$i]);

	for ($j=1;$j<count($pieces_chars);$j++) {
		$pieces_chars[$j] =  substr($pieces_chars[$j], 0, 100);
		$pos = strpos($pieces_chars[$j], '/');
		
		// a keresett gear mely karakterek mely gear szintjén található meg
		$character_name_of_gear[$j]=substr($pieces_chars[$j],0,$pos);
		$character_gear_of_gear[$j]=$pieces_gear_seged;
		
		// megnézi, hogy a karaktereim közül van-e ilyen karakter ezen a gear szinten
		for ($k=0;$k<count($character_name_of_user);$k++) {
			if ($character_name_of_gear[$j]==$character_name_of_user[$k] && $character_gear_of_gear[$j]==$character_gear_of_user[$k]) {
				echo "<tr><td colspan='2'>";
				echo str_replace("-"," ",$character_name_of_gear[$j])."<br>";
				echo "</td></tr>";
				$darab++;
			}
		}
	}
}

if ($darab==0 && $_SESSION["gear_page"]<>"") {
	echo "<tr><td colspan='2'><b>Sorry, at this time your roster do not need the selected gear.</b></td></tr>";
}

?>

	</table>
	<br><br><br>
	
<?php
	

	
if (isset($_SESSION["character_page"])) {
	
	$html = file_get_contents($_SESSION['character_page']);
	$html = str_replace('a href="','a href="?get_gear_page=',$html);
	echo $html;
	
}

?>
	
</center>
</body>