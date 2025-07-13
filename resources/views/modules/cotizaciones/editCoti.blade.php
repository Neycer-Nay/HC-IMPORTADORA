@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div  class="section-header"  >
            <h1 style="color:#151414"><i class="fas fa-file-invoice-dollar"></i> Cotización para Recepción N° {{ $recepcion->numero_recepcion }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('cotizaciones.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="section-body">
            <!-- Datos del Cliente -->
            <div class="card card-primary shadow-sm mb-4">
                <div class="card-header ">
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

            <form action="{{ route('cotizaciones.store', $recepcion->id) }}" method="POST">
                @csrf
                
                <!-- Sección de Descuento -->
                <div class="card card-info shadow-sm mb-4">
                    <div class="card-header  ">
                        <h5><i class="fas fa-percentage"></i> Descuento General</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row align-items-center">
                            <label for="descuento" class="col-md-2 col-form-label text-md-right">
                                <strong>Monto de descuento (Bs):</strong>
                            </label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="number" min="0" step="0.01" name="descuento" id="descuento" 
                                           class="form-control" value="{{ old('descuento', 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipos -->
                @foreach($recepcion->equipos as $equipo)
                <div class="card card-warning shadow-sm mb-4">
                    <div class="card-header  text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-tools"></i> Equipo: {{ $equipo->nombre }}
                            <small class="text-muted">({{ $equipo->tipo }})</small>
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <input type="hidden" name="equipos[{{ $loop->index }}][equipo_id]" value="{{ $equipo->id }}">
                        
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
                                    
                                    <!-- Fotos del equipo -->
                                    <div class="mt-4">
                                        <h6 class="text-primary"><i class="fas fa-camera"></i> Fotos Adjuntas</h6>
                                        <div class="fotos-container d-flex flex-wrap gap-2">
                                            @if($equipo->fotos && $equipo->fotos->count())
                                                @foreach($equipo->fotos as $foto)
                                                <div class="foto-item position-relative">
                                                    <label class="d-block">
                                                        <input type="checkbox" 
                                                               name="equipos[{{ $loop->parent->index }}][fotos][]"
                                                               value="{{ $foto->id }}" 
                                                               class="foto-checkbox visually-hidden"
                                                               {{ isset(old('equipos')[$loop->parent->index]['fotos']) && in_array($foto->id, old('equipos')[$loop->parent->index]['fotos']) ? 'checked' : '' }}>
                                                        <img src="{{ asset('storage/' . $foto->ruta) }}" 
                                                             alt="Foto del equipo" 
                                                             class="img-thumbnail foto-img">
                                                        <div class="foto-overlay">
                                                            
                                                        </div>
                                                    </label>
                                                </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">No hay fotos disponibles</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Columna derecha - Formulario de cotización -->
                            <div class="col-lg-7">
                                <!-- Descripción del trabajo -->
                                <div class="mb-4">
                                    <label for="descripcion_{{ $equipo->id }}" class="form-label text-primary">
                                        <i class="fas fa-clipboard-list"></i> Descripción del trabajo a realizar
                                    </label>
                                    <textarea name="equipos[{{ $loop->index }}][descripcion]"
                                              id="descripcion_{{ $equipo->id }}" 
                                              class="form-control" 
                                              rows="3"
                                              placeholder="Describa detalladamente el trabajo a realizar...">{{ old('equipos.' . $loop->index . '.descripcion') }}</textarea>
                                </div>
                                
                                <!-- Valor del trabajo -->
                                <div class="mb-4">
                                    <label for="valor_trabajo_{{ $equipo->id }}" class="form-label text-primary">
                                        <i class="fas fa-dollar-sign"></i> Valor del trabajo (Bs)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" min="0" step="0.01"
                                               name="equipos[{{ $loop->index }}][valor_trabajo]"
                                               id="valor_trabajo_{{ $equipo->id }}" 
                                               class="form-control"
                                               value="{{ old('equipos.' . $loop->index . '.valor_trabajo', 0) }}">
                                    </div>
                                </div>
                                
                                <!-- Repuestos -->
                                <div class="repuestos-container">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-cogs"></i> Repuestos a utilizar
                                        <button type="button" class="btn btn-primary"
                                                onclick="agregarRepuesto({{ $loop->index }})">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                        
                                    </h6>
                                    
                                    <div id="repuestos-container-{{ $loop->index }}">
                                        @php
                                            $repuestos = old('equipos.' . $loop->index . '.repuestos_detalle', []);
                                            if(empty($repuestos)) {
                                                $repuestos = [['nombre' => '', 'cantidad' => 1, 'precio' => 0]];
                                            }
                                        @endphp
                                        
                                        @foreach($repuestos as $index => $repuesto)
                                        <div class="repuesto-item card mb-3">
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label small">Nombre del repuesto</label>
                                                        <input type="text"
                                                               name="equipos[{{ $loop->parent->index }}][repuestos_detalle][{{ $index }}][nombre]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $repuesto['nombre'] }}"
                                                               required>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <label class="form-label small">Cantidad</label>
                                                        <input type="number" min="1"
                                                               name="equipos[{{ $loop->parent->index }}][repuestos_detalle][{{ $index }}][cantidad]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $repuesto['cantidad'] }}"
                                                               required>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <label class="form-label small">Precio (Bs)</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">Bs</span>
                                                            <input type="number" min="0" step="0.01"
                                                                   name="equipos[{{ $loop->parent->index }}][repuestos_detalle][{{ $index }}][precio]"
                                                                   class="form-control"
                                                                   value="{{ $repuesto['precio'] }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end mt-2">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="this.closest('.repuesto-item').remove()">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Botones de acción -->
                <div class="fixed-bottom bg-white p-3 shadow-lg border-top">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('cotizaciones.pdf', $recepcion->id) }}" 
                               class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf"></i> Generar PDF
                            </a>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Cotización
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
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
    
    .foto-checkbox:checked + .foto-img {
        border: 3px solid #0d6efd;
        opacity: 0.7;
    }
    
    .foto-overlay {
        position: absolute;
        top: 5px;
        right: 5px;
        color: #0d6efd;
        opacity: 0;
        transition: all 0.3s;
    }
    
    .foto-checkbox:checked + .foto-img + .foto-overlay {
        opacity: 1;
    }
    
    .repuesto-item {
        border-left: 3px solid #0dcaf0;
    }
    
    .fixed-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1030;
    }
    
    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>
