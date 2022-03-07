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

function registerOneLine(array $line, $bdd)
{
    $importCSV = $bdd->prepare('INSERT INTO depenses(date,checkNum,transacID,amount) VALUES(:date,:checkNum,:transacID,:amount)');
    $importCSV->execute(array(
        'date' => $line['date'],
        'checkNum' => $line['check'],
        'transacID' => $line['id'],
        'amount' => $line['amount'],
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

function getNumberOfTransaction($bdd): int
{
    $requestNumberOfTransaction = $bdd->query('SELECT COUNT(*) as number FROM depenses');
    $numberOfTransaction = $requestNumberOfTransaction->fetch();
    return $numberOfTransaction['number'];
}
