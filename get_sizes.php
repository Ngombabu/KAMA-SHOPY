<?php
require_once('db2.php');

$marque = $_GET['marque'] ?? '';
$couleur = $_GET['couleur'] ?? '';

if (empty($marque) || empty($couleur)) {
    echo json_encode([]);
    exit();
}

$sql = "SELECT DISTINCT taille FROM chaussure WHERE marque = ? AND couleur = ?";
$stmt = $connecte->prepare($sql);
$stmt->bind_param('ss', $marque, $couleur);
$stmt->execute();
$result = $stmt->get_result();

$sizes = [];
while ($row = $result->fetch_assoc()) {
    $sizes[] = $row;
}

echo json_encode($sizes);
$connecte->close();
?>