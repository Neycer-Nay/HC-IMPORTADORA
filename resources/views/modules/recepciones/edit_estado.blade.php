@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        @if($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5><i class="icon fas fa-ban"></i> Error al actualizar</h5>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Información de solo lectura -->
        <div class="card">
            <div class="card-header bg-light">
                <h4><i class="fas fa-info-circle"></i> Información de la Recepción (Solo lectura)</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Número de Recepción:</label>
                            <p class="form-control-plaintext">{{ $recepcion->numero_recepcion }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Fecha:</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($recepcion->fecha_ingreso)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Hora:</label>
                            <p class="form-control-plaintext">{{ $recepcion->hora_ingreso }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información del cliente -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3"><i class="fas fa-user"></i> Información del Cliente</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Nombre:</label>
                            <p class="form-control-plaintext">{{ $recepcion->cliente->nombre }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Documento:</label>
                            <p class="form-control-plaintext">{{ $recepcion->cliente->tipo_documento }}: {{ $recepcion->cliente->numero_documento }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Teléfono:</label>
                            <p class="form-control-plaintext">{{ $recepcion->cliente->telefono_1 }}</p>
                        </div>
                    </div>
                </div>

                @if($recepcion->observaciones)
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Observaciones:</label>
                            <p class="form-control-plaintext">{{ $recepcion->observaciones }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Formulario para editar equipos -->
        <form action="{{ route('recepciones.update', $recepcion) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4><i class="fas fa-edit"></i> Editar Equipos y Fotos</h4>
                    <small class="d-block mt-1">Puedes modificar los datos de los equipos y gestionar sus fotos</small>
                </div>
                <div class="card-body" id="equiposContainer">
                    @foreach($recepcion->equipos as $index => $equipo)
                        <div class="card mb-3 equipo-item border-warning">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-desktop"></i> Equipo #{{ $index + 1 }}
                                </h5>
                                @if(count($recepcion->equipos) > 1)
                                    <button type="button" class="btn btn-icon btn-danger remove-equipo" title="Eliminar equipo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="equipos[{{ $index }}][id]" value="{{ $equipo->id }}">
                                
                                <div class="row">
                                    <!-- Campos editables del equipo -->
                                    <div class="form-group col-md-6">
                                        <label><strong>Artículo</strong> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][nombre]" 
                                               value="{{ $equipo->nombre }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label><strong>Número de Serie</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][serie]" 
                                               value="{{ $equipo->numero_serie }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label><strong>Tipo de Equipo</strong> <span class="text-danger">*</span></label>
                                        <p class="form-control-plaintext">{{ $equipo->tipo }}</p>
                                    </div>

                                    <!-- Campos específicos por tipo -->
                                    <div class="form-group col-md-4" id="marca{{ $index }}">
                                        <label><strong>Marca</strong> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][marca]" 
                                               value="{{ $equipo->marca }}" required>
                                    </div>
                                    <div class="form-group col-md-4" id="modelo{{ $index }}">
                                        <label><strong>Modelo</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][modelo]" 
                                               value="{{ $equipo->modelo }}">
                                    </div>

                                    <!-- Color -->
                                    <div class="form-group col-md-4" id="color{{ $index }}">
                                        <label><strong>Colores</strong></label>
                                        <p class="form-control-plaintext">{{ $equipo->color }}</p>
                                    </div>

                                    <!-- Campos técnicos específicos -->
                                    <div class="form-group col-md-3" id="voltaje{{ $index }}">
                                        <label><strong>Voltaje</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][voltaje]" 
                                               value="{{ $equipo->voltaje }}">
                                    </div>
                                    <div class="form-group col-md-3" id="hp{{ $index }}">
                                        <label><strong>HP</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][hp]" 
                                               value="{{ $equipo->hp }}">
                                    </div>
                                    <div class="form-group col-md-3" id="rpm{{ $index }}">
                                        <label><strong>RPM</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][rpm]" 
                                               value="{{ $equipo->rpm }}">
                                    </div>
                                    <div class="form-group col-md-3" id="hz{{ $index }}">
                                        <label><strong>Hz</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][hz]" 
                                               value="{{ $equipo->hz }}">
                                    </div>
                                    <div class="form-group col-md-3" id="amp{{ $index }}">
                                        <label><strong>AMP</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][amperaje]" 
                                               value="{{ $equipo->amperaje }}">
                                    </div>
                                    <div class="form-group col-md-3" id="cablePositivo{{ $index }}">
                                        <label><strong>Cable +</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][cable_positivo]" 
                                               value="{{ $equipo->cable_positivo }}">
                                    </div>
                                    <div class="form-group col-md-3" id="cableNegativo{{ $index }}">
                                        <label><strong>Cable -</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][cable_negativo]" 
                                               value="{{ $equipo->cable_negativo }}">
                                    </div>
                                    <div class="form-group col-md-3" id="kvaKw{{ $index }}">
                                        <label><strong>Kva/Kw</strong></label>
                                        <input type="text" class="form-control" name="equipos[{{ $index }}][kva_kw]" 
                                               value="{{ $equipo->kva_kw }}">
                                    </div>
                                    <div class="form-group col-md-3" id="potencia{{ $index }}">
                                        <label><strong>Potencia</strong></label>
                                        <input type="number" class="form-control" name="equipos[{{ $index }}][potencia]" 
                                               value="{{ $equipo->potencia }}">
                                    </div>

                                    <!-- Gestión de fotos -->
                                    <div class="form-group col-12">
                                        <label><strong>Gestión de Fotos</strong></label>
                                        <small class="form-text text-muted mb-3">
                                            <i class="fas fa-info-circle"></i> Puedes eliminar fotos existentes, agregar nuevas y recortarlas
                                        </small>

                                        <!-- Fotos existentes -->
                                        @if($equipo->fotos->count() > 0)
                                            <div class="mb-3">
                                                <h6 class="text-muted">Fotos actuales:</h6>
                                                <div class="existing-photos-container">
                                                    @foreach($equipo->fotos as $foto)
                                                        <div class="preview-item existing-photo" data-foto-id="{{ $foto->id }}">
                                                            <img src="{{ asset('storage/' . $foto->ruta) }}" 
                                                                 onclick="showImageModal('{{ asset('storage/' . $foto->ruta) }}')" 
                                                                 title="Clic para ver en tamaño completo">
                                                            <div class="preview-controls">
                                                                <button type="button" class="btn btn-warning btn-sm" 
                                                                        onclick="editExistingPhoto('{{ asset('storage/' . $foto->ruta) }}', {{ $foto->id }})" 
                                                                        title="Recortar foto">
                                                                    <i class="fas fa-crop"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-danger btn-sm" 
                                                                        onclick="removeExistingPhoto(this, {{ $foto->id }})" 
                                                                        title="Eliminar foto">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="preview-badge">
                                                                <i class="fas fa-database"></i> Existente
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Pestañas para nuevas fotos -->
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
                                                    <div class="form-text">Máximo 8 fotos totales (JPEG, PNG, JPG, GIF) - Máx. 8MB cada una</div>
                                                </div>
                                            </div>

                                            <!-- Pestaña de cámara -->
                                            <div class="tab-pane fade" id="camara{{ $index }}" role="tabpanel">
                                                <div class="camera-container mt-3">
                                                    <div class="text-center mb-3">
                                                        <button type="button" class="btn btn-primary btn-lg camera-btn" data-equipo="{{ $index }}">
                                                            <i class="fas fa-video"></i> Activar Cámara
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contenedor de previsualizaciones para nuevas fotos -->
                                        <div id="allPreviews{{ $index }}" class="preview-container">
                                            <div class="empty-state" id="emptyState{{ $index }}" style="text-align: center; padding: 20px; color: #6c757d;">
                                                <i class="fas fa-plus-circle fa-2x mb-2" style="opacity: 0.5;"></i>
                                                <p class="mb-0"><strong>Agregar nuevas fotos</strong></p>
                                                <small>Selecciona archivos o toma fotos para agregarlas</small>
                                            </div>
                                            <div id="filePreviews{{ $index }}"></div>
                                            <div id="cameraPreviews{{ $index }}"></div>
                                        </div>

                                        <!-- Inputs ocultos -->
                                        <div id="cameraInputs{{ $index }}"></div>
                                        <div id="deletedPhotos{{ $index }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="addEquipo">
                        <i class="fas fa-plus"></i> Agregar Nuevo Equipo
                    </button>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('recepciones.show', $recepcion) }}" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Modal para vista ampliada de imágenes -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista de Imagen</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" style="max-width: 100%; max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cámara fullscreen -->
    <div class="modal fade camera-modal" id="cameraModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog camera-dialog" role="document">
            <div class="modal-content camera-content">
                <div class="modal-header camera-header">
                    <h5 class="modal-title">Capturar Foto</h5>
                    <button type="button" class="close camera-close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body camera-body">
                    <div class="camera-viewport">
                        <video id="cameraVideo" autoplay playsinline></video>
                        <canvas id="cameraCanvas" style="display: none;"></canvas>
                    </div>
                    <div class="camera-controls">
                        <button type="button" class="btn btn-success btn-lg capture-btn" id="captureBtn">
                            <i class="fas fa-camera"></i> Capturar
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para recortar imágenes -->
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

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    
    <style>
        .existing-photos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }

        .existing-photo {
            border: 3px solid #28a745 !important;
        }

        .form-control-plaintext {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            margin-bottom: 0;
        }

        .card.border-warning {
            border-color: #ffc107 !important;
        }

        /* Estilos para modales de cámara y recorte */
        .camera-modal .modal-dialog,
        .crop-modal .modal-dialog {
            max-width: 95vw;
            width: 95vw;
            height: 95vh;
            margin: 2.5vh auto;
        }

        .camera-content,
        .crop-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .camera-header,
        .crop-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-bottom: none;
        }

        .camera-body,
        .crop-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            background: #000;
        }

        .camera-viewport {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #000;
        }

        .crop-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            padding: 20px;
        }

        #cameraVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .camera-controls,
        .crop-controls {
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .camera-controls .btn,
        .crop-controls .btn {
            margin: 0 10px;
            min-width: 120px;
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .camera-modal .modal-dialog,
            .crop-modal .modal-dialog {
                width: 100vw;
                height: 100vh;
                margin: 0;
                max-width: 100vw;
            }

            .camera-controls,
            .crop-controls {
                padding: 15px;
            }

            .camera-controls .btn,
            .crop-controls .btn {
                display: block;
                width: 100%;
                margin: 5px 0;
            }

            #cameraVideo {
                border-radius: 0;
            }
        }

        /* Estilos para preview de fotos */
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

        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.8); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let equipoCount = {{ count($recepcion->equipos) }};
            let currentStream = null;
            let currentCropper = null;
            let currentEquipoIndex = null;
            let cropImageType = 'new'; // 'new' o 'existing'
            let currentPhotoId = null;

            // Array para almacenar IDs de fotos a eliminar
            let photosToDelete = [];

            // Configurar funcionalidad para cada equipo existente
            @foreach($recepcion->equipos as $index => $equipo)
                setupEquipoFunctionality({{ $index }});
                mostrarCamposPorTipo('{{ $index }}');
            @endforeach

            // Event listeners para cámara
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('camera-btn') || e.target.closest('.camera-btn')) {
                    const btn = e.target.classList.contains('camera-btn') ? e.target : e.target.closest('.camera-btn');
                    const equipoIndex = btn.getAttribute('data-equipo');
                    currentEquipoIndex = equipoIndex;
                    openCamera();
                }
            });

            // Función para abrir cámara
            function openCamera() {
                $('#cameraModal').modal('show');
                
                $('#cameraModal').on('shown.bs.modal', function() {
                    startCamera();
                });

                $('#cameraModal').on('hidden.bs.modal', function() {
                    stopCamera();
                });
            }

            // Función para iniciar cámara
            function startCamera() {
                const video = document.getElementById('cameraVideo');
                
                const constraints = {
                    video: {
                        width: { ideal: 1920 },
                        height: { ideal: 1080 },
                        facingMode: 'environment' // Cámara trasera por defecto
                    }
                };

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function(stream) {
                        currentStream = stream;
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch(function(err) {
                        console.error('Error al acceder a la cámara:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Cámara',
                            text: 'No se pudo acceder a la cámara. Verifica los permisos.',
                        });
                        $('#cameraModal').modal('hide');
                    });
            }

            // Función para detener cámara
            function stopCamera() {
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                    currentStream = null;
                }
                
                const video = document.getElementById('cameraVideo');
                video.srcObject = null;
            }

            // Capturar foto
            document.getElementById('captureBtn').addEventListener('click', function() {
                const video = document.getElementById('cameraVideo');
                const canvas = document.getElementById('cameraCanvas');
                const context = canvas.getContext('2d');

                // Configurar canvas con las dimensiones del video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Dibujar el frame actual del video en el canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convertir a blob y abrir para recortar
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    openCropModal(url, 'camera');
                    $('#cameraModal').modal('hide');
                }, 'image/jpeg', 0.8);
            });

            // Función para abrir modal de recorte
            function openCropModal(imageSrc, type, photoId = null) {
                cropImageType = type;
                currentPhotoId = photoId;
                
                const cropImage = document.getElementById('cropImage');
                cropImage.src = imageSrc;
                
                $('#cropModal').modal('show');
                
                $('#cropModal').on('shown.bs.modal', function() {
                    initCropper();
                });

                $('#cropModal').on('hidden.bs.modal', function() {
                    destroyCropper();
                });
            }

            // Inicializar Cropper
            function initCropper() {
                const cropImage = document.getElementById('cropImage');
                
                currentCropper = new Cropper(cropImage, {
                    aspectRatio: 1, // Relación cuadrada
                    viewMode: 1,
                    responsive: true,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    minContainerWidth: 300,
                    minContainerHeight: 300
                });
            }

            // Destruir Cropper
            function destroyCropper() {
                if (currentCropper) {
                    currentCropper.destroy();
                    currentCropper = null;
                }
            }

            // Guardar imagen recortada
            document.getElementById('cropSaveBtn').addEventListener('click', function() {
                if (!currentCropper) return;

                const canvas = currentCropper.getCroppedCanvas({
                    width: 800,
                    height: 800,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });

                canvas.toBlob(function(blob) {
                    if (cropImageType === 'camera') {
                        addCameraPhotoToPreview(blob);
                    } else if (cropImageType === 'existing') {
                        replaceExistingPhoto(blob);
                    } else if (cropImageType === 'file') {
                        addFilePhotoToPreview(blob);
                    }
                    
                    $('#cropModal').modal('hide');
                }, 'image/jpeg', 0.8);
            });

            // Función para agregar foto de cámara a preview
            function addCameraPhotoToPreview(blob) {
                const url = URL.createObjectURL(blob);
                const previewContainer = document.getElementById(`cameraPreviews${currentEquipoIndex}`);
                const emptyState = document.getElementById(`emptyState${currentEquipoIndex}`);
                
                if (emptyState) emptyState.style.display = 'none';

                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${url}" onclick="showImageModal('${url}')">
                    <div class="preview-controls">
                        <button type="button" class="btn btn-warning btn-sm" onclick="editNewPhoto(this, '${url}')" title="Recortar foto">
                            <i class="fas fa-crop"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeNewPhoto(this, ${currentEquipoIndex})" title="Eliminar foto">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="preview-badge">
                        <i class="fas fa-camera"></i> Cámara
                    </div>
                `;
                previewContainer.appendChild(div);

                // Crear input hidden con la imagen
                createHiddenInput(blob, 'camera');
            }

            // Función para crear input hidden con la imagen
            function createHiddenInput(blob, source) {
                const file = new File([blob], `${source}_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const input = document.createElement('input');
                input.type = 'file';
                input.name = `equipos[${currentEquipoIndex}][fotos][]`;
                input.style.display = 'none';
                
                // Crear FileList personalizado
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                
                document.getElementById(`cameraInputs${currentEquipoIndex}`).appendChild(input);
            }

            // Configurar input de archivos
            function setupFileInput(index) {
                const fileInput = document.getElementById(`fileInput${index}`);
                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        handleFileSelect(e.target, index);
                    });
                }
            }

            // Manejar selección de archivos
            function handleFileSelect(input, index) {
                const files = Array.from(input.files);
                
                if (files.length === 1) {
                    // Si es un solo archivo, abrir directamente en el recortador
                    const file = files[0];
                    const url = URL.createObjectURL(file);
                    currentEquipoIndex = index;
                    openCropModal(url, 'file');
                } else {
                    // Si son múltiples archivos, mostrar previa normal
                    previewFiles(input, index);
                }
            }

            // Función para previsualizar archivos múltiples
            function previewFiles(input, index) {
                const previewContainer = document.getElementById(`filePreviews${index}`);
                previewContainer.innerHTML = '';
                
                if (input.files && input.files.length > 0) {
                    const emptyState = document.getElementById(`emptyState${index}`);
                    if (emptyState) emptyState.style.display = 'none';
                    
                    Array.from(input.files).forEach((file) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'preview-item';
                            div.innerHTML = `
                                <img src="${e.target.result}" onclick="showImageModal('${e.target.result}')">
                                <div class="preview-controls">
                                    <button type="button" class="btn btn-warning btn-sm" onclick="editNewPhoto(this, '${e.target.result}')" title="Recortar foto">
                                        <i class="fas fa-crop"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeNewPhoto(this, ${index})" title="Eliminar foto">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="preview-badge">
                                    <i class="fas fa-file"></i> Archivo
                                </div>
                            `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            // Función para editar foto existente
            window.editExistingPhoto = function(imageSrc, photoId) {
                currentPhotoId = photoId;
                openCropModal(imageSrc, 'existing', photoId);
            };

            // Función para editar foto nueva
            window.editNewPhoto = function(button, imageSrc) {
                const previewItem = button.closest('.preview-item');
                const equipoIndex = findEquipoIndex(previewItem);
                currentEquipoIndex = equipoIndex;
                openCropModal(imageSrc, 'file');
            };

            // Función para encontrar índice del equipo
            function findEquipoIndex(element) {
                const equipoItem = element.closest('.equipo-item');
                const allEquipos = document.querySelectorAll('.equipo-item');
                return Array.from(allEquipos).indexOf(equipoItem);
            }

            // Función para eliminar foto existente
            window.removeExistingPhoto = function(button, fotoId) {
                Swal.fire({
                    title: '¿Eliminar foto?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const previewItem = button.closest('.preview-item');
                        
                        photosToDelete.push(fotoId);
                        updateDeletedPhotosInput();
                        
                        previewItem.style.animation = 'fadeOut 0.3s ease';
                        setTimeout(() => {
                            previewItem.remove();
                        }, 300);

                        Swal.fire({
                            icon: 'success',
                            title: 'Foto marcada para eliminar',
                            text: 'La foto se eliminará al guardar los cambios',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            };

            // Función para eliminar nueva foto
            window.removeNewPhoto = function(button, index) {
                const previewItem = button.closest('.preview-item');
                previewItem.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    previewItem.remove();
                    
                    // Limpiar inputs correspondientes
                    const container = previewItem.closest('[id^="filePreviews"], [id^="cameraPreviews"]');
                    if (container.id.includes('filePreviews')) {
                        const fileInput = document.getElementById(`fileInput${index}`);
                        if (fileInput) fileInput.value = '';
                    }
                    
                    checkEmptyState(index);
                }, 300);
            };

            // Función para actualizar input de fotos eliminadas
            function updateDeletedPhotosInput() {
                let deletedInput = document.querySelector('input[name="deleted_photos"]');
                if (!deletedInput) {
                    deletedInput = document.createElement('input');
                    deletedInput.type = 'hidden';
                    deletedInput.name = 'deleted_photos';
                    document.querySelector('form').appendChild(deletedInput);
                }
                deletedInput.value = JSON.stringify(photosToDelete);
            }

            // Función para verificar estado vacío
            function checkEmptyState(index) {
                const filePreviewContainer = document.getElementById(`filePreviews${index}`);
                const cameraPreviewContainer = document.getElementById(`cameraPreviews${index}`);
                const emptyState = document.getElementById(`emptyState${index}`);
                
                const hasNewPhotos = (filePreviewContainer && filePreviewContainer.children.length > 0) ||
                                   (cameraPreviewContainer && cameraPreviewContainer.children.length > 0);
                
                if (emptyState) {
                    emptyState.style.display = hasNewPhotos ? 'none' : 'block';
                }
            }

            // Función para mostrar imagen en modal
            window.showImageModal = function(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                $('#imageModal').modal('show');
            };

            // Función para configurar funcionalidad de un equipo
            function setupEquipoFunctionality(index) {
                setupFileInput(index);
            }

            // Validación del formulario
            document.querySelector('form').addEventListener('submit', function(e) {
                const equipos = document.querySelectorAll('.equipo-item');
                let isValid = true;

                equipos.forEach((equipo, index) => {
                    const nombre = equipo.querySelector(`input[name="equipos[${index}][nombre]"]`);
                    
                    if (!nombre.value.trim()) {
                        isValid = false;
                        Swal.fire('Error', `El nombre del equipo #${index + 1} es requerido`, 'error');
                        return;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });

        // Función para mostrar campos por tipo
        function mostrarCamposPorTipo(index) {
            // Implementar lógica específica según sea necesario
        }
    </script>
@endsection