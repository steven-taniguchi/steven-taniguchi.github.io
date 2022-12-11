<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            .correct {
                background-color: lightgreen;
            }
            .incorrect {
                background-color: lightcoral;
            }
            .question {
                border: solid black 2px;
                padding: 10px;
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <h1>Results</h1>
        <?php
        //Read file and logger
        include "ChromePhp.php";
        include "FileUtils.php";
        
        //Start session
        session_start();
        
        //Get quiz from session
        $theQuiz = $_SESSION['theQuiz'];
        
        //Init output string
        $quizResults = "";
        
        //Set up variables to display score later
        $numQuestions = count($theQuiz["questions"]);
        $numCorrect = 0;
        
        //Loop through quiz and display question number, question text, options, user answer and correct answer (if user answer was incorrect)
        for($i = 0; $i < $numQuestions; $i++) { 
            $questionNumber = $i + 1;
            $quizResults .= "<div class='question'><h2>Question $questionNumber</h2>";
            $question = $theQuiz["questions"][$i]["questionText"];
            $quizResults .= "<p>$question</p>";
            $choiceLength = count($theQuiz["questions"][$i]["choices"]);
            for($j = 0; $j < $choiceLength; $j++) {
                $answer = $theQuiz["questions"][$i]["choices"][$j];
                $quizResults .= "<input type='radio' disabled>$answer<br>";
            }
            $userAnswer = $theQuiz["questions"][$i]["choices"][$_SESSION["userAnswerArray"][$i]];
            $userAnswerIndex = array_search($userAnswer, $theQuiz["questions"][$i]["choices"]);
            $correctAnswerIndex = $theQuiz["questions"][$i]["answer"];
            //Add class to div depending on if user was correct or not to change styling. Also show correct answer if user was incorrect
            if($userAnswerIndex == $correctAnswerIndex) {
                $quizResults .= "<br><div class='correct'>Your answer: $userAnswer (correct)</div></div>";
                $numCorrect++;
            } else {
                $quizResults .= "<br><div class='incorrect'>Your answer: $userAnswer (incorrect)</div><br>Correct answer: {$theQuiz['questions'][$i]['choices'][$theQuiz['questions'][$i]['answer']]}</div>";
            }
        }       
        $quizResults .= "<h2>Score</h2><p>You scored $numCorrect/$numQuestions</p>";
        echo $quizResults;
        ?>
    </body>
</html>
