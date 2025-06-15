document.addEventListener("DOMContentLoaded", function () {
  const modal = document.querySelector("#modal-validar-post");
  const closeBtn = document.querySelector(".close");
  const form = document.querySelector("#form-validacao");
  let postId = ""
  let userId = ""

  document.querySelectorAll(".validateBtn").forEach((botao) => {
    botao.addEventListener("click", function () {
        postId = this.dataset.postId;
        userId = this.dataset.userId;
        modal.style.display = "flex";
    });
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append('post_id', postId);

    fetch('posts/validar_post.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            console.log(res)
            if (res.sucesso) {
                document.getElementById("modal-sucesso").style.display = "flex";
                document.getElementById("modal-mensagem").textContent = res.mensagem;
                modal.style.display = 'none';
                form.reset();
            } else {
                document.getElementById("modal-sucesso").style.display = "flex";
                document.getElementById("modal-mensagem").textContent = res.mensagem;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro na requisição.");
        });

  })

});
