
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/magicquotes.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/access.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

// Check for active login 
if (!userIsLoggedIn())
{
    include '../admin/login.html.php';
    exit();
}

// Check for access to this specific page
if (!userHasRole('Account Administrator'))
{
    $error = 'Only Account Administrators may access this page.';
    include '../admin/accessdenied.html.php';
    exit();
}
// --------------------------------------------------------------------------------

// Add Authors to database
if (isset($_GET['add']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    $pageTitle = 'New Author';
    $action = 'addform';
    $name = '';
    $email = '';
    $id = '';
    $button = 'Add Author';

    // Build the list of roles
    try
    {
        $result = $pdo->query('SELECT id, description FROM role');
    }
    catch (PDOException $e)
    {
        $error = 'Error fetching list of roles.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $roles[] = array( 'id' => $row['id'], 'description' => $row['description'], 'selected' => FALSE);
    }

    include 'form.html.php'; 
    exit();
}
// --------------------------------------------------------------------------------

// Watch for 'addform' action - see bottom of p 202-
if (isset($_GET['addform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'INSERT INTO author SET
            name = :name,
            email = :email';

        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error adding submitted author. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    $authorid = $pdo->lastInsertId();

    if ($_POST['password'] != '')
    {
        $password = md5($_POST['password'] . 'ijdb');

        try
        {
            $sql = 'UPDATE author SET
                password = :password
                WHERE id = :id';

            $s = $pdo->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $authorid);
            $s->execute();
        }
        catch (PDOException $e)
        {
            $error = 'Error setting author password.' . $e->getMessage();
            include 'error.html.php';
            exit();
        }
    }

    if (isset($_POST['roles']))
    {
        foreach ($_POST['roles'] as $role)
        {
            try
            {
                $sql = 'INSERT INTO authorrole SET
                    authorid = :authorid,
                    roleid = :roleid';

                $s = $pdo->prepare($sql);
                $s->bindValue(':authorid', $authorid);
                $s->bindValue(':roleid', $role);
                $s->execute();
            }
            catch (PDOException $e)
            {
                $error = 'Error assigning selected role to author.' . $e->getMessage();
                include 'error.html.php';
                exit();
            }
        }
    }

    header('Location: .');
    exit();
}
// --------------------------------------------------------------------------------

// Watch for Edit Author action
if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'SELECT id, name, email FROM author WHERE id = :id';

        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
    $error = 'Error fetching author details. ' . $e->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
    exit();
    }

    $row = $s->fetch();

    $pageTitle = 'Edit Author';
    $action = 'editform';
    $name = $row['name'];
    $email = $row['email'];
    $id = $row['id'];
    $button = 'Update author';

    // Get list of roles assigned to this author
    try
    {
        $sql = 'SELECT roleid FROM authorrole WHERE authorid = :id';

        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error fetching list of assigned roles.';
        include 'error.html.php';
        exit();
    }

    $selectedRoles = array();

    foreach ($s as $row)
    {
        $selectedRoles[] = $row['roleid'];
    }

    // Build the list of all roles
    try
    {
        $result = $pdo->query('SELECT id, description FROM role');
    }
    catch (PDOException $e)
    {
        $error = 'Error fetching list of roles.' . $e.getMessage();
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $roles[] = array(
        'id' => $row['id'],
        'description' => $row['description'],
        'selected' => in_array($row['id'], $selectedRoles));
    }

    include 'form.html.php'; 
    exit();
}
// --------------------------------------------------------------------------------

// Perform the author UPDATE action (Ref pp 204-205)
if (isset($_GET['editform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'UPDATE author SET
            name = :name,
            email = :email
            WHERE id = :id';

        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error updating submitted author. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    if ($_POST['password'] != '')
    {
        $password = md5($_POST['password'] . 'ijdb');

        try
        {
            $sql = 'UPDATE author SET
                password = :password
                WHERE id = :id';

            $s = $pdo->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $_POST['id']);
            $s->execute();
        }
        catch (PDOException $e)
        {
            $error = 'Error setting author password.' . $e->getMessage();
            include 'error.html.php';
            exit();
        }
    }

    try
    {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';

        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error removing obsolete author role entries.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }

    if (isset($_POST['roles']))
    {
        foreach ($_POST['roles'] as $role)
        {
            try
            {
                $sql = 'INSERT INTO authorrole SET
                    authorid = :authorid,
                    roleid = :roleid';

                $s = $pdo->prepare($sql);
                $s->bindValue(':authorid', $_POST['id']);
                $s->bindValue(':roleid', $role);
                $s->execute();
            }
            catch (PDOException $e)
            {
                $error = 'Error assigning selected role to author.';
                include 'error.html.php';
                exit();
            }
        }
    }

    header('Location: .');
    exit();
}
// --------------------------------------------------------------------------------

// Delete authors, with PHP based referential integrity (Ref pp 195-
if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // Delete role assignments for this author
    try
    {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error removing author from roles.';
        include 'error.html.php';
        exit();
    }

    // Get jokes belonging to author
    try
    {
        $sql = 'SELECT id FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error getting list of jokes to delete. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }	    

    $result = $s->fetchAll();

    // Delete joke category entries
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);

        // For each joke
        foreach ($result as $row)
        {
            $jokeId = $row['id'];	    
            $s->bindValue(':id', $jokeId);
            $s->execute();
        }	    
    }
    catch (PDOException $e)
    {
        $error = 'Error deleting category entries for joke. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }	    

    // Delete jokes belonging to author
    try
    {
        $sql = 'DELETE FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }	
    catch (PDOException $e)
    {
        $error = 'Error deleting jokes for author. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }	    

    // Delete the author
    try
    {
        $sql = 'DELETE FROM author WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error deleting author. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// --------------------------------------------------------------------------------

// This section is the 'default' action of displaying the list of authors.
// Editing buttons are provided in the 'include' page below.

echo 'DEFAULT Author action reached'; 

try
{
    $result = $pdo->query('SELECT id, name, email FROM author');
}
catch (PDOException $e)
{
    $error = 'Error fetching authors from the database! ' . $e->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
    exit();
}

foreach ($result as $row)
{
    $authorList[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']);
}

include 'authors.html.php';

?>

