<?php

// DO NOT USE THIS IN PRODUCTION
// VERY UNSAFE / VULNERABLE
$search_keyword = $_GET["q"];
// echo "<br><br>" . $search_keyword;

$db_path = "test.db";
// $db_path = ":memory:";

$db = new PDO("sqlite:$db_path");

// $statement = $db->prepare('SELECT * FROM some_table_name');

$statement_string = "SELECT * FROM some_table_name WHERE "; // trailing space required
$statement_string .= "a_field LIKE :search_keyword OR "; // trailing space required
$statement_string .= "b_field LIKE :search_keyword OR "; // trailing space required
$statement_string .= "c_field LIKE :search_keyword OR "; // trailing space required
$statement_string .= "d_field LIKE :search_keyword;";
$statement = $db->prepare($statement_string);
$statement->bindValue(':search_keyword', $search_keyword . "%");    // "%" is used for wildcard search

$statement->execute();
$results = $statement->fetchAll();

echo "<table border=1>"; // start a table tag in the HTML
echo "<th>a_field</th>";
echo "<th>b_field</th>";
echo "<th>c_field</th>";
echo "<th>d_field</th>";

foreach ($results as $result) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($result['a_field']) . "</td>";
    echo "<td>" . htmlspecialchars($result['b_field']) . "</td>";
    echo "<td>" . htmlspecialchars($result['c_field']) . "</td>";
    echo "<td>" . htmlspecialchars($result['d_field']) . "</td>";
    echo "</tr>";
}
echo "</table>"; //Close the table in HTML

?>