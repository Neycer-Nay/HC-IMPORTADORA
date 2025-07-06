<div class="card">
    <div class="card-header bg-whitesmoke">
        <h4><i class="fas fa-desktop"></i> Equipo(s) a Recepcionar</h4>
        <div class="card-header-action">
            <button type="button" class="btn btn-primary" id="addEquipo">
                <i class="fas fa-plus"></i> Agregar Equipo
            </button>
        </div>
    </div>
    <div class="card-body" id="equiposContainer">
        <!-- Los equipos se agregarán aquí dinámicamente -->
    </div>
</div>

<!-- Plantilla para nuevos equipos (hidden) -->
<template id="equipoTemplate">
    <div class="card mb-3 equipo-item">
        <div class="card-header d-flex justify-content-between align-items-center ">
            <h5 class="mb-0">
                <i class="fas fa-desktop"></i> Equipo #<span class="equipo-count">1</span>
            </h5>
            <button type="button" class="btn btn-icon btn-danger remove-equipo">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Campos que siempre se muestran -->
                <div class="form-group col-md-6">
                    <label><strong>Nombre del Equipo</strong> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][nombre]" required>
                </div>
                <div class="form-group col-md-6">
                    <label><strong>Número de Serie</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][serie]">
                </div>

                <!-- Selector de tipo de equipo -->
                <div class="form-group col-md-4">
                    <label><strong>Tipo de Equipo </strong><span class="text-danger">*</span></label>
                    <select class="form-control selectric" name="equipos[__INDEX__][tipo]" id="tipoEquipo__INDEX__"
                        required onchange="mostrarCamposPorTipo('__INDEX__')">
                        <option value="">Seleccione...</option>
                        <option value="MOTOR_ELECTRICO">Motor Eléctrico</option>
                        <option value="MAQUINA_SOLDADORA">Máquina Soldadora</option>
                        <option value="GENERADOR_DINAMO">Generador/Dinamo</option>
                        <option value="OTROS">Otros</option>
                    </select>
                </div>

                <!-- Campos comunes que se muestran según el tipo -->
                <div class="form-group col-md-4" id="marca__INDEX__" style="display: none;">
                    <label><strong>Marca</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][marca]" required>
                </div>
                <div class="form-group col-md-4" id="modelo__INDEX__" style="display: none;">
                    <label><strong>Modelo</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][modelo]">
                </div>
                <div class="form-group col-md-4" id="color__INDEX__" style="display: none;">
                    <label><strong>Colores</strong></label>
                    <select class="form-control select-colores" name="equipos[__INDEX__][color][]" multiple>
                        <option value="rojo">Rojo</option>
                        <option value="azul">Azul</option>
                        <option value="verde">Verde</option>
                        <option value="amarillo">Amarillo</option>
                        <option value="naranja">Naranja</option>
                        <option value="morado">Morado</option>
                        <option value="rosado">Rosado</option>
                        <option value="negro">Negro</option>
                        <option value="blanco">Blanco</option>
                        <option value="gris">Gris</option>
                        <option value="marrón">Marrón</option>
                        <option value="cian">Cian</option>
                    </select>
                    <small class="form-text text-muted">Puedes seleccionar hasta 2 colores.</small>
                </div>
                <div class="form-group col-md-3" id="voltaje__INDEX__" style="display: none;">
                    <label><strong>Voltaje</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][voltaje]">
                </div>

                <!-- Campos específicos para cada tipo -->
                <!-- Motor Eléctrico -->
                <div class="form-group col-md-3" id="hp__INDEX__" style="display: none;">
                    <label><strong>HP (Caballos de fuerza)</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][hp]">
                </div>
                <div class="form-group col-md-3" id="rpm__INDEX__" style="display: none;">
                    <label><strong>RPM</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][rpm]">
                </div>
                <div class="form-group col-md-3" id="hz__INDEX__" style="display: none;">
                    <label><strong>Hz (Hercios)</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][hz]">
                </div>

                <!-- Máquina Soldadora -->
                <div class="form-group col-md-3" id="amp__INDEX__" style="display: none;">
                    <label><strong>AMP (Amperio)</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][amp]">
                </div>
                <div class="form-group col-md-3" id="cablePositivo__INDEX__" style="display: none;">
                    <label><strong>Cable +</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_positivo]">
                </div>
                <div class="form-group col-md-3" id="cableNegativo__INDEX__" style="display: none;">
                    <label><strong>Cable -</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_negativo]">
                </div>

                <!-- Generador/Dinamo -->
                <div class="form-group col-md-3" id="kvaKw__INDEX__" style="display: none;">
                    <label><strong>Kva/Kw</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][kva_kw]">
                </div>

                <!-- Otros -->
                <div class="form-group col-md-3" id="potencia__INDEX__" style="display: none;">
                    <label><strong>Potencia</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][potencia]">
                </div>

                <!-- Campos que siempre se muestran -->
                <div class="form-group col-12">
                    <label><strong>Partes Faltantes</strong></label>
                    <textarea class="form-control" name="equipos[__INDEX__][partes_faltantes]" rows="2"></textarea>
                </div>

                <div class="form-group col-12">
                    <label><strong>Observaciones</strong></label>
                    <textarea class="form-control" name="equipos[__INDEX__][observaciones]" rows="2"></textarea>
                </div>

                <div class="form-group col-12">
                    <label><strong>Fotos del Equipo</strong></label>

                    <!-- Subida tradicional de fotos -->
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="fileInput__INDEX__"
                            name="equipos[__INDEX__][fotos][]" multiple
                            accept="image/jpeg,image/png,image/jpg,image/gif">
                        <label class="custom-file-label">Seleccionar fotos</label>
                        <div class="form-text">Puede seleccionar hasta 8 fotos (JPEG, PNG, JPG, GIF) - Máx. 8MB cada una</div>
                    </div>

                    <!-- Contenedor de previsualizaciones con altura fija -->
                    <div id="filePreviews__INDEX__" class="d-flex flex-wrap mt-2 mb-4"
                        style="max-height: 200px; overflow-y: auto; gap: 8px;"></div>

                    <!-- Captura desde cámara -->

                </div>
            </div>
        </div>
    </div>
