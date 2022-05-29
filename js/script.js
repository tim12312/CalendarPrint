document.getElementById('run').onclick = function(){
    var month = document.getElementById('month').value;
    var year = document.getElementById('year').value;
    window.open("/index.php/apps/calendarprint/print/"+year+"/"+month); 
    console.log(month);
};  