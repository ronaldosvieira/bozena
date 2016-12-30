<div class="week col-xs-12 col-lg-6">
    <div class="row">
        <h4 class="col-lg-offset-4 col-lg-4 text-center">
            {{ $key }}ª rodada
        </h4>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped table-condensed no-margin">
                @foreach ($matches as $match)
                    <tr class="match">
                        <td class="col-xs-5 text-right">
                            {{ $match->homePlayer->name }}
                        </td>
                        <td class="col-xs-1 text-center">
                            <span class="score score-home"></span>
                            x
                            <span class="score score-away"></span>
                        </td>
                        <td class="col-xs-5 text-left">
                            {{ $match->awayPlayer->name }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>