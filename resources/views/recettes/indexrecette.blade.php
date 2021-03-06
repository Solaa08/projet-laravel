@extends('layouts.layout_admin')



@section('content')

<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class="uper">

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  @if(session()->get('error'))
      <div class="alert alert-danger">
          {{ session()->get('error') }}
      </div><br />
  @endif

  <table class="table table-striped table-bordered dt-responsive nowrap" id="recette_table" style="width: 100em">

    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nom</th>
            <th scope="col">URL</th>
            <th scope="col">Type</th>
            <th scope="col">Action</th>
            <th scope="col">ㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤ</th>
        </tr>
    </thead>

    <tbody>
        @foreach($recettes as $recette)
        <tr>
            <td class="text-center" onclick="show_ingredients('{{$recette->id}}',this)">{{$recette->id}}</td>
            <td>{{$recette->nom}}</td>
            <td><a href="{!! $recette->url !!}">{!! $recette->url !!}</a></td>
            <td>{{$recette->type}}</td>
            <td>
                <a href="{{ route('recette.edit', $recette->id)}}" class="btn btn-primary">Modif</a>
                <form action="{{ route('recette.destroy', $recette->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Supp</button>
                </form>
            </td>
            <td id="table_ingredients_{{$recette->id}}" >
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <a href="recette/create"><button class="btn btn-primary">Ajouter</button></a>
<div>
@endsection

@section('script')
    <script type="application/javascript">
        let recette_table = $('#recette_table').DataTable({
            responsive: {
                details: true
            },
            language: {"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"}
        });

        function show_ingredients(id_recette,obj){
            var tr = $(obj).closest('tr');
            var row = recette_table.row( tr );

            if ( !row.child.isShown() ) {
                // Open this row
                $.ajax({
                method: "GET",
                url: "recette/"+id_recette,
                })
                .done(function( contenu_html ) {
                    row.child( contenu_html ).show();
                    tr.addClass('shown');
                });
            }
            /*
            else {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }*/
        }

        function delete_ingredient(id_ingredient,id_recette,obj) {
            var tr = $(obj).closest('tr');
            $.ajax({
                method: "POST",
                url: "recette/ingredient_destroy",
                data: {"_token": "{{ csrf_token() }}",ingredient_id: id_ingredient,recette_id: id_recette}
            })
            .done(function( contenu_html ) {
                tr.remove();
            });
        }
    </script>
@endsection
