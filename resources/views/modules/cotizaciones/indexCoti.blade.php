@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414">Cotizaciones</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 style="font-size:21px">Lista de cotizaciones</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <label style="color:#151414; font-size: 17px;" for="">Buscar cotizaciones por N° de
                                        cotización, recepción o cliente</label>
                                    <form method="GET" action="{{ route('cotizaciones.index') }}" class="form-inline mb-3">
                                        <input type="text" name="buscar" class="form-control mr-2" placeholder="Buscar"
                                            value="{{ request('buscar') }}">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                            Buscar</button>
                                        @if(request('buscar'))
                                            <a href="{{ route('cotizaciones.index') }}"
                                                class="btn btn-secondary ml-2">Limpiar</a>
                                        @endif
                                    </form>

                                    <!-- Tabla para pantallas medianas y grandes -->
                                    <div class="d-none d-md-block">
                                        <table class="table table-striped" id="table-1">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>

                                                    <th>N° Recepción</th>
                                                    <th>Fecha</th>
                                                    <th>Cliente</th>
                                                    <th>Subtotal</th>
                                                    <th>Total</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cotizaciones as $cotizacion)
                                                    <tr>
                                                        <td>{{ $cotizacion->id }}</td>

                                                        <td>{{ $cotizacion->recepcion->numero_recepcion }}</td>
                                                        <td>{{ $cotizacion->fecha}}</td>
                                                        <td>{{ $cotizacion->recepcion->cliente->nombre ?? 'N/A' }}</td>
                                                        <td>{{ number_format($cotizacion->subtotal, 2) }}Bs</td>
                                                        <td>{{ number_format($cotizacion->total, 2) }}Bs</td>
                                                        <td>
                                                            <a href="{{ route('cotizaciones.show', $cotizacion->recepcion->id) }}"
                                                                class="btn btn-primary btn-sm" title="Editar">
                                                                <i class="fas fa-edit"></i> Ver
                                                            </a>
                                                            
                                                            <a href="{{ route('cotizaciones.pdf', $cotizacion->id) }}"
                                                                class="btn btn-danger" target="_blank">
                                                                <i class="fas fa-file-pdf"></i> Generar PDF
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            {{ $cotizaciones->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>

                                    <!-- Tarjetas para pantallas pequeñas -->
                                    <div class="d-block d-md-none">
                                        @foreach($cotizaciones as $cotizacion)
                                            <div class="card mb-2">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title mb-1"><strong>Cotización N°:</strong>
                                                        {{ $cotizacion->recepcion->numero_recepcion }}</h5>
                                                    <p class="mb-1"></p>
                                                    <p class="mb-1"><strong>Fecha:</strong> {{ $cotizacion->fecha}}</p>
                                                    <p class="mb-1"><strong>Cliente:</strong>
                                                        {{ $cotizacion->recepcion->cliente->nombre ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>Subtotal:</strong>
                                                        {{ number_format($cotizacion->subtotal, 2) }}Bs</p>
                                                    <p class="mb-1"><strong>Total:</strong>
                                                        {{ number_format($cotizacion->total, 2) }}Bs</p>
                                                    <div class="d-flex">
                                                        <a href="{{ route('cotizaciones.edit', $cotizacion->recepcion->id) }}"
                                                            class="btn btn-primary btn-sm mr-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('cotizaciones.pdf', $cotizacion->id) }}"
                                                            class="btn btn-danger" target="_blank">
                                                            <i class="fas fa-file-pdf"></i> Generar PDF
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-center">
                                            {{ $cotizaciones->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

 @if(session('swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    position: 'center',
                    icon: '{{ session('swal.icon') }}',
                    title: '{{ session('swal.title') }}',
                    text: '{{ session('swal.text') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
    @endif
