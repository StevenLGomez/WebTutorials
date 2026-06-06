
<?php
    $firstname = $_REQUEST['firstname'];
    $lastname = $_REQUEST['lastname'];
    echo 'Welcome to our website, ' . 
        htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8') . ' ' .
        htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8') . 
        '!';
?>

