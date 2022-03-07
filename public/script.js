document.getElementById('selectAction').addEventListener('change', function(e){
    if(e.target.value !== ''){
        document.getElementById('submitBtn').disabled = false;
    }else{
        document.getElementById('submitBtn').disabled = true;
    }
    if(e.target.value == "import"){
        document.getElementById('importFile').classList.remove('displayNone');
    }else{
        document.getElementById('importFile').classList.add('displayNone');
    }
})

let getNewTransactionForm = document.getElementById('newTransaction');
let getAllInputNewTransaction = document.querySelectorAll('#newTransaction input')
let getTransactionTable = document.querySelector('tbody');
let getAmountIncome = document.getElementById('amountIncome');
let getAmountExpense = document.getElementById('amountExpense');
let getTotal = document.getElementById('amountTotal');
let getFormSelectAction = document.getElementById('selectForm');
let getValueOfSelect = document.getElementById('selectAction');

//Listener on adding transaction
getNewTransactionForm.addEventListener('submit', registerTransaction);
getFormSelectAction.addEventListener('submit', function(e){
    e.preventDefault();
    if(getValueOfSelect.value == 'delete' || getValueOfSelect.value == 'import'){
        console.log('ça fonctionne')
        doSelectAction();
    }
})

function registerTransaction(event){
    event.preventDefault();
    let donnee = new FormData(getNewTransactionForm);
    fetch('public/index.php?action=registerTransaction',{
        method : 'POST',
        body: donnee
    }).then((response)=> {
        displayAllTransaction();
        getAllInputNewTransaction.forEach(input =>{
            input.value = '';
        })
        return response.text();
    }).then((reponse)=>{
        if(reponse !== '') console.log(reponse);
    }).catch(error =>{
        console.log('Erreur : ' + error.message);
    })
}

function doSelectAction(){
    let donnee = new FormData(getFormSelectAction);
    fetch('public/index.php?action=doSelectOption',{
        method : 'POST',
        body: donnee
    }).then((response)=> {
        displayAllTransaction();
        getValueOfSelect.value = '';
        document.getElementById('importFile').value = '';
        document.getElementById('importFile').classList.add('displayNone');
        return response.text();
    }).then((reponse)=>{
        if(reponse !== '') console.log(reponse);
    }).catch(error =>{
        console.log('Erreur : ' + error.message);
    })
}

function displayAllTransaction(){
    let jsonTransaction = '';
    let amountArray = [];
    fetch('public/index.php?action=getTransaction')
        .then(response => {
            return response.json();
        }).then(transactions =>{
            for(let element in transactions){
                jsonTransaction += `<tr><td>${convertDate(transactions[element]['date'])}</td><td>${transactions[element]['transacID']}</td><td class= ${checkSign(transactions[element]['amount'])}>${transactions[element]['amount']}</td></tr>`;
                amountArray.push(transactions[element]['amount']);
            }
            getTransactionTable.innerHTML = jsonTransaction;
            updateTotals(amountArray);
        }).catch(error =>{
            console.log('Erreur : ' + error.message);
        });
}

function updateTotals(amountArray){
    let expense = 0;
    let income = 0;
    for(element in amountArray){
        if(checkSign(amountArray[element]) == 'positive'){
            income += convertAmount(amountArray[element]);
        }else{
            expense += convertAmount(amountArray[element]);
        }
    }
    getAmountIncome.innerText = `$${income.toFixed(2)}`;
    getAmountExpense.innerText = `$${expense.toFixed(2)}`;
    let total = income+expense;
    getTotal.innerText = `$${total.toFixed(2)}`;
    if(checkSign(total.toString())== "positive"){
        getTotal.classList.remove('negative');
        getTotal.classList.add('positive');
    }else if(checkSign(total.toString())== "negative"){
        getTotal.classList.add('negative');
        getTotal.classList.remove('positive');
    }else{
        getTotal.classList.remove('negative');
        getTotal.classList.remove('positive');
    }
}

function checkSign(amount){
    amount = convertAmount(amount);
   if(amount>0){
       return "positive";
   }else if(amount < 0){
       return 'negative';
   }else{
       return '';
   }
}

function convertAmount(amount){
    amount = amount.replace('$','');
    amount = amount.replace(',','');
    return parseFloat(amount);
}

function convertDate(date){
    let allMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let month = allMonths[parseInt(date.slice(0,2))-1];
    let day = date.slice(3,5);
    let year = date.slice(6,10);
    return `${month} ${day},${year}`;
}

displayAllTransaction();