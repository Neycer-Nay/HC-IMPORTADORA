@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Recepcion de equipos</h1>
                
            </div>

            <div class="section-body">
                


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                
                                    <h4>Tabla de recepciones</h4>
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-2">
                                        <thead>
                                            <tr>
                                               
                                                <th>ID</th>
                                                <th>N° Recepción</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Usuario</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection