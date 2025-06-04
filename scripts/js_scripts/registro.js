let checkbox = document.querySelector("#especialista");
let curLattes = document.querySelector("#lattes");
curLattes.style.display = "none";

checkbox.addEventListener("click", (ev) => {
    console.log(curLattes.getAttribute("display"))
    if (curLattes.style.display === 'none') {
        curLattes.style.display = "inline";
    } else {
        curLattes.style.display = "none";
    }
})