forms = document.getElementById("form-redefinir");
forms.addEventListener("submit", async function (e) {
  e.preventDefault();
    const codigo = document.querySelector("#codigo").value

    if(!codigo) {
    document.getElementById("modal-sucesso").style.display = "flex";
    document.getElementById("modal-mensagem").textContent = "Insira o código para redefinir a senha";
    return
    }

  const form = e.target;
  const formData = new FormData(form);
  const response = await fetch("?rota=submit_verificar_codigo", {
    method: "POST",
    body: formData,
  });

  const result = await response.json();
  console.log(result.mensagem);
  if (result.status === "ok") {
    // Mostra o modal
    document.getElementById("modal-sucesso").style.display = "flex";
    document.getElementById("modal-mensagem").textContent = result.mensagem;
    console.log(result.mensagem);
    setTimeout(() => {
      window.location.href = "index.php?rota=login";
    }, 1000);
  } else {
    console.log(result.mensagem);
    document.getElementById("modal-sucesso").style.display = "flex";
    document.getElementById("modal-mensagem").textContent = result.mensagem;
  }

})