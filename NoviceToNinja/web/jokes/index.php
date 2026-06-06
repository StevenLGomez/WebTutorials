
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/magicquotes.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/access.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

// Check for proper credentials
if (!userIsLoggedIn())
{
    include '../admin/login.html.php';
    exit();
}

if (!userHasRole('Content Editor'))
{
    $error = 'Only Content Editors may access this page.';
    include '../admin/accessdenied.html.php';
    exit();
}
// ============================================================================

// Display the Add Joke Page
if (isset($_GET['add']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    $pageTitle = 'New Joke';
    $action = 'addform';
    $text = '';
    $authorid = '';
    $button = 'Add Joke';

    // Build the list of authors in $authorList
    try
    {
        $result = $pdo->query('SELECT id, name, email FROM author');
    } 
    catch (PDOException $e)
    {
        $error = 'Error fetching list of authors. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $authorList[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']);
    }

    // Build the list of categories
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    } 
    catch (PDOException $e)
    {
        $error = 'Error fetching list of categories. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $categories[] = array('id' => $row['id'], 'name' => $row['name'], 'selected' => FALSE);
    }

    include 'form.html.php';
    exit();
}
// ============================================================================

// Process adding new joke 
if (isset($_GET['addform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // Require an author entry
    if ($_POST['author'] == '')
    {
        $error = 'You must choose an author for this joke.
        Click &lsquo;back&rsquo; and try again.';
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    // Perform the INSERT operations for the joke
    try
    {
        $sql = 'INSERT INTO joke SET
            joketext = :joketext,
            jokedate = CURDATE(),
            authorid = :authorid';

        $s = $pdo->prepare($sql);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error adding submitted joke. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }
    $jokeid = $pdo->lastInsertId(); // This the ID of the joke just inserted

    // Perform the INSERT operations for categories
    if (isset($_POST['categories']))
    {
        try
        {
            $sql = 'INSERT INTO jokecategory SET jokeid = :jokeid, categoryid = :categoryid';

            $s = $pdo->prepare($sql);

            foreach ($_POST['categories'] as $categoryid)
            {
                $s->bindValue(':jokeid', $jokeid);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        }
        catch (PDOException $e)
        {
            $error = 'Error inserting joke into selected categories. ' . $e->getMessage();
            include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
            exit();
        }
    }

    header('Location: .');
    exit();
}
// ============================================================================

// Prepare the Edit Joke page
if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // Perform a query to get the selected joke's values
    try
    {
        $sql = 'SELECT id, joketext, authorid FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch(PDOException $e)
    {
        $error = 'Error fetching joke details. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }
    $row = $s->fetch();

    $pageTitle = 'Edit Joke';
    $action = 'editform';
    $text = $row['joketext'];
    $authorid = $row['authorid'];
    $id = $row['id'];
    $button = 'Update Joke';

    // Build the list of authors in $authorList
    try
    {
        $result = 'SELECT id, name, email FROM author';
    }
    catch(PDOException $e)
    {
        $error = 'Error fetching list of authors. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $authorList[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']);
    }

    $authorLength = count($authorList);

    // Get list of categories containing this joke
    try
    {
        $sql = 'SELECT categoryid FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }
    catch(PDOException $e)
    {
        $error = 'Error fetching list of selected categories. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($s as $row)
    {
        $selectedCategories[] = $row['categoryid'];
    }

    // Build the list of all categories
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    }
    catch(PDOException $e)
    {
        $error = 'Error fetching list of categories. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($result as $row)
    {
        $categories[] = array('id' => $row['id'], 'name' => $row['name'], 'selected' => in_array($row['id'], $selectedCategories));
    }

    include 'form.html.php';
    exit();
}
// ============================================================================

// Apply the Edit Joke action
if (isset($_GET['editform']))
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    if ($_POST['author'] == '')
    {
        $error = 'You must choose an author for this joke.
            Click &lsquo;back&rsquo; and try again.';

        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    // Change the joke values
    try
    {
        $sql = 'UPDATE joke SET
            joketext = :joketext,
            authorid = :authorid
            WHERE id = :id';

        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error updating submitted joke. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    // Remove stale categories
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error removing obsolete joke category entries. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    // Update to new categories
    if (isset($_POST['categories']))
    {
        try
        {
            $sql = 'INSERT INTO jokecategory SET jokeid = :jokeid, categoryid = :categoryid';

            $s = $pdo->prepare($sql);

            foreach ($_POST['categories'] as $categoryid)
            {
                $s->bindValue(':jokeid', $_POST['id']);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        }
        catch (PDOException $e)
        {
            $error = 'Error inserting joke into selected categories. ' . $e->getMessage();
            include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
            exit();
        }
    }

    header('Location: .');
    exit();
}
// ============================================================================

// Perform the Delete action
if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // Delete category assignments for this joke
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error removing joke from categories. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    // Delete the joke
    try
    {
        $sql = 'DELETE FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        $error = 'Error deleting joke. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// ============================================================================

// Perform the search action
if (isset($_GET['action']) and $_GET['action'] == 'search')
{
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

    // The basic SELECT statement pieces
    $select = 'SELECT id, joketext';
    $from   = ' FROM joke';
    $where  = ' WHERE TRUE';

    $placeholders = array();

    // Check to see if an author was selected, if so, modify the WHERE clause
    if ($_GET['author'] != '')
    {
        $where .= " AND authorid = :authorid";
        $placeholders[':authorid'] = $_GET['author'];
    }

    // Check to see if a catetory was selected, if so, modify the WHERE clause
    if ($_GET['category'] != '')
    {
        $from .= ' INNER JOIN jokecategory ON id = jokeid';
        $where .= " AND categoryid = :categoryid";
        $placeholders[':categoryid'] = $_GET['category'];
    }

    // Check to see if search text was entered, if so, modify the WHERE clause
    if ($_GET['text'] != '')
    {
        $where .= " AND joketext LIKE :joketext";
        $placeholders[':joketext'] = '%' . $_GET['text'] . '%';
    }

    try
    {
        $sql = $select . $from . $where . ';';
        $s = $pdo->prepare($sql);
        $s->execute($placeholders);
    }
    catch (PDOException $e)
    {
        $error = 'Error fetching jokes. ' . $e->getMessage();
        include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
        exit();
    }

    foreach ($s as $row)
    {
        $jokes[] = array('id' => $row['id'], 'text' => $row['joketext']);
    }

    include 'jokes.html.php';
    exit();
}
// ============================================================================


// Display Search form
include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php';

// Get list of authors in $authorList
try
{
    $result = $pdo->query('SELECT id, name, email FROM author');
}
catch (PDOException $e)
{
    $error = 'Error fetching authors from database! ' . $e->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/error.inc.html.php';
    exit();
}

foreach ($result as $row)
{
    $authorList[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']);
}

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
    $categories[] = array('id' => $row['id'], 'name' => $row['name']);
}

include 'searchform.html.php';
// ============================================================================

?>

