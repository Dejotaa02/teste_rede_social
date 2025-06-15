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
});
