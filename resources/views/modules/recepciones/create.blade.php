@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        @include('modules.clientes.registrocliente')
        <form action="">
            @include('modules.recepciones.formulario_recepciones')
            @include('modules.equipos.formulario_equipo')
            <div class="d-flex justify-content-center mt-4 ">
                <a href="{{ route('recepciones.index') }}" class="btn btn-outline-secondary mr-2">
                    <i class="bi bi-arrow-left mr-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4  ">
                    <i class="bi bi-save mr-2"></i>Guardar Recepción
                </button>
            </div>
        </form>
    </div>
@endsection