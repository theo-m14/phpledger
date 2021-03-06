<?php

function bddcall()
{
    try {           //Récupération BDD
        $bdd = new PDO('mysql:dbname=phpledger;host=localhost', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $bdd;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function deleteBDD($bdd)
{
    $bdd->query('DELETE FROM depenses WHERE id > 0');
}

//OLD REGISTER
// function registerOneLine(array $line, $bdd)
// {
//     $importCSV = $bdd->prepare('INSERT INTO depenses(date,checkNum,transacID,amount) VALUES(:date,:checkNum,:transacID,:amount)');
//     $importCSV->execute(array(
//         'date' => $line['date'],
//         'checkNum' => $line['check'],
//         'transacID' => $line['id'],
//         'amount' => $line['amount'],
//     ));
// }

function registerOneLine(array $line, $bdd)
{
    $importCSV = $bdd->prepare('INSERT INTO depenses(date,checkNum,transacID,amount) VALUES(:date,:checkNum,:transacID,:amount)');
    $importCSV->execute(array(
        'date' => convertDateForSQL($line['date']),
        'checkNum' => (int)$line['check'],
        'transacID' => $line['id'],
        'amount' => formateAmount($line['amount']),
    ));
}

function importCSV(array $data, $bdd)
{
    for ($i = 1; $i < count($data); $i++) {
        registerOneLine($data[$i], $bdd);
    }
}

function catchAllTransaction($bdd)
{
    $allTransaction = $bdd->query('SELECT * FROM depenses ORDER BY id');
    return $allTransaction;
}
