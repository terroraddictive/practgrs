document.querySelector("#show_add_photo").addEventListener("click", function () {
    document.querySelector("#add_new_photo").classList.add("open");
});

document.querySelector("#cancel").addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector("#add_new_photo").classList.remove("open");
});