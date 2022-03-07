<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/style.css">
    <title>Suivi de dépense</title>
</head>

<body>
    <form action="index.php" method="post" enctype='multipart/form-data'>
        <select name="action" id="selectAction">
            <option value="">Choissiser une action</option>
            <option value="delete">Supprimer la bdd</option>
            <option value="import">Importer un CSV</option>
        </select>
        <input type="file" name="fichier" id="importFile" class="displayNone">
        <input type="submit" value="UPDATE" name="import" id="submitBtn" disabled>
    </form>
    <a href="src/dowloadexport.php" target="_blank" id="exportCSV">Export CSV</a>
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
            for ($i = 0; $i < $numberOfTransaction; $i++) {
                $currentTransaction = $allTransaction->fetch();
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
                <td><?php echo calculTotal(calculIncome($amountArray), calculExpense($amountArray)); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
    ?>
    <script src="./public/script.js"></script>
</body>

</html>