@endsection

@section('scripts')
<script>
    function agregarRepuesto(equipoIndex) {
        const container = document.getElementById(`repuestos-container-${equipoIndex}`);
        const count = container.querySelectorAll('.repuesto-item').length;
        
        const html = `
        <div class="repuesto-item card mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small">Nombre del repuesto</label>
                        <input type="text"
                               name="equipos[${equipoIndex}][repuestos_detalle][${count}][nombre]"
                               class="form-control form-control-sm"
                               required>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label small">Cantidad</label>
                        <input type="number" min="1"
                               name="equipos[${equipoIndex}][repuestos_detalle][${count}][cantidad]"
                               class="form-control form-control-sm"
                               value="1"
                               required>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label small">Precio (Bs)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Bs</span>
                            <input type="number" min="0" step="0.01"
                                   name="equipos[${equipoIndex}][repuestos_detalle][${count}][precio]"
                                   class="form-control"
                                   value="0"
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-2">
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="this.closest('.repuesto-item').remove()">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }
    
    // Mejorar interacción con fotos
    document.querySelectorAll('.foto-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if(e.target.tagName !== 'INPUT') {
                const checkbox = this.querySelector('.foto-checkbox');
                checkbox.checked = !checkbox.checked;
                this.querySelector('.foto-img').classList.toggle('border-primary');
            }
        });
    });
</script>
@endsection