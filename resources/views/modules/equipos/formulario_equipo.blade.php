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
                    <label>Nombre del Equipo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][nombre]" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Número de Serie</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][serie]">
                </div>

                <!-- Selector de tipo de equipo -->
                <div class="form-group col-md-4">
                    <label>Tipo de Equipo <span class="text-danger">*</span></label>
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
                    <label>Marca</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][marca]">
                </div>
                <div class="form-group col-md-4" id="modelo__INDEX__" style="display: none;">
                    <label>Modelo</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][modelo]">
                </div>
                <div class="form-group col-md-4" id="color__INDEX__" style="display: none;">
                    <label>Colores</label>
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
                    <label>Voltaje</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][voltaje]">
                </div>

                <!-- Campos específicos para cada tipo -->
                <!-- Motor Eléctrico -->
                <div class="form-group col-md-3" id="hp__INDEX__" style="display: none;">
                    <label>HP (Caballos de fuerza)</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][hp]">
                </div>
                <div class="form-group col-md-3" id="rpm__INDEX__" style="display: none;">
                    <label>RPM</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][rpm]">
                </div>
                <div class="form-group col-md-3" id="hz__INDEX__" style="display: none;">
                    <label>Hz (Hercios)</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][hz]">
                </div>

                <!-- Máquina Soldadora -->
                <div class="form-group col-md-3" id="amp__INDEX__" style="display: none;">
                    <label>AMP (Amperio)</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][amp]">
                </div>
                <div class="form-group col-md-3" id="cablePositivo__INDEX__" style="display: none;">
                    <label>Cable +</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_positivo]">
                </div>
                <div class="form-group col-md-3" id="cableNegativo__INDEX__" style="display: none;">
                    <label>Cable -</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_negativo]">
                </div>

                <!-- Generador/Dinamo -->
                <div class="form-group col-md-3" id="kvaKw__INDEX__" style="display: none;">
                    <label>Kva/Kw</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][kva_kw]">
                </div>

                <!-- Otros -->
                <div class="form-group col-md-3" id="potencia__INDEX__" style="display: none;">
                    <label>Potencia</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][potencia]">
                </div>

                <!-- Campos que siempre se muestran -->
                <div class="form-group col-12">
                    <label>Partes Faltantes</label>
                    <textarea class="form-control" name="equipos[__INDEX__][partes_faltantes]" rows="2"></textarea>
                </div>

                <div class="form-group col-12">
                    <label>Observaciones</label>
                    <textarea class="form-control" name="equipos[__INDEX__][observaciones]" rows="2"></textarea>
                </div>

                <div class="form-group col-12">
                    <label>Fotos del Equipo</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="equipos[__INDEX__][fotos][]" multiple
                            accept="image/jpeg,image/png,image/jpg,image/gif">
                        <label class="custom-file-label">Seleccionar archivos</label>
                    </div>
                    <small class="form-text text-muted">Puede seleccionar hasta 5 fotos (JPEG, PNG, JPG, GIF) - Máx. 8MB
                        cada una</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let equipoCount = 0;
        const equiposContainer = document.getElementById('equiposContainer');
        const equipoTemplate = document.getElementById('equipoTemplate').innerHTML;

        // Agregar nuevo equipo
        document.getElementById('addEquipo').addEventListener('click', function () {
            const newEquipoHTML = equipoTemplate.replace(/__INDEX__/g, equipoCount);
            const newEquipoElement = document.createElement('div');
            newEquipoElement.innerHTML = newEquipoHTML;
            equiposContainer.appendChild(newEquipoElement.firstElementChild);

            $(document).ready(function () {
            $('.select-colores').select2({
                placeholder: "",
                maximumSelectionLength: 2,
                width: '100%'
            });
        });

            // Actualizar contador
            reindexEquipos();

            // Inicializar selectric (si está definido)
            if (typeof $.fn.selectric !== 'undefined') {
                $('.selectric').selectric('destroy');
                $('.selectric').selectric();
            }

            // Animación
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
                });
            });
            equipoCount = equipos.length;
        }

        // Inicializar el primer equipo si es necesario
        if (equiposContainer.children.length === 0) {
            document.getElementById('addEquipo').click();
        }

        // Custom file input
        equiposContainer.addEventListener('change', function (e) {
            if (e.target.matches('.custom-file-input')) {
                const label = e.target.nextElementSibling;
                const files = e.target.files;
                if (files.length > 0) {
                    if (files.length > 1) {
                        label.textContent = `${files.length} archivos seleccionados`;
                    } else {
                        label.textContent = files[0].name;
                    }
                } else {
                    label.textContent = 'Seleccionar archivos';
                }
            }
        });


    });



    function mostrarCamposPorTipo(index) {
        const tipo = document.getElementById(`tipoEquipo${index}`).value;

        // Ocultar todos los campos específicos primero
        document.getElementById(`marca${index}`).style.display = 'none';
        document.getElementById(`modelo${index}`).style.display = 'none';
        document.getElementById(`color${index}`).style.display = 'none';
        document.getElementById(`voltaje${index}`).style.display = 'none';
        document.getElementById(`hp${index}`).style.display = 'none';
        document.getElementById(`rpm${index}`).style.display = 'none';
        document.getElementById(`hz${index}`).style.display = 'none';
        document.getElementById(`amp${index}`).style.display = 'none';
        document.getElementById(`cablePositivo${index}`).style.display = 'none';
        document.getElementById(`cableNegativo${index}`).style.display = 'none';
        document.getElementById(`kvaKw${index}`).style.display = 'none';
        document.getElementById(`potencia${index}`).style.display = 'none';

        // Mostrar campos según el tipo seleccionado
        if (tipo) {
            // Campos comunes a todos los tipos
            document.getElementById(`marca${index}`).style.display = 'block';
            document.getElementById(`modelo${index}`).style.display = 'block';
            document.getElementById(`color${index}`).style.display = 'block';
            document.getElementById(`voltaje${index}`).style.display = 'block';

            // Campos específicos
            switch (tipo) {
                case 'MOTOR_ELECTRICO':
                    document.getElementById(`hp${index}`).style.display = 'block';
                    document.getElementById(`rpm${index}`).style.display = 'block';
                    document.getElementById(`hz${index}`).style.display = 'block';
                    break;
                case 'MAQUINA_SOLDADORA':
                    document.getElementById(`amp${index}`).style.display = 'block';
                    document.getElementById(`cablePositivo${index}`).style.display = 'block';
                    document.getElementById(`cableNegativo${index}`).style.display = 'block';
                    break;
                case 'GENERADOR_DINAMO':
                    document.getElementById(`kvaKw${index}`).style.display = 'block';
                    document.getElementById(`hz${index}`).style.display = 'block';
                    document.getElementById(`rpm${index}`).style.display = 'block';
                    break;
                case 'OTROS':
                    document.getElementById(`potencia${index}`).style.display = 'block';
                    break;
            }
        }
    }

    
</script>