let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCart() {
  const cartItems = document.getElementById('cart-items');
  const totalPrice = document.getElementById('total-price');
  
  cartItems.innerHTML = '';  // Clear current items
  let total = 0;

  cart.forEach(item => {
    const itemDiv = document.createElement('div');
    itemDiv.classList.add('cart-item');
    
    itemDiv.innerHTML = `
      <p>${item.name} - $${item.price}</p>
      <button onclick="removeItem(${item.id})">Remove</button>
    `;
    
    cartItems.appendChild(itemDiv);
    total += item.price;
  });

  totalPrice.textContent = total.toFixed(2);
}

function addItem(id, name, price) {
  cart.push({ id, name, price });
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCart();
}

function removeItem(id) {
  cart = cart.filter(item => item.id !== id);
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCart();
}

function checkout() {
  if (cart.length === 0) {
    alert('Your cart is empty!');
    return;
  }

  // Send cart data to PHP for checkout
  fetch('checkout.php', {
    method: 'POST',
    body: JSON.stringify(cart),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    alert('Checkout successful');
    localStorage.removeItem('cart');
    updateCart();
  })
  .catch(error => alert('Error during checkout'));
}

updateCart();
