<?php
    include "ChromePhp.php";
    include "FileUtils.php";

    //Start the session
    session_start();

    //Read file selected by user and create quiz array
    $fileName = $_POST["quizFileName"];
    $fileContents = readFileIntoString($fileName);
    $theQuiz = json_decode($fileContents, true);

    //Create array to store user answers, set all to -1 initially
    $userAnswerArray = [];
    $numQuestions = count($theQuiz["questions"]);
    $_SESSION["numQuestions"] = $numQuestions;
    for($i = 0; $i < $numQuestions; $i++) {
         $userAnswerArray[$i]= -1;
    }

    //Send answer array, the quiz, the current question number to session
    $_SESSION["userAnswerArray"] = $userAnswerArray;
    $_SESSION["theQuiz"] = $theQuiz;
    $_SESSION["currentQuestionNumber"] = 0;

    //Forward to question page
    header("location: showQuestion.php");
?>