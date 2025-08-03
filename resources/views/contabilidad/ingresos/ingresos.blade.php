@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414">Ingresos</h1>
                <div class="section-header-breadcrumb">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoIngreso">
                        <i class="fas fa-plus"></i> Nuevo Ingreso
                    </a>
                    <a href="#" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalFiltroFecha">
                        <i class="fas fa-filter"></i> Filtrar por Fecha
                    </a>
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Aquí puedes gestionar los ingresos.</p>
            </div>
            <div class=" d-md-block table-responsive">
                <table class="table table-striped" id="table-ingresos">
                    <thead>
                        <tr>

                            <th>Fecha</th>
                            <th>Tipo de Ingreso</th>
                            <th>Glosa</th>
                            <th>Razón Social</th>
                            <th>N° de Recibo</th>
                            <th>Método de Pago</th>
                            <th>Subtotal</th>
                            <th>Descuento</th>
                            <th>Total</th>
                            <th>Estado Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingresos as $ingreso)
                            <tr>

                                <td>{{ $ingreso->created_at->format('d/m/Y')}}</td>
                                <td>{{ $ingreso->tipo_ingreso }}</td>
                                <td>{{ $ingreso->glosa }}</td>
                                <td>{{ $ingreso->razon_social }}</td>
                                <td>{{ Str::upper($ingreso->nro_recibo) }}</td>
                                <td>{{ $ingreso->metodo_pago }}</td>
                                <td>{{ number_format($ingreso->subtotal, 2) }}Bs</td>
                                <td>{{ number_format($ingreso->descuento, 2) }}Bs</td>
                                <td>{{ number_format($ingreso->total, 2) }}Bs</td>
                                <td>{{ $ingreso->estado_pago }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </section>
    </div>

    <!-- Modal de Nuevo Ingreso -->
    <div class="modal fade" id="modalNuevoIngreso" tabindex="-1" role="dialog" aria-labelledby="modalNuevoIngresoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"><!-- modal-lg para mayor ancho -->
            <form action="{{ route('ingresos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoIngresoLabel">Registrar Nuevo Ingreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="color: #151414;">
                            <div class="form-group col-md-6">
                                <label>Tipo de Ingreso</label>
                                <select class="form-control" name="tipo_ingreso" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Venta">Venta</option>
                                    <option value="Servicios">Servicios</option>
                                    <option value="Otro">Otro</option>
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
                                <label>N° de Recibo</label>
                                <input type="text" class="form-control" name="nro_recibo" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Método de Pago</label>
                                <select class="form-control" name="metodo_pago" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Por cobrar">Por cobrar</option>
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

                            <div class="form-group col-md-6">
                                <label>Estado de Pago</label>
                                <select name="estado_pago" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Anticipo">Anticipo</option>
                                    <option value="Saldo">Saldo</option>
                                    <option value="Completo">Completo</option>
                                </select>
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

    <!-- Modal Filtrar por Fecha -->
    <div class="modal fade" id="modalFiltroFecha" tabindex="-1" role="dialog" aria-labelledby="modalFiltroFechaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('ingresos.index') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFiltroFechaLabel">Filtrar Ingresos por Fecha</h5>
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
                        <a href="{{ route('ingresos.index') }}" class="btn btn-warning">Limpiar Filtro</a>
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
                    title: 'Ingreso registrado correctamente',
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
            $('#table-ingresos').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[0, "desc"]], // Ordenar por fecha descendente
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                "responsive": true,
                "dom": 'Blfrtip',
                "buttons": [
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        className: 'btn btn-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        orientation: 'landscape',
                        customize: function (doc) {
                            doc.images = doc.images || {};
                            doc.images.logo = 'data:image/png;base64,{{ base64_encode(file_get_contents(public_path("img/logoHc.png"))) }}';

                            doc.content.unshift({
                                columns: [
                                    {
                                        image: 'logo',
                                        width: 100,
                                        alignment: 'left',
                                        margin: [0, 0, 0, 0]
                                    },
                                    {
                                        stack: [
                                            { text: 'HC BOBINADOS INDUSTRIAL', alignment: 'center', fontSize: 16, bold: true, margin: [0, 10, 0, 0] },
                                            { text: 'Reporte de Ingresos', alignment: 'center', fontSize: 18, margin: [0, 5, 0, 0] }
                                        ],
                                        width: '*'
                                    }
                                ],
                                margin: [0, 0, 0, 12]
                            });

                            // Elimina el título por defecto de DataTables
                            doc.content.splice(1, 1);
                        }
                    }
                ]
            });
        });
    </script>
@endpush