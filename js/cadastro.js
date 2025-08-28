const form = document.getElementById("forms");
const emailInput = document.getElementById("email");
const emailConfirmInput = document.getElementById("emailConfirm");
const msg = document.getElementById("msg");

emailInput.addEventListener("input", function() {
    const emailValue = emailInput.value;
        fetch("./php/validate_email.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(emailValue)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            msg.textContent = "E-mail já cadastrado.";
            msg.style.color = "red";
        } else {
            msg.textContent = "E-mail disponível.";
            msg.style.color = "green";
        }
    })
    .catch(async error => {
        console.error("Erro ao validar e-mail:", error);
        const errText = await error.text?.();
        console.log("Resposta do servidor:", errText);
    });


form.addEventListener("submit", function(event){
    event.preventDefault(); 
    
    const nome = document.getElementById("nome").value.trim();
    const email = document.getElementById("email").value.trim();
    const emailConfirm = document.getElementById("emailConfirm").value.trim();
    const senha = document.getElementById("senha").value;
    const senhaConfirm = document.getElementById("senhaConfirm").value;


    if (email !== emailConfirm) {
        alert("Os e-mails não correspondem.");
        return; 
    }

    if (senha !== senhaConfirm) {
        alert("As senhas não correspondem.");
        return; 
    }
    

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