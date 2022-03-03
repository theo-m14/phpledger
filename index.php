<?php
        include('src/bddcall.php');
        include('functions.php');
        $bdd = bddcall();
         if(isset($_FILES['fichier'])){
            if(isset($_POST['delete']) && $_POST['delete'] == true){
                $bdd -> query('DELETE FROM depenses WHERE id > 0');
            }else{
                $origine = $_FILES['fichier']['tmp_name'];
                $destination = 'fichiers/'.$_FILES['fichier']['name'];
                move_uploaded_file($origine,$destination);
                $convertData = parseCSV($destination);
                importCSV($convertData,$bdd);
            }
        } 
        $allTransaction = catchAllTransaction($bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Suivi de dépense</title>
</head>
<body>
        <form action="index.php" method="post" id='import' enctype='multipart/form-data'>
            <input type="file" name="fichier" id="file" >
            <div>
                <label for="">DELETE</label>
            <input type="checkbox" name="delete" id="delete">
            </div>
            <input type="submit" value="UPDATE" name="import">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $amountArray = [];
                    $requestNumberOfTransaction =$bdd -> query('SELECT COUNT(*) as number FROM depenses');
                    $numberOfTransaction = $requestNumberOfTransaction -> fetch();
                    for ($i=0; $i < $numberOfTransaction['number'] ; $i++) {
                        $currentTransaction = $allTransaction -> fetch();
                        $amountArray[$i] = $currentTransaction['amount'];
                         echo "<tr><td>" . convertDate($currentTransaction['date']) . '</td><td>' . $currentTransaction['transacID'] . '</td><td class =' . checkSign($currentTransaction['amount']) . '>' . $currentTransaction['amount'] . '</td></tr>';
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total Income</td>
                    <td><?php echo calculIncome($amountArray); ?></td>
                </tr>
                <tr>
                    <td colspan="2">Total Expense</td>
                    <td><?php echo calculExpense($amountArray); ?></td>
                </tr>
                <tr>
                    <td colspan="2">Net Total</td>
                    <td><?php echo calculTotal(calculIncome($amountArray),calculExpense($amountArray)); ?></td>
                </tr>
            </tfoot>
        </table>
        <?php
        ?>
</body>
</html>