<ul class="nav-main">
    <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">MENU</span></li>

    <li>
        <a href="{{ route('vista.principal') }}"><i class="si si-home"></i><span class="sidebar-mini-hide">Principal</span></a>
    </li>
    <li>
        <a href="{{ route('vista.clientes') }}"><i class="fa fa-address-book-o"></i><span class="sidebar-mini-hide">Clientes</span></a>
    </li>
    <li>
        <a href="{{ route('vista.contratas') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Contratas</span></a>
    </li>
    <li>
        <a href="{{ route('historialCobranza') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Historial de cobros</span></a>
    </li>
    <li>
        <a href="{{ route('vista.historial_cobrador') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Historial de saldo</span></a>
    </li>

    <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Cobrar contratas</span></li>
    <li>
        <a href="{{ route('vista.contratas_cobrar') }}"><i class="fa fa-address-book-o"></i><span class="sidebar-mini-hide">Contratas a cobrar</span></a>
    </li>

    <li>
        <a href="{{ route('vista.gastos') }}"><i class="fa fa-money"></i><span class="sidebar-mini-hide">Gastos</span></a>
    </li>

    <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Sistema</span></li>
    <li>
        <a href="{{ route('vista.usuarios') }}"><i class="si si-info"></i><span class="sidebar-mini-hide">Información</span></a>
    </li>


</ul>