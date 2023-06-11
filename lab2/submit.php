<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laboratorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Error al conectar con la base de datos: " . $conn->connect_error);
}

$success_message = '';
$error_message = '';
$formSubmitted = isset($_POST['submit']); // Verificar si el formulario se envió

if ($formSubmitted) {
  // Verificar si el email ya está en la base de datos
  $email = $_POST['email'];

  $sql = "SELECT * FROM usuario WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<script>alert('El email ya está en la base de datos, introduzca otro.'); location.href= 'index.html';</script>";
  } else {
    // Validar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    $patterns = [
      'nombre' => '/^[a-zA-ZÀ-ÿ\s]{1,25}$/',
      'apellido1' => '/^[a-zA-ZÀ-ÿ\s]{1,40}$/',
      'apellido2' => '/^[a-zA-ZÀ-ÿ\s]{1,40}$/',
      'email' => '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
      'login' => '/^[a-zA-Z0-9\_\-]{4,16}$/',
      'password' => '/^.{4,8}$/'
    ];

    $hasErrors = false; // Variable para rastrear si se encontraron errores

    foreach ($patterns as $key => $pattern) {
      if (!preg_match($pattern, ${$key})) {
        $error_message = '<div class="error-message">Formato inválido para el campo ' . $key . '.</div>';
        $hasErrors = true; // Si se encontraron errores, establecemos la variable de errores en verdadero
      }
    }

    // Insertar los datos en la base de datos si no hay errores en la validación
    if (!$hasErrors) {
      try {
        $sql = "INSERT INTO usuario (nombre, apellido1, apellido2, email, login, password)
                VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$login', '$password')";

        if ($conn->query($sql) === TRUE) {
          $success_message = '<div class="success-message">Registro completado con éxito.</div>';
        } else {
          throw new Exception("Error al enviar el formulario: " . $conn->error);
        }
      } catch (Exception $e) {
        $error_message = '<div class="error-message">' . $e->getMessage() . '</div>';
      }
    }
  }
}

$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>

<head>
  <title>Tabla de Registros</title>
  <!-- Llamamos a la api de google para usar Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap" rel="stylesheet">
  <!-- Llamamos a la librería de boostrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style-submit.css">
</head>

<body>
  <!-- Estilo del fondo animado (ver css) -->
  <div class="bg"></div>
  <div class="bg bg2"></div>
  <div class="bg bg3"></div>

  <div class="tabla">
    <h2>Tabla de Registros</h2>
    <table>
      <tr>
        <th>Nombre</th>
        <th>Primer Apellido</th>
        <th>Segundo Apellido</th>
        <th>Email</th>
        <th>Login</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
          <td><?php echo $row['nombre']; ?></td>
          <td><?php echo $row['apellido1']; ?></td>
          <td><?php echo $row['apellido2']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['login']; ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <button class="btn btn-primary custom-btn" onclick="showTable()">Consulta</button>
    <button class="btn btn-primary custom-btn" onclick="hideTable()">Ocultar</button>
    <button class="btn btn-primary custom-btn" onclick="goToFormPage()">Volver al Formulario</button>

    <script>
      function showTable() {
        document.querySelector('table').style.display = 'table';
      }

      function hideTable() {
        document.querySelector('table').style.display = 'none';
      }

      function goToFormPage() {
        window.location.href = "/lab2/index.html";
      }
    </script>

    <br><br>

    <?php echo $success_message; ?> <!-- Muestra el mensaje de éxito -->
    <?php echo $error_message; ?> <!-- Muestra el mensaje de error que corresponda -->

</body>

</html>
