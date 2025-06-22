<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-clipboard-list"></i> Datos de la Recepción</h4>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Número de Recepción <span
                    class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-7">
                <input type="text" class="form-control" id="numero_recepcion" name="numero_recepcion" required>
                <div class="invalid-feedback">Ingrese el número de recepción</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Fecha <span
                    class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-3">
                <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" required>
                <div class="invalid-feedback">Seleccione la fecha</div>
            </div>

            <label class="col-form-label text-md-right col-12 col-md-2 col-lg-1">Hora <span
                    class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-3">
                <input type="time" class="form-control" id="hora_ingreso" name="hora_ingreso" required>
                <div class="invalid-feedback">Ingrese la hora</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Encargado <span
                    class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-7">
                <select class="form-control selectric" id="encargado_id" name="encargado_id" required>
                    <option value="">Seleccione...</option>
                    <!-- Opciones se llenarán dinámicamente -->
                </select>
                <div class="invalid-feedback">Seleccione un encargado</div>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-2">Observaciones</label>
            <div class="col-sm-12 col-md-7">
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    <script>
        // Inicializar selectric (selects estilizados)
        $(document).ready(function () {
            $('.selectric').selectric();

            // Establecer valores por defecto
            $('#fecha_recepcion').val(new Date().toISOString().split('T')[0]);

            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            $('#hora_ingreso').val(`${hours}:${minutes}`);
        });
    </script>
@endsection