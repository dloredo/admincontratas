<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <!-- Row #1 -->
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-wallet fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled"><?php echo "$" . number_format(round(((float)$saldo_esperado)),2,'.',',');?></span></div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">SALDO ESPERADO</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-users fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="780" class="js-count-to-enabled">{{ $total_clientes }}</span></div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Clientes</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="fa fa-usd fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="15">{{ $total_contratas }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Contratas</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="fa fa-user fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="4252">{{ $total_cobradores }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Cobradores</div>
            </div>
        </a>
    </div>
    <!-- END Row #1 -->
</div>