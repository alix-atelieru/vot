<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="{{ Route::currentRouteNamed('national.section') ? 'nav-item special_section_button active' : 'nav-item special_section_button' }}">
						<a class="btn btn-light" href="{{ route('national.section') }}">
							Raport secție
						</a>
					</li>
					<li class="{{ Route::currentRouteNamed('national.observers.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.observers.show') }}">
							Delegati
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('national.admins.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.admins.show') }}">
							Admini nationali
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.admins.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.admins.show') }}">
							Admini judeteni
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('national.sections.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.sections.show') }}">
							Sectii
						</a>
					</li>

					

					<!--
					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>
					-->

					<li class="{{ Route::currentRouteNamed('national.observers.stats') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.observers.stats') }}">
							Raportare Delegati
						</a>
					</li>

					<!--
					<li class="{{ Route::currentRouteNamed('national.election.count') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.election.count') }}">
							Rezultate nationale alegeri
						</a>
					</li>
					-->
					<li class="{{ Route::currentRouteNamed('national.election.judet.count') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.election.judet.count') }}">
							Rezultate alegeri judet
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('national.observers.quizes') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('national.observers.quizes') }}">
							Chestionar Verificare
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