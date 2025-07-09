
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proforma Recepción N° {{ $recepcion->numero_recepcion }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #151414; }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { float: left; width: 120px; }
        .empresa-info { text-align: right; font-size: 11px; }
        .clearfix { clear: both; }
        .section { margin-bottom: 10px; }
        .datos-empresa, .datos-cliente { width: 100%; margin-bottom: 10px; }
        .datos-empresa td, .datos-cliente td { font-size: 12px; padding: 2px 4px; }
        .titulo { background: #e0e7ef; font-weight: bold; padding: 4px; }
        .tabla-equipos, .tabla-repuestos { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .tabla-equipos th, .tabla-equipos td, .tabla-repuestos th, .tabla-repuestos td { border: 1px solid #b0b0b0; padding: 5px; font-size: 12px; }
        .tabla-equipos th, .tabla-repuestos th { background: #e0e7ef; }
        .fotos { margin-top: 5px; }
        .fotos img { width: 90px; height: auto; margin-right: 5px; margin-bottom: 5px; border-radius: 4px; border: 1px solid #aaa; }
        .condiciones { font-size: 10px; margin-top: 15px; }
        .totales { width: 250px; float: right; margin-top: 10px; }
        .totales td { font-size: 12px; padding: 3px 6px; }
        .footer { font-size: 10px; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- Cabecera -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="30%">
                    {{-- Si tienes logo, descomenta la siguiente línea --}}
                     <img src="{{ public_path('login.jpg') }}" class="logo"> 
                </td>
                <td width="70%" class="empresa-info">
                    <strong>HC INDUSTRIAL</strong><br>
                    MANTENIMIENTO Y REPARACIÓN DE MAQUINARIA ELÉCTRICA INDUSTRIAL<br>
                    Dir: Av. Alemana Esq. Costanera<br>
                    Cel: 76578154 - 72868051<br>
                    <strong>PRO-FORMA</strong>
                </td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>

    <!-- Datos de la empresa/cliente -->
    <table class="datos-empresa">
        <tr>
            <td class="titulo" colspan="4">DATOS DE LA EMPRESA O PARTICULAR</td>
        </tr>
        <tr>
            <td><strong>Tipo:</strong></td>
            <td>{{ $recepcion->cliente->tipo ?? 'Particular' }}</td>
            <td><strong>Solicitante:</strong></td>
            <td>{{ $recepcion->cliente->nombre }}</td>
        </tr>
        <tr>
            <td><strong>Celular:</strong></td>
            <td>{{ $recepcion->cliente->telefono_1 }}</td>
            <td><strong>Fecha:</strong></td>
            <td>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <!-- Información de equipos y repuestos -->
    @foreach($recepcion->equipos as $equipo)
        <table class="tabla-equipos">
            <tr>
                <th colspan="4">MANTENIMIENTO CORRECTIVO DE EQUIPO {{ strtoupper($equipo->tipo) }}</th>
            </tr>
            <tr>
                <td><strong>Tipo:</strong> {{ $equipo->tipo }}</td>
                <td><strong>Marca:</strong> {{ $equipo->marca }}</td>
                <td><strong>Modelo:</strong> {{ $equipo->modelo }}</td>
                <td><strong>Serie:</strong> {{ $equipo->serie }}</td>
            </tr>
            <tr>
                <td><strong>Color:</strong> {{ $equipo->color ?? '-' }}</td>
                <td><strong>HP:</strong> {{ $equipo->hp ?? '-' }}</td>
                <td><strong>RPM:</strong> {{ $equipo->rpm ?? '-' }}</td>
                <td><strong>KW:</strong> {{ $equipo->kw ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Descripcion:</strong> {{ $equipo->pivot->descripcion ?? '-' }}</td>
            </tr>
        </table>

        <table class="tabla-repuestos">
            <tr>
                <th>CANT</th>
                <th>DESCRIPCIÓN</th>
                <th>U. UNITARIO</th>
                <th>TOTAL</th>
            </tr>
            @if(isset($equipo->pivot->repuestos_detalle) && is_array($equipo->pivot->repuestos_detalle))
                @foreach($equipo->pivot->repuestos_detalle as $repuesto)
                    <tr>
                        <td>{{ $repuesto['cantidad'] }}</td>
                        <td>{{ $repuesto['descripcion'] }}</td>
                        <td>{{ number_format($repuesto['unitario'], 2) }}</td>
                        <td>{{ number_format($repuesto['total'], 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $equipo->pivot->repuestos ?? 0 }}</td>
                    <td>Repuestos varios</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            @endif
            <tr>
                <td colspan="2"><strong>IMAGEN {{ strtoupper($equipo->nombre) }}</strong></td>
                <td colspan="2">
                    <div class="fotos">
                        @if(isset($equipo->fotosSeleccionadas) && count($equipo->fotosSeleccionadas))
                            @foreach($equipo->fotosSeleccionadas as $foto)
                                <img src="{{ public_path('storage/' . $foto->ruta) }}" alt="Foto">
                            @endforeach
                        @else
                            <span class="text-muted">Sin fotos</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    @endforeach

    <!-- Totales (puedes calcularlos en el controlador y pasarlos a la vista) -->
    <table class="totales">
        <tr>
            <td>Sub Total Bs</td>
            <td>{{ number_format($subtotal ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Descuento Bs</td>
            <td>{{ number_format($descuento ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total Bs</strong></td>
            <td><strong>{{ number_format($total ?? 0, 2) }}</strong></td>
        </tr>
    </table>
    <div class="clearfix"></div>

    <!-- Condiciones -->
    <div class="condiciones">
        <strong>Esta cotización está sujeta a los términos y condiciones que se enuncian a continuación:</strong><br>
        1. Tiempo de entrega 2 a 3 días hábiles<br>
        2. Vigencia de la oferta 15 días hábiles<br>
        3. Forma de pago 50% por adelantado y saldo contra entrega<br>
        4. Garantía del servicio 3 meses<br>
        5. Taller HC no se responsabiliza por el equipo dejado más de 90 días<br>
        <br>
        Agradecemos su preferencia y quedamos a su disposición para cualquier consulta adicional. Su satisfacción es nuestra prioridad y estamos comprometidos a proporcionar servicios de la más alta calidad.
    </div>

    <div class="footer">
        HC INDUSTRIAL - Mantenimiento y Reparación de Maquinaria Eléctrica Industrial
    </div>
</body>
</html>