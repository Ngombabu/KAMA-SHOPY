<?php
require_once('db2.php');

$marque = $_GET['marque'] ?? '';
$couleur = $_GET['couleur'] ?? '';
$taille = $_GET['taille'] ?? '';
$type = $_GET['type'] ?? '';

if (empty($marque) || empty($couleur)) {
    echo json_encode([]);
    exit();
}

$table = ($type === 'chaussure') ? 'chaussure' : 'sac';
$sql = "SELECT * FROM $table WHERE marque = ? AND couleur = ?";
$params = [$marque, $couleur];

if ($type === 'chaussure' && !empty($taille)) {
    $sql .= " AND taille = ?";
    $params[] = $taille;
}

$stmt = $connecte->prepare($sql);
$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$connecte->close();
?>