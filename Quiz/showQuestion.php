<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <?php        
        //Session start
        session_start();
        
        //Logger and read file
        include "ChromePhp.php";
        include "FileUtils.php";
                
        //Retrieve all relevant info from session. numQuestions for disable/enable buttons. currentQuestionNumber to keep track of what question to display
        $numQuestions = $_SESSION["numQuestions"];        
        $currentQuestionNumber = $_SESSION["currentQuestionNumber"];
        
        //If user answered question, save result to session under userAnswerArray
        //Since we have not incremented/decremented the question number yet, we are looking at the previous question answers
        if(isset($_POST["question$currentQuestionNumber"])) {
                $_SESSION["userAnswerArray"][$currentQuestionNumber] = $_POST["question$currentQuestionNumber"];
        }
        
        //Check which button pressed
        if(isset($_POST["next"])){
            $currentQuestionNumber++;
            $_SESSION["currentQuestionNumber"] = $currentQuestionNumber;
        }
        if (isset($_POST["previous"])){
            $currentQuestionNumber--;
            $_SESSION["currentQuestionNumber"] = $currentQuestionNumber;
        }
        if (isset($_POST["done"])) {
            //Check if every question answered via session. Handle if accordingly
            if(in_array(-1, $_SESSION["userAnswerArray"])){
                header("location: errorPage.php");             
            } else {
                header("location: results.php");
            }
        }
        
        //CREATE THE QUIZ PAGE
        //Get quiz stored in session
        $theQuiz = $_SESSION["theQuiz"];

        //Page output string
        $pageOutput = "";
        
        //Question text
        $quizQuestion = $theQuiz["questions"][$currentQuestionNumber]["questionText"];
        
        //Question number
        $pageOutput .= "<h1>Question " . ($currentQuestionNumber+1) . "</h1><p>$quizQuestion</p>";
        
        //Choices array
        $choices = $theQuiz["questions"][$currentQuestionNumber]["choices"];

        //Loop through choices array to put them in radio button options
        for ($i = 0; $i < count($choices); $i++) {
            $option = $choices[$i];
            //Check to see if the question currently being displayed has been answered yet. If it has, check the radio button, if not leave it unchecked
            if($i == $_SESSION["userAnswerArray"][$currentQuestionNumber]) {
                $pageOutput .= "<input type='radio' name='question$currentQuestionNumber' value='$i' checked>$option<br>";
            } else {
                $pageOutput .= "<input type='radio' name='question$currentQuestionNumber' value='$i'>$option<br>";
            }
        }
        
        //Form with page output
        echo "<form action='showQuestion.php' method='POST'>";
        //Display page
        echo $pageOutput;
        
        //Three buttons, next, previous, and done. Check current question number and disable accordingly
        //Disable previous button if first question
        if ($currentQuestionNumber == 0) {
            echo "<button  class='btn btn-primary' disabled>Previous</button>";
        } else {
            echo "<button type='submit' class='btn btn-primary' name='previous'>Previous</button>";
        }
        //Disable next button if on last question
        if ($currentQuestionNumber == $numQuestions - 1) {
            echo "<button class='btn btn-primary' disabled>Next</button>";
        } else {
            echo "<button type='submit' class='btn btn-primary' name='next'>Next</button>";
        }
        //Disable done button if not on last question
        if ($currentQuestionNumber != $numQuestions - 1) {
            echo "<button class='btn btn-success' disabled>Done</button>";
        } else {
            echo "<button type='submit' type='button' class='btn btn-success' name='done'>Done</button>";
        }
        
        //Close the form
        echo "</form>";
        //END QUIZ PAGE        
        ?>
    </body>
</html>
