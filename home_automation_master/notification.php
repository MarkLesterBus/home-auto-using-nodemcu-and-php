<?php
require_once('../include/config.php');
		
$query = "";

$stmt = $conn->prepare($query);
$stmt->execute();
$notifications = $stmt->fetchAll();
$stmt->closeCursor();

?>