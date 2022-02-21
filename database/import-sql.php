<?php

// --- Warning: Make sure your database name correct, same name will be overwrited
// --- Warning: This custom import sql code currently cannot import trigger bound database
// --- Warning: Make sure you not included trigger in imported file

// Short options / -db
$shortopts  = "";
$shortopts  .= "d:"; // dabatase name / required
$shortopts  .= "b:"; // backup file path / optional / use db as backup pathname


$options    = getopt($shortopts);

//ENTER THE RELEVANT INFO BELOW
$hostname     = "localhost";
$username     = "root";
$password     = "";
$dbname       = @$options['d'] ? $options['d'] : "database";
$backup_name  = @$options['b'] ? $options['b'] : "$dbname.sql";

// Create connection
$conn = new mysqli($hostname, $username, $password);

// Create new database if not exist
echo "Creating database: $dbname";
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->query("USE `$dbname`");
echo "\n";

// Reset database tables
$query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$dbname'";
$tables = $conn->query($query);
while ($data = $tables->fetch_assoc()) {
  echo "Restructuring... " . $data['table_name'];
  $conn->query("DROP TABLE $data[table_name]");
  echo "\n";
}

// Import database
// $query = file_get_contents('database/$dbname.sql');

echo "Importing... $backup_name";
echo "\n";

$templine = "";
$lines = file("$backup_name");
foreach ($lines as $line) {
  if (substr($line, 0, 2) == '--' || $line == '') continue;

  $templine .= $line;
  if (substr(trim($line), -1, 1) == ';') {
    $conn->query($templine);
    $templine = "";
  }
}

echo "Successfully... All tables is imported";
echo "\n";

echo "Successfuly... database is imported!";
