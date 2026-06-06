
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Manage Jokes: Search Results</title>
    </head>
    <body>
        <!-- This section is diagnostic information -->
        <h1>Query Contents</h1>
        <div>
            <?php echo 'SELECT:    ' . $select; ?>
        </div>
        <div>
            <?php echo 'FROM:      ' . $from; ?>
        </div>
        <div>
            <?php echo 'WHERE:     ' . $where; ?>
        </div>
        <div>
            <?php echo 'FULL SQL:  ' . $sql; ?>
        </div>
        <hr/>
        <!-- End of diagnostic information section -->

        <h1>Search Results</h1>

        <!-- Check to see if any jokes were found in the search -->
        <?php if (isset($jokes)): ?>
            <table>
                <tr>
                    <th>Joke Text</th><th>Options</th>
                </tr>

                <!-- Loop through the list of found jokes -->
                <?php foreach($jokes as $joke): ?>
                <tr>
                    <!-- Display the text of the joke -->
                    <td><?php markdownout($joke['text']); ?></td>

                    <!-- Display action buttons -->
                    <td>
                        <form action="?" method="post">
                            <div>
                                <input type="hidden" name="id" value="<?php htmlout($joke['id']); ?>">
                                <input type="submit" name="action" value="Edit">
                                <input type="submit" name="action" value="Delete">
                            </div>
                        </form>
                    </td>
                </tr>

                <?php endforeach; ?>
            </table>

        <?php endif; ?>

        <p><a href="?">New search</a></p>
        <p><a href="..">Return fo JMS Home</a></p>
        <?php include '../admin/logout.inc.html.php'; ?>

    </body>
</html>
</!doctype>


