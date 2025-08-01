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
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Aquí puedes gestionar los ingresos.</p>
            </div>
            <div class="d-none d-md-block">
                <table class="table table-striped" id="table-1">
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
                <div class="d-flex justify-content-center">
                    {{-- Paginación aquí si la necesitas --}}
                </div>
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
    
@endpush