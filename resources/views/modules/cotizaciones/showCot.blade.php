@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header bg-primary text-white">
            <h1><i class="fas fa-file-invoice-dollar"></i> Detalle de Cotización N° {{ $recepcion->numero_recepcion }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('cotizaciones.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="section-body">
            <!-- Datos del Cliente -->
            <div class="card card-primary shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-user-tie"></i> Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-id-card"></i> Nombre:</strong> {{ $recepcion->cliente->nombre }}</p>
                            <p><strong><i class="fas fa-phone"></i> Teléfono:</strong> {{ $recepcion->cliente->telefono_1 }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $recepcion->cliente->email }}</p>
                            <p><strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong> {{ $recepcion->cliente->ciudad }} - {{ $recepcion->cliente->direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de la cotización -->
            <div class="card card-info shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-receipt"></i> Resumen de la Cotización</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="border p-3 rounded bg-light">
                                <h6 class="text-center text-primary">Subtotal</h6>
                                <h4 class="text-center">Bs. {{ number_format($cotizacion->subtotal, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded bg-light">
                                <h6 class="text-center text-danger">Descuento</h6>
                                <h4 class="text-center">Bs. {{ number_format($cotizacion->descuento, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded bg-success text-white">
                                <h6 class="text-center">Total</h6>
                                <h4 class="text-center">Bs. {{ number_format($cotizacion->total, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipos cotizados -->
            @foreach($recepcion->equipos as $equipo)
                @php
        $cotizacionEquipo = $equipo->cotizaciones ? $equipo->cotizaciones->where('cotizacion_id', $cotizacion->id)->first() : null;
    @endphp
                
                 @if($cotizacionEquipo)
                <div class="card card-warning shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-tools"></i> Equipo: {{ $equipo->nombre }}
                            <small class="text-muted">({{ $equipo->tipo }})</small>
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Columna izquierda - Datos del equipo -->
                            <div class="col-lg-5 border-end">
                                <div class="equipo-info p-3">
                                    <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información Técnica</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Serie:</strong> {{ $equipo->numero_serie }}</li>
                                        <li><strong>Marca:</strong> {{ $equipo->marca }}</li>
                                        <li><strong>Modelo:</strong> {{ $equipo->modelo }}</li>
                                        @if($equipo->tipo == 'MOTOR_ELECTRICO')
                                            <li><strong>HP:</strong> {{ $equipo->hp ?? 'N/A' }}</li>
                                            <li><strong>RPM:</strong> {{ $equipo->rpm ?? 'N/A' }}</li>
                                        @endif
                                        <!-- Más campos según tipo -->
                                    </ul>
                                </div>
                                
                                <!-- Fotos del equipo -->
                                <div class="mt-4">
                                    <h6 class="text-primary"><i class="fas fa-camera"></i> Fotos Incluidas</h6>
                                    <div class="fotos-container d-flex flex-wrap gap-2">
                                        @if($cotizacionEquipo && $cotizacionEquipo->fotos->count())
                                            @foreach($cotizacionEquipo->fotos as $foto)
                                                <div class="foto-item">
                                                    <img src="{{ asset('storage/' . $foto->ruta) }}" 
                                                         alt="Foto del equipo" 
                                                         class="img-thumbnail foto-img"
                                                         data-bs-toggle="modal" 
                                                         data-bs-target="#fotoModal"
                                                         data-bs-img="{{ asset('storage/' . $foto->ruta) }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted">No se incluyeron fotos para este equipo</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Columna derecha - Detalles de la cotización -->
                            <div class="col-lg-7">
                                <!-- Descripción del trabajo -->
                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-clipboard-list"></i> Trabajo a realizar
                                    </h6>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($cotizacionEquipo->trabajo_realizar ?? 'No se especificó trabajo')) !!}
                                    </div>
                                </div>
                                
                                <!-- Valor del trabajo -->
                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-dollar-sign"></i> Valor del trabajo
                                    </h6>
                                    <div class="p-3 bg-light rounded">
                                        <strong>Bs. {{ number_format($cotizacionEquipo->precio_trabajo ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                
                                <!-- Repuestos utilizados -->
                                <div class="repuestos-container">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-cogs"></i> Repuestos utilizados
                                    </h6>
                                    
                                    @if($cotizacionEquipo && $cotizacionEquipo->repuestos->count())
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Repuesto</th>
                                                        <th width="100">Cantidad</th>
                                                        <th width="120">P. Unitario</th>
                                                        <th width="120">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cotizacionEquipo->repuestos as $repuesto)
                                                    <tr>
                                                        <td>{{ $repuesto->nombre }}</td>
                                                        <td class="text-center">{{ $repuesto->cantidad }}</td>
                                                        <td class="text-end">Bs. {{ number_format($repuesto->precio_unitario, 2) }}</td>
                                                        <td class="text-end">Bs. {{ number_format($repuesto->cantidad * $repuesto->precio_unitario, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr class="table-secondary">
                                                        <td colspan="3" class="text-end"><strong>Total repuestos:</strong></td>
                                                        <td class="text-end"><strong>Bs. {{ number_format($cotizacionEquipo->total_repuestos, 2) }}</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            No se registraron repuestos para este equipo
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Botones de acción -->
            <div class="fixed-bottom bg-white p-3 shadow-lg border-top">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cotizaciones.pdf', $recepcion->id) }}" 
                           class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                        
                        <a href="{{ route('cotizaciones.edit', $cotizacion->id) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Cotización
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal para ver fotos en grande -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto del equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFoto" src="" class="img-fluid" alt="Foto ampliada">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estilos personalizados */
    .equipo-info {
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    
    .foto-item {
        position: relative;
        cursor: pointer;
    }
    
    .foto-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        transition: all 0.3s;
    }
    
    .foto-img:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    
    .fixed-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1030;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
</style>
@endsection

@section('scripts')
<script>
    // Activar el modal para ver fotos en grande
    const fotoModal = document.getElementById('fotoModal');
    if (fotoModal) {
        fotoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imgSrc = button.getAttribute('data-bs-img');
            const modalImg = fotoModal.querySelector('#modalFoto');
            modalImg.src = imgSrc;
        });
    }
</script>
@endsection