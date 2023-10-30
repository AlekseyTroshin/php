<?php

require_once "config.php";

require "INewsDB.class.php";

class NewsDB implements INewsDB {
	const DB_NAME = "news.db";
	const ERROR_PROPERTY = "Wrong property name";
	const RSS_NAME = "rss.xml";
	const RSS_TITLE = "Последние новости";
	const RSS_LINK = "http://localhost:8888/news/news.php";

	private $_db;

	function __construct() {
		$this->_db = new SQLite3(self::DB_NAME);
		if (!filesize(self::DB_NAME)) {
			try {
				$sql = SQL_CREATE_MSGS;
				if(!$this->_db->exec($sql)) {
					throw new Exception("SQL_CREATE_MSGS");
				}

				$sql = SQL_CREATE_CATEGORY;
				if(!$this->_db->exec($sql)) {
					throw new Exception("SQL_CREATE_CATEGORY");
				}

				$sql = SQL_INSERT;
				if(!$this->_db->exec($sql)) {
					throw new Exception("SQL_INSERT");
				}
			} catch (Exception $ex) {
				die($ex->getMessage());
			}
		}

	}

	function __destruct() {
		unset($this->_db);
	}

	function __get($name) {
		if($name == "db") {
			return $this->_db;
		}

		throw new Exception(self::ERROR_PROPERTY . " GET");
	}

	function __set($name, $value) {
		throw new Exception(self::ERROR_PROPERTY . " SET");
	}

	function saveNews($title, $category, $description, $source) {
		$dt = time();

		$sql = "INSERT INTO msgs(title, category, description, source, datetime) 
		VALUES ('$title', $category, '$description', '$source', $dt)";

		if(!$result = $this->_db->exec($sql)) {
			$this->_db->lastErrorCode();
			$this->_db->lastErrorMsg();
		}

		return	$result;
	}

	function getNews() {
		$sql = "SELECT 
				msgs.id as id, 
				title, 
				category.name as category,
		        description, 
		        source, 
		        datetime
		      FROM msgs, category
		      WHERE category.id = msgs.category
		      ORDER BY msgs.id DESC";

		$items = [];

		if(!$result = $this->db->query($sql)) {
			return false;
		}

		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$items[] = $row;	
		}

		return $items;
	}

	function deleteNews($id) {
		$sql = "DELETE FROM msgs WHERE id = $id";
		return $this->db->exec($sql);
	}

	function createRss() {
		$dom = new DomDocument("1.0", "UTF-8");
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;

		$rss = $dom->createElement("rss");

		$version = $dom->createAttribute("varsion");
		$version->value = '2.0';
		$rss->appendChild($version);

		$dom->appendChild($rss);

		$channel = $dom->createElement("channel");
		$title = $dom->createElement("title", self::RSS_TITLE);
		$link = $dom->createElement("link", self::RSS_LINK);

		$items = $this->getNews();

		foreach ($items as $itemSql) {
			$item = $dom->createElement("item");
			
			$title = $dom->createElement("title", $itemSql['title']);
			$category = $dom->createElement("category", $itemSql['category']);

			$description = $dom->createElement("description");
			$cdata = $dom->createCDATASection($itemSql["description"]);
			$description->appendChild($cdata);

			$source = $dom->createElement("source", $itemSql['source']);
			$pubDate = $dom->createElement("pubDate", $itemSql['datetime']);
		
			$item->appendChild($title);
			$item->appendChild($category);
			$item->appendChild($description);
			$item->appendChild($source);
			$item->appendChild($pubDate);
			$item->appendChild($link);

			$channel->appendChild($item);

			$rss->appendChild($channel);

			$dom->save(self::RSS_NAME);
		}
	}

}





