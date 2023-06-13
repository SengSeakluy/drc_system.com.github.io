$('.apiImportBtn').click(function() {
    $(this).css("opacity", '0.5');
    $(this).css("pointer-events", "none");
})
$(function(){

    var apiImportBtn = document.querySelector('.apiImportBtn');
    
    apiImportBtn.addEventListener("click", function() {
        apiImportBtn.innerHTML = "Processing";
        apiImportBtn.classList.add('spinning');
        
    setTimeout( 
            function  (){  
                apiImportBtn.classList.remove('spinning');
                apiImportBtn.innerHTML = "Import";
            }, 6000);
    }, false);
    
});