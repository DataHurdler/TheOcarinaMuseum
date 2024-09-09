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

<?php include('static/template/header.php'); ?>

<main>
	
<?php
	// Retrieve the value of the "f" parameter
	$h = $_GET['h'];
	
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/dbconnect.php');
	
	$stmt = $conn->prepare("SELECT * FROM ocarina LEFT JOIN brand ON ocarina.brand_id = brand.id WHERE folder_hash = ?");
    $stmt->bind_param("s", $h);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
?>

<!-- Slideshow container -->
<div class="container">
  <!-- Full-width images with number and caption text -->
<?php
	foreach ($data as $row) {
		$f = $row['folder'];
		$folder = "./photos/".$f;
		$files = glob($folder."*.JPG");
		$n = count($files);
		$i = 1;
		foreach ($files as $file) {
			echo '<div class="mySlides">';
			echo '<div class="numbertext">'.$i.' / '.$n.'</div>';
			echo '<img src="'.$file.'" class="slideshow-photo">';
			//echo '<div class="text">Caption Text</div>';
			echo '</div>';
			$i++;
		}
	}
?>
  <!-- Next and previous buttons
  <a class="prev" onclick="plusSlides(-1)">&#10094; Previous</a>
  <a class="next" onclick="plusSlides(1)">Next &#10095;</a>
  -->
  <div class="thumbnail-container">
    <?php
    $i = 1;
    foreach ($files as $file) {
      echo '<div class="thumbnail">';
      echo '<img class="demo cursor" src="'.$file.'" onclick="currentSlide('.$i.')">';
      echo '</div>';
      $i++;
    }
    ?>
  </div>
</div>
</br>
<script>
	let slideIndex = 1;
	showSlides(slideIndex);

	// Next/previous controls
	function plusSlides(n) {
	showSlides(slideIndex += n);
	}
	// Thumbnail image controls
	function currentSlide(n) {
	showSlides(slideIndex = n);
	}
	function showSlides(n) {
	let i;
	let slides = document.getElementsByClassName("mySlides");
	let dots = document.getElementsByClassName("demo");
	//let captionText = document.getElementById("caption");
	if (n > slides.length) {slideIndex = 1}
	if (n < 1) {slideIndex = slides.length}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex-1].style.display = "block";
	dots[slideIndex-1].className += " active";
	captionText.innerHTML = dots[slideIndex-1].alt;
} 
</script>
<p>
<?php
// Display the results
foreach ($data as $row) {
    // Do something with each row of data
    // For example, you could echo out the values of specific columns:
    echo 'Brand: '.$row['name'].'<br>';
    echo 'Country: '.$row['country'].'<br>';
    echo 'Number of chambers: '.$row['chamber_num'].'<br>';
    echo 'Number of holes: '.$row['hole_num'].'<br>';
    echo 'Key: '.$row['key_value'].'<br>';
}
// Close the database connection
mysqli_close($conn);
?>
</p>
</main>

<?php
include('static/template/footer.php');
?>
</body>
</html>