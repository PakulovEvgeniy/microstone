$(function () {
	let dropdown = $('.menu-dropdown');
	dropdown.find('.menu-dropdown-target').on('click', function () {
		$(this).parent().toggleClass('menu-dropdown-active');
	})
})