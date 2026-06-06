
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Manage Jokes</title>
    </head>
    <body>
        <h1>Manage Jokes</h1>
        <p><a href="?add">Add new joke</a></p>
        
        <form action="" method="get">
        <p>View jokes satisfying the following criteria:</p>

            <!-- Author section -->
            <div>
                <label for="author">By author:</label>
                <select name="author" id="author">
                    <option value="">Any author</option>

                    <?php foreach ($authorList as $author): ?>
                        <option value="<?php htmlout($author['id']); ?>">
                            <?php htmlout($author['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category section -->
            <div>
                <label for"category">By category:</label>
                <select name="category" id="category">
                    <option value="">Any category</option>

                    <?php foreach ($categories as $category): ?>
                        <option value="<?php htmlout($category['id']); ?>">
                            <?php htmlout($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Section for entering search test -->
            <div>
                <label for="text">Containing text:</label>
                <input type="text" name="text" id="text">
            </div>

            <!-- Section for the Search button -->
            <div>
                <input type="hidden" name="action" value="search">
                <input type="submit" value="Perform Search">
            </div>
        </form>

        <p><a href="..">Return to JMS home</a></p>
        <?php include '../admin/logout.inc.html.php'; ?>

    </body>
</html>
</!doctype>

