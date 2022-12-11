<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <?php
        //Logger and read file
        include "ChromePhp.php";
        include "FileUtils.php";
        
        //Display title, prompt, and dropdown menu of available quizzes
        echo "<h1>Quiz App</h1>";
        echo "<p>Select a quiz and press Start to begin</p>";
        echo "<form action='buildQuiz.php' method='POST'>
                <select class='form-select' name='quizFileName'>
                    <option value='WorldGeography.json'>World Geography</option>
                    <option value='NumberSystems.json'>Number Systems</option>
                </select>
                <button type='submit' class='btn btn-primary'>Start</button>
             </form>"
        ?>
    </body>
</html>
