<div class="tab-pane active" id="tab_instalacoes_associadas">
    @if(count($unidade->instalacoes) == 0)
        <p>Não existem instalações associadas.</p>
    @else
        <table id="tabela_instalacoes_associadas" class="table table-bordered table-hover dataTable sorting"
               cellspacing="0">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            </thead>

            <tbody>
            @foreach($unidade->instalacoes as $instalacao)
                <tr>
                    <td data-order="{{ str_slug($instalacao->nome) }}">
                        {{ $instalacao->nome }}
                    </td>
                    <td class="text-center">
                        {{ Form::open(['method' => 'post',
                            'route' => ['unidade.instalacao.desassociar',
                            $unidade->id, $instalacao->id],
                            'class' => 'form-inline-destroy', 'data-nome' => $instalacao->nome]) }}
                        <a href="#" class="destroy">
                            <i class="material-icons md-black md-24 show-tooltip"
                                title="Desassociar instalação">
                                &#xE14C;
                            </i>
                        </a>
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>