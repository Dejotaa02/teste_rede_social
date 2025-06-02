forms = document.getElementById("form-redefinir");
forms.addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  const response = await fetch("?rota=submit_redefinir_senha", {
    method: "POST",
    body: formData,
  });

  const result = await response.json();
  console.log(result.mensagem);
  if (result.status === "ok") {
    // Mostra o modal
    document.getElementById("modal-sucesso").style.display = "block";
    document.getElementById("modal-mensagem").textContent = result.mensagem;
    console.log(result.mensagem);

    
    setTimeout(() => {
      window.location.href = "index.php?rota=verificar_codigo";
    }, 1000);
  } else {
    console.log(result.mensagem);
    document.getElementById("modal-sucesso").style.display = "block";
    document.getElementById("modal-mensagem").textContent = result.mensagem;
  }
});
