<?php
require_once 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: index.php?msg=deleted');
exit;
