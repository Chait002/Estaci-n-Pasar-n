<?php
$servername = "localhost"; // Dirección del servidor de la base de datos
$dBUsername = "id20857778_pasarondb"; // Nombre de usuario de la base de datos
$dBPassword = "ncr7y82tA3%"; // Contraseña de la base de datos
$dBName = "id20857778_pasarondb"; // Nombre de la base de datos

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName); // Conexión a la base de datos

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Verificar si la conexión ha fallado
}

if (isset($_POST['toggle_LED'])) { // Verificar si se ha enviado el formulario para cambiar el estado del LED
    $sql = "UPDATE LED_status SET status = IF(status = 0, 1, 0) WHERE id = 1;"; // Consulta para cambiar el estado del LED en la base de datos
    mysqli_query($conn, $sql); // Ejecutar la consulta
}

$sql = "SELECT * FROM LED_status;"; // Consulta para obtener el estado actual del LED
$result = mysqli_query($conn, $sql); // Ejecutar la consulta
$row = mysqli_fetch_assoc($result); // Obtener el resultado de la consulta
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estación Pasarón</title>
    <link rel="shortcut icon" href="icon.svg">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
</head>

<body>
    <div class="hero">
      <div clas="mega-container">
        <nav class="menu">
            <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open"/>
            <label class="menu-open-button" for="menu-open">
                <span class="hamburger hamburger-1"></span>
                <span class="hamburger hamburger-2"></span>
                <span class="hamburger hamburger-3"></span>
            </label>
            <a href="" class="menu-item"> <i class="fa-sharp fa-solid fa-code"></i></i></a>
            <a href="#" class="menu-item"><i class="fa-solid fa-file"></i> </a>
            <a class="menu-item"><i class="fa-sharp fa-solid fa-house"></i> </a>
        </nav>
        <div class="github">
            <a href="https://github.com/tu-usuario" target="_blank">
                <img src="github-logo.svg" alt="GitHub" width="45" height="45">
            </a>
        </div>
        <form action="index.php" method="post" id="LED" enctype="multipart/form-data">
            <button id="submit_button" type="submit" name="toggle_LED" value="Toggle LED" class="switch-btn <?= ($row['status'] == 1) ? 'on' : '' ?>" onclick="toggleSwitch()">
                <span class="circle"></span>
                <span class="switch-text"><?= ($row['status'] == 1) ? 'ON' : 'OFF' ?></span>
            </button>
        </form>
        
        <script type="text/javascript">
            $(document).ready(function() {
                var updater = setInterval(function() {
                    $('section.container').load('index.php section.container');
                }, 1000);
            });

            <?php if ($row['status'] == 1) { ?>
            $(document).ready(function() {
                $('.switch-btn').addClass('on');
            });
            <?php } ?>
        </script>
        <h1><?= $row['status'] ?></h1>
      </div>
    </div>
    <footer>
      <script src="https://kit.fontawesome.com/affce8d235.js" crossorigin="anonymous"></script>
      <script>
            function toggleSwitch() {
                const switchBtn = document.querySelector('.switch-btn');
                const switchText = document.querySelector('.switch-text');

                switchBtn.classList.toggle('on');
                switchText.textContent = (switchBtn.classList.contains('on')) ? 'ON' : 'OFF';
            }
        </script>
    </footer>
</body>

</html>