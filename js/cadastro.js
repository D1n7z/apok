const form = document.getElementById("forms"); 
form.addEventListener("submit", function(event){
    event.preventDefault()
    const nome = document.getElementById("nome").value.trim();
    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value;

    if (nome.length < 3) {
        alert("Nome deve ter pelo menos 3 caracteres.");
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("E-mail inválido.");
        return;
    }

    if (senha.length < 6) {
        alert("Senha deve ter pelo menos 6 caracteres.");
        return;
    }

    form.submit();
    alert("Cadastro realizado com sucesso!");
});

    