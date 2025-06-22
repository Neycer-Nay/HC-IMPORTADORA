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
                <div class="form-group col-md-6">
                    <label>Nombre del Equipo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][nombre]" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Número de Serie</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][serie]">
                </div>

                <div class="form-group col-md-4">
                    <label>Tipo de Equipo <span class="text-danger">*</span></label>
                    <select class="form-control selectric" name="equipos[__INDEX__][tipo]" required>
                        <option value="">Seleccione...</option>
                        <option value="MOTOR_ELECTRICO">Motor Eléctrico</option>
                        <option value="MAQUINA_SOLDADORA">Máquina Soldadora</option>
                        <option value="GENERADOR_DINAMO">Generador/Dinamo</option>
                        <option value="OTROS">Otros</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Marca</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][marca]">
                </div>
                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][modelo]">
                </div>

                <div class="form-group col-md-3">
                    <label>Color</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][color]">
                </div>
                <div class="form-group col-md-3">
                    <label>Voltaje</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][voltaje]">
                </div>
                <div class="form-group col-md-3">
                    <label>RPM</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][rpm]">
                </div>
                <div class="form-group col-md-3">
                    <label>Potencia</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][potencia]">
                </div>

                <div class="form-group col-md-6">
                    <label>Estado al recibir</label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][estado]">
                </div>
                <div class="form-group col-md-6">
                    <label>Costo Estimado (Bs)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bs.</span>
                        </div>
                        <input type="number" step="0.01" class="form-control" name="equipos[__INDEX__][costo_estimado]">
                    </div>
                </div>

                <div class="form-group col-12">
                    <label>Partes Faltantes</label>
                    <textarea class="form-control" name="equipos[__INDEX__][partes_faltantes]" rows="2"></textarea>
                </div>

                <div class="form-group col-12">
                    <label>Trabajo a Realizar</label>
                    <textarea class="form-control" name="equipos[__INDEX__][trabajo_realizar]" rows="2"></textarea>
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
                    <small class="form-text text-muted">Puede seleccionar hasta 5 fotos (JPEG, PNG, JPG, GIF) - Máx. 8MB cada una</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let equipoCount = 0;
    const equiposContainer = document.getElementById('equiposContainer');
    const equipoTemplate = document.getElementById('equipoTemplate').innerHTML;
    
    // Agregar nuevo equipo
    document.getElementById('addEquipo').addEventListener('click', function() {
        const newEquipoHTML = equipoTemplate.replace(/__INDEX__/g, equipoCount);
        const newEquipoElement = document.createElement('div');
        newEquipoElement.innerHTML = newEquipoHTML;
        equiposContainer.appendChild(newEquipoElement.firstElementChild);
        
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
    equiposContainer.addEventListener('click', function(e) {
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
    equiposContainer.addEventListener('change', function(e) {
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
</script>