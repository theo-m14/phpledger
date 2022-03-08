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
    <div id="errorMessage"><p></p></div>
    <form action="" method="post" enctype='multipart/form-data' id="selectForm">
        <select name="action" id="selectAction">
            <option value="">Choissiser une action</option>
            <option value="delete">Supprimer la bdd</option>
            <option value="import">Importer un CSV</option>
        </select>
        <input type="file" name="fichier" id="importFile" class="displayNone">
        <input type="submit" value="UPDATE" name="import" id="submitBtn" disabled>
    </form>
    <a href="./src/dowloadexport.php" target="_blank" id="exportCSV">Export CSV</a>
    <form action="" method="post" id="newTransaction">
        <label for="date">Date de la transaction</label>
        <input type="date" name="date" id="" required>
        <label for="transacID">Numéro de la transaction</label>
        <input type="number" name="transacID" id="" required>
        <label for="amount">Montant de la transaction</label>
        <input type="number" name="amount" id="" required>
        <button type="submit">Enregistrer</button>
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
            <!-- TRANSACTIONS GO HERE -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Income</td>
                <td id="amountIncome" class="positive"></td>
            </tr>
            <tr>
                <td colspan="2">Total Expense</td>
                <td id="amountExpense" class="negative"></td>
            </tr>
            <tr>
                <td colspan="2">Net Total</td>
                <td id="amountTotal"></td>
            </tr>
        </tfoot>
    </table>
    <?php
    ?>
    <script src="./public/script.js"></script>
</body>

</html>