<?php

$begin = time();

// $db_path = ":memory:";
$db_path = "test.db";

if (file_exists($db_path)) {
    echo "<br>Database file is already created at: " . getcwd() . "\\" . $db_path;
    exit;
}

$db = new PDO("sqlite:$db_path");

// Enable Write-Ahead Log (WAL) for better concurrency:
// https://www.sqlite.org/wal.html
$db->exec("PRAGMA journal_mode=WAL");

$wal_status = $db->query("PRAGMA journal_mode;")->fetchColumn();

echo "WAL mode: " . $wal_status;

$statement = $db->prepare("CREATE TABLE IF NOT EXISTS some_table_name (
	id INTEGER PRIMARY KEY,
	a_field TEXT NOT NULL,
	b_field TEXT NOT NULL,
	c_field TEXT NOT NULL,
	d_field TEXT NOT NULL
	-- c_field TEXT NOT NULL UNIQUE,
	-- d_field TEXT NOT NULL UNIQUE
);");

$statement->execute();

$statement = $db->prepare("SELECT * FROM some_table_name");
$statement->execute();
$results = $statement->fetchAll();

echo "<br>Results: \n\n" . sizeof($results);

for ($x = 0; $x <= 9999; $x++) {
    $db->exec("INSERT INTO some_table_name (a_field, b_field, c_field, d_field) VALUES (hex(randomblob(32)), hex(randomblob(32)), hex(randomblob(32)), hex(randomblob(32)))");
}

echo "<br>Database was created at: " . getcwd() . "\\" . $db_path;

$end = time();

echo "<br>Time taken: " . ($end - $begin);

?>