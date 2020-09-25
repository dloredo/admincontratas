<nav id="sidebar" data-simplebar="init">
    <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: -17px; bottom: 0px;">
                <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                    <div class="simplebar-content" style="padding: 0px;">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Side Header -->
                            <div class="content-header content-header-fullrow px-15">
                                <!-- Mini Mode -->
                                <div class="content-header-section sidebar-mini-visible-b">
                                    <!-- Logo -->
                                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                        <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                                    </span>
                                    <!-- END Logo -->
                                </div>
                                <!-- END Mini Mode -->

                                <!-- Normal Mode -->
                                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                                    <!-- Close Sidebar, Visible only on mobile screens -->
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                    <!-- END Close Sidebar -->

                                    <!-- Logo -->
                                    <div class="content-header-item">
                                        <a class="link-effect font-w700" href="index.html">
                                            <i class="si si-fire text-primary"></i>
                                            <span class="font-size-xl text-dual-primary-dark"></span><span class="font-size-xl text-primary">Contratas</span>
                                        </a>
                                    </div>
                                    <!-- END Logo -->
                                </div>
                                <!-- END Normal Mode -->
                            </div>
                            <!-- END Side Header -->


                            <!-- Side Navigation -->
                            <div class="content-side content-side-full">
                                @if(Auth::user()->id_rol == 1)
                                    @include('layouts.menu._menuAdmin')
                                @else
                                    @include('layouts.menu._menuCobrador')
                                @endif
                            </div>
                            <!-- END Side Navigation -->
                        </div>
                        <!-- Sidebar Content -->
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: auto; height: 1362px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar" style="height: 316px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
    </div>
</nav>