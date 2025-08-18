document.addEventListener('DOMContentLoaded', function(){
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');

    const div = document.getElementById('divErro');
    if(error === '1'){
        div.style.display = 'block';
    }
})

