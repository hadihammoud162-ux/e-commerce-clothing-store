document.addEventListener('DOMContentLoaded', function () {
  const dropdowns = document.querySelectorAll('.dropdown')
  dropdowns.forEach((dropdown) => {
    dropdown.addEventListener('mouseenter', function () {
      this.querySelector('.dropdown-content').classList.add('show')
    })
    dropdown.addEventListener('mouseleave', function () {
      this.querySelector('.dropdown-content').classList.remove('show')
    })
  })

  const addToCartButtons = document.querySelectorAll('.add-to-cart')
  const removeFromCartButtons = document.querySelectorAll('.remove-from-cart')

  const cartCountElement = document.getElementById('cart-count')
  const cartItemsElement = document.getElementById('cart-items')
  const totalPriceElement = document.getElementById('total-price')

  const cartModal = document.getElementById('cart-contents')
  const cartItemsModal = document.getElementById('cart-items-modal')
  const totalPriceModal = document.getElementById('total-price-modal')

  function clearCart() {
    cartCount = 0
    cartItems = []
    totalPrice = 0
    updateCart()
    updateCartModal()
  }

  // Clear cart button event listener
  const clearCartButton = document.getElementById('clear-cart-button')
  if (clearCartButton) {
    clearCartButton.addEventListener('click', function () {
      if (confirm('Are you sure you want to clear the cart?')) {
        clearCart()
        alert('Cart cleared successfully.')
      }
    })
  }

  let cartCount = 0
  let cartItems = []
  let totalPrice = 0

  function saveCart() {
    let cartItemsString = ''
    for (let i = 0; i < cartItems.length; i++) {
      if (i > 0) {
        cartItemsString += ','
      }
      cartItemsString += cartItems[i].product + '|' + cartItems[i].price
    }
    localStorage.setItem('cartCount', cartCount)
    localStorage.setItem('cartItems', cartItemsString)
    localStorage.setItem('totalPrice', totalPrice)
  }

  function loadCart() {
    const savedCartCount = localStorage.getItem('cartCount')
    const savedCartItems = localStorage.getItem('cartItems')
    const savedTotalPrice = localStorage.getItem('totalPrice')

    if (savedCartCount) {
      cartCount = parseInt(savedCartCount, 10)
    }
    if (savedCartItems) {
      const itemsArray = savedCartItems.split(',')
      cartItems = []
      for (let i = 0; i < itemsArray.length; i++) {
        const item = itemsArray[i].split('|')
        cartItems.push({ product: item[0], price: parseFloat(item[1]) })
      }
    }
    if (savedTotalPrice) {
      totalPrice = parseFloat(savedTotalPrice)
    }

    updateCart()
    updateCartModal()
  }

  function updateCart() {
    cartCountElement.textContent = cartCount
    totalPriceModal.textContent = totalPrice.toFixed(2)
    saveCart()
  }

 function addToCart(product, price) {
  if (!product || !price) {
    alert('Please select a product to add to the cart.');
    return;
  }

  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    credentials: 'include',
    body: `product=${encodeURIComponent(product)}&price=${encodeURIComponent(price)}`,
  })
  .then(response => {
    if (!response.ok) {
      if (response.status === 401) {
        alert('Please log in to add items to your cart.');
        throw new Error('Not logged in');
      }
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
      if (data.success) {
    cartCount = data.cartCount;
    cartItems = data.cartItems;
    totalPrice = data.totalPrice;

    updateCart();
    updateCartModal();

    alert(`Added ${product} to cart! Total items: ${cartCount}`);
    } else {
      alert('Failed to add to cart.');
    }
  })
  .catch(error => {
    console.error('Error adding to cart:', error);
  });
}


  function removeFromCart(product, price) {
    const itemIndex = cartItems.findIndex((item) => item.product === product)
    if (itemIndex !== -1) {
      cartCount--
      totalPrice -= price
      cartItems.splice(itemIndex, 1)
      updateCart()
      updateCartModal()
    } else {
      alert('This item is not in the cart.')
    }
  }

  function updateCartModal() {
    cartItemsModal.innerHTML = ''
    for (let i = 0; i < cartItems.length; i++) {
      const item = cartItems[i]
      const li = document.createElement('li')
      li.textContent = item.product + ': $' + item.price.toFixed(2)
      cartItemsModal.appendChild(li)
      cartItemsModal.appendChild(document.createElement('br'))
    }
    if (cartCount === 0) {
      cartItemsModal.innerHTML = ''
    }
    totalPriceModal.textContent = totalPrice.toFixed(2)
  }

  for (let i = 0; i < addToCartButtons.length; i++) {
    addToCartButtons[i].addEventListener('click', function () {
      const product = this.dataset.product
      const price = parseFloat(this.dataset.price)
      addToCart(product, price)
    })
  }

  for (let i = 0; i < removeFromCartButtons.length; i++) {
    removeFromCartButtons[i].addEventListener('click', function () {
      const product = this.dataset.product
      const price = parseFloat(this.dataset.price)
      removeFromCart(product, price)
    })
  }

  const cartIcon = document.getElementById('cart-icon')
  if (cartIcon) {
    cartIcon.addEventListener('click', function () {
      cartModal.style.display = 'block'
    })
  }

  const closeBtn = document.querySelector('.close')
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      cartModal.style.display = 'none'
    })
  }

  // Optionally disable add-to-cart buttons if user is not logged in
  if (!isLoggedIn) {
    addToCartButtons.forEach(btn => {
      btn.disabled = true
      btn.title = 'Please log in to add items to cart'
    })
  }

  loadCart()
})
