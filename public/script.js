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