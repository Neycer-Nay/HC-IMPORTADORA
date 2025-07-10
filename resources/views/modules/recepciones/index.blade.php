@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414" >Recepción de equipos</h1>
                <div class="section-header-breadcrumb">
                    <a href="{{ route('recepciones.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Recepción
                    </a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>Lista de recepciones</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <label style="color:#151414; font-size: 17px;" for="">Buscar recepciones por N° de recepcion, usuario o cliente</label>
                                    <form method="GET" action="{{ route('recepciones.index') }}" class="form-inline mb-3">
                                        <input type="text" name="buscar" class="form-control mr-2" placeholder="Buscar "
                                            value="{{ request('buscar') }}">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                            Buscar</button>
                                        @if(request('buscar'))
                                            <a href="{{ route('recepciones.index') }}"
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
                                                    <th>Fecha y hora</th>
                                                    <th>Cliente</th>
                                                    <th>Usuario</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recepciones as $recepcion)
                                                    <tr>
                                                        <td>{{ $recepcion->id }}</td>
                                                        <td>{{ $recepcion->numero_recepcion }}</td>
                                                        <td>{{ $recepcion->fecha_ingreso->format('d/m/Y') }}-{{ \Carbon\Carbon::parse($recepcion->hora_ingreso)->format('H:i') }}
                                                        </td>
                                                        <td>{{ $recepcion->cliente->nombre ?? 'N/A' }}</td>
                                                        <td>{{ $recepcion->usuario->nombre ?? 'N/A' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{  $recepcion->estado == ($recepcion->estado == 'EN_REPARACION' ? 'warning' : ($recepcion->estado == 'REPARADO' ? 'success' : 'secondary')) }}">
                                                                {{ str_replace('_', ' ', $recepcion->estado) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('recepciones.show', $recepcion->id) }}"
                                                                class="btn btn-info btn-sm" title="Ver">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('recepciones.edit', $recepcion->id) }}"
                                                                class="btn btn-primary btn-sm" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('cotizaciones.edit', $recepcion->id) }}"
                                                                class="btn btn-primary btn-sm" title="Editar">
                                                                <i class="fas fa-edit"></i> Cotizar
                                                            </a>
                                                            <form action="{{ route('recepciones.destroy', $recepcion->id) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            {{ $recepciones->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>

                                    <!-- Tarjetas para pantallas pequeñas -->
                                    <div class="d-block d-md-none">
                                        @foreach($recepciones as $recepcion)
                                            <div class="card mb-2">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title mb-1">N° Recepción: {{ $recepcion->numero_recepcion }}
                                                    </h5>
                                                    <p class="mb-1"><strong>ID:</strong> {{ $recepcion->id }}</p>
                                                    <p class="mb-1"><strong>Fecha y hora:</strong>
                                                        {{ $recepcion->fecha_ingreso->format('d/m/Y') }} -
                                                        {{ \Carbon\Carbon::parse($recepcion->hora_ingreso)->format('H:i') }}</p>
                                                    <p class="mb-1"><strong>Cliente:</strong>
                                                        {{ $recepcion->cliente->nombre ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>Usuario:</strong>
                                                        {{ $recepcion->usuario->nombre ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>Estado:</strong>
                                                        <span
                                                            class="badge badge-{{  $recepcion->estado == ($recepcion->estado == 'EN_REPARACION' ? 'warning' : ($recepcion->estado == 'REPARADO' ? 'success' : 'secondary')) }}">
                                                            {{ str_replace('_', ' ', $recepcion->estado) }}
                                                        </span>
                                                    </p>
                                                    <div>
                                                        <a href="{{ route('recepciones.show', $recepcion->id) }}"
                                                            class="btn btn-info btn-sm" title="Ver">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('recepciones.edit', $recepcion->id) }}"
                                                            class="btn btn-primary btn-sm" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('cotizaciones.edit', $recepcion->id) }}"
                                                                class="btn btn-primary btn-sm" title="Editar">
                                                                <i class="fas fa-edit"></i> Cotizar
                                                            </a>
                                                        <form action="{{ route('recepciones.destroy', $recepcion->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                                                onclick="return confirm('¿Estás seguro?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-center">
                                            {{ $recepciones->links('pagination::bootstrap-4') }}
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
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#table-1').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "order": [[0, "desc"]]
                });
            });
        </script>
    @endpush
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Recepción registrada',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
    @endif
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#table-1').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "order": [[0, "desc"]]
                });
            });
        </script>
    @endpush

    @if(session('swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '{{ session('swal.icon') }}',
                    title: '{{ session('swal.title') }}',
                    text: '{{ session('swal.text') }}',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
    @if($recepciones->isEmpty() && request('buscar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin resultados',
                    text: 'No se encontraron recepciones que coincidan con la búsqueda.',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection