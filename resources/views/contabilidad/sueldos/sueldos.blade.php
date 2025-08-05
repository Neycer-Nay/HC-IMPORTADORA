
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
                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalAnticipo">
                        <i class="fas fa-hand-holding-usd"></i> Registrar Anticipo
                    </button>
                    <button class="btn btn-info" data-toggle="modal" data-target="#modalFiltros">
                        <i class="fas fa-filter"></i> Filtros Avanzados
                    </button>
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Gestión completa de planilla de sueldos de trabajadores.</p>

                @if($filtroTrabajador || !empty(array_filter($filtros)))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        @if($filtroTrabajador)
                            Mostrando registros de: <strong>{{ $filtroTrabajador->nombre }}</strong><br>
                        @endif
                        @if($filtros['fecha_inicio'])
                            Desde: <strong>{{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }}</strong>
                        @endif
                        @if($filtros['fecha_fin'])
                            Hasta: <strong>{{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}</strong>
                        @endif
                        @if($filtros['tipo_pago'])
                            Tipo: <strong>{{ ucfirst(str_replace('_', ' ', $filtros['tipo_pago'])) }}</strong>
                        @endif
                        @if($filtros['nombre_trabajador'])
                            Nombre: <strong>{{ $filtros['nombre_trabajador'] }}</strong>
                        @endif
                        <a href="{{ route('sueldos.index') }}" class="btn btn-sm btn-warning ml-2">
                            <i class="fas fa-times"></i> Limpiar Filtros
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
                            <th>Mes/Período</th>
                            <th>Tipo de Pago</th>
                            <th>Salario</th>
                            <th>Anticipos</th>
                            <th>Descuentos</th>
                            <th>Horas Extras</th>
                            <th>Total Pagable</th>
                            <th>Saldo Pendiente</th>
                            <th>Fecha de Pago</th>
                            <!--<th>Observaciones</th>-->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sueldos as $sueldo)
                            <tr class="{{ $sueldo->tipo_pago == 'anticipo' ? 'table-warning' : ($sueldo->tipo_pago == 'pago_final' ? 'table-success' : '') }}">
                                <td>{{ $sueldo->trabajador->nombre }}</td>
                                <td>{{ $sueldo->trabajador->cargo }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($sueldo->mes . '-01')->locale('es')->translatedFormat('F') }}
                                    @if($sueldo->periodo_mes_anio)
                                        <br><small class="text-muted">{{ $sueldo->periodo_mes_anio }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($sueldo->tipo_pago)
                                        @case('anticipo')
                                            <span class="badge badge-warning">Anticipo</span>
                                            @break
                                        @case('pago_final')
                                            <span class="badge badge-success">Pago Final</span>
                                            @break
                                        @default
                                            <span class="badge badge-primary">Salario Completo</span>
                                    @endswitch
                                </td>
                                <td>{{ number_format($sueldo->salario, 2) }} Bs</td>
                                <td>{{ number_format($sueldo->anticipos, 2) }} Bs</td>
                                <td>{{ number_format($sueldo->descuentos, 2) }} Bs</td>
                                <td>{{ number_format($sueldo->horas_extras, 2) }} Bs</td>
                                <td><strong>{{ number_format($sueldo->total_pagable, 2) }} Bs</strong></td>
                                <td>
                                    @if($sueldo->saldo_pendiente > 0)
                                        <span>{{ number_format($sueldo->saldo_pendiente, 2) }} Bs</span>
                                    @else
                                        <span>0.00 Bs</span>
                                    @endif
                                </td>
                                <td>{{ $sueldo->fecha_pago ? \Carbon\Carbon::parse($sueldo->fecha_pago)->format('d/m/Y') : '-' }}</td>
                                <!--<td>
                                    @if($sueldo->observaciones)
                                        <span data-toggle="tooltip" title="{{ $sueldo->observaciones }}">
                                            <i class="fas fa-comment-alt text-info"></i>
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>-->
                                <td>
                                    <form action="{{ route('sueldos.destroy', $sueldo->id) }}" method="POST" class="form-eliminar" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                    @if(count($sueldos) > 0)
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right">TOTALES:</th>
                                <th>{{ number_format($totalSalarios, 2) }} Bs</th>
                                <th>{{ number_format($totalAnticipos, 2) }} Bs</th>
                                <th>{{ number_format($totalDescuentos, 2) }} Bs</th>
                                <th>{{ number_format($totalHorasExtras, 2) }} Bs</th>
                                <th><strong>{{ number_format($totalPagable, 2) }} Bs</strong></th>
                                <th></th>
                                <th></th>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo *</label>
                                    <input type="text" name="cargo" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sueldo Base Mensual *</label>
                                    <input type="number" name="sueldo_base" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Ingreso</label>
                                    <input type="date" name="fecha_ingreso" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
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
                                    <select name="trabajador_id" id="trabajadorSelect" class="form-control" required>
                                        <option value="">Seleccione un trabajador</option>
                                        @foreach($trabajadores as $trabajador)
                                            <option value="{{ $trabajador->id }}" data-sueldo="{{ $trabajador->sueldo_base }}">
                                                {{ $trabajador->nombre }} - {{ $trabajador->cargo }} ({{ number_format($trabajador->sueldo_base, 2) }} Bs)
                                            </option>
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
                                    <label>Tipo de Pago *</label>
                                    <select name="tipo_pago" id="tipoPago" class="form-control" required>
                                        <option value="">Seleccione tipo de pago</option>
                                        <option value="salario_completo">Salario Completo</option>
                                        <option value="pago_final">Pago Final (Descontando Anticipos)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Pago *</label>
                                    <input type="date" name="fecha_pago" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salario Base *</label>
                                    <input type="number" name="salario" id="salario" class="form-control" step="0.01" min="0" readonly style="background-color: #f8f9fa;">
                                    <small class="text-muted">Se carga automáticamente desde el perfil del trabajador</small>
                                </div>
                                <div class="form-group">
                                    <label>Horas Extras</label>
                                    <input type="number" name="horas_extras" id="horasExtras" class="form-control" step="0.01" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Descuentos</label>
                                    <input type="number" name="descuentos" id="descuentos" class="form-control" step="0.01" min="0" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Anticipos (Solo si es salario completo)</label>
                                    <input type="number" name="anticipos" id="anticipos" class="form-control" step="0.01" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Líquido Pagable (Vista Previa)</label>
                                    <input type="text" id="totalPagable" class="form-control" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales..."></textarea>
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

    <!-- Modal para registrar anticipo -->
    <div class="modal fade" id="modalAnticipo" tabindex="-1" role="dialog" aria-labelledby="modalAnticipoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('sueldos.storePago') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo_pago" value="anticipo">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalAnticipoLabel">Registrar Anticipo</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Los anticipos son pagos parciales que se descontarán del salario final del mes.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Trabajador *</label>
                                    <select name="trabajador_id" id="trabajadorAnticipoSelect" class="form-control" required>
                                        <option value="">Seleccione un trabajador</option>
                                        @foreach($trabajadores as $trabajador)
                                            <option value="{{ $trabajador->id }}" data-sueldo="{{ $trabajador->sueldo_base }}">
                                                {{ $trabajador->nombre }} - {{ $trabajador->cargo }} ({{ number_format($trabajador->sueldo_base, 2) }} Bs)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mes del Anticipo *</label>
                                    <input type="month" name="mes" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto del Anticipo *</label>
                                    <input type="number" name="anticipos" id="montoAnticipo" class="form-control" step="0.01" min="0" required>
                                    <small class="text-muted">Máximo 80% del sueldo base: <span id="maxAnticipo">0.00</span> Bs</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha del Anticipo *</label>
                                    <input type="date" name="fecha_pago" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2" placeholder="Motivo del anticipo, observaciones..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-hand-holding-usd"></i> Registrar Anticipo
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para filtros avanzados -->
    <div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('sueldos.index') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFiltrosLabel">Filtros Avanzados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Trabajador</label>
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Desde</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Hasta</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">Aplicar Filtros</button>
                        <a href="{{ route('sueldos.index') }}" class="btn btn-warning">Limpiar Filtros</a>
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
                    columns: ':not(:last-child)',
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                        page: 'all'
                    }
                },
                customizeData: function (data) {
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
                    columns: ':not(:last-child)',
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

                    var tablaPrincipal = doc.content.find(function (c) {
                        return c.table && c.table.body && c.table.body.length > 1;
                    });
                    if (tablaPrincipal) {
                        tablaPrincipal.table.widths = [
                            '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*'
                        ];
                        tablaPrincipal.table.body.forEach(function(row, idx) {
                            if (idx === 0) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                    cell.bold = true;
                                });
                            } else {
                                row.forEach(function(cell, i) {
                                    if (i >= 4) {
                                        cell.alignment = 'right';
                                    } else {
                                        cell.alignment = 'left';
                                    }
                                });
                            }
                        });
                    }

                    doc.content.push({
                        table: {
                            widths: ['10%', '5%', '10%', '10%', '10%', '10%', '8%', '10%', '10%', '10%', '10%'],
                            body: [
                                [
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: 'TOTALES', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalSalarios, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalAnticipos, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalDescuentos, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalHorasExtras, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '{{ number_format($totalPagable, 2) }} Bs', bold: true, alignment: 'right', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] }
                                ]
                            ]
                        },
                        layout: 'noBorders',
                        margin: [0, 10, 0, 0]
                    });

                    if (doc.content.length > 1 && doc.content[1].text) {
                        doc.content.splice(1, 1);
                    }
                }
            }
        ]
    });

    function calcularTotalPagable() {
        let salario = parseFloat($('#salario').val()) || 0;
        let anticipos = parseFloat($('#anticipos').val()) || 0;
        let descuentos = parseFloat($('#descuentos').val()) || 0;
        let horasExtras = parseFloat($('#horasExtras').val()) || 0;

        let total = salario + horasExtras - anticipos - descuentos;
        $('#totalPagable').val(total.toFixed(2) + ' Bs');
    }

    $('#salario, #anticipos, #descuentos, #horasExtras').on('input', calcularTotalPagable);

    $('#trabajadorSelect').on('change', function() {
        const sueldoBase = $(this).find(':selected').data('sueldo');
        if (sueldoBase) {
            $('#salario').val(sueldoBase);
            calcularTotalPagable();
        } else {
            $('#salario').val('');
        }
    });

    $('#trabajadorAnticipoSelect').on('change', function() {
        const sueldoBase = $(this).find(':selected').data('sueldo');
        if (sueldoBase) {
            const maxAnticipo = sueldoBase * 0.8;
            $('#maxAnticipo').text(maxAnticipo.toFixed(2));
            $('#montoAnticipo').attr('max', maxAnticipo);
        } else {
            $('#maxAnticipo').text('0.00');
            $('#montoAnticipo').removeAttr('max');
        }
    });

    $('#montoAnticipo').on('input', function() {
        const monto = parseFloat($(this).val()) || 0;
        const maxAnticipo = parseFloat($('#montoAnticipo').attr('max')) || 0;

        if (monto > maxAnticipo) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">El monto excede el máximo permitido</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    $(document).on('submit', '.form-eliminar', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Deseas eliminar este registro de sueldo? Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush