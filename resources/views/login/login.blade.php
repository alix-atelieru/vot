@include('login/header')
	<div id="login">
		<h2 style="color:red">
			@if(session('errorLabel'))
				{{ session('errorLabel') }}
			@endif
		</h2>
		<form method="POST" action="{{ route('admin.login') }}">
			{{ csrf_field() }}

			<p>
				<label for="username">
					Username:<br/>
					<input type="text" name="username" id="username" class="input" />
				</label>
			</p>

			<p>
				<label for="password">
					Parola:<br/>
					<input type="password" name="password" id="password" class="input" />
				</label>
			</p>

			<p class="submit" style="text-align:center">
				<input type="submit" class="button button-large" />
			</p>

		</form>
	</div>
@include('login/footer')