<div class="row">
@foreach ($tournament->players as $player)
    <div class="col-sm-6 col-lg-3">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-{{ $cores[$loop->iteration % count($cores)] }}">
                <div class="widget-user-image">
                    <img class="img-circle" src="{{ asset('img/user.png') }}" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ $player->name }}</h3>
                <h5 class="widget-user-desc">
                    {{ $player->pivot->team }}
                </h5>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
@endforeach
</div>

<div class="col-xs-12 col-lg-offset-3 col-lg-6">
    <h3 class="text-center">Tabela</h3>
    <div class="table-responsive">
        <table class="table table-standings table-bordered table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th class="col-xs-4">Nome</th>
                <th class="col-xs-1 text-center">J</th>
                <th class="col-xs-1 text-center">V</th>
                <th class="col-xs-1 text-center">E</th>
                <th class="col-xs-1 text-center">D</th>
                <th class="col-xs-1 text-center">Gp</th>
                <th class="col-xs-1 text-center">Gc</th>
                <th class="col-xs-1 text-center">Sg</th>
                <th class="col-xs-1 text-center">Pts</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@if ($tournament->matches->count())
<div class="row">
    <div class="col-lg-offset-1 col-lg-10">
        <h3 class="col-xs-12 text-center">Partidas</h3>
            @each('tournament.partials._week',
                $tournament->matches->groupBy('week')->sort(), 'matches')
    </div>
</div>
@endif

<div class="row voffset">
    <div class="col-lg-offset-1 col-lg-10"><div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Gols</h3>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-goals">
                <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Time</th>
                    <th>Gols</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Assistências</h3>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-assists">
                <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Time</th>
                    <th>Gols</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div></div>
</div>

<div class="box-footer">
    <a href="{{ route('tournament.index') }}"
       class="btn btn-default">
        Voltar
    </a>
    <!--button type="submit" class="btn btn-success pull-right">
        Ativar torneio
    </button-->
</div>

@push('js')
<script>
    $(document).ready(function() {
        $('.start-match, .end-match').click(function() {
            $(this).closest('form').submit();
        });

        $('.start-match-form').on('submit', function() {
            return confirm('Deseja realmente iniciar a partida?');
        });

        $('.end-match-form').on('submit', function() {
            return confirm('Deseja realmente terminar a partida?');
        });
    });

    var rows = {
        standing: function(player) {
            return $('<tr>')
                .append($('<td>', {class: "text-left ", text: player.name}))
                .append($('<td>', {class: "text-center ", text: player.matches}))
                .append($('<td>', {class: "text-center ", text: player.won}))
                .append($('<td>', {class: "text-center ", text: player.draw}))
                .append($('<td>', {class: "text-center ", text: player.lost}))
                .append($('<td>', {class: "text-center ", text: player.goals_for}))
                .append($('<td>', {class: "text-center ", text: player.goals_against}))
                .append($('<td>', {class: "text-center ", text: player.goal_diff}))
                .append($('<td>', {class: "text-center ", text: player.points}));
        },
        week: function(week) {},
        goal: function(info) {
            return $('<tr>')
                .append($('<td>', {text: info.name}))
                .append($('<td>', {text: info.team}))
                .append($('<td>', {text: info.goals}));
        },
        assist: function(info) {
            return $('<tr>')
                .append($('<td>', {text: info.name}))
                .append($('<td>', {text: info.team}))
                .append($('<td>', {text: info.assists}));
        }
    };

    function fetch() {
        $.ajax({
            method: 'POST',
            url: '{{ route('tournament.fetch', $tournament->id) }}',
            dataType: 'json',
            data: {_token: '{{ csrf_token() }}'},
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            success: function(data) {
                console.log(data);

                $('.table-standings tbody, .table-goals tbody, .table-assists tbody').html('');

                $('.table-standings tbody').append(
                    data.players
                        .sort(function (a, b) {
                            var criteria = ['points', 'goal_diff', 'won', 'goals_for'];
                            var res = 0;

                            for (var i = 0; res === 0 && i < criteria.length; i++)
                                res = b[criteria[i]] - a[criteria[i]];

                            return res;
                        })
                        .map(rows['standing']));

                $('.table-goals tbody').append(
                    data.goals
                        .sort(function (a, b) {return b.goals - a.goals;})
                        .map(rows['goal']));

                $('.table-assists tbody').append(
                    data.assists
                        .sort(function (a, b) {return b.assists - a.assists;})
                        .map(rows['assist']));
            }
        });
    }

    fetch();
</script>
@endpush