let products = []; // Az összes termék tárolása
let currentPage = 1;
const itemsPerPage = 3; // Hány termék jelenjen meg egy oldalon

document.addEventListener("DOMContentLoaded", function () {
    loadProducts();
    updateCartCount();
});

// **1. Termékek betöltése PHP-ból AJAX segítségével**
function loadProducts() {
    fetch("products2.php")
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
    productList.innerHTML = ""; // Korábbi termékek törlése

    let start = (currentPage - 1) * itemsPerPage;
    let end = start + itemsPerPage;
    let paginatedItems = products.slice(start, end); // Lapozott termékek

    paginatedItems.forEach(product => {
        const productItem = document.createElement("div");
        productItem.classList.add("product");

        productItem.innerHTML = `
            
	    <h3>${product.name}</h3>
            <p>Ár: ${product.price} Ft</p>
            <button class="add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">Kosárba</button>
        `;

        productList.appendChild(productItem);
    });

    updatePagination();
    attachCartEventListeners(); //  Gombokhoz eseménykezelők hozzáadása
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

    // Összes termék darabszámának kiszámítása
    let totalItems = cart.reduce((sum, item) => sum + item.mennyiseg, 0);

    console.log("Frissített kosár darabszám:", totalItems); // Debug log

    // Frissítjük a HTML-ben a kosár számlálóját
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
}
