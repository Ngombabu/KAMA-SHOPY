<?php
require_once('db2.php');

$marque = $_GET['marque'] ?? '';
$type = $_GET['type'] ?? '';

if (empty($marque)) {
    echo json_encode([]);
    exit();
}

$table = ($type === 'chaussure') ? 'chaussure' : 'sac';
$sql = "SELECT DISTINCT couleur, image, prix FROM $table WHERE marque = ? GROUP BY couleur";
$stmt = $connecte->prepare($sql);
$stmt->bind_param('s', $marque);
$stmt->execute();
$result = $stmt->get_result();

$colors = [];
while ($row = $result->fetch_assoc()) {
    $colors[] = $row;
}

echo json_encode($colors);
$connecte->close();
?>