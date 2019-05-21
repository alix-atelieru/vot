@include('judet/header')

@if (session('error'))
	{{ session('error') }}
@endif

@if (session('success'))
	{{ session('success') }}
@endif

<form method="POST" action="{{ route('judet.message.upsert') }}">
	{{ csrf_field() }}
	
	<div>
		Mesaj:
		<textarea name="content" value="{{ $message->content }}">{{ $message->content }}</textarea>
	</div>
	<div>
		<input type="submit" value="Salveaza" />
	</div>
</form>