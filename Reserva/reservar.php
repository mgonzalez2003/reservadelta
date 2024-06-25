<?php
$date = $_GET["fecha"];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Ceibalita - Liceo Delta el Tigre</title>
    <link rel="shortcut icon" href="../icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const celdas = document.querySelectorAll('.reserva-vacia');
            const cambios = {};

            celdas.forEach(celda => {
                celda.addEventListener('dblclick', function () {
                    const contenidoActual = this.textContent;
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = contenidoActual.trim();
                    input.addEventListener('blur', () => {
                        const nuevoValor = input.value.trim();
                        this.textContent = nuevoValor;
                        const idCelda = this.dataset.idCelda; // Obtener el ID de la celda
                        cambios[idCelda] = nuevoValor; // Guardar el cambio en el objeto cambios
                        console.log(`Cambio registrado: ${idCelda} = ${nuevoValor}`);
                    });
                    this.textContent = '';
                    this.appendChild(input);
                    input.focus();
                });
            });

            document.getElementById('guardarCambios').addEventListener('click', function () {
                console.log('Guardando cambios:', cambios);
                guardarCambios(cambios);
            });
            document.getElementById('actualizarPagina').addEventListener('click', function () {
                Swal.fire({
                    title: "Estás seguro?",
                    text: "Los datos cargados podrían perderse si no los guardas!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, actualizar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
        });

        function guardarCambios(cambios) {
            fetch('guardar.php?fecha=<?php echo $date; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(cambios)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Datos guardados correctamente');
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus cambios fueron guardados correctamente!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        console.error('Error al guardar los datos:', data.message);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Algo salió mal!",
                            footer: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo salió mal!",
                        footer: error
                    });
                });
        }
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const fecha = urlParams.get('fecha');
            if (fecha) {
                const partesFecha = fecha.split("-");
                const fechaReordenada = `${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`;

                document.getElementById("fechaReordenada").textContent = fechaReordenada;
            }
        });
    </script>
</head>

<body>
    <header>
        <h1>Reserva de Ceibalita - Liceo Delta el Tigre</h1>
    </header>
    <main>
        <section class="horario">
            <div class="botones">
                <input type="button" onclick="history.back()" value="Volver atrás">
                <input type="button" id="actualizarPagina" value="Actualizar">
                <input type="button" id="guardarCambios" value="Guardar Cambios">
            </div>
                <h2>Horario de Reserva</h2>
                <h4 id="fechaReordenada"></h4><p>Por favor, no borres las reservas de otras personas. Respeta los horarios y nombres de reserva de los demás usuarios.</p>
            <table>
                <thead>
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <th>Ceibalita <?php echo $i; ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "server", "server1234", "reserva_ceibal");

                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT `hora`, DATE_FORMAT(`hora_de_inicio`, '%H:%i') AS `hora_de_inicio`, DATE_FORMAT(`hora_de_fin`, '%H:%i') AS `hora_de_fin`, `laptop1`, `laptop2`, `laptop3`, `laptop4`, `laptop5`, `laptop6`, `laptop7`, `laptop8`, `laptop9`, `laptop10`, `laptop11`, `laptop12` FROM `$date`";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $morningEnd = 7; // Número de la última clase de la mañana
                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                        foreach ($rows as $index => $row) {
                            if ($index == $morningEnd) {
                                echo "<tr class='spacer-row'><td colspan='15'></td></tr>";
                            }
                            echo "<tr>";
                            echo "<td>" . $row["hora_de_inicio"] . "</td>";
                            echo "<td>" . $row["hora_de_fin"] . "</td>";
                            for ($i = 1; $i <= 12; $i++) {
                                $laptop = "laptop" . $i;
                                echo "<td class='reserva-vacia' data-id-celda='" . $row["hora"] . "-" . $i . "'>" . $row[$laptop] . "</td>";
                            }
                            echo "</tr>";
                        }
                    } elseif ($result->num_rows == 0) {
                        echo "<tr><td colspan='15'>No hay reservas para el día especificado.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>