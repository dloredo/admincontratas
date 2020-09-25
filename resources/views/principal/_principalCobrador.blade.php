<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">

    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled">{{ $total_clientes }}</span></div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Clientes asignados</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-body-bg-dark"></i>
                </div>
                @foreach ($saldo as $saldo)
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled">{{ "$" . number_format(round(((float)$saldo->saldo)),2,'.',',') }}</span></div>
                @endforeach
                <div class="font-size-sm font-w600 text-uppercase text-muted">Saldo actual</div>
            </div>
        </a>
    </div>
    <!-- END Row #1 -->
</div>