<?php
include('bddcall.php');
include('functions.php');
$bdd = bddcall();
convertBddToCSV($bdd);
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=export.csv");
