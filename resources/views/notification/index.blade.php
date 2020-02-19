@extends('layout.default')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ Asset::get('css/ieducar.css') }}"/>
@endpush

@section('content')
    <form id="formcadastro" action="" method="get">
        <table class="tablecadastro" width="100%" border="0" cellpadding="2" cellspacing="0">
            <tbody>
            <tr>
                <td class="formdktd" colspan="2" height="24"><b>Notificações</b></td>
            </tr>
            <tr id="tr_nm_instituicao">
                <td class="formmdtd" valign="top"><span class="form">Notificações:</span></td>
                <td class="formmdtd" valign="top">
                    <span class="form">
                        <select class="geral" name="status" id="status" style="width: 308px;">
                            <option value="">Todas</option>
                            <option value="lidas">Lidas</option>
                            <option value="naolidas">Não lidas</option>
                        </select>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="separator"></div>

        <div style="text-align: center">
            <button class="btn-green" type="submit">Buscar</button>
        </div>

    </form>

    <table class="table-default">
        <thead>
        <tr>
            <th>Texto</th>
            <th>Data</th>
        </tr>
        </thead>
        <tbody>
        @forelse($notifications as $notification)
            <tr>
                <td><a href="{{$notification->link}}">{{$notification->text}}</a></td>
                <td>{{$notification->created_at->format('d/m/Y H:i')}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">Não existe nenhuma notificação</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="separator"></div>

    <div style="text-align: center">
        {{ $notifications->links() }}
    </div>
@endsection

@prepend('scripts')
    <script>
        if ($j('#nova_situacao').val() != '4') {
            $j('.field-transfer').hide();
        }

        $j('#nova_situacao').on('change', function () {
            if ($j(this).val() == '4') {
                $j('.field-transfer').show();
            } else {
                $j('.field-transfer').hide();
            }
        })
    </script>

    <script type="text/javascript"
            src="{{ Asset::get("/modules/Portabilis/Assets/Javascripts/ClientApi.js") }}"></script>
    <script type="text/javascript"
            src="{{ Asset::get("/modules/DynamicInput/Assets/Javascripts/DynamicInput.js") }}"></script>
    <script type="text/javascript"
            src="{{ Asset::get("/modules/DynamicInput/Assets/Javascripts/Escola.js") }}"></script>
    <script type="text/javascript"
            src="{{ Asset::get("/modules/DynamicInput/Assets/Javascripts/Curso.js") }}"></script>
    <script type="text/javascript"
            src="{{ Asset::get("/modules/DynamicInput/Assets/Javascripts/Serie.js") }}"></script>
    <script type="text/javascript"
            src="{{ Asset::get("/modules/DynamicInput/Assets/Javascripts/Turma.js") }}"></script>
@endprepend
