@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414">Egresos</h1>
                <div class="section-header-breadcrumb">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoEgreso">
                        <i class="fas fa-plus"></i> Nuevo Egreso
                    </a>
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Aquí puedes gestionar los egresos.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-egresos">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre Cuenta</th>
                            <th>Glosa</th>
                            <th>Razón Social</th>
                            <th>N° Factura</th>
                            <th>Responsable</th>
                            <th>Método de Pago</th>
                            <th>Subtotal</th>
                            <th>Descuento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ejemplo de fila vacía, puedes agregar datos de prueba si lo deseas -->
                        <tr>
                            <td>--/--/----</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal de Nuevo Egreso (solo estructura visual) -->
    <div class="modal fade" id="modalNuevoEgreso" tabindex="-1" role="dialog" aria-labelledby="modalNuevoEgresoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoEgresoLabel">Registrar Nuevo Egreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="color: #151414;">
                            <div class="form-group col-md-6">
                                <label>Nombre Cuenta</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Glosa</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Razón Social</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>N° Factura</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Responsable</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Método de Pago</label>
                                <select class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Por pagar">Por pagar</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Subtotal</label>
                                <input type="number" step="0.01" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Descuento</label>
                                <input type="number" step="0.01" class="form-control" value="0">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Total</label>
                                <input type="number" step="0.01" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection