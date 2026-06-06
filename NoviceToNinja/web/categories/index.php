
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/magicquotes.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/access.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';


//* Check for correct credentials
if (!userIsLoggedIn())
{
    include '../admin/login.html.php';
    exit();
}

if (!userHasRole('Site Administrator'))
{
    $error = 'Only Site Administrators may access this page.';
    include '../admin/accessdenied.html.php';
    exit();
}
// ============================================================================

//* Action to add a new category 
if (isset($_GET['add']))
{
    $pageTitle = 'New Category';
    $action = 'addform';
    $name = '';
    $id = '';
    $button = 'Add Category';

    include 'form.html.php';
    exit();
}
// ============================================================================

//* Action to add a new category 
if (isset($_GET['addform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'INSERT INTO category SET name = :name';

        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error adding submitted category. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// ============================================================================

// Open the Edit Category form
if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'SELECT id, name FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error adding submitted category. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    $row = $s->fetch();

    $pageTitle = 'Edit Category';
    $action = 'editform';
    $name = $row['name'];
    $id = $row['id'];
    $button = 'Update category';

    include 'form.html.php';
    exit();
}
// ============================================================================

// Apply the Edit Category action
if (isset($_GET['editform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    try
    {
        $sql = 'UPDATE category SET name = :name WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error updating submitting category. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// ============================================================================

// Apply the Delete Category action
if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // Delete joke associations with this category
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE categoryid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error removing jokes from category. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }	    

    // Delete the category 
    try
    {
        $sql = 'DELETE FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error deleting category. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }	    

    header('Location: .');
    exit();
}
// ============================================================================

// Display category list
include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

try
{
    $result = $pdo->query('SELECT id, name FROM category');
}
catch (PDOException $e)
{
    $error = 'Error fetching categories from database! ' . $e->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
    exit();
}

foreach ($result as $row)
{
    $categories[] = array('id' => $row['id'], 'name'=> $row['name']);
}

include 'categories.html.php';


?>
