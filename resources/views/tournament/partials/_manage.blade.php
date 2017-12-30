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
        <div class="weeks"></div>
    </div>
</div>
@endif

<!-- week template -->
<div class="week template col-xs-12 col-lg-6">
    <div class="row">
        <h4 class="col-lg-offset-4 col-lg-4 text-center">
            <span class="week-num"></span>
            ª rodada
        </h4>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-matches table-striped table-condensed no-margin"></table>
        </div>
    </div>
</div>

<!-- match template -->
<table>
    <tr class="match template" data-match="" data-tournament="">
        <td class="col-xs-2 match-state"></td>
        <td class="col-xs-3 home-team text-right"></td>
        <td class="col-xs-1 text-center">
            <span class="score score-home"></span>
            x
            <span class="score score-away"></span>
        </td>
        <td class="col-xs-3 away-team text-left"></td>
        <td class="col-xs-2 actions">
            <a class="acao start-match">
                <i class="fa fa-play" data-toggle="tooltip"
                   data-placement="bottom" title="Iniciar partida"></i>
            </a>

            <a class="acao add-goal">
                <i class="fa fa-soccer-ball-o" data-toggle="tooltip"
                   data-placement="bottom" title="Adicionar gol"></i>
            </a>

            <a class="acao end-match">
                <i class="fa fa-hand-paper-o" data-toggle="tooltip"
                   data-placement="bottom" title="Terminar partida"></i>
            </a>
        </td>
    </tr>
</table>

@include('tournament.partials._goal_modal')

<div class="row voffset">
    <div class="col-lg-offset-1 col-lg-10"><div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Gols</h3>
        <table class="table table-responsive table-hover table-striped table-goals">
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
    <div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Assistências</h3>
        <table class="table table-responsive table-hover table-striped table-assists">
            <thead>
            <tr>
                <th>Jogador</th>
                <th>Time</th>
                <th>Gols</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
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
    if (!Object.entries)
        Object.entries = function( obj ){
            var ownProps = Object.keys( obj ),
                i = ownProps.length,
                resArray = new Array(i); // preallocate the Array
            while (i--)
                resArray[i] = [ownProps[i], obj[ownProps[i]]];

            return resArray;
        };

    $(document).ready(function() {
        $('#goal-modal input, #goal-modal select')
            .change(function() {
                $('#goal-modal .form-group').removeClass('has-error');
            });

        $('.add-goal')
            .click(function() {
                var match = $(this).closest('.match');
                var goal_modal = $('#goal-modal');

                goal_modal.find('.home-team-modal').text(match.find('.home-team').text());
                goal_modal.find('.away-team-modal').text(match.find('.away-team').text());

                goal_modal.find('#match_id').val(match.data('match'));
                goal_modal.find('#team').val('').change();
                goal_modal.find('#scorer').val('');
                goal_modal.find('#assister').val('');

                goal_modal.modal('show');
            });

        $('.add-goal-submit')
            .click(function() {
                var goal_modal = $('#goal-modal');
                var data = {
                    match_id: goal_modal.find('#match_id').val(),
                    team: goal_modal.find('#team').val(),
                    scorer: goal_modal.find('#scorer').val(),
                    assister: goal_modal.find('#assister').val()
                };

                if (!data.team || ['HOME', 'AWAY'].indexOf(data.team) === -1) {
                    goal_modal.find('#team').closest('.form-group').addClass('has-error');
                    return;
                }

                if (!data.scorer) {
                    goal_modal.find('#scorer').closest('.form-group').addClass('has-error');
                    return;
                }

                $.ajax({
                    method: 'POST',
                    url: '{{ route('tournament.match.goal.store', $tournament->id) }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        match_id: data.match_id,
                        team: data.team,
                        scorer: data.scorer,
                        assister: data.assister
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    success: function (data) {
                        if (!data.success) console.log(data.error);

                        fetch();
                        goal_modal.modal('hide');
                    }
                });
            });

        $('.start-match')
            .click(function() {
                if (!confirm('Deseja realmente iniciar a partida?'))
                    return;

                var match = $(this).closest('.match');

                $.ajax({
                    method: 'POST',
                    url: '{{ route('tournament.match.start', $tournament->id) }}',
                    data: {_token: '{{ csrf_token() }}', match_id: match.data('match')},
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    success: function (data) {
                        if (!data.success) console.log(data.error);

                        fetch();
                    }
                });
            });

        $('.end-match')
            .click(function() {
                if (!confirm('Deseja realmente terminar a partida?'))
                    return;

                var match = $(this).closest('.match');

                $.ajax({
                    method: 'POST',
                    url: '{{ route('tournament.match.end', $tournament->id) }}',
                    data: {_token: '{{ csrf_token() }}', match_id: match.data('match')},
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    success: function (data) {
                        if (!data.success) console.log(data.error);

                        fetch();
                    }
                });
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
        week: function(entry) {
            var week_num = entry[0];
            var matches = entry[1];

            var el = $('.week.template').clone(true, true).removeClass('template');

            el.find('.table-matches').append(
                matches.map(function (match, index, matches) {
                    var el2 = $('.match.template').clone(true, true).removeClass('template');

                    el2.attr('data-match', match.id);
                    el2.attr('data-tournament', '{{ $tournament->id }}');

                    el2.find('.match-state').text(match.state);
                    el2.find('.home-team').text(match.home_team);
                    el2.find('.away-team').text(match.away_team);

                    if (match.is_started) {
                        el2.find('.score-home').text(match.home_score);
                        el2.find('.score-away').text(match.away_score);
                    }

                    if (match.can_add_goals)
                        el2.find('.acao.start-match').remove();
                    else if (!match.is_done)
                        el2.find('.acao.end-match, .acao.add-goal').remove();
                    else
                        el2.find('.acao').remove();

                    return el2;
                }));

            el.find('.week-num').text(week_num);

            return el;
        },
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
        $('.refresh-button').attr('disabled', true);

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
            success: function(data) {console.log(data);
                $('.table-standings tbody, .weeks, .table-goals tbody, .table-assists tbody').html('');

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

                $('.weeks').append(
                    Object.entries(data.matches)
                        .map(rows['week']));

                $('.table-goals tbody').append(
                    data.goals
                        .sort(function (a, b) {return b.goals - a.goals;})
                        .map(rows['goal']));

                $('.table-assists tbody').append(
                    data.assists
                        .sort(function (a, b) {return b.assists - a.assists;})
                        .map(rows['assist']));

                $('.refresh-button')
                    .attr('disabled', false)
                    .find('.last')
                    .html(new Date().toLocaleTimeString());
            }
        });
    }

    $('.refresh-button').click(fetch);

    var fetch_loop = function () {
        fetch();
        setTimeout(fetch_loop, 60 * 1000);
    };

    fetch_loop();
</script>
@endpush