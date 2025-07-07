@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Clientes</h1>
                <div class="section-header-breadcrumb">
                    
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Lista de clientes</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ route('clientes.index') }}" class="form-inline mb-3">
                                        <input type="text" name="buscar" class="form-control mr-2"
                                            placeholder="Buscar por nombre" value="{{ request('buscar') }}">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                            Buscar</button>
                                        @if(request('buscar'))
                                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ml-2">Limpiar</a>
                                        @endif
                                    </form>
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>N° Documento</th>
                                                <th>telefonos</th>
                                                <th>Correo</th>
                                                <th>Direcion</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clientes as $cliente)
                                                <tr>
                                                    <td>{{ $cliente->nombre }}</td>
                                                    <td>{{ $cliente->tipo }}</td>
                                                    <td>{{ $cliente->tipo_documento}}-{{ $cliente->numero_documento }} </td>
                                                    <td>{{ $cliente->telefono_1}}-
                                                    {{ $cliente->telefono_2}}- 
                                                    {{ $cliente->telefono_3}} </td>
                                                    <td>{{ $cliente->email}} </td>
                                                    <td>{{ $cliente->ciudad }}-{{ $cliente->direccion}} </td>
                                                    <td> <a href="{{ route('clientes.show', $cliente->id) }}"
                                                            class="btn btn-info btn-sm" title="Ver">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {{ $clientes->links('pagination::bootstrap-4') }}
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
@section('scripts')
    @if($clientes->isEmpty() && request('buscar'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Sin resultados',
                text: 'No se encontraron clientes que coincidan con la búsqueda.',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@endsection



