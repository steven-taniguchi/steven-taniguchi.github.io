
<html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
    </head>
    <body>
        <?php
        session_start();
        echo "<h1>Error</h1><p>Some questions have not been answered</p>";
        //Redirect back to question page
        echo "<form action='showQuestion.php' method='POST'><button type='submit'>Go Back</button></form>";
        ?>
    </body>
</html>
