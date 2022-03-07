<?php
include('src/bddcall.php');
include('src/functions.php');
$bdd = bddcall();
if (isset($_POST['action']) && $_POST['action'] !== "") {
    if ($_POST['action'] == "delete") {
        $bdd->query('DELETE FROM depenses WHERE id > 0');
    } elseif ($_POST['action'] == "import" && isset($_FILES['fichier'])) {
        $origine = $_FILES['fichier']['tmp_name'];
        $destination = 'fichiers/' . $_FILES['fichier']['name'];
        move_uploaded_file($origine, $destination);
        $convertData = parseCSV($destination);
        importCSV($convertData, $bdd);
    }
}
$allTransaction = catchAllTransaction($bdd);
$amountArray = [];
$numberOfTransaction = getNumberOfTransaction($bdd);
require('views/viewIndex.php');
