
<?php

function html ($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout ($text)
{
    echo html($text);
}

// Homebrew markdown functions
// Would be more appropriately replaced with the PHP Markdown project's
// markdown.php in this project's include directory.
function markdown2html($text)
{
    $text = html($text);

    // strong emphasis
    $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

    // 'normal' emphasis
    $text = preg_replace('/_(.+?)_/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/s', '<strong>$1</strong>', $text);

    // Line ending fixes (NOTE "\n" is a PHP string containing a newline character)

    // Convert Windoze (\r\n) to Unix (\n)
    $text = str_replace("\r\n", "\n", $text);

    // Convert Macintosh (\r) to Unix (\n)
    $text = str_replace("\r", "\n", $text);

    /* This group of substitutions seem to break the output */

    // Create paragraphs from double enters
    // $text = '<p>' . str_replace("\n\n", '</p><p>', $test) . '</p>';

    // Create line breaks from single enters
    // $text = str_replace("\n", '<br>', $test);

    // Create links in HTML using syntax [linked text](link URL) see page 257.
    //$text = preg_replace(
    //    '/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i', 
    //    '<a href="$2">$1</a>', $text
    //    );

    // return 'MD ' . $text;

    return $text;
}

function markdownout ($text)
{
    echo markdown2html($text);
}

