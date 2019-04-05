<header>
	<div class="navbar-menu">
		<div class="header-top">
			<div class="container">
				<ul class="header-top-menu-list">
					<li>
						<i class="fas fa-phone"></i>
						<span class="header-top-phone">{{ $settings['company_phone'] }}</span>
						<span class="header-top-calltime">{{ $settings['company_calltime'] }}</span>
					</li>
					<li class="menu-dropdown header-top-menu-shop">
						<a class="menu-dropdown-target">Покупателям</a>
						<i class="fa fa-chevron-down"></i>
						<ul class="menu-dropdown-items">
							<li><a>Как оформить заказ</a></li>
							<li><a>Доставка</a></li>
							<li><a>Способы оплаты</a></li>
							<li><a>Бонусная программа</a></li>
							<li><a>Узнать статус заказа</a></li>
							<li><a>Обмен и возврат товара</a></li>
							<li><a>Информация для юр. лиц</a></li>
						</ul>
						
					</li>
					<uvedoml-component></uvedoml-component>
					@guest
						<li><a href="{{ route('login') }}">Войти</a></li>
						<li><a href="{{ route('register') }}">Регистрация</a></li>
					@else
						<li class="menu-dropdown">
							<i class="fa fa-user"></i>
							<a class="menu-dropdown-target">{{ Auth::user()->email }}</a>
							<i class="fa fa-chevron-down"></i>
							<ul class="menu-dropdown-items">
								<li><a>Профиль</a></li>
								<li><a>Контрагенты</a></li>
								<li><a>Бонусы</a></li>
								<li><a>Заказы</a></li>
								<li><a>Списки</a></li>
								<li><a>Уведомления</a></li>
								<li><a>Обратная связь</a></li>
								<li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Выход</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
							</ul>
						</li>
					@endguest
				</ul>
				
			</div>
		</div>
	</div>
	<nav class="header-bottom">
		<div class="container">
			<div class="header-bottom-container">
				<div class="logo-container">
					<a href={{ URL::to('/') }}>MICROSTONE</a>
				</div>
				<form class="main-search-form" method="get">
					<div class="main-search-form-container">
						<input type="text" class="main-search-form-input" name="search" placeholder="Поиск по каталогу">
						<span class="main-search-form-button-container">
							<button tabindex="2" type="submit" class="main-search-form-button"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form>
				<div class="header-buttons">
					<a>
						<span>Все списки</span>
					</a>
					<a>
						<span>Избранное</span>
					</a>
					<a class="btn-cart">
						<i class="fa fa-shopping-cart"></i>
						<span class="price">
							<span>Корзина</span>
						</span>
					</a>
				</div>
			</div>
		</div>
	</nav>
</header>