
if ( $_SERVER['REQUEST_METHOD'] != 'POST' )
{
    # Form display statements to be inserted here.

    echo '
    <form action="single.php" method="POST">
        <fieldset>
            <legend>Send us your comments</legend>
            <textarea rows="5" cols="40" name="comment"></textarea>
        </fieldset>
        <p><input type="submit"></p>
    </form> ';
{
else
{
    # Form handling statements to be inserted here.
    if ( !empty($_POST['comment']))
    {
        $comment = $_POST['comment'];
        echo "Comment: $comment ";
    }
    else
    {
        $comment = NULL;
        echo 'You must enter a comment';
    }
}

