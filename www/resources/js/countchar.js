var text = document.querySelector('#projectDescription');
var result = document.querySelector('#characterCount');

if (text){
    text.addEventListener('keydown', function(){
        result.innerHTML = textarea.value.length + '/1000';
        console.log(textarea.value.length + '/1000'); 
    });
}

    