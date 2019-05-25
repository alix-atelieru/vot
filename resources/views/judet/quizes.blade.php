@include('judet/header')
@include("admin/jump_to_page")

<form method="GET" action="{{ route('judet.quiz.export') }}">
	<input class="btn btn-primary mb-2" type="submit" value="Exporta chestionarele din judet" />
</form>


@include('admin/quizes')