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
					Care nu s-au logat
				</option>

				<option value="NO_QUIZ">
					Care nu au completat quizul
				</option>

				<option value="NO_VOTES_COUNT_SENT">
					Nu au trimis numaratoarea
				</option>
			</select>
		</div>

		<div>
			<textarea name="message" placeholder="Mesaj..."></textarea>
		</div>
		<input type="submit" value="Trimite sms" />
	</div>
</form>