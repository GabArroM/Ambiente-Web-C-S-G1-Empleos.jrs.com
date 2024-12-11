document.getElementById("loginButton").addEventListener("click", function(event) {
    event.preventDefault();  
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!email || !password) {
        alert("Por favor, ingresa tu correo y contraseña.");
        return;
    }

    fetch("iniciar_sesion.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `email=${email}&password=${password}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.tipoUsuario === "Junior") {
                window.location.href = "ModSolicitantes.html";
            } else if (data.tipoUsuario === "Empleador") {
                window.location.href = "ModEmpleados.html";
            }
        } else {
            alert(data.message); 
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
    });
});
