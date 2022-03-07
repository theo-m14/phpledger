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

function convertDate(string $date) : string{
    $timestamp = strtotime($date);
    $month = substr(getdate($timestamp)['month'],0,3);
    $day = substr($date,3,2);
    $year = substr($date,6,4);
    return $month . ' ' . $day . ',' . $year;
}

function checkSign(string $amount):string{
   $amount = convertAmount($amount);
   if($amount>0){
       return "positive";
   }else if($amount < 0){
       return 'negative';
   }else{
       return '';
   }
}

function convertAmount(string $amount):float{
    $amount = str_replace('$','',$amount);
    $amount = str_replace(',','',$amount);
    return (float)$amount;
}

function calculIncome(array $amount) : string{
    $sum = 0;
    for ($i=0; $i < count($amount) ; $i++) { 
       if(checkSign($amount[$i]) == "positive"){
           $sum += convertAmount($amount[$i]);
       }
    }
    return "$" . $sum;
}

function calculExpense(array $amount) : string{
    $sum = 0;
    for ($i=0; $i < count($amount) ; $i++) { 
       if(checkSign($amount[$i]) == "negative"){
           $sum += convertAmount($amount[$i]);
       }
    }
    return "$" . $sum;
}

function calculTotal(string $income,string $expense) : string{
    $income = str_replace('$','',$income);
    $expense = str_replace('$','',$expense);
    $total = (float)$income + (float)$expense;
    return "$" . $total;
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
