document.getElementById("authForm").addEventListener("submit", function (e) {
    e.preventDefault();
    showLoader();

    fetch(this.action, {
        method: "POST",
        body: new FormData(this),
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            window.location.assign(data.redirect);
        } else if (data.type === "validation") {
            hideLoader();
            Object.entries(data.errors).forEach(([field, messages]) => {
                document.getElementById(field + "Error").textContent = messages[0];
                document.getElementById(field).classList.add("border-red-600!");
            });
        } else if (data.type === "auth") {
            hideLoader();
            document.querySelector("#globalError span").textContent = data.message;
            document.getElementById("globalError").style.maxHeight = "200px";
            setTimeout(() => {
                document.getElementById("globalError").style.maxHeight = "0px";
            }, 3000);
        }
    })
    .catch(xhr => {
        hideLoader();
        console.error("Server error:", xhr);
    });
});
