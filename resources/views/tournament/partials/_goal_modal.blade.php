<div class="modal fade goal-modal" id="goal-modal" tabindex="-1" role="dialog" aria-labelledby="goal-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="goal-modal-label">
                    Adicionar gol à partida
                    <span class="home-team-modal"></span>
                    x
                    <span class="away-team-modal"></span>
                </h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="match_id">

                <div class="form-group team-modal">
                    <label class="control-label col-sm-3 text-right" for="team">Jogador *</label>
                    <div class="col-sm-8">
                        <select id="team" class="form-control">
                            <option value="" selected>(Escolha um time)</option>
                            <option value="HOME" class="home-team-modal"></option>
                            <option value="AWAY" class="away-team-modal"></option>
                        </select>
                    </div>
                </div>

                <div class="form-group scorer-modal">
                    <label class="control-label col-sm-3 text-right" for="scorer">Autor do gol *</label>
                    <div class="col-sm-8">
                        <input type="text" id="scorer" class="form-control">
                    </div>
                </div>

                <div class="form-group assister-modal">
                    <label class="control-label col-sm-3 text-right" for="assister">Autor da assistência</label>
                    <div class="col-sm-8">
                        <input type="text" id="assister" class="form-control">
                    </div>
                </div>

                <p class="pull-right">* Campos obrigatórios</p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success add-goal-submit">Adicionar gol</button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>