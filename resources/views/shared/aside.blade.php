<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route("dashboard.index") }}"><img src="{{ asset('dist/assets/img/logo.jpeg') }}" alt="logo" width="70"
        class="shadow-light "></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route("dashboard.index") }}"><img src="{{ asset('dist/assets/img/logo.jpeg') }}" alt="logo" width="50"
        class="shadow-light "></a>
        </div>
        <ul class="sidebar-menu">
            
            <li class="dropdown ">
                <a href="{{ route("dashboard.index") }}" class="nav-link "><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                
            </li>
            
            <li class="dropdown">
                <a href="{{ route('recepciones.index') }}" class="nav-link "><i class="fas fa-cogs"></i>
                    <span>Equipos recepcionados</span></a>
                
            </li>
            <li class="dropdown">
                <a href="{{ route('cotizaciones.index') }}" class="nav-link "><i class="fas fa-file-invoice"></i>
                    <span>Cotizaciónes de equipos</span></a>
                
            </li>
            <li><a class="nav-link" href="{{ route('recepciones.create') }}"><i class="fas fa-file-signature"></i> <span>
                Crear nueva recepción
            </span></a></li>
            <li class="dropdown">
                <a href="{{ route('usuarios.index') }}" class="nav-link "><i class="far fa-user"></i> <span>Usuarios</span></a>
                
            </li>
            <li class="dropdown">
                <a href="{{ route('clientes.index') }}" class="nav-link "><i class="fas fa-users"></i>
                <span>Clientes</span></a>
                
            
            
            
            
        </ul>

        
    </aside>
</div>