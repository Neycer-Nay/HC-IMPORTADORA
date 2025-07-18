@extends('layouts.main')

@section('title', 'Editar Recepción')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-edit"></i> Editar Recepción</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('recepciones.index') }}">Recepciones</a></div>
                <div class="breadcrumb-item">Editar</div>
            </div>
        </div>

        <div class="section-body">
            <form action="{{ route('recepciones.update', $recepcion) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="deleted_photos" name="deleted_photos" value="">

                <!-- Información de la Recepción (No editable) -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-info-circle"></i> Información de la Recepción</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><strong>Número de Recepción</strong></label>
                                    <input type="text" class="form-control" value="{{ $recepcion->numero_recepcion }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><strong>Cliente</strong></label>
                                    <input type="text" class="form-control" value="{{ $recepcion->cliente->nombre }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><strong>Fecha de Ingreso</strong></label>
                                    <input type="text" class="form-control" value="{{ $recepcion->fecha_ingreso ? \Carbon\Carbon::parse($recepcion->fecha_ingreso)->format('d/m/Y') : 'No especificada' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><strong>Hora de Ingreso</strong></label>
                                    <input type="text" class="form-control" value="{{ $recepcion->hora_ingreso }}" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label><strong>Observaciones de la Recepción</strong></label>
                                    <textarea class="form-control" name="observaciones" rows="3">{{ $recepcion->observaciones }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipos -->
                <div class="card">
                    <div class="card-header bg-whitesmoke">
                        <h4><i class="fas fa-desktop"></i> Equipos Recepcionados</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" id="addEquipo">
                                <i class="fas fa-plus"></i> Agregar Equipo
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="equiposContainer">
                        @foreach($recepcion->equipos as $index => $equipo)
                        <div class="card mb-3 equipo-item">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-desktop"></i> Equipo #<span class="equipo-count">{{ $index + 1 }}</span>
                                </h5>
                                <button type="button" class="btn btn-icon btn-danger remove-equipo">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="equipos[{{ $index }}][id]" value="{{ $equipo->id }}">
                                
                                <div class="row">
                                    <!-- Campos que siempre se muestran -->
                                    <div class="form-group col-md-6">
                                        <label><strong>Articulo</strong> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][nombre]" 
                                               value="{{ $equipo->nombre }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label><strong>Número de Serie</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][serie]" 
                                               value="{{ $equipo->numero_serie }}">
                                    </div>

                                    <!-- Tipo de equipo (No editable) -->
                                    <div class="form-group col-md-4">
                                        <label><strong>Tipo de Equipo</strong></label>
                                        <input type="text" class="form-control" 
                                               value="{{ ucwords(str_replace('_', ' ', strtolower($equipo->tipo))) }}" readonly>
                                        <input type="hidden" name="equipos[{{ $index }}][tipo]" value="{{ $equipo->tipo }}">
                                    </div>

                                    <!-- Campos editables -->
                                    <div class="form-group col-md-4">
                                        <label><strong>Marca</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][marca]" 
                                               value="{{ $equipo->marca }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label><strong>Modelo</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][modelo]" 
                                               value="{{ $equipo->modelo }}">
                                    </div>

                                    <!-- Color (No editable pero se muestra) -->
                                    @if($equipo->color)
                                    <div class="form-group col-md-4">
                                        <label><strong>Colores</strong></label>
                                        <input type="text" class="form-control" 
                                               value="{{ str_replace(',', ', ', $equipo->color) }}" readonly>
                                        <input type="hidden" name="equipos[{{ $index }}][color]" value="{{ $equipo->color }}">
                                    </div>
                                    @endif

                                    <!-- Campos específicos según tipo -->
                                    @if($equipo->voltaje)
                                    <div class="form-group col-md-3">
                                        <label><strong>Voltaje</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][voltaje]" 
                                               value="{{ $equipo->voltaje }}">
                                    </div>
                                    @endif

                                    @if($equipo->hp)
                                    <div class="form-group col-md-3">
                                        <label><strong>HP (Caballos de fuerza)</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][hp]" 
                                               value="{{ $equipo->hp }}">
                                    </div>
                                    @endif

                                    @if($equipo->rpm)
                                    <div class="form-group col-md-3">
                                        <label><strong>RPM</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][rpm]" 
                                               value="{{ $equipo->rpm }}">
                                    </div>
                                    @endif

                                    @if($equipo->hz)
                                    <div class="form-group col-md-3">
                                        <label><strong>Hz (Hercios)</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][hz]" 
                                               value="{{ $equipo->hz }}">
                                    </div>
                                    @endif

                                    @if($equipo->amperaje)
                                    <div class="form-group col-md-3">
                                        <label><strong>AMP (Amperio)</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][amperaje]" 
                                               value="{{ $equipo->amperaje }}">
                                    </div>
                                    @endif

                                    @if($equipo->cable_positivo)
                                    <div class="form-group col-md-3">
                                        <label><strong>Cable +</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][cable_positivo]" 
                                               value="{{ $equipo->cable_positivo }}">
                                    </div>
                                    @endif

                                    @if($equipo->cable_negativo)
                                    <div class="form-group col-md-3">
                                        <label><strong>Cable -</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][cable_negativo]" 
                                               value="{{ $equipo->cable_negativo }}">
                                    </div>
                                    @endif

                                    @if($equipo->kva_kw)
                                    <div class="form-group col-md-3">
                                        <label><strong>Kva/Kw</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][kva_kw]" 
                                               value="{{ $equipo->kva_kw }}">
                                    </div>
                                    @endif

                                    @if($equipo->potencia)
                                    <div class="form-group col-md-3">
                                        <label><strong>Potencia</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][potencia]" 
                                               value="{{ $equipo->potencia }}">
                                    </div>
                                    @endif

                                    <!-- Fotos del Equipo -->
                                    <div class="form-group col-12">
                                        <label><strong>Fotos del Equipo</strong></label>
                                        <small class="form-text text-muted mb-2">
                                            <i class="fas fa-info-circle"></i> Puedes eliminar fotos existentes y agregar nuevas
                                        </small>

                                        <!-- Pestañas para seleccionar modo -->
                                        <ul class="nav nav-tabs" id="fotoTabs{{ $index }}" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="archivo-tab{{ $index }}" data-toggle="tab"
                                                    href="#archivo{{ $index }}" role="tab">
                                                    <i class="fas fa-folder-open"></i> Seleccionar archivos
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="camara-tab{{ $index }}" data-toggle="tab" 
                                                   href="#camara{{ $index }}" role="tab">
                                                    <i class="fas fa-camera"></i> Tomar foto
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="fotoTabContent{{ $index }}">
                                            <!-- Pestaña de archivos -->
                                            <div class="tab-pane fade show active" id="archivo{{ $index }}" role="tabpanel">
                                                <div class="custom-file mb-3 mt-3">
                                                    <input type="file" class="custom-file-input" id="fileInput{{ $index }}"
                                                        name="equipos[{{ $index }}][fotos][]" multiple
                                                        accept="image/jpeg,image/png,image/jpg,image/gif">
                                                    <label class="custom-file-label">Seleccionar nuevas fotos</label>
                                                    <div class="form-text">Puede seleccionar hasta 8 fotos en total (JPEG, PNG, JPG, GIF) - Máx. 8MB cada una</div>
                                                </div>
                                            </div>

                                            <!-- Pestaña de cámara -->
                                            <div class="tab-pane fade" id="camara{{ $index }}" role="tabpanel">
                                                <div class="camera-container mt-3">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <video id="cameraVideo{{ $index }}" width="300" height="225" autoplay
                                                            style="border: 2px solid #ddd; border-radius: 8px; display: none;"></video>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-primary" id="startCamera{{ $index }}">
                                                            <i class="fas fa-video"></i> Activar cámara
                                                        </button>
                                                        <button type="button" class="btn btn-success" id="capturePhoto{{ $index }}"
                                                            style="display: none;">
                                                            <i class="fas fa-camera"></i> Tomar foto
                                                        </button>
                                                        <button type="button" class="btn btn-danger" id="stopCamera{{ $index }}"
                                                            style="display: none;">
                                                            <i class="fas fa-stop"></i> Detener cámara
                                                        </button>
                                                    </div>
                                                    <canvas id="cameraCanvas{{ $index }}" width="300" height="225"
                                                        style="display: none;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contenedor de previsualizaciones -->
                                        <div id="allPreviews{{ $index }}" class="preview-container">
                                            <!-- Fotos existentes -->
                                            <div id="existingPreviews{{ $index }}">
                                                @foreach($equipo->fotos as $foto)
                                                <div class="preview-item" data-photo-id="{{ $foto->id }}">
                                                    <img src="{{ asset('storage/' . $foto->ruta) }}" 
                                                         onclick="showImageModal('{{ asset('storage/' . $foto->ruta) }}')" 
                                                         title="Clic para ver en tamaño completo">
                                                    <div class="preview-controls">
                                                        <button type="button" class="btn btn-warning btn-sm" 
                                                                onclick="openPhotoEditor('{{ asset('storage/' . $foto->ruta) }}', {{ $index }}, 'existing', {{ $foto->id }})" 
                                                                title="Editar foto">
                                                            <i class="fas fa-crop"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="deleteExistingPhoto(this, {{ $foto->id }})" 
                                                                title="Eliminar foto">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="preview-badge">
                                                        <i class="fas fa-image"></i> Existente
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            
                                            <!-- Estado vacío (solo si no hay fotos) -->
                                            @if($equipo->fotos->count() == 0)
                                            <div class="empty-state" id="emptyState{{ $index }}" style="width: 100%; text-align: center; padding: 40px; color: #6c757d;">
                                                <i class="fas fa-images fa-3x mb-3" style="opacity: 0.5;"></i>
                                                <p class="mb-0"><strong>No hay fotos agregadas</strong></p>
                                                <small>Selecciona archivos o toma fotos para previsualizarlas aquí</small>
                                            </div>
                                            @endif
                                            
                                            <!-- Previsualizaciones de archivos nuevos -->
                                            <div id="filePreviews{{ $index }}"></div>
                                            <!-- Previsualizaciones de cámara -->
                                            <div id="cameraPreviews{{ $index }}"></div>
                                        </div>

                                        <!-- Campos ocultos para fotos de cámara -->
                                        <div id="cameraInputs{{ $index }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-right">
                            <a href="{{ route('recepciones.show', $recepcion) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Plantilla para nuevos equipos (hidden) -->
