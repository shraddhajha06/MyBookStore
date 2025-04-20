let cart = [];

// Load cart from backend
async function loadCart() {
  try {
    const response = await fetch('cart_api.php');
    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = 'login.html';
        return;
      }
      throw new Error('Failed to load cart');
    }
    cart = await response.json();
    updateCart();
  } catch (error) {
    console.error('Error:', error);
    showNotification('Failed to load cart');
  }
}

function updateCart() {
  const cartItems = document.getElementById('cart-items');
  const subtotalPrice = document.getElementById('subtotal-price');
  const taxAmount = document.getElementById('tax-amount');
  const totalPrice = document.getElementById('total-price');
  
  cartItems.innerHTML = '';  // Clear current items
  let subtotal = 0;

  if (cart.length === 0) {
    cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
  } else {
    cart.forEach(item => {
      const itemDiv = document.createElement('div');
      itemDiv.classList.add('cart-item');
      
      itemDiv.innerHTML = `
        <img src="${item.image || 'Images/default-book.jpg'}" alt="${item.title}" class="item-image">
        <div class="item-details">
          <p class="item-name">${item.title}</p>
          <p class="item-author">${item.author}</p>
          <p class="item-price">$${item.price.toFixed(2)}</p>
          <div class="quantity-controls">
            <button class="quantity-btn" onclick="updateQuantity(${item.book_id}, ${item.quantity - 1})">-</button>
            <input type="number" class="quantity" value="${item.quantity}" min="1" 
                   onchange="updateQuantity(${item.book_id}, this.value)">
            <button class="quantity-btn" onclick="updateQuantity(${item.book_id}, ${item.quantity + 1})">+</button>
          </div>
        </div>
        <button class="remove-btn" onclick="removeItem(${item.book_id})">Remove</button>
      `;
      
      cartItems.appendChild(itemDiv);
      subtotal += item.price * item.quantity;
    });
  }

  const tax = subtotal * 0.1; // 10% tax
  const total = subtotal + tax;

  subtotalPrice.textContent = subtotal.toFixed(2);
  taxAmount.textContent = tax.toFixed(2);
  totalPrice.textContent = total.toFixed(2);
}

async function addItem(bookId, quantity = 1) {
  try {
    const response = await fetch('cart_api.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        book_id: bookId,
        quantity: quantity
      })
    });

    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = 'login.html';
        return;
      }
      throw new Error('Failed to add item to cart');
    }

    await loadCart();
    showNotification('Item added to cart');
  } catch (error) {
    console.error('Error:', error);
    showNotification('Failed to add item to cart');
  }
}

async function updateQuantity(bookId, newQuantity) {
  newQuantity = parseInt(newQuantity);
  if (newQuantity < 1) newQuantity = 1;
  
  try {
    const response = await fetch('cart_api.php', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        book_id: bookId,
        quantity: newQuantity
      })
    });

    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = 'login.html';
        return;
      }
      throw new Error('Failed to update quantity');
    }

    await loadCart();
  } catch (error) {
    console.error('Error:', error);
    showNotification('Failed to update quantity');
  }
}

async function removeItem(bookId) {
  try {
    const response = await fetch('cart_api.php', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        book_id: bookId
      })
    });

    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = 'login.html';
        return;
      }
      throw new Error('Failed to remove item');
    }

    await loadCart();
    showNotification('Item removed from cart');
  } catch (error) {
    console.error('Error:', error);
    showNotification('Failed to remove item');
  }
}

function showNotification(message) {
  const notification = document.getElementById('notification');
  const notificationMessage = document.getElementById('notification-message');
  
  notificationMessage.textContent = message;
  notification.style.display = 'block';
  
  setTimeout(() => {
    notification.style.display = 'none';
  }, 3000);
}

async function checkout() {
  if (cart.length === 0) {
    showNotification('Your cart is empty!');
    return;
  }

  try {
    const response = await fetch('checkout.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(cart)
    });

    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = 'login.html';
        return;
      }
      throw new Error('Checkout failed');
    }

    showNotification('Checkout successful!');
    await loadCart();
  } catch (error) {
    console.error('Error:', error);
    showNotification('Failed to complete checkout');
  }
}

// Initialize cart on page load
loadCart();
