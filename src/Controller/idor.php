<?php

$pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");

// input utilisateur NON sécurisé
$id = $_GET['id'];

// ❌ concaténation directe -> injection SQL possible
$sql = "SELECT * FROM users WHERE id = $id";

$result = $pdo->query($sql);

$user = $result->fetch();

echo json_encode($user);



$name = $_GET['name'];

// ❌ XSS (CWE-79)
echo "<h1>Hello $name</h1>";

session_start();

$id = $_GET['id'];

// ❌ pas de check owner
$stmt = $pdo->query("SELECT * FROM invoices WHERE id = $id");

echo json_encode($stmt->fetch());

$file = $_GET['file'];

// ❌ accès fichier arbitraire
echo file_get_contents("uploads/" . $file);

$pdo = new PDO(
    "mysql:host=localhost;dbname=test",
    "root",
    "123456" // ❌ secret en dur
);

$ip = $_GET['ip'];

// ❌ injection commande OS
echo shell_exec("ping -c 1 " . $ip);

ession_start();

// ❌ pas de token CSRF
if ($_POST) {
    $pdo->query("UPDATE users SET email='{$_POST['email']}'");
}

session_start();

$userId = $_SESSION['user_id'];
$invoiceId = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * 
    FROM invoices 
    WHERE id = ?
");

$stmt->execute([$invoiceId]);

$invoice = $stmt->fetch();

echo json_encode($invoice);