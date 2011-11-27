<?php
//to be commented later
echo "Hi";
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL ^ E_WARNING);

set_time_limit(0);
$time_start = microtime(true);
function getDirectoryListing($path, $cat){
	// fetching the page to local storage using cURL to avoid problems with Cyrillic URLs
	//echo "Loading - ".$cat."<br>";
	$path .= " > ".$cat;
	$cat = str_replace(" ", "_", $cat);
	$ch = curl_init();
	$fp = fopen("testtt.html", "w");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2");
	curl_setopt($ch, CURLOPT_URL, "http://ru.wikipedia.org/wiki/Категория:".$cat);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_exec($ch);
	curl_close ($ch);
	fclose($fp);

	// working with the pre-fetched page using DOMDocument
	$html = new DOMDocument();
	$html->loadHTMLFile("testtt.html");
	
	$subCats = $html->getElementById("mw-subcategories");

	if($subCats){
		$subCatLinks = $subCats->getElementsByTagName("a");
		foreach ($subCatLinks as $s){
			//echo "Length: ".$subCatLinks->length;
			//echo $path." > <b>".$s->textContent."</b><br>";
			getDirectoryListing($path, $s->textContent);
		}
	}
	//$articles = $html->getElementById("mw-pages")->getElementsByTagName("a");
	$mwpages = $html->getElementById("mw-pages");
	if($mwpages) {
		foreach ($mwpages->getElementsByTagName("a") as $a){
			echo $path." > <b>".$a->textContent."</b><br>";
		}
	}
	//else echo "NOOOOO!";
}
getDirectoryListing(null, "Дискретная математика");

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "<br><br>Finished in $time seconds";
?>