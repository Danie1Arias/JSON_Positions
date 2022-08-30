<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
}

if ( isset($_POST['cancel'])) {
  header( 'Location: index.php' ) ;
  return;
}

if (!isset($_GET['profile_id'])) {
  $_SESSION['error'] = "Could not load profile";
  header("Location: index.php");
  return;
}

// Guardian: Make sure that profile_id is present
if ( ! isset($_GET['profile_id']) ) {

  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$statement = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
    
$statement->execute(array(
    ":profile_id" => $_GET["profile_id"],
));

$row = $statement->fetch(PDO::FETCH_ASSOC);

if ($row == false) {
    $_SESSION['error'] = "Could not load profile";
    header("Location: index.php");
    return;
}

$first_name = htmlentities($row["first_name"]);
$last_name = htmlentities($row["last_name"]);
$email = htmlentities($row["email"]);
$headline = htmlentities($row["headline"]);
$summary = htmlentities($row["summary"]);

?>

<!DOCTYPE html>
<html>
    <head>
    <title>Daniel Arias Severance's Resume Registry 2921eaf4</title>
    <?php require_once "bootstrap.php"; ?>
</head>

<body>
<div class="container">

<h1>Deleteing Profile</h1>
<form method="post" action="delete.php">
  <p>First Name: <?= htmlentities($row['first_name']) ?></p>
  <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
  <input type="hidden" name="profile_id" value="<?= $_GET['profile_id'] ?>">
  <input type="submit" value="Delete" name="delete">
  <input type="submit" value="Cancel" name="cancel">
</form>

</div>
</body>