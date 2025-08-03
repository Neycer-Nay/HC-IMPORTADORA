@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414">Egresos</h1>
                <div class="section-header-breadcrumb">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoEgreso">Nuevo Egreso
                    </a>
                    <a href="#" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalNuevaCuenta">Agregar
                        Cuenta
                    </a>
                    <a href="#" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalFiltroFecha">
                        <i class="fas fa-filter"></i> Filtrar por Fecha
                    </a>
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Aquí puedes gestionar los egresos.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-egresos">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre Cuenta</th>
                            <th>Glosa</th>
                            <th>Razón Social</th>
                            <th>N° Factura</th>
                            <th>Responsable</th>
                            <th>Método de Pago</th>
                            <th>Subtotal</th>
                            <th>Descuento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ejemplo de fila vacía, puedes agregar datos de prueba si lo deseas -->
                        @foreach ($egresos as $egreso)
                            <tr>
                                <td>{{ $egreso->created_at->format('d/m/Y') }}</td>
                                <td>{{ $egreso->cuenta->nombre_cuenta }}</td>
                                <td>{{ $egreso->glosa }}</td>
                                <td>{{ $egreso->razon_social }}</td>
                                <td>{{ $egreso->nro_factura }}</td>
                                <td>{{ $egreso->responsable }}</td>
                                <td>{{ $egreso->metodo_pago }}</td>
                                <td>{{ number_format($egreso->subtotal, 2) }}</td>
                                <td>{{ number_format($egreso->descuento, 2) }}</td>
                                <td>{{ number_format($egreso->total, 2) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal de Nuevo Egreso -->
    <div class="modal fade" id="modalNuevoEgreso" tabindex="-1" role="dialog" aria-labelledby="modalNuevoEgresoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('egresos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoEgresoLabel">Registrar Nuevo Egreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="color: #151414;">
                            <div class="form-group col-md-6">
                                <label>Nombre Cuenta</label>
                                <select class="form-control" name="cuenta_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($cuentas as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->nombre_cuenta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Glosa</label>
                                <input type="text" class="form-control" name="glosa" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Razón Social</label>
                                <input type="text" class="form-control" name="razon_social" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>N° Factura</label>
                                <input type="text" class="form-control" name="nro_factura" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Responsable</label>
                                <select name="responsable" id="responsable" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Tito">Tito</option>
                                    <option value="Aldo">Aldo</option>
                                    <option value="Augusto">Augusto</option>
                                    <option value="Arnold">Arnold</option>
                                    <option value="Plinio">Plinio</option>
                                    <option value="Jose">Jose</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Método de Pago</label>
                                <select class="form-control" name="metodo_pago" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Por pagar">Por pagar</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Subtotal</label>
                                <input type="number" step="0.01" class="form-control" name="subtotal" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Descuento</label>
                                <input type="number" step="0.01" class="form-control" name="descuento" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nueva Nombre Cuenta -->
    <div class="modal fade" id="modalNuevaCuenta" tabindex="-1" role="dialog" aria-labelledby="modalNuevaCuentaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('cuentas.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevaCuentaLabel">Agregar Nombre Cuenta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre Cuenta</label>
                            <input type="text" class="form-control" name="nombre_cuenta" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Filtrar por Fecha -->
    <div class="modal fade" id="modalFiltroFecha" tabindex="-1" role="dialog" aria-labelledby="modalFiltroFechaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('egresos.index') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFiltroFechaLabel">Filtrar Egresos por Fecha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio"
                                value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" value="{{ request('fecha_fin') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">Aplicar Filtro</button>
                        <a href="{{ route('egresos.index') }}" class="btn btn-warning">Limpiar Filtro</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Registro exitoso',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
        {{ session()->forget('success') }}
    @endif
    <script>
        $(document).ready(function () {
            $('#table-egresos').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[0, "desc"]], // Ordenar por fecha descendente
                "pageLength": 25,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'excel', 'pdf'
                ]
            });
        });
    </script>
@endpush