@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Usuarios</h1>
                <div class="section-header-breadcrumb">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#usuarioModal">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </button>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Lista de Usuarios</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->nombre }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->rol}} </td>
                                                    <td> <a href="{{ route('usuarios.edit', $user->id) }}"
                                                            class="btn btn-warning btn-sm" title="Editar"><i
                                                                class="fas fa-edit"></i></a>
                                                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                                                data-name="{{ $user->nombre ?? 'usuario' }}"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" role="dialog" aria-labelledby="usuarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Registrar Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="usuarioForm" action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                required value="{{ old('nombre') }}">
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                required value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <select class="form-control w-100 @error('rol') is-invalid @enderror" id="rol" name="rol"
                                required>
                                <option value="Gerente" {{ old('rol') == 'Gerente' ? 'selected' : '' }}>Gerente</option>
                                <option value="Contabilidad" {{ old('rol') == 'Contabilidad' ? 'selected' : '' }}>Contabilidad
                                </option>
                                <option value="Supervisor" {{ old('rol') == 'Supervisor' || !old('rol') ? 'selected' : '' }}>
                                    Supervisor</option>
                            </select>
                            @error('rol')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="usuarioForm" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Asegurarse que el DOM está completamente cargado
    document.addEventListener('DOMContentLoaded', function() {



        @if(session('swal'))
        Swal.fire({
            icon: '{{ session('swal')['icon'] }}',
            title: '{{ session('swal')['title'] }}',
            text: '{{ session('swal')['text'] }}',
            confirmButtonColor: '#3085d6',
            timer: 5000,
            timerProgressBar: true
        });
        @endif
        // Confirmación para eliminar usuarios
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const userName = this.getAttribute('data-name');
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Vas a eliminar a ${userName}. Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Mostrar alerta de éxito/error después de operaciones
        @if(session('swal'))
            Swal.fire({
                icon: '{{ session('swal')['icon'] }}',
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: true
            });
        @endif

        // Abrir automáticamente el modal si hay errores
        @if($errors->any())
            $(function() {
                $('#usuarioModal').modal('show');
                
                // Hacer scroll al primer campo con error
                $('.is-invalid').first().focus();
            });
        @endif
    });
</script>
@endsection