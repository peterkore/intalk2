document.addEventListener('DOMContentLoaded', () => {
    // Eseménykezelő a "add-to-cart-btn" osztályú gombokhoz
    document.addEventListener('click', (event) => {
        // Ellenőrizzük, hogy a kattintott elem tartalmazza-e a megfelelő osztályt
        if (event.target.classList.contains('add-to-cart-btn')) {
            // Lekérjük a data-id és data-quantity attribútumokat
            const productId = event.target.getAttribute('data-id');
            const quantity = event.target.getAttribute('data-quantity') ?? 1;

            if (productId && quantity) {
                // Meghívjuk a getData függvényt az URL-lel
                const url = `/cart/addtocart/${productId}`;
                sendData(url, {
                    quantity: quantity
                })
                .then(response => {
                    if(response?.message){
                        alert(response.message)
                    }else{
                        const quantityElement = document.getElementById('prodcuct-quantity')
                        quantityElement.textContent = parseInt(quantityElement.textContent) - parseInt(quantity)

                    }
                })
                .catch(error => alert('Hiba történt:', error.message));
            } else {
                console.error('Hiányzó attribútum(ok): data-id vagy data-quantity');
            }
        }
    });
});


const sendData = async (url, data = {}) => {
    try {
        // Adataidat query paraméterekké alakítjuk
        const queryString = new URLSearchParams(data).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;

        // GET kérés küldése
        const response = await fetch(fullUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        // Ellenőrizzük, hogy sikeres-e a válasz
        if (!response.ok) {
            console.error(`Hiba történt: ${response.status} ${response.statusText}`);
        }

        // Válasz JSON formátumban
        try {
            return await response.json();
        } catch (error) {}
    } catch (error) {
        console.error('Hiba a GET kérés során:', error);
        throw error;
    }
};

