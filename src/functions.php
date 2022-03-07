<?php

function parseCSV($nomFichier){
    $openFile = fopen($nomFichier,'r');
    if($openFile !== false){
        $dataArray = [];
        $line = 0;
        while (($content = fgetcsv($openFile, null, ",")) !== false) {
            for ($i=0; $i <count($content); $i++) { 
               switch ($i) {
                   case 0:
                       $dataArray[$line]['date'] = $content[$i] ;
                       break;
                    case 1:
                        $dataArray[$line]['check'] = $content[$i] ;
                        break;
                    case 2:
                        $dataArray[$line]['id'] = $content[$i] ;
                        break;
                   default:
                       $dataArray[$line]['amount'] = $content[$i] ;
                       break;
               }
            }
            $line++;
        }
        return $dataArray;
    }else{
        return "Une erreur est survenue";
    }
}

function convertFormDate(string $date) : string{
    $year = substr($date,0,4);
    $month = substr($date,5,2);
    $day = substr($date,8);
    return $month . '/' . $day . "/" . $year;
}

function convertBddToCSV($bdd){
    $exportFile = fopen("php://output", "wb");
    $requestNumberOfTransaction =$bdd -> query('SELECT COUNT(*) as number FROM depenses');
    $numberOfTransaction = $requestNumberOfTransaction -> fetch();
    $allTransaction = catchAllTransaction($bdd);
    $arrayCSV = [];
    for ($i=0; $i < $numberOfTransaction['number']; $i++) { 
        $currentTransaction = $allTransaction -> fetch();
        $arrayCSV[$i] = array(
            'date' => $currentTransaction['date'],
            'check' => $currentTransaction['checkNum'],
            'transacID' => $currentTransaction['transacID'],
            'amount' => $currentTransaction['amount'] ,
        );
        fputcsv($exportFile, $arrayCSV[$i]);
    }
    fclose($exportFile);
}

function getAllTransaction($bdd){
    $queryTransaction = catchAllTransaction($bdd);
    $allTransaction = $queryTransaction->fetchAll();
    return json_encode($allTransaction);
}