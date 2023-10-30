<?php
	const RSS_URL = "http://localhost:8888/specialist/3/news/rss.xml";
	const FILE_NAME = "rss.xml";
	const RSS_TTL = 3600;

	function download($url, $filename) {
		$file = file_get_contents($url);
		if($file) {
			file_get_contents($filename, $file);
		}
	}

	if(!is_file(FILE_NAME)) {
		download(RSS_URL, FILE_NAME);
	}
?>
<!DOCTYPE html>

<html>
<head>
	<title>Новостная лента</title>
	<meta charset="utf-8" />
</head>
<body>

<h1>Последние новости</h1>
<?php

	$xml = simplexml_load_file(FILE_NAME);
	foreach($xml->channel->item as $item) {
		$pubDate = date("d.m.Y H:i:s", (int) $item->pubDate);
		echo <<<_ITEM
		<h3>$item->title</h3>
		<p>
			$item->description
			<br>
			$item->category,
			Опубликовано: $pubDate
		</p>
		<p align="right">
			<a href="$item->link">Читать дальше...</a>
		</p>
_ITEM;
	}

if(time() > filemtime(FILE_NAME) + RSS_TTL) {
	download(RSS_URL, FILE_NAME);
}

?>
</body>
</html>









