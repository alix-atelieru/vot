@include('superadmin/header')

@if (session('error'))
	{{ session('error') }}
@endif

@if (session('success'))
	{{ session('success') }}
@endif

<form method="POST">
	{{ csrf_field() }}
	<div>
		<div>
			Judet:
			<select name="judet_id">
				<option value="ALL">
					Toate
				</option>

				@foreach ($filter['judete'] as $judet)

					<option value="{{ $judet->id }}">
						{{ $judet->name }}
					</option>
				@endforeach
			</select>

			<select name="type">
				<option value="ALL">
					Toti
				</option>
				<option value="NO_LOGIN">
					Nu au facut login in app
				</option>

				<option value="QUIZ_DONE">
					Au completat Chestionar
				</option>

				<option value="NO_QUIZ">
					Nu au completat Chestionar
				</option>

				<option value="VOTES_COUNT_SENT">
					Au completat datele pentru Numaratoare Paralela
				</option>

				<option value="NO_VOTES_COUNT_SENT">
					Nu au completat datele pentru Numaratoare Paralela
				</option>
			</select>
		</div>

		<div>
			<textarea name="message" placeholder="Mesaj..."></textarea>
		</div>
		<input type="submit" value="Trimite sms" />
	</div>
</form>