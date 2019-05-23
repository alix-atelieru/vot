<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="{{ Route::currentRouteNamed('superadmin.observers.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.observers.show') }}">
							Observatori
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('superadmin.admins.judet.add.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.admins.judet.add.show') }}">
							Adauga admini nationali/judeteni
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('superadmin.sections.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.sections.show') }}">
							Sectii
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('superadmin.mass-sms.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.mass-sms.show') }}">
							Mass SMS
						</a>
					</li>

					<!--
					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>
					-->

					<li class="{{ Route::currentRouteNamed('superadmin.observers.stats') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.observers.stats') }}">
							Raportare
						</a>
					</li>
					
					<li class="{{ Route::currentRouteNamed('superadmin.observers.quizes') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('superadmin.observers.quizes') }}">
							Chestionare
						</a>
					</li>
					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>