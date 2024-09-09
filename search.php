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
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/dbconnect.php');

function getDistinctValues($table, $column) {
  global $conn;

  $stmt = mysqli_prepare($conn, "SELECT DISTINCT $column FROM $table");
  if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
  }
  if (!mysqli_stmt_execute($stmt)) {
    die("Error executing statement: " . mysqli_stmt_error($stmt));
  }
  mysqli_stmt_bind_result($stmt, $value);

  $values = array();
  while (mysqli_stmt_fetch($stmt)) {
    $values[] = $value;
  }
  mysqli_stmt_close($stmt);
  return $values;
}
?>
<form method="post" action="">
  <label for="chamber_num">Chamber Number:</label>
  <select id="chamber_num" name="chamber_num">
    <option value="">Select a chamber number</option>
    <?php
      $chamber_nums = getDistinctValues("ocarina", "chamber_num"); // function to retrieve all unique chamber_num values from the ocarina table
      foreach ($chamber_nums as $num) {
        echo '<option value="' . $num . '">' . $num . '</option>';
      }
    ?>
  </select>
  <br>
  <label for="hole_num">Hole Number:</label>
  <select id="hole_num" name="hole_num">
    <option value="">Select a hole number</option>
    <?php
      $hole_nums = getDistinctValues("ocarina", "hole_num");
      foreach ($hole_nums as $num) {
        echo '<option value="' . $num . '">' . $num . '</option>';
      }
    ?>
  </select>
  <br>
  <label for="key_value">Key Value:</label>
  <select id="key_value" name="key_value">
    <option value="">Select a key value</option>
    <?php
      $key_vals = getDistinctValues("ocarina", "key_value");
      foreach ($key_vals as $val) {
        echo '<option value="' . $val . '">' . $val . '</option>';
      }
    ?>
  </select>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>

<p>Please use the above filters to choose your criterion. <br> Note that some combinations may yield no result. <br> <br> Click on a thumbnail for more photos!</p>

<?php
if (isset($_POST['submit'])) {
  echo "<p>Showing ocarinas (if any) based on the following Selection(s):<p>";
if (isset($_POST['chamber_num']) && !empty($_POST['chamber_num'])) {
  echo '<p>Number of Chambers: ' . $_POST['chamber_num'] . '</p>';
}
if (isset($_POST['hole_num']) && !empty($_POST['hole_num'])) {
  echo '<p>Number of Holes: ' . $_POST['hole_num'] . '</p>';
}
if (isset($_POST['key_value']) && !empty($_POST['key_value'])) {
  echo '<p>Key: ' . $_POST['key_value'] . '</p>';
}
if (isset($_POST['country']) && !empty($_POST['country'])) {
  echo '<p>Brand/Maker Country: ' . $_POST['country'] . '</p>';
}
}
$query = "SELECT folder, folder_hash FROM ocarina WHERE 1=1 ";
$chamber_num = $_POST['chamber_num'];
$hole_num = $_POST['hole_num'];
$key_value = $_POST['key_value'];
$country = $_POST['country'];
if (!empty($chamber_num)) {
  $query .= "AND chamber_num = $chamber_num ";
}
if (!empty($hole_num)) {
  $query .= "AND hole_num = $hole_num ";
}
if (!empty($key_value)) {
  $query .= "AND key_value = '$key_value' ";
}
if (!empty($country)) {
  $query .= "AND folder IN (SELECT folder FROM ocarina JOIN brand ON ocarina.brand_id = brand.id WHERE brand.country = '$country') ";
}
// $result = mysqli_query($conn, $query);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// echo $query;
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
  die("Error preparing statement: " . mysqli_error($conn));
}
if (!mysqli_stmt_execute($stmt)) {
  die("Error executing statement: " . mysqli_stmt_error($stmt));
}
mysqli_stmt_bind_result($stmt, $folder, $folder_hash);
$folders = array();
$folder_hashes = array();
while (mysqli_stmt_fetch($stmt)) {
  $folders[] = $folder;
  $folder_hashes[] = $folder_hash;
}
mysqli_stmt_close($stmt);
for ($i = 0; $i < count($folders); $i++) {
  $folder = $folders[$i];
  $folder_hash = $folder_hashes[$i];
  $folder2 = rtrim($folder, '/');
  $parts = explode("/", $folder2);
  $filename = end($parts);
  $file = "./photos/".$folder.$filename."_0.JPG";
  if (file_exists($file)) {
    echo '<a href="./result.php?h='.$folder_hash.'"><img src="'.$file.'" alt="" height="200"></a>';
  }
}
?>
</main>

<?php
include('static/template/footer.php');
?>
</body>
</html>