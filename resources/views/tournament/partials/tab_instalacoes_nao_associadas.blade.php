<div class="tab-pane" id="tab_instalacoes_nao_associadas">
    @if(count(\App\Instalacao::all()->diff($unidade->instalacoes)) == 0)
        <p>Não existem instalações não associadas.</p>
    @else
        <table id="tabela_instalacoes_nao_associadas" class="table table-bordered table-hover dataTable sorting"
               cellspacing="0">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Instalacao::all()->diff($unidade->instalacoes) as $instalacao)
                <tr>
                    <td data-order="{{ str_slug($instalacao->nome) }}">
                        {{ $instalacao->nome }}
                    </td>
                    <td class="text-center">
                        {{ Form::open(['method' => 'post',
                            'route' => ['unidade.instalacao.associar',
                            $unidade->id, $instalacao->id],
                            'class' => 'form-inline-destroy', 'data-nome' => $instalacao->nome]) }}
                        <a href="#" class="destroy">
                            <i class="material-icons md-black md-24 show-tooltip"
                               title="Associar instalação">
                                &#xE145;
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