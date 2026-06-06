
<?php 
try
{
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb', 'ijdbuser', '8vU*7AqaHjHs');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
    $error = 'Unable to connect to the database server. ' . $e->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
    exit();
}
/* Note that the Ninja book doesn't show include closing PHP tag on page 166*/