</template>

<style>
    @media (max-width: 768px) {
        #filePreviews__INDEX__ {
            max-height: 150px !important;
        }

        #filePreviews__INDEX__ img {
            width: 80px !important;
            height: 80px !important;
        }
    }

    /* Espaciado adicional para los botones */
    .mt-4 {
        margin-top: 2rem !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let equipoCount = 0;
        const equiposContainer = document.getElementById('equiposContainer');
        const equipoTemplate = document.getElementById('equipoTemplate').innerHTML;
        
        // Funciones para la cámara
        function previewFiles(input, index) {
    const previewContainer = document.getElementById(`filePreviews${index}`);
    previewContainer.innerHTML = '';
    const MAX_SIZE_MB = 8; // Tamaño máximo en MB
    const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024; // Convertir a bytes

    // Validar cantidad máxima de archivos (8)
    if (input.files && input.files.length > 8) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No puedes seleccionar más de 8 fotos. Por favor, selecciona hasta 8 archivos.',
            confirmButtonText: 'Entendido'
        });
        input.value = '';
        const label = input.nextElementSibling;
        label.textContent = 'Seleccionar fotos';
        return;
    }

    // Validar tamaño de cada archivo
    if (input.files) {
        let hasInvalidSize = false;
        
        Array.from(input.files).forEach(file => {
            if (file.size > MAX_SIZE_BYTES) {
                hasInvalidSize = true;
            }
        });

        if (hasInvalidSize) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Una o más fotos superan el tamaño máximo de ${MAX_SIZE_MB}MB. Por favor, selecciona archivos más pequeños.`,
                confirmButtonText: 'Entendido'
            });
            input.value = '';
            const label = input.nextElementSibling;
            label.textContent = 'Seleccionar fotos';
            return;
        }

        // Procesar archivos si pasan todas las validaciones
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.className = 'img-thumbnail';
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);
        });

        // Actualizar label
        const label = input.nextElementSibling;
        label.textContent = input.files.length > 1 ?
            `${input.files.length} archivos seleccionados` :
            input.files[0].name;
    }
}
        

        // Agregar nuevo equipo
        document.getElementById('addEquipo').addEventListener('click', function () {
            const newEquipoHTML = equipoTemplate.replace(/__INDEX__/g, equipoCount);
            const newEquipoElement = document.createElement('div');
            newEquipoElement.innerHTML = newEquipoHTML;
            equiposContainer.appendChild(newEquipoElement.firstElementChild);

            // Inicializar Select2 para colores
            $(`select[name="equipos[${equipoCount}][color][]"]`).select2({
                placeholder: "Seleccione colores",
                maximumSelectionLength: 2,
                width: '100%'
            });

            // Mostrar campos básicos inicialmente
            mostrarCamposPorTipo(equipoCount);


            // Incrementar contador
            equipoCount++;

            // Reindexar equipos
            reindexEquipos();


            // Inicializar selectric (si está definido)
            if (typeof $.fn.selectric !== 'undefined') {
                $('.selectric').selectric('destroy');
                $('.selectric').selectric();
            }

            // Animación de aparición
            const lastEquipo = equiposContainer.lastElementChild;
            lastEquipo.style.opacity = '0';
            let opacity = 0;
            const fadeIn = setInterval(() => {
                opacity += 0.1;
                lastEquipo.style.opacity = opacity;
                if (opacity >= 1) clearInterval(fadeIn);
            }, 50);
        });

        // Eliminar equipo (delegación de eventos)
        equiposContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-equipo')) {
                const equipoItem = e.target.closest('.equipo-item');
                // Detener la transmisión de la cámara
                const video = equipoItem.querySelector('video');
                if (video && video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }
                equipoItem.style.opacity = '1';
                let opacity = 1;
                const fadeOut = setInterval(() => {
                    opacity -= 0.1;
                    equipoItem.style.opacity = opacity;
                    if (opacity <= 0) {
                        clearInterval(fadeOut);
                        equipoItem.remove();
                        reindexEquipos();
                    }
                }, 50);
            }
        });

        // Función para reindexar equipos
        function reindexEquipos() {
            const equipos = equiposContainer.querySelectorAll('.equipo-item');
            equipos.forEach((equipo, index) => {
                // Actualizar número de equipo
                equipo.querySelector('.equipo-count').textContent = index + 1;

                // Actualizar todos los names de los inputs
                equipo.querySelectorAll('[name^="equipos["]').forEach(input => {
                    const currentName = input.name;
                    const newName = currentName.replace(/equipos\[\d+\]/, `equipos[${index}]`);
                    input.name = newName;

                    // Actualizar IDs para campos dinámicos
                    if (input.id && input.id.includes('__INDEX__')) {
                        input.id = input.id.replace(/__INDEX__/, index);
                    }
                });

                // Actualizar IDs de los divs contenedores
                const tipos = ['marca', 'modelo', 'color', 'voltaje', 'hp', 'rpm', 'hz', 'amp',
                    'cablePositivo', 'cableNegativo', 'kvaKw', 'potencia'];

                tipos.forEach(tipo => {
                    const div = equipo.querySelector(`#${tipo}__INDEX__`);
                    if (div) {
                        div.id = `${tipo}${index}`;
                    }
                });

                // Actualizar el evento onchange del select de tipo
                const tipoSelect = equipo.querySelector('[name^="equipos["][name$="[tipo]"]');
                if (tipoSelect) {
                    tipoSelect.onchange = function () {
                        mostrarCamposPorTipo(index);
                    };
                }



            });

            equipoCount = equipos.length;
        }

        // Inicializar el primer equipo


        // Manejar cambio en inputs de archivo
        equiposContainer.addEventListener('change', function (e) {
            if (e.target.matches('.custom-file-input')) {
                const index = e.target.id.replace('fileInput', '');
                previewFiles(e.target, index);
            }
        });

        // Validación antes de enviar el formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                let isValid = true;
                const equipos = equiposContainer.querySelectorAll('.equipo-item');

                if (equipos.length === 0) {
                    Swal.fire('Error', 'Debe agregar al menos un equipo', 'error');
                    isValid = false;
                }

                equipos.forEach((equipo, index) => {
                    const tipo = equipo.querySelector('[name^="equipos["][name$="[tipo]"]').value;
                    const marca = equipo.querySelector('[name^="equipos["][name$="[marca]"]');

                    if (!tipo) {
                        Swal.fire('Error', `El tipo del equipo #${index + 1} es requerido`, 'error');
                        isValid = false;
                    }

                    if (marca && !marca.value) {
                        Swal.fire('Error', `La marca del equipo #${index + 1} es requerida`, 'error');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        }
    });

    // Función mejorada para mostrar campos según tipo
    function mostrarCamposPorTipo(index) {
        const tipo = document.getElementById(`tipoEquipo${index}`)?.value;
        if (!tipo) return;

        // Definir qué campos mostrar para cada tipo
        const campos = {
            comunes: ['marca', 'modelo', 'color', 'voltaje'],
            MOTOR_ELECTRICO: ['hp', 'rpm', 'hz'],
            MAQUINA_SOLDADORA: ['amp', 'cablePositivo', 'cableNegativo'],
            GENERADOR_DINAMO: ['kvaKw', 'hz', 'rpm'],
            OTROS: ['potencia']
        };

        // Ocultar todos los campos específicos primero
        const todosCampos = [...campos.comunes, ...campos.MOTOR_ELECTRICO, ...campos.MAQUINA_SOLDADORA,
        ...campos.GENERADOR_DINAMO, ...campos.OTROS];

        todosCampos.forEach(campo => {
            const elemento = document.getElementById(`${campo}${index}`);
            if (elemento) {
                elemento.style.display = 'none';
                // Quitar requerido al ocultar (excepto marca)
                if (campo !== 'marca') {
                    const input = elemento.querySelector('input, select, textarea');
                    if (input) input.required = false;
                }
            }
        });

        // Mostrar campos comunes
        campos.comunes.forEach(campo => {
            const elemento = document.getElementById(`${campo}${index}`);
            if (elemento) {
                elemento.style.display = 'block';
                // Marcar marca como requerida
                if (campo === 'marca') {
                    const input = elemento.querySelector('input');
                    if (input) input.required = true;
                }
            }
        });

        // Mostrar campos específicos según tipo
        if (campos[tipo]) {
            campos[tipo].forEach(campo => {
                const elemento = document.getElementById(`${campo}${index}`);
                if (elemento) elemento.style.display = 'block';
            });
        }
    }



</script>