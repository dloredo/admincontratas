<ul class="nav-main">
    <li class="nav-main-heading" style="padding-top: 0px !important;"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">MENU</span></li>

    <li>
        <a href="{{ route('vista.principal') }}"><i class="si si-home"></i><span class="sidebar-mini-hide">Principal</span></a>
    </li>
    <li>
        <a href="{{ route('vista.capital.cortes') }}"><i class="fa fa-money"></i><span class="sidebar-mini-hide">Capital</span></a>
    </li>
    <li>
        <a href="{{ route('vista.clientes') }}"><i class="fa fa-address-book-o"></i><span class="sidebar-mini-hide">Clientes</span></a>
    </li>

    <li>
        <a href="{{ route('historialCobranza') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Hisotrial de cobros</span></a>
    </li>
    <!--<li>
        <a href="{{ route('vista.contratas') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Contratas</span></a>
    </li>-->

    <li>
        <a href="{{ route('vista.noPagadas') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Listados</span></a>
    </li>

    <li>
        <a href="{{ route('vista.gastos') }}"><i class="fa fa-money"></i><span class="sidebar-mini-hide">Gastos</span></a>
    </li>

    <li>
        <a href="{{ route('vista.categorias') }}"><i class="fa fa-clipboard"></i><span class="sidebar-mini-hide">Categorias de gastos</span></a>
    </li>
    <li>
        <a href="{{ route('numerosHabiles') }}"><i class="fa fa-address-book-o"></i><span class="sidebar-mini-hide">Clientes vacantes</span></a>
    </li>

    <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Sistema</span></li>
    
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><span class="sidebar-mini-hide">Administración</span></a>
        <ul>
            <li>
                <a href="{{ route('vista.usuarios') }}">Usuarios</a>
            </li>
            <li>
                <a href="{{ route('vista.desestimarFechas') }}">Desestimar fechas</a>
            </li>
            <!-- <li>
                <a href="be_pages_hosting_account.html">Información</a>
            </li>
            <li>
                <a href="be_pages_hosting_support.html">Support</a>
            </li> -->
        </ul>
    </li>


</ul>