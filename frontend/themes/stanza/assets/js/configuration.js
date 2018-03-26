(function($) {
    "use strict";
	
	
		
	
	//////////////////////////////////////////////////////
	// NAVIGATION PAGES DROPDOWN MENU SCRIPT
	//////////////////////////////////////////////////////
	function mymenu() {
		var myTarget = $(".main_menu_cont > ul > li");
		var childname = '.sub-menu';
		myTarget.each(function() {
			if ( $(this).children(childname).length > 0 ) {
				if($(this).children("i.showSMenu").length < 1) {
					$(this).append('<i class="showSMenu"></i>');
				}
			}		
		});
		$(".main_menu_cont > ul > li > i.showSMenu").on( "click", function(e){
			e.preventDefault();
			$(this).prev("ul").stop().slideToggle(200);
		});
	}
	
	
	
	//////////////////////////////////////////////////////
	// DROPDOWN MENU POSITION ADJUSTMENT
	//////////////////////////////////////////////////////
	function submenu_adjustments() {
		var somevar = $(window).width();
		if (somevar > 1200) {
			$(".main_menu_cont > ul > li").mouseenter(function() {
				var childname = '.sub-menu';
				if ( $(this).children(childname).length > 0 ) {
					var submenu = $(this).children(childname);
					var window_width = parseInt($(window).innerWidth(),10);
					var submenu_width = parseInt(submenu.width(),10);
					var submenu_offset_left = parseInt(submenu.offset().left,10);
					var submenu_adjust = window_width - submenu_width - submenu_offset_left;
					
					if (submenu_adjust < 0) {
						submenu.css("left", submenu_adjust-30 + "px").addClass("no-arrow");
					}
				} else {
					$(this).children(childname).removeClass("no-arrow");
				}
			});
		}
	}
	
	
	
	
	
	//////////////////////////////////////////////////////
	// Sticky Header Style 7 & 2 
	//////////////////////////////////////////////////////
	function stickyMenu() {
		function checkSticky() {
	
			var stickyTar = $("header.header.style7, header.header.style2"); //add sticky only on these header styles
	
			if( stickyTar.length) {
				var winScrollTop = $(window).scrollTop();
				var somevar = $(window).width();
				
				if(somevar >= 1200 && winScrollTop > 80) {
					addSticky();
				}
				else {
					removeSticky();
				}
			}
	
			function addSticky() {
				stickyTar.addClass("sticky_head");
			}
			function removeSticky() {
				stickyTar.removeClass("sticky_head");
			}
			function removeSticky2() {
				$("#content .sticky_head").removeClass("sticky_head");
			}
			if ($('.noSticky').length){
				removeSticky2();
			}
		}
	
		checkSticky();
	
		$(window).resize(function() {
			checkSticky();
		});
		$(window).scroll(function() {
			checkSticky();
		});
	
	
	}
	
	//////////////////////////////////////////////////////
	// Custom Drop Down Menu For Shop Pages
	//////////////////////////////////////////////////////
	function DropDown(el) {
		this.dd = el;
		this.placeholder = this.dd.children('span');
		this.opts = this.dd.find('ul.dropdown > li');
		this.val = '';
		this.index = -1;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;
			obj.dd.on('click', function(){
				$(this).toggleClass('active');
				return false;
			});
			obj.opts.on('click',function(){
				var opt = $(this);
				obj.val = opt.text();
				obj.index = opt.index();
				obj.placeholder.text(obj.val);
			});
		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	};
	
	
	//////////////////////////////////////////////////////
	// Call Custom Drop Down Menu For Shop Pages
	//////////////////////////////////////////////////////
	$(function() {
		new DropDown( $('#selectDropdown') );  //Shop Litsing Page :- Sort by popularity
		new DropDown( $('#selectDropdown1') ); //Shop Litsing Page :- Filter By Size
		new DropDown( $('#selectDropdown2') ); //Single Shop Page (select-options-style.html) :- Size Select Drop Down option
		new DropDown( $('#selectDropdown3') ); //Single Shop Page (select-options-style.html) :- Color Select Drop Down option
		$(document).on("click", function() {
			$('.selectDropdown').removeClass('active');
		});
	});
	
		
	
		$(window).resize(function() {				
			//////////////////////////////////////////////////////
			// submenu fixes on device/screen change
			//////////////////////////////////////////////////////
			var somevar = $(window).width();
			if (somevar >= 1200) {
				$(".main_menu_cont > ul ul").removeAttr("style");
			}
			submenu_adjustments();
		});
		
		
		
		//////////////////////////////////////////////////////
		// calling dropdown navigation menu function
		//////////////////////////////////////////////////////
		mymenu();
		submenu_adjustments();
		stickyMenu();
		
		
		
		//////////////////////////////////////////////////////
		// Column height equalize on resize/rotate
		//////////////////////////////////////////////////////
		$('.eq-height').matchHeight();
		$('.eq-height1').matchHeight();
		$('.best-seller > div').matchHeight();
		$('.blogRow .blogBox').matchHeight();
		$('.prodCarousel .productBox').matchHeight();
		
		
		
		//////////////////////////////////////////////////////
		// NAVIGATION SEARCH SCRIPT
		//////////////////////////////////////////////////////
		$(".nav_search>.searchBTN").on("click", function(){
			$(this).parent(".nav_search").find(".mini-search").stop().slideToggle(200);
			return false;
		});
		$(document).mouseup(function (e) {
			var popup = $(".mini-search");
			if (!$('.mini-search').is(e.target) && !popup.is(e.target) && popup.has(e.target).length === 0) {
				popup.slideUp(200);
			}
			return false;
		});
	
		//////////////////////////////////////////////////////
		// MOBILE MENU SCRIPT
		//////////////////////////////////////////////////////
		$('.mbmenu').on( "click", function(e) {
			$(this).next('div').children('ul').slideToggle(400);
			e.preventDefault();
			return false;
		});
		
		//////////////////////////////////////////////////////
		// CART ICON SCRIPT
		//////////////////////////////////////////////////////	
		$(".mini-cart").on( "mouseenter", function(){
			$(this).find("div.cartSummery").stop().slideDown(200);
		});
		$(".mini-cart").on( "mouseleave", function(){
			$(this).find("div.cartSummery").stop().slideUp(200);
		});
		
		//////////////////////////////////////////////////////
		// OWL CAROUSEL SCRIPT FOR CATEGORY
		//////////////////////////////////////////////////////
		if($('#catCarousel').length) {
			var owl = $("#catCarousel");
			owl.owlCarousel({
			 
			  itemsCustom : [
				[320, 1],
				[480, 1],
				[640, 2],
				[768, 3],
				[991, 3],
				[1280, 4],
				[1600, 4]
			  ],
			  navigation: true,
			  navigationText : false,
			  pagination: true,
			  afterUpdate: function(){
				  $.fn.matchHeight._update(); //on carousel rezied call match height function again
			  }
			
			});
		}
		
		//////////////////////////////////////////////////////
		// OWL CAROUSEL SCRIPT FOR PRODUCTS
		//////////////////////////////////////////////////////
		if($('.prodCarousel').length) {
			var owl2 = $(".prodCarousel");
			owl2.owlCarousel({
			 
			  itemsCustom : [
				[320, 1],
				[480, 1],
				[640, 2],
				[768, 3],
				[991, 3],
				[1280, 4],
				[1600, 4]
			  ],
			  navigation: true,
			  navigationText : false,
			  pagination: true,
			  afterUpdate: function(){
				  $.fn.matchHeight._update(); //on carousel rezied call match height function again
			  }
			
			});
			
		}
		
		//////////////////////////////////////////////////////
		// OWL CAROUSEL SCRIPT FOR TESTIMONIALS
		//////////////////////////////////////////////////////
		if($('#testimonial_slider').length) {
			var owl3 = $("#testimonial_slider");
			owl3.owlCarousel({
			 
			  itemsCustom : [
				[320, 1],
				[480, 1],
				[768, 1],
				[980, 1],
				[1280, 1],
				[1600, 1]
			  ],
			  navigation: true,
			  navigationText : false,
			  pagination: false
			
			});
		}
		
		//////////////////////////////////////////////////////
		// ACCORDION SCRIPT
		//////////////////////////////////////////////////////
		if($('.list_accordion').length) {
			$('.list_accordion dt:eq(0)').addClass('open').next().slideDown();
			
			$('.list_accordion dt').on("click", function() {
				//$(this).children().toggleClass('open').end().next('.faq_a').slideToggle();
				if($(this).hasClass('open')){
					$(this).removeClass('open').next().slideUp();
				}
				else {
					$('.list_accordion dt.open').removeClass('open').next().slideUp();
					$(this).toggleClass('open').end();
					$(this).next().slideToggle();
				}
				return false;
			});
		}
		
		//////////////////////////////////////////////////////
		// ACCORDION LI SCRIPT
		//////////////////////////////////////////////////////
		if($('.li_accordion').length) {
			$('.li_accordion li').find('ul').addClass('acorDropDown');
			$('.acorDropDown:eq(0)').slideDown().prev().addClass('open');
			
			$('.acorDropDown').prev().on("click", function() {
				//$(this).children().toggleClass('open').end().next('.faq_a').slideToggle();
				if($(this).hasClass('open')){
					$(this).removeClass('open').next().slideUp();
				}
				else {
					$('.li_accordion li a.open').removeClass('open').next().slideUp();
					$(this).toggleClass('open').end();
					$(this).next().slideToggle();
				}
				return false;
			});
		}
		
		//////////////////////////////////////////////////////
		// SCROLL TOP SCRIPT
		//////////////////////////////////////////////////////
		if($('.scroll_top').length) {
			$('.scroll_top').on("click", function(){
				$('html, body').animate({scrollTop : 0},600);
				return false;
			});
		}
		$(window).scroll(function () {
			if($(window).scrollTop() > 200) {
				$('.scroll_top').addClass('show');
			}else{
				$('.scroll_top').removeClass('show');
			}
			return false;
		});
		
		//////////////////////////////////////////////////////
		// TOGGLE SIDE NAV SCRIPT
		//////////////////////////////////////////////////////
		$( ".toggleNav" ).on( "click", function() {
			$(".overlay").addClass("closed");
			$(".slideNav").removeClass("closed");
			$(this).addClass("closed");
			//$(".content").addClass("closed");
			//$("#wrapper").addClass("closed")
			return false;
		});
		$(".overlay").on( "click", function() {
			$(this).removeClass("closed");
			$(".slideNav").addClass("closed");
			$(this).removeClass("closed");
			//$(".content").removeClass("closed");
			//$("#wrapper").removeClass("closed")
			return false;
		});
		$(".close-btn").on( "click", function() {
			$(".slideNav").addClass("closed");
			$(".overlay").removeClass("closed");
			return false;
		});
		
		//////////////////////////////////////////////////////
		// PRODUCT THUMB CAROUSEL SCRIPT
		//////////////////////////////////////////////////////
		var sync1 = $("#product-image");
		var sync2 = $("#product-thumb");
		
		sync1.owlCarousel({
			singleItem : true,
			slideSpeed : 1000,
			navigation: true,
			pagination:false,
			afterAction : syncPosition,
			responsiveRefreshRate : 200,
		});
		 
		sync2.owlCarousel({
			items : 4,
			itemsDesktop      : [1199,4],
			itemsDesktopSmall : [979,3],
			itemsTablet       : [640,4],
			itemsMobile       : [479,2],
			pagination:false,
			responsiveRefreshRate : 100,
			afterInit : function(el){
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});
		 
		function syncPosition(){
			/*jshint validthis: true */
			var current = this.currentItem;
			$("#product-thumb")
				.find(".owl-item")
				.removeClass("synced")
				.eq(current)
				.addClass("synced");
			if($("#product-thumb").data("owlCarousel") !== undefined){
				center(current);
			}
		}
		
		//////////////////////////////////////////////////////
		// Product Page Thumbnail
		//////////////////////////////////////////////////////
		$("#product-thumb").on("click", ".owl-item", function(e){
			e.preventDefault();
			var number = $(this).data("owlItem");
			sync1.trigger("owl.goTo",number);
		});
		 
		function center(number){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					found = true;
				}
			}
		 
			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2);
				}else{
					if(num - 1 === -1){
					num = 0;
				}
				sync2.trigger("owl.goTo", num);
			}
			} else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1]);
			} else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1);
			}
		}
		
		//////////////////////////////////////////////////////
		// PRODUCT PLUS MINUS SCRIPT
		//////////////////////////////////////////////////////
		$(".qtyClick").on("click", function (e) {
			e.preventDefault();
			var button = $(this);
			var input = button.closest('.sp-quantity').find("input.quntity-input");
			input.val(function(i, value) {
				return +value + (1 * +button.data('multi'));
			});
		});
		
		
		//////////////////////////////////////////////////////
		// COLOR BOX SCRIPT
		//////////////////////////////////////////////////////
		$('.popup').each(function(){ 
			$(this).colorbox({rel:$(this).attr('data-rel')});
		});
		$('.popup').colorbox({maxWidth:'95%', maxHeight:'95%', scrolling:false, fixed:true});
		$(".liteBox").colorbox({maxWidth:'95%', maxHeight:'95%'});
		$(".customLiteBox").colorbox({inline:true, width:"80%"});
		$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		$(".vimeo").colorbox({iframe:true, innerWidth:"80%", innerHeight:"80%"});
		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
		$(".inline").colorbox({inline:true, width:"80%", height:"auto", maxHeight:'95%'});
		
		
		
		//////////////////////////////////////////////////////
		// Home Page Style One, Five, Eight & Ten:- SLIDER SCRIPTS
		//////////////////////////////////////////////////////
		$("#slider1").revolution({
			sliderType:"standard",
			//jsFileLocation:"../revolution/js/",
			sliderLayout:"auto",
			delay:8000,
			navigation: {
				keyboardNavigation:"off",
				keyboard_direction: "horizontal",
				mouseScrollNavigation:"off",
				onHoverStop:"off",
				arrows: {
					style:"erinyen",
					enable:true,
					hide_onmobile:true,
					hide_under:600,
					hide_onleave:true,
					hide_delay:200,
					hide_delay_mobile:1200,
					tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div>    <div class="tp-arr-img-over"></div>	<span class="tp-arr-titleholder">{{title}}</span> </div>',
					left: {
						h_align:"left",
						v_align:"center",
						h_offset:30,
						v_offset:0
					},
					right: {
						h_align:"right",
						v_align:"center",
						h_offset:30,
						v_offset:0
					}
				},
				bullets: {
					enable: true,
					hide_onmobile: true,
					hide_under: 800,
					style: "zeus",
					hide_onleave: false,
					direction: "horizontal",
					h_align: "center",
					v_align: "bottom",
					h_offset: 0,
					v_offset: 30,
					space: 5,
					tmp: '<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title">{{title}}</span>'
				}
			},
			gridwidth:1120,
			gridheight:617
		});
	
		//////////////////////////////////////////////////////
		// Home Page Style Two & Nine:- SLIDER SCRIPTS
		//////////////////////////////////////////////////////
		$("#slider2").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:8000,
			navigation: {
				keyboardNavigation:"off",
				keyboard_direction: "horizontal",
				mouseScrollNavigation:"off",
				onHoverStop:"off",
				arrows: {
					style:"erinyen",
					enable:true,
					hide_onmobile:true,
					hide_under:600,
					hide_onleave:true,
					hide_delay:200,
					hide_delay_mobile:1200,
					tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div>    <div class="tp-arr-img-over"></div>	<span class="tp-arr-titleholder">{{title}}</span> </div>',
					left: {
						h_align:"left",
						v_align:"center",
						h_offset:30,
						v_offset:0
					},
					right: {
						h_align:"right",
						v_align:"center",
						h_offset:30,
						v_offset:0
					}
				},
				thumbnails: {
					style:"gyges",
					enable:true,
					width:40,
					height:40,
					min_width:40,
					wrapper_padding:0,
					wrapper_color:"transparent",
					wrapper_opacity:"1",
					tmp:'<span class="tp-thumb-img-wrap">  <span class="tp-thumb-image"></span></span>',
					visibleAmount:5,
					hide_onmobile:true,
					hide_under:800,
					hide_onleave:false,
					direction:"horizontal",
					span:false,
					position:"inner",
					space:5,
					h_align:"center",
					v_align:"bottom",
					h_offset:0,
					v_offset:20
				}
			},
			gridwidth:1120,
			gridheight:771
		});
	
		//////////////////////////////////////////////////////
		// Home Page Style Four:- SLIDER SCRIPTS
		//////////////////////////////////////////////////////
		$("#slider3").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:8000,
			navigation: {
				keyboardNavigation:"off",
				keyboard_direction: "horizontal",
				mouseScrollNavigation:"off",
				onHoverStop:"off",
				arrows: {
					style:"erinyen",
					enable:true,
					hide_onmobile:true,
					hide_under:600,
					hide_onleave:true,
					hide_delay:200,
					hide_delay_mobile:1200,
					tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div>    <div class="tp-arr-img-over"></div>	<span class="tp-arr-titleholder">{{title}}</span> </div>',
					left: {
						h_align:"left",
						v_align:"center",
						h_offset:30,
						v_offset:0
					},
					right: {
						h_align:"right",
						v_align:"center",
						h_offset:30,
						v_offset:0
					}
				}
			},
			gridwidth:835,
			gridheight:665
		});
	
		//////////////////////////////////////////////////////
		// Home Page Style Six:- SLIDER SCRIPTS
		//////////////////////////////////////////////////////
		$("#slider4").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:8000,
			navigation: {
				keyboardNavigation:"off",
				keyboard_direction: "horizontal",
				mouseScrollNavigation:"off",
				onHoverStop:"off",
				arrows: {
					style:"zeus",
					enable:true,
					hide_onmobile:true,
					hide_under:600,
					hide_onleave:true,
					hide_delay:200,
					hide_delay_mobile:1200,
					tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
					left: {
						h_align:"left",
						v_align:"center",
						h_offset:30,
						v_offset:0
					},
					right: {
						h_align:"right",
						v_align:"center",
						h_offset:30,
						v_offset:0
					}
				},
				bullets: {
					enable:true,
					hide_onmobile:false,
					style:"uranus",
					hide_onleave:false,
					direction:"horizontal",
					h_align:"center",
					v_align:"bottom",
					h_offset:30,
					v_offset:30,
					space:5,
					tmp:'<span class="tp-bullet-inner"></span>'
				}
			},
			gridwidth:1120,
			gridheight:617
		});
	
		//////////////////////////////////////////////////////
		// 	Shop Page Grid Layout Slider :- SLIDER SCRIPTS
		//////////////////////////////////////////////////////
		$("#slider5").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:8000,
			navigation: {
				keyboardNavigation:"off",
				keyboard_direction: "horizontal",
				mouseScrollNavigation:"off",
				onHoverStop:"off",
				arrows: {
					style:"zeus",
					enable:true,
					hide_onmobile:true,
					hide_under:600,
					hide_onleave:true,
					hide_delay:200,
					hide_delay_mobile:1200,
					tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
					left: {
						h_align:"left",
						v_align:"center",
						h_offset:30,
						v_offset:0
					},
					right: {
						h_align:"right",
						v_align:"center",
						h_offset:30,
						v_offset:0
					}
				},
				bullets: {
					enable: true,
					hide_onmobile: true,
					hide_under: 800,
					style: "zeus",
					hide_onleave: false,
					direction: "horizontal",
					h_align: "center",
					v_align: "bottom",
					h_offset: 0,
					v_offset: 30,
					space: 5,
					tmp: '<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title">{{title}}</span>'
				}
			},
			gridwidth:1120,
			gridheight:460
		});
		
		
		//////////////////////////////////////////////////////
		// NAV CLICK TO SCROLL
		//////////////////////////////////////////////////////
		$('.linkScroll a').on('click', function(event) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.substr(1) +']');
			if (target.length) {
				event.preventDefault();
				$('html,body').animate({
				  scrollTop: target.offset().top-79
				}, 1000);
				return false;
			}
		});
		if( location.hash ) {
			$('html,body').animate({
				scrollTop: $(location.hash).offset().top-79
			}, 1000);
		}
		
		
		
		
		//////////////////////////////////////////////////////
		// Carousel Slider integrated into Single Product Page  
		//////////////////////////////////////////////////////
		if($('#product-slider').length) {
			$('#product-slider').lightSlider({
				gallery:true,
				item:1,
				loop:false,
				thumbItem:4,
				auto: false,
				slideMargin:0,
				enableDrag: false,
				controls: true,
				currentPagerPosition:'left',
				onSliderLoad: function(el) {
					el.lightGallery({
						selector: '#product-slider .hoverIcons .eye',
						enableDrag: false
					});
				}   
			});
		}
		
	
	//////////////////////////////////////////////////////
	// Masonry Grid Layout For Shop & Blog
	//////////////////////////////////////////////////////
	$(window).load(function() {		
		if($('.isotope_cont').length){
			$('.isotope_cont').isotope({
				itemSelector: '.isotope-item'
			});
		}
	});	
	
	
	
})(jQuery);