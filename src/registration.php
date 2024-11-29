<!DOCTYPE html>
<html>
<head>
  <title>Registracija</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="login-container">
    <h2>Registracija</h2>
    <form action="register.php" method="post">
      <label for="username">Įveskite vartotojo vardą:</label>
      <input type="text" id="username" name="username" placeholder="Vartotojo vardas" required>

      <label for="password">Įveskite slaptažodį:</label>
      <input type="password" id="password" name="password" placeholder="Slaptažodis" required>

      <label for="email">Įveskite elektroninį paštą:</label>
      <input type="text" id="email" name="email" placeholder="Elektroninis paštas" required>

      <button type="submit">Registruotis</button>
    </form>

    <form action="index.php">
        <button type="submit">Atgal</button>
    </form>

     <!-- Error message -->
     <?php
     // Check if the error query parameter exists and equals "invalid"
     if (isset($_GET['error']) && $_GET['error'] === 'exists') {
         echo '<div class="error-message">Vartotojas su duotu vardu jau yra sukurtas. Pabandykite iš naujo.</div>';
     }
     if (isset($_GET['error']) && $_GET['error'] === 'dberror') {
        echo '<div class="error-message">Duomenų bazės klaida. Pabandykite iš naujo.</div>';
     }
     ?>
  </div>
</body>
</html>