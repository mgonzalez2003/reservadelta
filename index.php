<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Ceibalita - Liceo Delta el Tigre</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="icon.png" type="image/x-icon">
    <script>
        window.onload = function () {
            var fecha = new Date(); //Fecha actual
            var mes = fecha.getMonth() + 1; //obteniendo mes
            var dia = fecha.getDate(); //obteniendo dia
            var ano = fecha.getFullYear(); //obteniendo año
            if (dia < 10)
                dia = '0' + dia; //agrega cero si el menor de 10
            if (mes < 10)
                mes = '0' + mes //agrega cero si el menor de 10
            document.getElementById('fecha').value = ano + "-" + mes + "-" + dia;
        }
    </script>
</head>

<body>
    <header>
        <h1>Reserva de Ceibalita - Liceo Delta el Tigre</h1>
    </header>
    <main>
        <section class="manual">
            <h2>¿Cómo reservar una Ceibalita?</h2>
            <p>¡Bienvenido al manual de reserva de las Ceibalitas del Liceo Delta el Tigre! Sigue los pasos a
                continuación para reservar una Ceibalita:</p>
            <ol>
                <li><strong>Selecciona un día:</strong> Utiliza el calendario a continuación para elegir la fecha en la
                    que deseas reservar una Ceibalita.</li>
                <li><strong>Buscar disponibilidad:</strong> Haz clic en el botón "Buscar" para verificar la
                    disponibilidad de las Ceibalitas para la fecha seleccionada.</li>
                <li><strong>Reservar la Ceibalita:</strong> Una vez que hayas verificado la disponibilidad, selecciona
                    la hora y la Ceibalita que deseas reservar e ingresa tu nombre en el campo correspondiente. Luego,
                    confirma la reserva presionando en el botón verde("Guardar cambios").</li>
            </ol>
            <p><strong>Ten en cuenta:</strong> Las reservas solamente están configuradas de Lunes a Viernes.</p>
            <h2>Ingrese fecha a reservar:</h2>
            <form action="./Reserva/reservar.php" method="get">
                <input type="date" id="fecha" name="fecha" required>
                <input type="submit" id="botonbuscar" value="Buscar">
            </form>
        </section>
    </main>
    <script>
        document.getElementById("fecha").addEventListener("change", function () {
            var fechaSeleccionada = this.value;
            console.log(fechaSeleccionada);
        });
    </script>
</body>

</html>