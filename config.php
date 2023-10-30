<?php 

const SQL_CREATE_MSGS = "CREATE TABLE msgs(
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	title TEXT,
	category INTEGER,
	description TEXT,
	source TEXT,
	datetime INTEGER
)";

const SQL_CREATE_CATEGORY = "CREATE TABLE category(
	id INTEGER,
	name TEXT
)";

const SQL_INSERT = "INSERT INTO category(id, name)
					SELECT 1 as id, 'Политика' as name
					UNION SELECT 2 as id, 'Культура' as name
					UNION SELECT 3 as id, 'Спорт' as name ";