<template id="equipoTemplate">
    <div class="card mb-3 equipo-item">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-desktop"></i> Equipo #<span class="equipo-count">1</span>
            </h5>
            <button type="button" class="btn btn-icon btn-danger remove-equipo">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Campos que siempre se muestran -->
                <div class="form-group col-md-6">
                    <label><strong>Articulo</strong> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][nombre]" required>
                </div>
                <div class="form-group col-md-6">
                    <label><strong>Número de Serie</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][serie]">
                </div>

                <!-- Selector de tipo de equipo -->
                <div class="form-group col-md-4">
                    <label><strong>Tipo de Equipo </strong><span class="text-danger">*</span></label>
                    <select class="form-control selectric" name="equipos[__INDEX__][tipo]" id="tipoEquipo__INDEX__"
                        required onchange="mostrarCamposPorTipo('__INDEX__')">
                        <option value="">Seleccione...</option>
                        <option value="MOTOR_ELECTRICO">Motor Eléctrico</option>
                        <option value="MAQUINA_SOLDADORA">Máquina Soldadora</option>
                        <option value="GENERADOR_DINAMO">Generador/Dinamo</option>
                        <option value="OTROS">Otros</option>
                    </select>
                </div>

                <!-- Campos comunes que se muestran según el tipo -->
                <div class="form-group col-md-4" id="marca__INDEX__" style="display: none;">
                    <label><strong>Marca</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][marca]" required>
                </div>
                <div class="form-group col-md-4" id="modelo__INDEX__" style="display: none;">
                    <label><strong>Modelo</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][modelo]">
                </div>
                <div class="form-group col-md-4" id="color__INDEX__" style="display: none;">
                    <label><strong>Colores</strong></label>
                    <select class="form-control select-colores" name="equipos[__INDEX__][color][]" multiple>
                        <option value="rojo">Rojo</option>
                        <option value="azul">Azul</option>
                        <option value="verde">Verde</option>
                        <option value="amarillo">Amarillo</option>
                        <option value="naranja">Naranja</option>
                        <option value="morado">Morado</option>
                        <option value="rosado">Rosado</option>
                        <option value="negro">Negro</option>
                        <option value="blanco">Blanco</option>
                        <option value="gris">Gris</option>
                        <option value="marrón">Marrón</option>
                        <option value="cian">Cian</option>
                    </select>
                    <small class="form-text text-muted">Puedes seleccionar hasta 2 colores.</small>
                </div>
                <div class="form-group col-md-3" id="voltaje__INDEX__" style="display: none;">
                    <label><strong>Voltaje</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][voltaje]">
                </div>

                <!-- Campos específicos para cada tipo -->
                <!-- Motor Eléctrico -->
                <div class="form-group col-md-3" id="hp__INDEX__" style="display: none;">
                    <label><strong>HP (Caballos de fuerza)</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][hp]">
                </div>
                <div class="form-group col-md-3" id="rpm__INDEX__" style="display: none;">
                    <label><strong>RPM</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][rpm]">
                </div>
                <div class="form-group col-md-3" id="hz__INDEX__" style="display: none;">
                    <label><strong>Hz (Hercios)</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][hz]">
                </div>
                <div class="form-group col-md-3" id="kvaKw__INDEX__" style="display: none;">
                    <label><strong>Kva/Kw</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][kva_kw]">
                </div>

                <!-- Máquina Soldadora -->
                <div class="form-group col-md-3" id="amp__INDEX__" style="display: none;">
                    <label><strong>AMP (Amperio)</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][amperaje]">
                </div>
                <div class="form-group col-md-3" id="cablePositivo__INDEX__" style="display: none;">
                    <label><strong>Cable +</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_positivo]">
                </div>
                <div class="form-group col-md-3" id="cableNegativo__INDEX__" style="display: none;">
                    <label><strong>Cable -</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][cable_negativo]">
                </div>

                <!-- Generador/Dinamo -->
                <div class="form-group col-md-3" id="kvaKw__INDEX__" style="display: none;">
                    <label><strong>Kva/Kw</strong></label>
                    <input type="text" class="form-control" name="equipos[__INDEX__][kva_kw]">
                </div>

                <!-- Otros -->
                <div class="form-group col-md-3" id="potencia__INDEX__" style="display: none;">
                    <label><strong>Potencia</strong></label>
                    <input type="number" class="form-control" name="equipos[__INDEX__][potencia]">
                </div>

                <!-- Fotos del Equipo -->
                <div class="form-group col-12">
                    <label><strong>Fotos del Equipo</strong> <span class="text-danger">*</span></label>
                    <small class="form-text text-muted mb-2">
                        <i class="fas fa-info-circle"></i> Es obligatorio agregar al menos una foto por equipo
                    </small>

                    <!-- Pestañas para seleccionar modo -->
                    <ul class="nav nav-tabs" id="fotoTabs__INDEX__" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="archivo-tab__INDEX__" data-toggle="tab"
                                href="#archivo__INDEX__" role="tab">
                                <i class="fas fa-folder-open"></i> Seleccionar archivos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="camara-tab__INDEX__" data-toggle="tab" href="#camara__INDEX__"
                                role="tab">
                                <i class="fas fa-camera"></i> Tomar foto
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="fotoTabContent__INDEX__">
                        <!-- Pestaña de archivos -->
                        <div class="tab-pane fade show active" id="archivo__INDEX__" role="tabpanel">
                            <div class="custom-file mb-3 mt-3">
                                <input type="file" class="custom-file-input" id="fileInput__INDEX__"
                                    name="equipos[__INDEX__][fotos][]" multiple
                                    accept="image/jpeg,image/png,image/jpg,image/gif">
                                <label class="custom-file-label">Seleccionar fotos</label>
                                <div class="form-text">Puede seleccionar hasta 8 fotos incluyendo tomadas de camara y
                                    seleccionados (JPEG, PNG, JPG, GIF) - Máx. 8MB
                                    cada una</div>
                            </div>
                        </div>

                        <!-- Pestaña de cámara -->
                        <div class="tab-pane fade" id="camara__INDEX__" role="tabpanel">
                            <div class="camera-container mt-3">
                                <div class="d-flex justify-content-center mb-3">
                                    <video id="cameraVideo__INDEX__" width="300" height="225" autoplay
                                        style="border: 2px solid #ddd; border-radius: 8px; display: none;"></video>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="startCamera__INDEX__">
                                        <i class="fas fa-video"></i> Activar cámara
                                    </button>
                                    <button type="button" class="btn btn-success" id="capturePhoto__INDEX__"
                                        style="display: none;">
                                        <i class="fas fa-camera"></i> Tomar foto
                                    </button>
                                    <button type="button" class="btn btn-danger" id="stopCamera__INDEX__"
                                        style="display: none;">
                                        <i class="fas fa-stop"></i> Detener cámara
                                    </button>
                                </div>
                                <canvas id="cameraCanvas__INDEX__" width="300" height="225"
                                    style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor de previsualizaciones -->
                    <div id="allPreviews__INDEX__" class="preview-container">
                        <div class="empty-state" id="emptyState__INDEX__" style="width: 100%; text-align: center; padding: 40px; color: #6c757d;">
                            <i class="fas fa-images fa-3x mb-3" style="opacity: 0.5;"></i>
                            <p class="mb-0"><strong>No hay fotos agregadas</strong></p>
                            <small>Selecciona archivos o toma fotos para previsualizarlas aquí</small>
                        </div>
                        <!-- Previsualizaciones de archivos -->
                        <div id="filePreviews__INDEX__"></div>
                        <!-- Previsualizaciones de cámara -->
                        <div id="cameraPreviews__INDEX__"></div>
                    </div>

                    <!-- Campos ocultos para fotos de cámara -->
                    <div id="cameraInputs__INDEX__"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal para editar fotos -->
