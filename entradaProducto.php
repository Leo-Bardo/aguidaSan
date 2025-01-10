<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENTRADA-PRODUCTO</title>
    <!-- ELIMINAR CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" type="text/css" href="styles/cartastilo.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Incluir la barra de navegación -->
    <div id="navbar-container"></div>
    <div class="container">
        <div class="title">ENTRADA</div>
        <form action="registro.php" name="bdaguidasan" method="POST" onsubmit="return validarFormulario()">
            <!-- Diseño y estilo para los campos de formulario -->
            <style>
                .input-box input:required,
                .input-box select:required {
                    border: 2.2px solid #1a1a1c;;
                    
                    transition: border-color 0.3s;
                }
            
                .input-box input:invalid,
                .input-box select:invalid {
                    border-color: #1a1a1c; ;
                }
            </le>
            <style>
            .input-box input:required,
            .input-box select:required {
                border: 2px solid #ccc; /* Borde normal para campos requeridos */
                
                transition: border-color 0.3s;
            }
            
            .input-box input:valid,
            .input-box select:valid {
                border-color: #A9A9A9; /* Borde para campos válidos */
            }
            
            .input-box input:invalid,
            .input-box select:invalid {
                border-color: #1a1a1c; /* Borde  para campos inválidos */
            }
            </style>
            <style>
                .input-box input[type="text"],
                .input-box input[type="number"],
                .input-box select {
                    width: 100%;
                    padding: 10px;
                   
                    border-radius: 5px;
                    transition: border-color 0.3s;
                }
               </style>
               <style>
                .user-details {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                }
        
                .input-box {
                    flex: 1 1 calc(50% - 1rem);
                    display: flex;
                    flex-direction: column;
                }
        
                .input-box span.details {
                    font-weight: bold;
                    margin-bottom: 0.5rem;
                }
        
                .input-box input,
                .input-box select {
                    width: 100%;
                    padding: 0.8rem;
                    border: 2px solid #ccc;
                    border-radius: 5px;
                    transition: border-color 0.3s;
                }
            </style>
             <!-- Termina Diseño -->
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Pesador:</span>
                    <input type="text" placeholder="Ingresa nombre" name="nombrePesador" required>
                </div>
                <div class="input-box">
                    <span class="details">Fecha:</span>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="input-box">
                    <span class="details">Recibe:</span>
                    <input type="text" placeholder="Ingresa nombre" name="nombreReceptor" required>
                </div>
                <div class="input-box">
                    <label class="details">Seleccione el Área o Departamento:</label>
                    <select id="area" name="area" required>
                        <option value="" selected disabled>- seleccione un área -</option>
                        
                        <!-- Almacenes y Áreas de Almacenamiento -->
                        <optgroup label="Almacenes y Áreas de Almacenamiento">
                            <option value="ASTZ">Almacén Sanitizantes</option>
                            <option value="AMP">Almacén MP</option>
                            <option value="APT">Almacén PT</option>
                            <option value="EMB">Embalaje</option>
                            <option value="ENV">Envasado</option>
                            <option value="FLEX100">A3 Flex 100</option>
                            <option value="TBA81LB">TBA8 1L Base</option>
                            <option value="TBA81LS">TBA8 1L Slim</option>
                            <option value="FLEXEDGE">A3 Flex Edge</option>
                            <option value="FLEXPRISMA">A3 Flex Prisma</option>
                            <option value="CFLEX">Compact Flex Prisma</option>
                        </optgroup>
                        
                        <!-- Oficinas y Servicios Generales -->
                        <optgroup label="Oficinas y Servicios Generales">
                            <option value="ADM">Administración</option>
                            <option value="ING">Ingeniería</option>
                            <option value="IF">Inventario Físico</option>
                            <option value="LIM">Limpieza</option>
                            <option value="SHS">Seguridad, Higiene y Sanidad</option>
                            <option value="VIG">Vigilancia</option>
                        </optgroup>
                        
                        <!-- Producción y Mantenimiento -->
                        <optgroup label="Producción y Mantenimiento">
                            <option value="AGRI">Agrícola/RN</option>
                            <option value="AC">Aseguramiento de Calidad</option>
                            <option value="CONS">Construcción</option>
                            <option value="CTAMB">Control Ambiental</option>
                            <option value="MTS">Mantenimiento Servicios</option>
                            <option value="MTP">Mantenimiento TP</option>
                            <option value="MPR">Mantenimiento Procesos</option>
                            <option value="PREP">Preparación</option>
                            <option value="PES">Pesadas</option>
                            <option value="PTAR">PTAR</option>
                        </optgroup>
                        
                        <!-- Áreas Comunes y Otros -->
                        <optgroup label="Áreas Comunes y Otros">
                            <option value="BCV">Baño Casa de Visitas</option>
                            <option value="BC">Baño Comedor</option>
                            <option value="BT">Baño Transportistas</option>
                            <option value="BH">Baños Hombres</option>
                            <option value="BM">Baños Mujeres</option>
                            <option value="BO">Baños Oficinas</option>
                            <option value="CV">Casa de Visitas</option>
                            <option value="COM">Comedor</option>
                            <option value="PROM1">Past.Promato</option>
                            <option value="SPIRA1">Past.Spiraflo</option>
                            <option value="TAS">Tanque Acepticos</option>
                            <option value="VEH">Vehículos y Taller Mecánico</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="input-box">
                    <span class="details">Ingrese Nombre de Receptor:</span>
                    <input type="text" placeholder="Ingresa nombre"  name="nombreReceptor" required>
                
                </div>
                <div class="input-box">
                    <span class="details">Turno:</span>
                    <select id="turno" name="turno" required>
                 
                        <option value="" selected disabled>- seleccione un turno -</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="M">M</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Produccion Realizada:</span>
                    <select id="produccion" name="produccionRealizada" required>
                        <option value="" selected>- seleccione una producción -</option>
                    </select>
                </div>
                
                <script src="src/jquery-3.7.1.min.js"></script>
                <script>
                    $(document).ready(function() {
                        // Realizar la solicitud AJAX para obtener las opciones de producción
                        $.ajax({
                            url: 'opciones_produccion.php',
                            dataType: 'html',
                            success: function(data) {
                                // Agregar las opciones obtenidas al elemento select
                                $('#produccion').append(data);
                            }
                        });
                    });
                </script>
                
                
                
                
                
                <div class="input-box">
                    <span class="details">Elige el Producto:</span>
                    <select id="producto" name="producto" required>
                        <option value="" selected>- seleccione un producto -</option>
                        <optgroup label="ALCALINOS">
                            <option value="S10">CAUSTI FOAM CL</option>
                            <option value="S11">CAUSTI LC 200</option>
                            <option value="S14">HIPOCLORITO 13%</option>
                            <option value="S20">SOSA LIQUIDA 50%</option>
                            <option value="S24">LCC ALKA</option>
                        </optgroup>
                        <optgroup label="ACIDOS">
                            <option value="S02">CLORHIDRICO</option>
                            <option value="S03">NITRICO</option>
                            <option value="S04">SULFURICO</option>
                            <option value="S12">DESINOX FN</option>
                            <option value="S18">SANIPER 15%</option>
                            <option value="S25">STONE NF</option>
                        </optgroup>
                        <optgroup label="NEUTROS">
                            <option value="S07">BETA QUAT 4</option>
                            <option value="S08">BIO BASIC A</option>
                            <option value="S09">BIO TECH</option>
                            <option value="S13">GEL TROLL FOAM</option>
                            <option value="S15">ML-100 FOAM</option>
                            <option value="S29">SHAMPOO MANOS</option>
                            <option value="S31">FX3</option>
                        </optgroup>
                        <optgroup label="PEROXIDOS">
                            <option value="S23">PEROXIDO BATH</option>
                            <option value="S26">PER WASH</option>
                            <option value="S23B">PEROXIDO IPS</option>
                        </optgroup>
                        <optgroup label="OTROS">
                            <option value="S05">ALCOHOL</option>
                            <option value="S27">AGUA DESTILADA</option>
                            <option value="S28">THINNER</option>
                            <option value="S32">CAUSTI LC 200</option>
                            <option value="S33">HIPOCLORITO 13%</option>
                        </optgroup>
                        <optgroup label="MMPP">
                            <option value="S30">CLORHIDRICO 2N</option>
                        </optgroup>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Ingrese la Cantidad que Necesita:</span>
                    <input type="number" id="cantidadSalida" name="cantidadSalida" min="1" required>
            
                </div>
            </div>
            <div class="button">
                <input type="submit" name="registro" value="ENVIAR"  id="botonsalida">
            </div>
        </form>
    </div>     
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para validar el formulario
        function validarFormulario() {
            var form = document.forms["bdaguidasan"];
            var isValid = true;
    
            // Resetear bordes a valores predeterminados
            var inputs = form.querySelectorAll("input, select");
            inputs.forEach(function(input) {
                input.classList.remove("invalid");
            });
    
            // Definir mensajes de error por campo
            var errorMessages = {
                nombrePesador: "Ingrese el nombre del pesador",
                fecha: "Ingresa una fecha válida",
                nombreEntrego: "Ingresa el nombre del entregador",
                area: "Área",
                nombreReceptor: "Recibe",
                turno: "Selecciona un turno",
                produccionRealizada: "Ingresa la producción realizada",
                producto: "Selecciona un producto",
                cantidadSalida: "Ingresa la cantidad que necesita",
               
            };
    
            // Verificar cada campo
            var fields = ["nombrePesador", "fecha", "nombreEntrego", "area", "nombreReceptor", "turno", "produccionRealizada", "producto", "cantidadSalida"];
            fields.forEach(function(field) {
                if (form[field].value.trim() === "") {
                    form[field].classList.add("invalid");
                    isValid = false;
    
                    // Mostrar mensaje de error específico
                    Swal.fire({
                        title: "¡Oops!",
                        text: errorMessages[field],
                        icon: "error",
                        confirmButtonText: "Entendido"
                        
                    });
                }
            });
    
            return isValid;
        }
    
        // Manejar el evento de envío del formulario
        document.getElementById("botonsalida").addEventListener("click", function(e) {
            e.preventDefault();
            if (validarFormulario()) {
                Swal.fire({
                    title: "¿Deseas enviar?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, enviar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(document.forms["bdaguidasan"]);
    
                        fetch("registro.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                // Mostrar mensaje de éxito
                                Swal.fire({
                                    icon: "success",
                                    title: "Registro exitoso",
                                    text: "El registro ha sido exitoso.",
                                    confirmButtonColor: "#3085d6"
                                });
    
                                // Limpiar el formulario
                                document.forms["bdaguidasan"].reset();
    
                            } else {
                                // Mostrar mensaje de error si hay algún problema con la solicitud
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Hubo un problema al procesar tu solicitud.",
                                    confirmButtonColor: "#3085d6"
                                });
                            }
                        })
                        .catch(error => {
                            // Capturar errores de red u otros errores
                            console.error("Error:", error);
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Hubo un error al procesar la solicitud.",
                                confirmButtonColor: "#3085d6"
                            });
                        });
                    }
                });
            }
        });
    
        // Cargar la barra de navegación desde un archivo externo
        document.addEventListener('DOMContentLoaded', function() {
            fetch('navbar.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-container').innerHTML = data;
    
                    // Una vez cargada la barra de navegación, añadir el event listener para el menú
                    const menuIcon = document.querySelector('.menu-icon');
                    const drawer = document.getElementById('drawer');
                    const overlay = document.querySelector('.overlay');
                    const submenuItems = document.querySelectorAll('.submenu-label');
                    const logoutButton = document.querySelector('.logout-button');
    
                    menuIcon.addEventListener('click', function() {
                        drawer.classList.toggle('open');
                        overlay.classList.toggle('active');
                    });
    
                    overlay.addEventListener('click', function() {
                        drawer.classList.remove('open');
                        overlay.classList.remove('active');
                    });
    
                    submenuItems.forEach(item => {
                        item.addEventListener('click', function() {
                            this.querySelector('.submenu-content').classList.toggle('active');
                        });
                    });
    
                    logoutButton.addEventListener('click', function() {
                        Swal.fire({
                            title: '¿Cerrar sesión?',
                            text: '¿Estás seguro de que quieres cerrar sesión?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, cerrar sesión'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'pagina_de_inicio.html';
                            }
                        });
                    });
                })
                .catch(error => console.error('Error al cargar la barra de navegación:', error));
        });
    
        // Obtener el elemento del input de fecha por su ID
        var inputFecha = document.getElementById('fecha');
    
        // Obtener la fecha actual en el formato YYYY-MM-DD (compatible con input type=date)
        var fechaActual = new Date().toISOString().slice(0, 10);
    
        // Asignar la fecha actual al valor del input
        inputFecha.value = fechaActual;
    </script>
    
    
</body>
</html>