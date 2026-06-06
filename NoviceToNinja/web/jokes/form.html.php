
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/helpers.inc.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/db.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php htmlout($pageTitle); ?></title>
        <style type="text/css">
            textarea 
            {
                display: block; width: 100%;
            }
        </style>
    </head>
    <body>
        <!-- This section is diagnostic information -->
        <h1>Controller Provided Information</h1>
        <div>
            <?php echo '$pageTitle:    ' . $pageTitle; ?>
        </div>
        <div>
            <?php echo '$authorid:    ' . $authorid; ?>
        </div>
        <div>
            <?php echo 'Joke $id:    ' . $id; ?>
        </div>
        <div>
            <?php echo '$authorList length:    ' . count($authorList); ?>
        </div>
        <div>
            <?php echo '$authorLength :    ' . $authorLength; ?>
        </div>
        <div>
            <?php echo '$selectedCategories length:    ' . count($selectedCategories); ?>
        </div>
        <hr/>
        <!-- End of diagnostic information section -->

        <h1><?php htmlout($pageTitle); ?></h1>
        <form action="?<?php htmlout($action); ?>" method="post">

            <!-- Create the text box for entry -->
            <div>
                <label for="text">Type your joke here:</label>
                <textarea id="text" name="text" rows="3" cols="40">
                    <?php htmlout($text); ?>
                </textarea>
            </div>

            <!-- Create the prompt for author selection -->
            <div>
                <label for="author">Author:</label>
                <select name="author" id="author">
                    <option value="">Select one</option>
                    <?php foreach ($authorList as $author): ?>
                        <option value="<?php htmlout($author['id']); ?>">
                            <?php 
                            if ($author['id'] == $authorid)
                            {
                                echo ' selected';
                            }
                            ?>
                            <?php htmlout($author['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Create Catetory selection -->
            <div>
                <fieldset>
                    <legend>Categories:</legend>

                    <?php foreach ($categories as $category): ?>
                    <div><label for="category<?php htmlout($category['id']); ?>">
                        <input type="checkbox" name="categories[]"
                            id="category<?php htmlout($category['id']); ?>"
                            value="<?php htmlout($category['id']); ?>"
                            <?php
                                 if ($category['selected'])
                                 {
                                     echo ' checked';
                                 } ?>
                        >
                        <?php htmlout($category['name']); ?>
                        </label>
                        </div>
                    <?php endforeach; ?>


                </fieldset>
            </div>

            <!-- Create the submit button -->
            <div>
                <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                <input type="submit" value="<?php htmlout($button); ?>">
            </div>
        </form>
    </body>
</html>
</!doctype>