<div class="modal fade" id="photoEditorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-crop"></i> Recortar Foto
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="img-container">
                            <img id="cropperImage" style="max-width: 100%;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="preview-container">
                            <h6>Vista previa:</h6>
                            <div class="preview"
                                style="width: 150px; height: 150px; overflow: hidden; border: 1px solid #ddd; margin-bottom: 10px;">
                            </div>
                            <div class="btn-group-vertical w-100">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="resetCrop()">
                                    <i class="fas fa-undo"></i> Resetear
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="rotateImage(-90)">
                                    <i class="fas fa-undo"></i> Rotar ↺
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="rotateImage(90)">
                                    <i class="fas fa-redo"></i> Rotar ↻
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="cropAndSave">
                    <i class="fas fa-check"></i> Recortar y Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir los mismos estilos del formulario original -->
<style>
    /* Estilos mejorados para previsualizaciones */
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }

    .preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        background: white;
        border: 3px solid #e9ecef;
    }

    .preview-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        border-color: #007bff;
    }

    .preview-item img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        display: block;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .preview-item img:hover {
        transform: scale(1.05);
    }

    .preview-controls {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preview-item:hover .preview-controls {
        opacity: 1;
    }

    .preview-controls .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        border: 2px solid white;
    }

    .preview-badge {
        position: absolute;
        bottom: 8px;
        left: 8px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .preview-container {
            gap: 10px;
            max-height: 250px;
            padding: 8px;
        }

        .preview-item img {
            width: 120px;
            height: 120px;
        }

        .preview-controls {
            opacity: 1; /* Siempre visible en móviles */
        }

        .preview-controls .btn {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .preview-item img {
            width: 100px;
            height: 100px;
        }

        .preview-container {
            gap: 8px;
            padding: 6px;
        }
    }

    /* Modal para vista ampliada */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s ease;
    }

    .image-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-modal-content {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 0 50px rgba(255, 255, 255, 0.1);
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .image-modal-close:hover {
        color: #ff6b6b;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Espaciado adicional para los botones */
    .mt-4 {
        margin-top: 2rem !important;
    }

    /* Estilo para cámara fullscreen */
    .camera-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .camera-fullscreen video {
        max-width: 100%;
        max-height: 80vh;
        width: auto;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .camera-fullscreen .controls {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 20px;
        z-index: 10000;
    }

    .camera-fullscreen .controls button {
        padding: 15px 25px;
        font-size: 18px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .camera-fullscreen .controls button:hover {
        transform: scale(1.1);
    }

    .camera-fullscreen .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .camera-fullscreen .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .camera-info {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    /* Responsivo para móviles */
    @media (max-width: 768px) {
        .camera-fullscreen .controls {
            bottom: 70px;
            gap: 15px;
        }

        .camera-fullscreen .controls button {
            padding: 12px 20px;
            font-size: 16px;
        }
    }

    @keyframes fadeOut {
        from { 
            opacity: 1; 
            transform: scale(1); 
        }
        to { 
            opacity: 0; 
            transform: scale(0.8); 
        }
    }
    
    .preview-item {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: scale(0.8); 
        }
        to { 
            opacity: 1; 
            transform: scale(1); 
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let equipoCount = {{ $recepcion->equipos->count() }};
        const equiposContainer = document.getElementById('equiposContainer');
        const equipoTemplate = document.getElementById('equipoTemplate').innerHTML;
        let deletedPhotos = [];

        // Inicializar cámaras para equipos existentes
        @foreach($recepcion->equipos as $index => $equipo)
        setupCamera({{ $index }});
        @endforeach

        // Función para eliminar fotos existentes
        window.deleteExistingPhoto = function(button, photoId) {
            Swal.fire({
                title: '¿Eliminar esta foto?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const previewItem = button.closest('.preview-item');
                    previewItem.style.animation = 'fadeOut 0.3s ease';
                    
                    // Agregar a la lista de fotos a eliminar
                    deletedPhotos.push(photoId);
                    document.getElementById('deleted_photos').value = JSON.stringify(deletedPhotos);
                    
                    setTimeout(() => {
                        previewItem.remove();
                        checkEmptyStateAfterRemoval();
                    }, 300);

                    Swal.fire({
                        icon: 'success',
                        title: 'Foto eliminada',
                        text: 'La foto será eliminada al guardar los cambios.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        };

        function checkPhotoRequirement(index) {
            const fileInput = document.getElementById(`fileInput${index}`);
            const cameraPreviewContainer = document.getElementById(`cameraPreviews${index}`);
            const existingPreviewContainer = document.getElementById(`existingPreviews${index}`);
            const photoLabel = document.querySelector(`#equiposContainer .equipo-item:nth-child(${index + 1}) label[for*="fotos"]`);

            const hasFilePhotos = fileInput && fileInput.files && fileInput.files.length > 0;
            const hasCameraPhotos = cameraPreviewContainer && cameraPreviewContainer.children.length > 0;
            const hasExistingPhotos = existingPreviewContainer && existingPreviewContainer.children.length > 0;

            // Mostrar/ocultar estado vacío
            toggleEmptyState(index, hasFilePhotos || hasCameraPhotos || hasExistingPhotos);
        }

        // Función para mostrar/ocultar estado vacío
        function toggleEmptyState(index, hasPhotos) {
            const emptyState = document.getElementById(`emptyState${index}`);
            if (emptyState) {
                emptyState.style.display = hasPhotos ? 'none' : 'block';
            }
        }

        // Función para verificar estado vacío después de eliminar
        function checkEmptyStateAfterRemoval() {
            const equipos = equiposContainer.querySelectorAll('.equipo-item');
            equipos.forEach((equipo, index) => {
                const fileInput = document.getElementById(`fileInput${index}`);
                const cameraPreviewContainer = document.getElementById(`cameraPreviews${index}`);
                const existingPreviewContainer = document.getElementById(`existingPreviews${index}`);

                const hasFilePhotos = fileInput && fileInput.files && fileInput.files.length > 0;
                const hasCameraPhotos = cameraPreviewContainer && cameraPreviewContainer.children.length > 0;
                const hasExistingPhotos = existingPreviewContainer && existingPreviewContainer.children.length > 0;

                toggleEmptyState(index, hasFilePhotos || hasCameraPhotos || hasExistingPhotos);
            });
        }

        // Función para previsualizar archivos
        function previewFiles(input, index) {
            const previewContainer = document.getElementById(`filePreviews${index}`);
            const cameraPreviewContainer = document.getElementById(`cameraPreviews${index}`);
            const existingPreviewContainer = document.getElementById(`existingPreviews${index}`);
            previewContainer.innerHTML = '';
            const MAX_SIZE_MB = 8;
            const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

            // ✅ VALIDAR TOTAL DE FOTOS (ARCHIVOS + CÁMARA + EXISTENTES)
            const totalCameraPhotos = cameraPreviewContainer.children.length;
            const totalExistingPhotos = existingPreviewContainer.children.length;
            const totalFilePhotos = input.files ? input.files.length : 0;
            const totalPhotos = totalFilePhotos + totalCameraPhotos + totalExistingPhotos;

            if (totalPhotos > 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Límite de fotos superado',
                    text: `No puedes seleccionar más fotos. Ya tienes ${totalExistingPhotos} fotos existentes, ${totalCameraPhotos} fotos de cámara y ${totalFilePhotos} seleccionados. El límite máximo es de 8 fotos por equipo.`,
                    confirmButtonText: 'Entendido'
                });
                input.value = '';
                const label = input.nextElementSibling;
                label.textContent = 'Seleccionar nuevas fotos';
                return;
            }

            if (input.files && input.files.length > 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error cantidad de fotos superada',
                    text: 'No puedes seleccionar más de 8 fotos. Por favor, selecciona hasta 8 archivos.',
                    confirmButtonText: 'Entendido'
                });
                input.value = '';
                const label = input.nextElementSibling;
                label.textContent = 'Seleccionar nuevas fotos';
                return;
            }

            if (input.files) {
                let hasInvalidSize = false;

                Array.from(input.files).forEach(file => {
                    if (file.size > MAX_SIZE_BYTES) {
                        hasInvalidSize = true;
                    }
                });

                if (hasInvalidSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error tamaño superado',
                        text: `Una o más fotos superan el tamaño máximo de ${MAX_SIZE_MB}MB. Por favor, selecciona archivos más pequeños.`,
                        confirmButtonText: 'Entendido'
                    });
                    input.value = '';
                    const label = input.nextElementSibling;
                    label.textContent = 'Seleccionar nuevas fotos';
                    return;
                }

                Array.from(input.files).forEach((file, fileIndex) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" onclick="showImageModal('${e.target.result}')" title="Clic para ver en tamaño completo">
                            <div class="preview-controls">
                                <button type="button" class="btn btn-warning btn-sm" onclick="openPhotoEditor('${e.target.result}', ${index}, 'file', ${fileIndex})" title="Editar foto">
                                    <i style="font-size: 20px;" class="fas fa-crop"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removePreview(this)" title="Eliminar foto">
                                    <i style="font-size: 20px;" class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="preview-badge">
                                <i class="fas fa-file"></i> Nueva
                            </div>
                        `;
                        previewContainer.appendChild(div);
                        
                        // Ocultar estado vacío
                        toggleEmptyState(index, true);
                    }
                    reader.readAsDataURL(file);
                });

                const label = input.nextElementSibling;
                label.textContent = input.files.length > 1 ?
                    `${input.files.length} archivos seleccionados` :
                    input.files[0].name;
            }
        }

        // Función para manejar la cámara con fullscreen
        function setupCamera(index) {
            const video = document.getElementById(`cameraVideo${index}`);
            const canvas = document.getElementById(`cameraCanvas${index}`);
            const startBtn = document.getElementById(`startCamera${index}`);
            const captureBtn = document.getElementById(`capturePhoto${index}`);
            const stopBtn = document.getElementById(`stopCamera${index}`);
            const previewContainer = document.getElementById(`cameraPreviews${index}`);
            const inputContainer = document.getElementById(`cameraInputs${index}`);

            let stream = null;
            let cameraPhotoCount = 0;
            let fullscreenContainer = null;

            // Activar cámara en fullscreen
            startBtn.addEventListener('click', async function () {
                try {
                    // Verificar si getUserMedia está disponible
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        throw new Error('getUserMedia no está soportado en este navegador');
                    }

                    // Verificar permisos primero
                    const permissions = await navigator.permissions.query({ name: 'camera' });
                    console.log('Permisos de cámara:', permissions.state);

                    if (permissions.state === 'denied') {
                        throw new Error('Permisos de cámara denegados');
                    }

                    // Obtener dispositivos de cámara disponibles
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const cameras = devices.filter(device => device.kind === 'videoinput');

                    if (cameras.length === 0) {
                        throw new Error('No se encontraron cámaras disponibles');
                    }

                    console.log('Cámaras disponibles:', cameras);

                    // Intentar diferentes configuraciones
                    let stream = null;
                    const constraints = [
                        // Configuración ideal
                        {
                            video: {
                                width: { ideal: 1920 },
                                height: { ideal: 1080 },
                                facingMode: 'environment'
                            }
                        },
                        // Configuración básica
                        {
                            video: {
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        },
                        // Configuración mínima
                        {
                            video: true
                        }
                    ];

                    for (const constraint of constraints) {
                        try {
                            stream = await navigator.mediaDevices.getUserMedia(constraint);
                            console.log('Stream obtenido con:', constraint);
                            break;
                        } catch (constraintError) {
                            console.log('Error con constraint:', constraint, constraintError);
                            continue;
                        }
                    }

                    if (!stream) {
                        throw new Error('No se pudo obtener stream de cámara con ninguna configuración');
                    }

                    // Crear contenedor fullscreen
                    fullscreenContainer = document.createElement('div');
                    fullscreenContainer.className = 'camera-fullscreen';
                    fullscreenContainer.innerHTML = `
                        <div class="camera-info">
                            <i class="fas fa-camera"></i> Equipo #${index + 1} - Tomar Foto
                        </div>
                        <button class="close-btn" onclick="closeCameraFullscreen(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <video id="fullscreenVideo${index}" autoplay playsinline></video>
                        <div class="controls">
                            <button type="button" class="btn btn-success btn-lg" onclick="captureFullscreenPhoto(${index})">
                                <i class="fas fa-camera"></i> Capturar
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" onclick="closeCameraFullscreen(${index})">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    `;

                    document.body.appendChild(fullscreenContainer);
                    document.body.style.overflow = 'hidden';

                    // Asignar stream al video fullscreen
                    const fullscreenVideo = document.getElementById(`fullscreenVideo${index}`);
                    fullscreenVideo.srcObject = stream;

                    // Ocultar botones originales
                    startBtn.style.display = 'none';
                    captureBtn.style.display = 'none';
                    stopBtn.style.display = 'none';

                } catch (err) {
                    console.error('Error detallado:', err);

                    let errorMessage = 'Error desconocido al acceder a la cámara.';
                    let errorTitle = 'Error de cámara';

                    switch (err.name) {
                        case 'NotAllowedError':
                            errorTitle = 'Permisos denegados';
                            errorMessage = 'Has denegado el acceso a la cámara. Por favor, permite el acceso en la configuración del navegador.';
                            break;
                        case 'NotFoundError':
                            errorTitle = 'Cámara no encontrada';
                            errorMessage = 'No se detectó ninguna cámara en tu dispositivo.';
                            break;
                        case 'NotReadableError':
                            errorTitle = 'Cámara en uso';
                            errorMessage = 'La cámara está siendo usada por otra aplicación. Cierra otras aplicaciones que puedan estar usando la cámara.';
                            break;
                        case 'OverconstrainedError':
                            errorTitle = 'Configuración no compatible';
                            errorMessage = 'La configuración de cámara solicitada no es compatible con tu dispositivo.';
                            break;
                        case 'SecurityError':
                            errorTitle = 'Error de seguridad';
                            errorMessage = 'El acceso a la cámara fue bloqueado por razones de seguridad. Asegúrate de estar usando HTTPS o localhost.';
                            break;
                        default:
                            errorMessage = `${err.message || err}`;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: errorTitle,
                        html: `
                            <p>${errorMessage}</p>
                            <hr>
                            <small><strong>Detalles técnicos:</strong><br>
                            ${err.name}: ${err.message}<br>
                            Navegador: ${navigator.userAgent.split(' ')[0]}<br>
                            Protocolo: ${location.protocol}</small>
                        `,
                        width: '500px',
                        confirmButtonText: 'Entendido'
                    });
                }
            });

            // Función global para cerrar fullscreen
            window.closeCameraFullscreen = function (equipoIndex) {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }

                if (fullscreenContainer) {
                    document.body.removeChild(fullscreenContainer);
                    fullscreenContainer = null;
                }

                document.body.style.overflow = 'auto'; // Restaurar scroll

                // Mostrar botón de inicio
                startBtn.style.display = 'inline-block';
                captureBtn.style.display = 'none';
                stopBtn.style.display = 'none';
            };

            // Función global para capturar foto en fullscreen
            window.captureFullscreenPhoto = function (equipoIndex) {
                // Validar límite de 8 fotos total
                const fileInput = document.getElementById(`fileInput${equipoIndex}`);
                const existingPreviewContainer = document.getElementById(`existingPreviews${equipoIndex}`);
                const totalFilePhotos = fileInput.files ? fileInput.files.length : 0;
                const totalCameraPhotos = previewContainer.children.length;
                const totalExistingPhotos = existingPreviewContainer.children.length;
                const totalPhotos = totalFilePhotos + totalCameraPhotos + totalExistingPhotos;

                if (totalPhotos >= 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Límite de fotos superado',
                        text: 'No puedes tomar más fotos. El límite máximo es de 8 fotos por equipo.',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                const fullscreenVideo = document.getElementById(`fullscreenVideo${equipoIndex}`);

                // Crear canvas temporal para captura
                const tempCanvas = document.createElement('canvas');
                const context = tempCanvas.getContext('2d');

                // Obtener dimensiones del video
                tempCanvas.width = fullscreenVideo.videoWidth;
                tempCanvas.height = fullscreenVideo.videoHeight;

                // Dibujar frame actual del video
                context.drawImage(fullscreenVideo, 0, 0);

                // Convertir a blob
                tempCanvas.toBlob(function (blob) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Crear preview
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" onclick="showImageModal('${e.target.result}')" title="Clic para ver en tamaño completo">
                            <div class="preview-controls">
                                <button type="button" class="btn btn-warning btn-sm" onclick="openPhotoEditor('${e.target.result}', ${equipoIndex}, 'camera', ${previewContainer.children.length})" title="Editar foto">
                                    <i class="fas fa-crop"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeCameraPhoto(this, ${equipoIndex}, ${cameraPhotoCount})" title="Eliminar foto">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="preview-badge">
                                <i class="fas fa-camera"></i> Cámara
                            </div>
                        `;
                        previewContainer.appendChild(div);
                        
                        // Ocultar estado vacío
                        toggleEmptyState(equipoIndex, true);

                        // Crear input hidden con la imagen
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `equipos[${equipoIndex}][camera_photos][]`;
                        input.value = e.target.result;
                        input.id = `cameraPhoto${equipoIndex}_${cameraPhotoCount}`;
                        inputContainer.appendChild(input);

                        cameraPhotoCount++;

                        // Mostrar notificación de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Foto capturada',
                            text: 'La foto ha sido capturada correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Cerrar fullscreen después de capturar
                        closeCameraFullscreen(equipoIndex);
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg', 0.9); // Calidad alta
            };

            // Detener cámara (función original mantenida por compatibilidad)
            stopBtn.addEventListener('click', function () {
                closeCameraFullscreen(index);
            });
        }

        // Función global para remover preview
        window.removePreview = function (button) {
            const previewItem = button.closest('.preview-item');
            const equipoContainer = previewItem.closest('.equipo-item');
            const equipoIndex = Array.from(equipoContainer.parentNode.children).indexOf(equipoContainer);
            
            previewItem.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                previewItem.remove();
                // Verificar si quedan fotos y mostrar estado vacío si es necesario
                checkEmptyStateAfterRemoval();
            }, 300);
        };

        // Función global para remover foto de cámara
        window.removeCameraPhoto = function (button, equipoIndex, photoIndex) {
            const previewItem = button.closest('.preview-item');
            const input = document.getElementById(`cameraPhoto${equipoIndex}_${photoIndex}`);
            
            // Animación de salida
            previewItem.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                previewItem.remove();
                if (input) input.remove();
                // Verificar si quedan fotos y mostrar estado vacío si es necesario
                checkEmptyStateAfterRemoval();
            }, 300);

            // Mostrar mensaje de confirmación
            Swal.fire({
                icon: 'info',
                title: 'Foto eliminada',
                text: 'La foto ha sido eliminada correctamente.',
                timer: 1000,
                showConfirmButton: false
            });
        };

        // Agregar nuevo equipo
        document.getElementById('addEquipo').addEventListener('click', function () {
            const newEquipoHTML = equipoTemplate.replace(/__INDEX__/g, equipoCount);
            const newEquipoElement = document.createElement('div');
            newEquipoElement.innerHTML = newEquipoHTML;
            equiposContainer.appendChild(newEquipoElement.firstElementChild);

            // Inicializar Select2 para colores
            $(`select[name="equipos[${equipoCount}][color][]"]`).select2({
                placeholder: "Seleccione colores",
                maximumSelectionLength: 2,
                width: '100%'
            });

            // Configurar cámara para este equipo
            setupCamera(equipoCount);

            // Mostrar campos básicos inicialmente
            mostrarCamposPorTipo(equipoCount);

            equipoCount++;
            reindexEquipos();

            // Inicializar selectric
            if (typeof $.fn.selectric !== 'undefined') {
                $('.selectric').selectric('destroy');
                $('.selectric').selectric();
            }

            // Animación
            const lastEquipo = equiposContainer.lastElementChild;
            lastEquipo.style.opacity = '0';
            let opacity = 0;
            const fadeIn = setInterval(() => {
                opacity += 0.1;
                lastEquipo.style.opacity = opacity;
                if (opacity >= 1) clearInterval(fadeIn);
            }, 50);
        });

        // Eliminar equipo
        equiposContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-equipo')) {
                const equipoItem = e.target.closest('.equipo-item');

                // Detener cámara si está activa
                const video = equipoItem.querySelector('video');
                if (video && video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }

                equipoItem.style.opacity = '1';
                let opacity = 1;
                const fadeOut = setInterval(() => {
                    opacity -= 0.1;
                    equipoItem.style.opacity = opacity;
                    if (opacity <= 0) {
                        clearInterval(fadeOut);
                        equipoItem.remove();
                        reindexEquipos();
                    }
                }, 50);
            }
        });

        // Función para reindexar equipos
        function reindexEquipos() {
            const equipos = equiposContainer.querySelectorAll('.equipo-item');
            equipos.forEach((equipo, index) => {
                equipo.querySelector('.equipo-count').textContent = index + 1;

                equipo.querySelectorAll('[name^="equipos["]').forEach(input => {
                    const namePattern = /equipos\[\d+\]/;
                    const newName = input.name.replace(namePattern, `equipos[${index}]`);
                    input.name = newName;
                });

                // Actualizar IDs
                const elementos = equipo.querySelectorAll('[id*="__INDEX__"]');
                elementos.forEach(elemento => {
                    elemento.id = elemento.id.replace(/__INDEX__/g, index);
                });

                const tipoSelect = equipo.querySelector('[name^="equipos["][name$="[tipo]"]');
                if (tipoSelect) {
                    tipoSelect.setAttribute('onchange', `mostrarCamposPorTipo('${index}')`);
                }
            });

            equipoCount = equipos.length;
        }

        // Manejar cambio en inputs de archivo
        equiposContainer.addEventListener('change', function (e) {
            if (e.target.matches('.custom-file-input')) {
                const index = e.target.id.replace('fileInput', '');
                previewFiles(e.target, index);
            }
        });

        // Validación del formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                let isValid = true;
                const equipos = equiposContainer.querySelectorAll('.equipo-item');

                if (equipos.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Debes tener al menos un equipo.',
                        confirmButtonText: 'Entendido'
                    });
                    isValid = false;
                }

                equipos.forEach((equipo, index) => {
                    const fileInput = document.getElementById(`fileInput${index}`);
                    const cameraPreviewContainer = document.getElementById(`cameraPreviews${index}`);
                    const existingPreviewContainer = document.getElementById(`existingPreviews${index}`);

                    const hasFilePhotos = fileInput && fileInput.files && fileInput.files.length > 0;
                    const hasCameraPhotos = cameraPreviewContainer && cameraPreviewContainer.children.length > 0;
                    const hasExistingPhotos = existingPreviewContainer && existingPreviewContainer.children.length > 0;

                    if (!hasFilePhotos && !hasCameraPhotos && !hasExistingPhotos) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Falta foto en equipo',
                            text: `El equipo #${index + 1} debe tener al menos una foto.`,
                            confirmButtonText: 'Entendido'
                        });
                        isValid = false;
                    }
                });

                if (!isValid) {
                    return false;
                }
            });
        }
    });

    // Función para mostrar campos por tipo
    function mostrarCamposPorTipo(index) {
        const tipo = document.getElementById(`tipoEquipo${index}`)?.value;
        if (!tipo) return;

        const campos = {
            comunes: ['marca', 'modelo', 'color', 'voltaje'],
            MOTOR_ELECTRICO: ['hp', 'rpm', 'hz', 'kvaKw'],
            MAQUINA_SOLDADORA: ['amp', 'cablePositivo', 'cableNegativo'],
            GENERADOR_DINAMO: ['kvaKw', 'hz', 'rpm'],
            OTROS: ['potencia']
        };

        const todosCampos = [...campos.comunes, ...campos.MOTOR_ELECTRICO, ...campos.MAQUINA_SOLDADORA,
        ...campos.GENERADOR_DINAMO, ...campos.OTROS];

        todosCampos.forEach(campo => {
            const elemento = document.getElementById(`${campo}${index}`);
            if (elemento) {
                elemento.style.display = 'none';
                if (campo !== 'marca') {
                    const input = elemento.querySelector('input, select');
                    if (input) input.required = false;
                }
            }
        });

        campos.comunes.forEach(campo => {
            const elemento = document.getElementById(`${campo}${index}`);
            if (elemento) {
                elemento.style.display = 'block';
                if (campo === 'marca') {
                    const input = elemento.querySelector('input');
                    if (input) input.required = true;
                }
            }
        });

        if (campos[tipo]) {
            campos[tipo].forEach(campo => {
                const elemento = document.getElementById(`${campo}${index}`);
                if (elemento) elemento.style.display = 'block';
            });
        }
    }

    // Variables globales para el editor
    let cropper = null;
    let currentEditingEquipo = null;
    let currentEditingType = null; // 'file', 'camera' o 'existing'
    let currentEditingIndex = null;

    // Función para abrir el editor de fotos
    function openPhotoEditor(imageSrc, equipoIndex, type, fileIndex = null) {
        currentEditingEquipo = equipoIndex;
        currentEditingType = type;
        currentEditingIndex = fileIndex;

        const cropperImage = document.getElementById('cropperImage');
        cropperImage.src = imageSrc;

        $('#photoEditorModal').modal('show');

        // Inicializar cropper cuando se abra el modal
        $('#photoEditorModal').on('shown.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropperImage, {
                aspectRatio: NaN, // Permitir cualquier aspecto
                viewMode: 1,
                dragMode: 'crop',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                preview: '.preview'
            });
        });
    }

    // Función para resetear el recorte
    function resetCrop() {
        if (cropper) {
            cropper.reset();
        }
    }

    // Función para rotar imagen
    function rotateImage(degrees) {
        if (cropper) {
            cropper.rotate(degrees);
        }
    }

    // Función para recortar y guardar
    document.getElementById('cropAndSave').addEventListener('click', function () {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 600,
            imageSmoothingEnabled: false,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function (blob) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const croppedImageSrc = e.target.result;

                if (currentEditingType === 'camera') {
                    updateCameraPhoto(croppedImageSrc, currentEditingEquipo, currentEditingIndex);
                } else if (currentEditingType === 'existing') {
                    updateExistingPhoto(croppedImageSrc, currentEditingEquipo, currentEditingIndex);
                } else {
                    updateFilePhoto(croppedImageSrc, currentEditingEquipo, currentEditingIndex);
                }

                $('#photoEditorModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Foto editada',
                    text: 'La foto ha sido recortada correctamente.',
                    timer: 1500,
                    showConfirmButton: false
                });
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg', 0.8);
    });

    // Función para actualizar foto de cámara
    function updateCameraPhoto(newImageSrc, equipoIndex, photoIndex) {
        const previewContainer = document.getElementById(`cameraPreviews${equipoIndex}`);
        const photoDiv = previewContainer.children[photoIndex];
        const img = photoDiv.querySelector('img');
        const input = document.getElementById(`cameraPhoto${equipoIndex}_${photoIndex}`);

        if (img) {
            img.src = newImageSrc;
            img.setAttribute('onclick', `showImageModal('${newImageSrc}')`);
        }
        if (input) input.value = newImageSrc;
    }

    // Función para actualizar foto existente
    function updateExistingPhoto(newImageSrc, equipoIndex, photoId) {
        // Para fotos existentes, solo actualizamos la vista previa
        // La imagen editada se enviará como nueva foto
        const existingContainer = document.getElementById(`existingPreviews${equipoIndex}`);
        const photoDiv = existingContainer.querySelector(`[data-photo-id="${photoId}"]`);
        const img = photoDiv.querySelector('img');

        if (img) {
            img.src = newImageSrc;
            img.setAttribute('onclick', `showImageModal('${newImageSrc}')`);
        }

        // Agregar como nueva foto
        const inputContainer = document.getElementById(`cameraInputs${equipoIndex}`);
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `equipos[${equipoIndex}][camera_photos][]`;
        input.value = newImageSrc;
        inputContainer.appendChild(input);
    }

    // Función para actualizar foto de archivo
    function updateFilePhoto(newImageSrc, equipoIndex, fileIndex) {
        const previewContainer = document.getElementById(`filePreviews${equipoIndex}`);
        const photoDiv = previewContainer.children[fileIndex];
        const img = photoDiv.querySelector('img');

        if (img) {
            img.src = newImageSrc;
            img.setAttribute('onclick', `showImageModal('${newImageSrc}')`);
        }

        // Crear nuevo archivo blob y actualizar el input
        fetch(newImageSrc)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], `edited_photo_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const fileInput = document.getElementById(`fileInput${equipoIndex}`);
                const dt = new DataTransfer();

                // Mantener otros archivos y reemplazar el editado
                for (let i = 0; i < fileInput.files.length; i++) {
                    if (i === fileIndex) {
                        dt.items.add(file);
                    } else {
                        dt.items.add(fileInput.files[i]);
                    }
                }

                fileInput.files = dt.files;
            });
    }

    // Limpiar cropper al cerrar modal
    $('#photoEditorModal').on('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // Controles de teclado
    document.addEventListener('keydown', function (e) {
        // Si hay una cámara fullscreen activa
        if (document.querySelector('.camera-fullscreen')) {
            switch (e.key) {
                case 'Enter':
                case ' ': // Barra espaciadora
                    e.preventDefault();
                    const captureBtn = document.querySelector('.camera-fullscreen .controls .btn-success');
                    if (captureBtn) captureBtn.click();
                    break;
                case 'Escape':
                    e.preventDefault();
                    const closeBtn = document.querySelector('.camera-fullscreen .close-btn');
                    if (closeBtn) closeBtn.click();
                    break;
            }
        }
        
        // Si hay un modal de imagen activo
        if (document.querySelector('.image-modal.show')) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        }
    });

    // Función para mostrar imagen en modal ampliado
    function showImageModal(imageSrc) {
        // Crear modal si no existe
        let modal = document.getElementById('imageModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.className = 'image-modal';
            modal.innerHTML = `
                <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
                <img class="image-modal-content" id="modalImage">
            `;
            document.body.appendChild(modal);
            
            // Cerrar modal al hacer clic fuera de la imagen
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeImageModal();
                }
            });
        }
        
        // Mostrar imagen
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar modal de imagen
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }
</script>

@endsection