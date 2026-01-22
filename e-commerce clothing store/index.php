
<?php
session_start();  // Start the session at the very top
$loggedIn = isset($_SESSION['user_id']);  // Or whichever session variable you use for login
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WoMen - Fashion Store</title>
    <link rel="stylesheet" href="style.css" />
    <script>
  
</script>

  </head>
  <body>
    <header>
      <nav>
        <ul>
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
          <li><?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
  <div style="margin: 20px 0;">
    <a href="add_product.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
      + Add Product
    </a>
  </div>
<?php endif; ?>
</li>
          
          <!-- in order to check if the user is logged in -->
          
          <?php if (isset($_SESSION['email'])) {
              echo '<li><a href="#">Welcome, ' .
                  $_SESSION['email'] .
                  '</a></li>';
              echo '<li><a href="logout.php">Logout</a></li>';
          } else {
              // If the user is not logged in, show login and signup buttons
              echo '<li><a href="#" onclick="document.getElementById(\'loginModal\').style.display=\'block\'">Login</a></li>';
              echo '<li><a href="#" onclick="document.getElementById(\'signupModal\').style.display=\'block\'">Signup</a></li>';
          } ?>
        </ul>
      </nav>
    </header>
    <!-- form for the login -->
    <div id="loginModal" class="modal">
      <span
        onclick="document.getElementById('loginModal').style.display='none'"
        class="close"
        title="Close Modal"
      >
        &times;
      </span>
      <form class="modal-content animate" action="login.php" method="post">
        <div class="imgcontainer">
          <span
            onclick="document.getElementById('loginModal').style.display='none'"
            class="close"
            title="Close Modal"
          >
            &times;
          </span>
        </div>

        <div class="container">
          <label for="uname"><b>Email</b></label>
          <input
            type="email"
            placeholder="Enter your Email"
            name="email"
            style="height: 30px; width:400px; font-size: 16px; padding: 8px;"
      
            required
          />
<br><br>
          <label for="psw"><b>Password</b></label>
          <input
            type="password"
            placeholder="Enter Password"
            name="psw"
            required
          />

          <button type="submit">Login</button>
          
        </div>

        <div class="container" style="background-color: #f1f1f1;">
          <button
            type="button"
            onclick="document.getElementById('loginModal').style.display='none'"
            class="cancelbtn"
          >
            Cancel
          </button>
          
        </div>
      </form>
    </div>

    <!-- form for the signup -->
    <div id="signupModal" class="modal">
      <span
        onclick="document.getElementById('signupModal').style.display='none'"
        class="close"
        title="Close Modal"
      >
        &times;
      </span>
      
      <form class="modal-content" action="signup.php" method="post" onsubmit="return validateForm()">
    <div class="container">
        <h1>Sign Up</h1>
        <p>Please fill in this form to create an account.</p>
        <hr />
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="email" id="email" required />

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required />

        <label for="psw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required />

        <p>By creating an account you agree to our Terms & Privacy.</p>

        <div class="clearfix">
            <button type="button" onclick="closeModal()" class="cancelbtn">Cancel</button>
            <button type="submit" class="signupbtn">Sign Up</button>
        </div>
    </div>
</form>
    </div>


    

    <div class="image-container">
      <img src="WoMen.jpeg" alt="Header Image" class="header-image" />
    </div>

    <section id="about">
      <div class="about-content">
        <h2>About Us</h2>
        <p>
          Welcome to WoMen - your ultimate destination for the latest fashion
          trends and styles. At WoMen, we're passionate about helping you
          express your unique sense of style through our curated collection of
          clothing and accessories.
        </p>
        <p>
          Our mission is to provide high-quality, on-trend fashion at affordable
          prices, making it easy for you to stay stylish and confident every
          day. Whether you're shopping for casual everyday wear, chic formal
          attire, or trendy accessories to complete your look, we've got you
          covered.
        </p>
        <p>
          With a wide range of options for women, men, and kids, WoMen offers
          something for everyone, no matter your age, size, or personal style.
          Our constantly updated inventory ensures that you'll always find the
          latest styles and hottest trends right here.
        </p>
        <p>
          At WoMen, we believe that fashion is more than just clothing - it's a
          form of self-expression and empowerment. We're here to inspire and
          empower you to look and feel your best, whether you're stepping out
          for a night on the town or lounging at home in style.
        </p>
        <p>
          Thank you for choosing WoMen as your go-to fashion destination. We're
          excited to embark on this stylish journey with you!
        </p>
      </div>
    </section>

    <footer>
      <div class="footer-content">
        <p>&copy; 2025 WoMen. All rights reserved.</p>
        <p>Contact: contact@womenfashion.com</p>
      </div>
    </footer>







    <script>

document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                alert('Invalid username or password. Please try again.');
            }
        });
      
      var loginModal = document.getElementById('loginModal')
      var signupModal = document.getElementById('signupModal')

      
      window.onclick = function (event) {
        if (event.target == loginModal || event.target == signupModal) {
          loginModal.style.display = 'none'
          signupModal.style.display = 'none'
        }
      }

      function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("psw").value;
        var repeatPassword = document.getElementById("psw-repeat").value;

        if (!isValidEmail(email)) {
            alert("Please enter a valid email address");
            return false;
        }

        if (password.length < 8) {
            alert("Password must be at least 8 characters long");
            return false; 
        }

        if (password !== repeatPassword) {
            alert("Passwords do not match");
            return false; 
        }

        return true; 
    }

    function isValidEmail(email) {
        // Regular expression for validating email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function closeModal() {
        document.getElementById('signupModal').style.display='none';
    }
    
    </script>


    <script src="script.js"></script>
  </body>
</html>
