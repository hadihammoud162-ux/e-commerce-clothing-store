<?php
session_start();
include 'db.php'; // Make sure this sets $conn
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>hats</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    .allcolumns {
      display: flex;
      flex-wrap: wrap;
    }
    .c {
      flex: 0 0 20%;
      padding: 10px;
    }
    .img {
      width: 200px;
      height: 300px;
      object-fit: cover;
    }
    .p1 {
      font-size: large;
      font-family: Georgia, 'Times New Roman', Times, serif;
    }
  </style>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="index.php"><i class="fas fa-home"></i></a></li>
        <li class="dropdown">
          <a href="#" class="dropbtn">Women</a>
          <div class="dropdown-content">
            <a href="dresses.php">Dresses</a>
            <a href="top.php">Tops</a>
            <a href="pants.php">Pants</a>
            <a href="WomenShoes.php">Shoes</a>
          </div>
        </li>
        <li class="dropdown">
          <a href="#" class="dropbtn">Men</a>
          <div class="dropdown-content">
            <a href="shirts.php">Shirts</a>
            <a href="pantm.php">Pants</a>
            <a href="menshoes.php">Shoes</a>
          </div>
        </li>
        <li class="dropdown">
          <a href="#" class="dropbtn">Accessories</a>
          <div class="dropdown-content">
            <a href="bags.php">Bags</a>
            <a href="hat.php">Hats</a>
          </div>
        </li>
        <li><a href="index.php#about">About</a></li>
        <li class="cart-summary">
          <a href="#" id="cart-icon">&#128722; <span id="cart-count">(0)</span></a>
          <div id="cart-contents" class="modal">
            <div class="modal-content">
              <span class="close">&times;</span>
              <h3>Your Cart</h3>
              <ul class="cart-items" id="cart-items-modal"></ul>
              <p>Total: $<span id="total-price-modal">0.00</span></p>
              <button id="clear-cart-button">Clear Cart</button>
              <button id="place-order-button" onclick="placeOrder()">Place Order</button>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <div class="allcolumns">
    <?php
    $query = "SELECT * FROM products WHERE category_id = 4 AND subcategory_id = 9"; // Men Pants
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
    ?>
      <div class="c">
        <img src="uploads/<?= htmlspecialchars($row['image_url']) ?>" class="img" />
        <p class="p1">
          <?= htmlspecialchars($row['name']) ?><br />
          $<?= number_format($row['price'], 2) ?>
        </p>
        <button class="add-to-cart" data-product="<?= htmlspecialchars($row['name']) ?>" data-price="<?= $row['price'] ?>">+</button>
        <button class="remove-from-cart" data-product="<?= htmlspecialchars($row['name']) ?>" data-price="<?= $row['price'] ?>">−</button>
               <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="edit_product.php?product_id=<?= $row['id'] ?>">Edit</a>
      
  <form action="delete_product.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
      <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
      <button type="submit" style="margin-top: 5px; background-color: red; color: white;">🗑 Delete</button>
  </form>
    <?php endif; ?>
    
    
      </div>
    <?php
      endwhile;
    else:
      echo "<p>No products found.</p>";
    endif;
    ?>
  </div>

  <footer>
    <div class="footer-content">
      <p>&copy; 2024 WoMen. All rights reserved.</p>
      <p>Contact: contact@womenfashion.com</p>
    </div>
  </footer>

  <script>
  function clearCartAfterOrder() {
  cartCount = 0;
  cartItems = [];
  totalPrice = 0;

  // Update UI
  document.getElementById('cart-count').textContent = '(0)';
  document.getElementById('total-price-modal').textContent = totalPrice.toFixed(2);

  // Clear any cart items display as well
  const cartItemsModal = document.getElementById('cart-items-modal');
  if (cartItemsModal) {
    cartItemsModal.innerHTML = '';
  }

  // Clear localStorage if used
  localStorage.removeItem('cartCount');
  localStorage.removeItem('cartItems');
  localStorage.removeItem('totalPrice');
}
function placeOrder() {
  var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

  var cartItems = getCartItems(); // Make sure this gets current cart items from your client state
  if (cartItems.length === 0) {
    alert('Your cart is empty.');
    return;
  }

  if (!isLoggedIn) {
    alert('Please log in to place an order.');
    return;
  }

  // Send order request to server (adjust URL as needed)
  fetch('place_order.php', {
    method: 'POST',
    credentials: 'include', // to send cookies/session
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ cart: cartItems }) // optionally send cart data or let server read session cart
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Order placed!');

      // Clear client-side cart UI and state
      clearCart();
    } else {
      alert('Failed to place order: ' + (data.error || 'Unknown error'));
    }
  })
  .catch(err => {
    console.error('Order error:', err);
    alert('An error occurred while placing the order.');
  });
}

    function getCartItems() {
      return document.querySelectorAll('#cart-items-modal li');
    }

    function addToCart(product, price) {
      var cartItemsModal = document.getElementById('cart-items-modal');
      var newItem = document.createElement('li');
      newItem.textContent = product + ' - $' + price.toFixed(2);
      cartItemsModal.appendChild(newItem);
      updateCartCountAndTotal(price);
    }

    function updateCartCountAndTotal(price) {
      var cartCount = document.getElementById('cart-count');
      var totalPrice = document.getElementById('total-price-modal');
      var currentCount = parseInt(cartCount.textContent.replace(/[()]/g, '')) + 1;
      cartCount.textContent = '(' + currentCount + ')';
      var currentTotal = parseFloat(totalPrice.textContent) + parseFloat(price);
      totalPrice.textContent = currentTotal.toFixed(2);
    }

    function clearCart() {
      document.getElementById('cart-items-modal').innerHTML = '';
      document.getElementById('cart-count').innerText = '(0)';
      document.getElementById('total-price-modal').innerText = '0.00';
    }

    // Attach event listeners
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".add-to-cart").forEach(function(button) {
        button.addEventListener("click", function() {
          addToCart(this.dataset.product, parseFloat(this.dataset.price));
        });
      });

      document.querySelectorAll(".remove-from-cart").forEach(function(button) {
        button.addEventListener("click", function() {
          // Implement remove logic if needed
          alert("Remove logic not implemented.");
        });
      });

      document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("cart-contents").style.display = "none";
      });

      document.getElementById("cart-icon").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("cart-contents").style.display = "block";
      });

      document.getElementById("clear-cart-button").addEventListener("click", clearCart);
    });
  </script>

  <script src="script.js"></script>
</body>
</html>
