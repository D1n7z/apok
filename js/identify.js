document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const email = document.querySelector("input[name='txtEmail']");
    const emailConfirm = document.querySelector("input[name='txtEmailConfirm']");
    const divErro = document.getElementById("divErro");

    form.addEventListener("submit", function(event) {
        if (email.value.trim() !== emailConfirm.value.trim()) {
            divErro.style.display = "block";
            divErro.textContent = "Os e-mails não coincidem.";
            event.preventDefault();
        } else {
            divErro.style.display = "none";
        }
    });

    fetch("php/identify.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "email=" + encodeURIComponent(email.value.trim())
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            // E-mail existe no banco
        } else {
            // E-mail não existe ou erro
        }
    })
    .catch(error => {
        // Trate o erro
    });
});