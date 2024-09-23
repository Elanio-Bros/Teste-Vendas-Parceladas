@extends('Base\list', ['links' => $products['links']])
@section('thead')
    <th scope="col">id</th>
    <th scope="col">Nome</th>
    <th scope="col">Qtnd. Estoque</th>
    <th scope="col">Preço Unit.</th>
    <th scope="col">Adicionado em</th>
    <th scope="col">Ações</th>
@endsection

@section('tbody')
    <div class="row">
        <div class="col-12 text-right">
            <button type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
    @foreach ($products['data'] as $value)
        <tr>
            <td>{{ $value['id'] }}</td>
            <td>{{ $value['name'] }}</td>
            <td>{{ $value['stock_quantity'] }}</td>
            <td>{{ number_format($value['unity_price'], 2, ',', '.') }}</td>
            <td>{{ date('d/m/Y H:i:s', strtotime($value['created_at'])) }}</td>
            <td>
                <button type="button" class="btn btn-warning" onclick="product_edit({{ $value['id'] }})"><i
                        class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
@endsection

@section('modal')
    @extends('Base\modal_form', ['id' => 'product'])
@section('modal_title')
    <h2>Teste</h2>
@endsection
@section('modal_body')
    <h2>Teste</h2>
@endsection
@section('modal_footer')
    <h2>Teste</h2>
@endsection
@endsection

@section('script')
<script>
    function product_edit(id) {
        $.get('/produtos/produto/' + id, function(response) {
            console.log(response)
        });
        $('#product').modal('show')
    }
</script>
@endsection
