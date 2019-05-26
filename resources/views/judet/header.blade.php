<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="{{ Route::currentRouteNamed('judet.section') ? 'nav-item special_section_button active' : 'nav-item special_section_button' }}">
						<a class="btn btn-light" href="{{ route('judet.section') }}">
							Raport secție
						</a>
					</li>
					<li class="{{ Route::currentRouteNamed('judet.observers.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.show') }}">
							Delegati
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.election.count') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.election.count') }}">
							Rezultate alegeri in judet
						</a>
					</li>					

					

					<li class="{{ Route::currentRouteNamed('judet.observers.stats') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.stats') }}">
							Raportare
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.observers.quizes') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.quizes') }}">
							Chestionar Verificare
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.accounts.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.accounts.show') }}">
							Admini judeteni
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('admin.logout') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('admin.logout') }}">
							Logout
						</a>
					</li>

					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>