<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Ocarina Museum</title>
    <link rel="icon" href="img/icon.png" type="image/png">
    <link rel="stylesheet" href="static/css/styles.css" type="text/css">
</head>
<body>

<?php
// Include the dbconnect.php file
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/dbconnect.php');
?>

<?php include('static/template/header.php'); ?>

<main>
    <section>
        <h1>Under Construction</h1>
        <p>Welcome to The Ocarina Museum!</p>
        <p>I am an ocarina collector, player, and maker. I started this museum website in August 2019, but it was neglected until Spring 2023. Unfortunately the website was hacked in summer 2024.</p>
        <p>It is now August 2024. I have decided to take this opportunity to completely redesign the website.</p>
        <p>If you have any questions, please feel free to email me at <a href="mailto:theocarinamuseum@gmail.com">theocarinamuseum@gmail.com</a>. I look forward to hearing from you.
    </section>
</main>

<?php
include('static/template/footer.php');
?>
</body>
</html>