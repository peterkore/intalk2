let products = []; // Az összes termék tárolása
let currentPage = 1;
const itemsPerPage = 5; // Hány termék jelenjen meg egy oldalon

document.addEventListener("DOMContentLoaded", function () {
    loadProducts();
    updateCartCount();
    if (document.getElementByID("cart-list")) {
	displayCart();
	}
});

// **1. Termékek betöltése PHP-ból AJAX segítségével**
function loadProducts() {
    fetch("products.php")
        .then(response => response.json())
        .then(data => {
            products = data; // Elmentjük a termékeket
            displayProducts();
        })
        .catch(error => console.error("Hiba történt a termékek betöltésekor:", error));
}

// **2. Termékek megjelenítése**
function displayProducts() {
    const productList = document.getElementById("product-list");
    productList.innerHTML = ""; // Előző termékek törlése

    let start = (currentPage - 1) * itemsPerPage;
    let end = start + itemsPerPage;
    let paginatedItems = products.slice(start, end); // Az aktuális oldalon lévő termékek

    paginatedItems.forEach(product => {
        const productItem = document.createElement("div");
        productItem.classList.add("product");

        productItem.innerHTML = `
            <h3>${product.termek_nev}</h3>
            <p>Ár: ${product.termek_ara} Ft</p>
            <button class="add-to-cart" data-i ="${product.id}" data-name="${product.termek_nev}" data-price="${product.termek_ara}">Kosárba</button>
        `;

        productList.appendChild(productItem);
    });

    updatePagination();
    attachCartEventListeners(); // Fontos! Kosár gombokhoz event listener hozzáadása
}

// **3. Kosár gomb események hozzáadása (hogy működjön a dinamikusan betöltött gomboknál is)**
function attachCartEventListeners() {
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let nev = this.getAttribute("data-name");
            let ara = this.getAttribute("data-price");
            addToCart(id, nev, ara);
        });
    });
}

// **4. Termék hozzáadása a kosárhoz**
function addToCart(id, nev, ara) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    let price = parseFloat(ara); // Biztos, hogy az ár szám legyen

    if (isNaN(price)) {
        console.error(`Hiba: Az ár nem szám! (${ara})`); // Hibakeresés
        return;
    }

    let itemIndex = cart.findIndex(item => item.id === id);
    if (itemIndex !== -1) {
        cart[itemIndex].mennyiseg += 1;
    } else {
        cart.push({ id, nev, ara: price, mennyiseg: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    console.log("Kosár tartalma mentés után:", cart); // Ellenőrizzük, mi van a localStorage-ban

    updateCartCount();
    alert(`${nev} hozzáadva a kosárhoz!`);
}

// **5. Kosár ikon frissítése**
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.mennyiseg, 0);
    document.getElementById("cart-count").textContent = totalItems;
}

function viewCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart.length === 0) {
        alert("A kosár üres.");
        return;
    }

    console.log("Kosár tartalma:", cart); // Ellenőrzés

    let totalPrice = 0;
    let cartDetails = cart.map(item => {
        let itemTotal = parseFloat(item.ara) * item.mennyiseg; // Biztos, hogy szám legyen!

        if (isNaN(itemTotal)) {
            console.error(`Hiba: A számítás hibás! Termék: ${item.nev}, Ár: ${item.ara}`);
            return `${item.nev} - ${item.mennyiseg} db - HIBA!`;
        }

        totalPrice += itemTotal;
        return `${item.nev} - ${item.mennyiseg} db - ${itemTotal} Ft`;
    }).join("\n");

    alert("Kosár tartalma:\n" + cartDetails + `\nÖsszesen: ${totalPrice} Ft`);
}





// **6. Lapozás frissítése**
function updatePagination() {
    document.getElementById("page-number").textContent = currentPage;
    document.getElementById("prev-btn").disabled = currentPage === 1;
    document.getElementById("next-btn").disabled = currentPage * itemsPerPage >= products.length;
}

// **7. Előző oldal gomb működtetése**
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayProducts();
    }
}

// **8. Következő oldal gomb működtetése**
function nextPage() {
    if (currentPage * itemsPerPage < products.length) {
        currentPage++;
        displayProducts();
    }



document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("cart-list")) {
        displayCart();
    }
});

// **Kosár tartalmának megjelenítése a termekek.html oldalon**
function displayCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || []; // Kosár betöltése
    let cartList = document.getElementById("cart-list");
    let totalPriceElement = document.getElementById("total-price");

    // Ellenőrizzük, hogy a HTML elemek léteznek-e
    if (!cartList || !totalPriceElement) {
        console.error("Hiba: Nem található a cart-list vagy total-price elem!");
        return;
    }

    cartList.innerHTML = ""; // Korábbi elemek törlése
    let totalPrice = 0;

    if (cart.length === 0) {
        cartList.innerHTML = "<li>A kosár üres.</li>"; // Ha nincs termék
        totalPriceElement.textContent = "0 Ft";
        return;
    }

    cart.forEach((item, index) => {
        let itemTotal = parseFloat(item.ara) * item.mennyiseg;
        totalPrice += itemTotal;

        let listItem = document.createElement("li");
        listItem.innerHTML = `
            ${item.nev} - ${item.mennyiseg} db - ${itemTotal} Ft
            <button onclick="removeFromCart(${index})">❌</button>
        `;
        cartList.appendChild(listItem);
    });

    totalPriceElement.textContent = totalPrice + " Ft";
}

// **Termék eltávolítása a kosárból**
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.splice(index, 1); // Kivesszük az adott elemet a tömbből

    localStorage.setItem("cart", JSON.stringify(cart)); // Frissítjük a kosarat
    displayCart(); // Újratöltjük a kosár tartalmát
    updateCartCount(); // Frissítjük a kosár ikonját a főoldalon
}

// **Kosár teljes ürítése**
function clearCart() {
    localStorage.removeItem("cart");
    displayCart();
    updateCartCount();
}




}
