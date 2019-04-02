$(function () {
	let dropdown = $('.menu-dropdown');
	let headbot = $('.header-bottom');
	let header = $('header');
	dropdown.find('.menu-dropdown-target').on('click', function (e) {
		let par = $(this).parent();
		dropdown.not(par).removeClass('menu-dropdown-active');
		par.toggleClass('menu-dropdown-active');
		e.stopPropagation();

	});
	$(document).on('click', function(e) {
		dropdown.removeClass('menu-dropdown-active');
	});
	$(window).on('scroll', function (e) {
		if (headbot.is(':hidden')) {return}
		let scrolled = $(document).scrollTop();
		if (scrolled>40) {
			headbot.addClass('header-fixed');
			header.css('padding-top', '60px');
		} else {
			header.css('padding-top', '0');
			headbot.removeClass('header-fixed');
		}
	})
})