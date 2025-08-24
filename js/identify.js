document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const emailInput = document.querySelector("input[name='txtEmail']");
    const emailConfirmInput = document.querySelector("input[name='txtEmailConfirm']");
    const divErro = document.getElementById("divErro");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const email = emailInput.value.trim();
        const emailConfirm = emailConfirmInput.value.trim();

        //Verifica se os e-mails são iguais
        if (email !== emailConfirm) {
            divErro.style.display = "block";
            divErro.textContent = "Os e-mails não coincidem.";
            return; 
        }
        
        divErro.style.display = "none";

        //Verifica se o e-mail existe no banco de dados
        fetch("php/identify.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "email=" + encodeURIComponent(email)
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                form.submit();
            } else {
                divErro.style.display = "block";
                divErro.textContent = "E-mail não cadastrado.";
            }
        })
        .catch(error => {
            console.error("Erro ao verificar o e-mail:", error);
            divErro.style.display = "block";
            divErro.textContent = "Ocorreu um erro ao verificar o e-mail. Tente novamente.";
        });
    });
});