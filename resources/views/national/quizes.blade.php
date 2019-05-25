@include('national/header')
@include('admin/quizes_filter')
@include('admin/jump_to_page')

<form method="GET" action="{{ route('national.quiz.export') }}">
	<input class="btn btn-primary mb-2" type="submit" value="Exporta toate chestionarele" />
</form>

@include('admin/quizes')
