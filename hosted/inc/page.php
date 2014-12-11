<?php

global $buffer;
$buffer = array();

function loadPage($page){
    if(pageExists($page))
    {
        ob_start();
        include(pages . 'navigation/header.php');
        include(pages . 'navigation/header.html');
        include(pages . $page . '.php');
        include(pages . $page . '.html');
        include(pages . 'navigation/footer.php');
        include(pages . 'navigation/footer.html');
        $content = ob_get_clean();
        parsePage($content);
    }
    else{
        echo '404 Placeholder';
        exit();
    }
}

function pageExists($page)
{
    $files = scandir(pages);
    if (in_array($page . '.php', $files) && in_array($page . '.html', $files)) {
        return true;
    }
    return false;
}

function parsePage($content)
{
    $content = parseContent($content);
    echo $content;
    ob_flush();
}


function parseContent($content)
{
    global $buffer;
    foreach ($buffer as $key => $value) {
        $content = str_replace('%' . $key . '%', $value, $content);
    }
    return $content;
}