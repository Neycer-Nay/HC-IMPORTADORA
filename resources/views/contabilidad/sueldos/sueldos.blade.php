@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1 style="color:#151414">Sueldos de Trabajadores</h1>
            <div class="section-header-breadcrumb">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalTrabajador">
                    <i class="fas fa-user-plus"></i> Agregar Trabajador
                </button>
                <button class="btn btn-success" data-toggle="modal" data-target="#modalPago">
                    <i class="fas fa-money-bill-wave"></i> Registrar Pago
                </button>
                <button class="btn btn-info" data-toggle="modal" data-target="#modalFiltros">
                    <i class="fas fa-filter"></i> Filtrar por Trabajador
                </button>
            </div>
        </div>
        <div class="section-body">
            <p style="font-size: 1.2em;">Gestión completa de planilla de sueldos de trabajadores.</p>

            <!-- Resumen de totales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Salarios</h6>
                            <h4>{{ number_format($totalSalarios, 2) }} Bs</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Total Anticipos</h6>
                            <h4>{{ number_format($totalAnticipos, 2) }} Bs</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Total Descuentos</h6>
                            <h4>{{ number_format($totalDescuentos, 2) }} Bs</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Pagable</h6>
                            <h4>{{ number_format($totalPagable, 2) }} Bs</h4>
                        </div>
                    </div>
                </div>
            </div>

            @if($filtroTrabajador)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Mostrando registros de: <strong>{{ $filtroTrabajador->nombre }}</strong>
                    <a href="{{ route('sueldos.index') }}" class="btn btn-sm btn-warning ml-2">
                        <i class="fas fa-times"></i> Limpiar Filtro
                    </a>
                </div>
            @endif
        </div>

        <!-- Tabla de sueldos -->
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="table-sueldos">
                <thead>
                    <tr class="thead-dark">
                        <th>Trabajador</th>
                        <th>Cargo</th>
                        <th>Mes</th>
                        <th>Salario</th>
                        <th>Anticipos</th>
                        <th>Descuentos</th>
                        <th>Horas Extras</th>
                        <th>Total Pagable</th>
                        <th>Fecha de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sueldos as $sueldo)
                        <tr>
                            <td>{{ $sueldo->trabajador->nombre }}</td>
                            <td>{{ $sueldo->trabajador->cargo }}</td>
                            <td>{{ $sueldo->mes }}</td>
                            <td>{{ number_format($sueldo->salario, 2) }} Bs</td>
                            <td>{{ number_format($sueldo->anticipos, 2) }} Bs</td>
                            <td>{{ number_format($sueldo->descuentos, 2) }} Bs</td>
                            <td>{{ number_format($sueldo->horas_extras, 2) }} Bs</td>
                            <td class="text-success font-weight-bold">{{ number_format($sueldo->total_pagable, 2) }} Bs</td>
                            <td>{{ $sueldo->fecha_pago ? \Carbon\Carbon::parse($sueldo->fecha_pago)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay registros de sueldos</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($sueldos) > 0)
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">TOTALES:</th>
                            <th>{{ number_format($totalSalarios, 2) }} Bs</th>
                            <th>{{ number_format($totalAnticipos, 2) }} Bs</th>
                            <th>{{ number_format($totalDescuentos, 2) }} Bs</th>
                            <th>{{ number_format($totalHorasExtras, 2) }} Bs</th>
                            <th class="text-success">{{ number_format($totalPagable, 2) }} Bs</th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </section>
</div>

<!-- Modal para agregar trabajador -->
<div class="modal fade" id="modalTrabajador" tabindex="-1" role="dialog" aria-labelledby="modalTrabajadorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('sueldos.storeTrabajador') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTrabajadorLabel">Agregar Trabajador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Cargo *</label>
                        <input type="text" name="cargo" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para registrar pago -->
<div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('sueldos.storePago') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagoLabel">Registrar Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Trabajador *</label>
                                <select name="trabajador_id" class="form-control" required>
                                    <option value="">Seleccione un trabajador</option>
                                    @foreach($trabajadores as $trabajador)
                                        <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }} - {{ $trabajador->cargo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mes *</label>
                                <input type="month" name="mes" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Salario *</label>
                                <input type="number" name="salario" id="salario" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Anticipos</label>
                                <input type="number" name="anticipos" id="anticipos" class="form-control" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descuentos</label>
                                <input type="number" name="descuentos" id="descuentos" class="form-control" step="0.01" min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label>Horas Extras</label>
                                <input type="number" name="horas_extras" id="horasExtras" class="form-control" step="0.01" min="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Pago *</label>
                                <input type="date" name="fecha_pago" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Líquido Pagable (Vista Previa)</label>
                                <input type="text" id="totalPagable" class="form-control" readonly style="background-color: #f8f9fa; font-weight: bold;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Pago
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('sueldos.index') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFiltrosLabel">Filtrar por Trabajador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="color: #151414;">
                        <label>Seleccionar Trabajador</label>
                        <select name="trabajador_id" class="form-control">
                            <option value="">Todos los trabajadores</option>
                            @foreach($trabajadores as $trabajador)
                                <option value="{{ $trabajador->id }}" {{ request('trabajador_id') == $trabajador->id ? 'selected' : '' }}>
                                    {{ $trabajador->nombre }} - {{ $trabajador->cargo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Aplicar Filtro</button>
                    <a href="{{ route('sueldos.index') }}" class="btn btn-warning">Limpiar Filtro</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#table-sueldos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[0, "asc"]],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "responsive": true,
        "dom": 'Blfrtip',
        "buttons": [
            {
                extend: 'copy',
                text: 'Copiar',
                className: 'btn btn-secondary',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                        page: 'all'
                    }
                }
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                        page: 'all'
                    }
                },
                customizeData: function (data) {
                    // Agrega los totales al final del Excel
                    data.body.push([
                        '',
                        '',
                        'TOTALES',
                        '{{ number_format($totalSalarios, 2) }} Bs',
                        '{{ number_format($totalAnticipos, 2) }} Bs',
                        '{{ number_format($totalDescuentos, 2) }} Bs',
                        '{{ number_format($totalHorasExtras, 2) }} Bs',
                        '{{ number_format($totalPagable, 2) }} Bs',
                        ''
                    ]);
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                orientation: 'landscape',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                        page: 'all'
                    }
                },
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
                                    { text: 'Planilla de Sueldos', alignment: 'center', fontSize: 18, margin: [0, 5, 0, 0] }
                                ],
                                width: '*'
                            }
                        ],
                        margin: [0, 0, 0, 12]
                    });

                    // Agrega los totales al final del PDF
                    doc.content.push({
                        table: {
                            widths: ['13%', '10%', '10%', '12%', '12%', '12%', '12%', '12%', '7%'],
                            body: [
                                [
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: 'TOTALES', bold: true, alignment: 'center', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalSalarios, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalAnticipos, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalDescuentos, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalHorasExtras, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalPagable, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] }
                                ]
                            ]
                        },
                        layout: 'noBorders',
                        margin: [0, 10, 0, 0]
                    });

                    // Elimina el título por defecto si aparece duplicado
                    if (doc.content.length > 1 && doc.content[1].text) {
                        doc.content.splice(1, 1);
                    }
                }
            }
        ]
    });

    // Calcular total pagable en tiempo real
    function calcularTotalPagable() {
        let salario = parseFloat($('#salario').val()) || 0;
        let anticipos = parseFloat($('#anticipos').val()) || 0;
        let descuentos = parseFloat($('#descuentos').val()) || 0;
        let horasExtras = parseFloat($('#horasExtras').val()) || 0;
        
        let total = salario + horasExtras - anticipos - descuentos;
        $('#totalPagable').val(total.toFixed(2) + ' Bs');
    }

    // Event listeners para cálculo automático
    $('#salario, #anticipos, #descuentos, #horasExtras').on('input', calcularTotalPagable);
});
</script>
@endpush