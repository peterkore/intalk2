
document.addEventListener("DOMContentLoaded", loadCartContent);

function loadCartContent() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartContainer = document.getElementById("cart-container");
    cartContainer.innerHTML = ""; 

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>A kosár üres.</p>";
        return;
    }

    cart.forEach(item => {
        const itemDiv = document.createElement("div");
        itemDiv.classList.add("cart-item");
        itemDiv.innerHTML = `
            <strong>${item.nev}</strong>
            - Ár: ${item.ara} Ft
            - Mennyiség: ${item.mennyiseg}
        `;
        cartContainer.appendChild(itemDiv);
    });
}

//  Kosár törlés
function clearCart() {
    localStorage.removeItem("cart");
    loadCartContent(); // Frissítés
}

document.getElementById("submit-order").addEventListener("click", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        alert("A kosár üres!");
        return;
    }

    fetch("megrendeles.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(cart)
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        alert("Megrendelés elküldve!");
        localStorage.removeItem("cart"); // Kosár ürítése
    })
    .catch(error => console.error("Hiba:", error));
});

