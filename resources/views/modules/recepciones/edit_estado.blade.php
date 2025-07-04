
@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Editar Estado de Recepción</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('recepciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4>Recepción: {{ $recepcion->numero_recepcion }}</h4></div>
                        <div class="card-body">
                            <form action="{{ route('recepciones.update', $recepcion->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control" required>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado }}" {{ $recepcion->estado == $estado ? 'selected' : '' }}>
                                                {{ $estado }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Estado
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection