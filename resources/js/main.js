$(function () {
	let dropdown = $('.menu-dropdown');
	dropdown.find('.menu-dropdown-target').on('click', function (e) {
		let par = $(this).parent();
		dropdown.not(par).removeClass('menu-dropdown-active');
		par.toggleClass('menu-dropdown-active');
		e.stopPropagation();

	});
	$(document).on('click', function(e) {
		dropdown.removeClass('menu-dropdown-active');
	});
})