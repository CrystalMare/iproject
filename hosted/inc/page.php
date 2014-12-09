<?php
function loadPage($page){
    if(pageExists($page))
    {
        $content = "";
        ob_start();
        include(pages . $page . '.php');
        $content = ob_get_clean();
        parsePage($content);
    }
    else{
        header('Location: ?p=404');
        exit();
    }
}

function pageExists($page)
{
    switch($page){
        case index:
            return true;
        case '404':
            return true;
        default:
            return false;
    }
}

function parsePage($content)
{
    echo $content;
    ob_flush();
}

%iets%