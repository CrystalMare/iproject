<html>
  <head>
  <link rel="stylesheet" type="text/css" href="style.css"/>
  </head>
  <body>
    <h1>Repository Status</h1>
    <hr>
    <?php
      // Call Shell Script to perform a git fetch & reset
      echo str_replace(array("\r\n","\r","\n"),'<br>', shell_exec("sh force.sh 2>&1"));
    ?>
  </body>
</html>
