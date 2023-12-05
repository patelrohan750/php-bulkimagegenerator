<?php

function searchQuestion($text) {
    $logFile = 'debug.log'; // Path to your custom log file
    $dsn = 'mysql:host=localhost;dbname=scraper';
    $username = 'root';
    $password = '';

    try {
        // Create a PDO instance
        $pdo = new PDO($dsn, $username, $password);

        // Set PDO to throw exceptions on errors
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        error_log($text, 3, $logFile);

        // $text = "'$text'";
        $text1 = 'how to trigger the css style as we typing the onchange in react';
        echo "Text <br>";
        echo $text;
        echo "<br>";
        echo "Text1 <br>";
        echo $text1;
        echo "<br>";
        echo "Check Match <br>";
        echo ($text == $text1);
        echo "<br>";
        echo $text;
        echo "<br>";
        // Prepare and execute a query with a WHERE clause
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE LOWER(question) = LOWER(:text)");
        $stmt->bindParam(':text', $text);
        echo $stmt->queryString;
        echo "<br>";
        $stmt->execute();

        // Fetch a single result
        // $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo print_r($result);
        // Return the result or an empty array if not found
        return $result ? $result : [];
    } catch (PDOException $e) {
        // Handle database connection or query errors
        echo "Error: " . $e->getMessage();
        return []; // Return an empty array in case of an error
    }
}