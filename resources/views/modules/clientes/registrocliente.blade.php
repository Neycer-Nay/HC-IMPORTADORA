<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-user-tag"></i> Datos del Cliente</h4>
        </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Seleccionar Cliente Existente <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-12 col-md-7">
                        <select class="form-control selectric" id="cliente_id" name="cliente_id" required>
                            <option value="">Seleccione un cliente...</option>
                            
                            <!-- Opciones se llenarán dinámicamente -->
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un cliente</div>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                            data-target="#nuevoClienteModal">
                            <i class="fas fa-plus"></i> Registar nuevo cliente
                        </button>
                    </div>
                </div>

                <!-- Información del cliente seleccionado (oculto inicialmente) -->
                <div class="row mt-3 d-none" id="clienteInfo">
                    <div class="col-12 col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-light">
                                <i class="far fa-id-card text-primary"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h6>Documento</h6>
                                </div>
                                <div class="card-body" id="clienteDocumento">
                                    -
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-light">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h6>Teléfono</h6>
                                </div>
                                <div class="card-body" id="clienteTelefono">
                                    -
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h6>Email</h6>
                                </div>
                                <div class="card-body" id="clienteEmail">
                                    -
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-light">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h6>Dirección</h6>
                                </div>
                                <div class="card-body" id="clienteDireccion">
                                    -
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Modal Nuevo Cliente -->
<div class="modal fade" tabindex="-1" role="dialog" id="nuevoClienteModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoCliente" method="POST" action="{{ route('clientes.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nombre/Razón Social <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" value="{{ old('nombre') }}" name="nombre" required>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tipo <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <select class="form-control" name="tipo" required>
                                <option value="">-- Seleccione --</option>
                                <option value="PERSONA" {{ old('tipo') == 'PERSONA' ? 'selected' : '' }}>Persona</option>
                                <option value="EMPRESA" {{ old('tipo') == 'EMPRESA' ? 'selected' : '' }}>Empresa</option>
                            </select>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tipo Documento <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <select class="form-control" name="tipo_documento" required>
                                <option value="">-- Seleccione --</option>
                                <option value="CI"  {{old('tipo_documento') == 'CI' ? 'selected' : ''}}>Carnet de Identidad</option>
                                <option value="NIT" {{old('tipo_documento') == 'NIT' ? 'selected' : ''}}>NIT</option>
                                <option value="PASAPORTE" {{old('tipo_documento') == 'PASAPORTE' ? 'selected' : ''}}>Pasaporte</option>
                                <option value="OTRO" {{old('tipo_documento') == 'OTRO' ? 'selected' : ''}}>Otro</option>
                            </select>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Número Documento <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control @error('numero_documento') is-invalid @enderror" value="{{ old('numero_documento') }}" name="numero_documento" required>
                            @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Teléfono Principal <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control" value="{{ old('telefono_1') }}" name="telefono_1" required>
                            
                        </div>
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Teléfono Secundario <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control" value="{{ old('telefono_2') }}" name="telefono_2" >
                            
                        </div>
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Teléfono Terciario <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control" value="{{ old('telefono_3') }}" name="telefono_3">
                           
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ciudad</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" name="ciudad" value="Santa Cruz">
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Dirección</label>
                        <div class="col-sm-12 col-md-9">
                            <textarea name="direccion" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const selectCliente = document.getElementById('cliente_id');
    const clienteInfo = document.getElementById('clienteInfo');

    selectCliente.addEventListener('change', function() {
        if (this.value === '') {
            clienteInfo.classList.add('d-none'); // Oculta si no hay selección
            return;
        }

        // Obtiene los datos del option seleccionado
        const selectedOption = this.options[this.selectedIndex];
        const documento = selectedOption.getAttribute('data-documento') || '-';
        const telefono = selectedOption.getAttribute('data-telefono') || '-';
        const email = selectedOption.getAttribute('data-email') || '-';
        const direccion = selectedOption.getAttribute('data-direccion') || '-';

        // Actualiza los campos en la tarjeta de información
        document.getElementById('clienteDocumento').textContent = documento;
        document.getElementById('clienteTelefono').textContent = telefono;
        document.getElementById('clienteEmail').textContent = email;
        document.getElementById('clienteDireccion').textContent = direccion;

        // Muestra la sección de información
        clienteInfo.classList.remove('d-none');
    });
});
    </script>


    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente registrado',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#nuevoClienteModal').modal('show');
            });
        </script>
    @endif
@endsection


