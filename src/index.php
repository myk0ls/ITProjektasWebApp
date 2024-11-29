
<!DOCTYPE html>
<html>
<head>
  <title>Prisijungti</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="login-container">
    <h2>Prisijungti</h2>
    <form action="login.php" method="post">
    <label for="username">Įveskite vartotojo vardą:</label>
      <input type="text" id="username" name="username" placeholder="Vartotojo vardas" required>

      <label for="password">Įveskite slaptažodį:</label>
      <input type="password" id="password" name="password" placeholder="Slaptažodis" required>

      <div class="button-container">
      <button type="submit">Prisijungti</button>
    
      </div>
    </form>

      <div class="button-container">
      <form action="registration.php">
          <button type="submit">Sukurti naują paskyrą</button>
      </form>

     <!-- Error message -->
     <?php
     // Check if the error query parameter exists and equals "invalid"
     if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
         echo '<div class="error-message">Blogas slapyvardis arba slaptažodis. Pabandykite iš naujo.</div>';
     }
     ?>
  </div>
</body>
</html>