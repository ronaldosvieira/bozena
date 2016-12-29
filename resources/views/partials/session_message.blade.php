@if (Session::has('message'))
	<div class="alert alert-success alert-dismissable col-sm-12 col-sm-offset-0 col-md-6 col-md-offset-3">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Sucesso</h4>
        {{ Session::get('message') }}
    </div>
    <div class="clearfix"></div>
@endif

@if (Session::has('error'))
	<div class="alert alert-danger alert-dismissable col-sm-12 col-sm-offset-0 col-md-6 col-md-offset-3">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Ação inválida</h4>
        {{ Session::get('error') }}
    </div>
    <div class="clearfix"></div>
@endif

@if (Session::has('info'))
    <div class="alert alert-info alert-dismissable col-sm-12 col-sm-offset-0 col-md-6 col-md-offset-3">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Info</h4>
        {{ Session::get('info') }}
    </div>
    <div class="clearfix"></div>
@endif