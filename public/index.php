<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);
define('SRC_PATH', $root . 'src' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', $root . 'public' . DIRECTORY_SEPARATOR);
define('FILE_PATH', $root . 'fichiers' . DIRECTORY_SEPARATOR);

include(SRC_PATH . 'bddcall.php');
include(SRC_PATH . 'functions.php');
$bdd = bddcall();
if (isset($_POST['action']) && $_POST['action'] !== "") {
    if ($_POST['action'] == "delete") {
        deleteBDD($bdd);
    } elseif ($_POST['action'] == "import" && isset($_FILES['fichier'])) {
        $origine = $_FILES['fichier']['tmp_name'];
        $destination = FILE_PATH . $_FILES['fichier']['name'];
        move_uploaded_file($origine, $destination);
        $convertData = parseCSV($destination);
        importCSV($convertData, $bdd);
    }
}
$allTransaction = catchAllTransaction($bdd);
$amountArray = [];
$numberOfTransaction = getNumberOfTransaction($bdd);
require(VIEWS_PATH . 'viewIndex.php');
