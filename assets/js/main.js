/*-------------------------------------
Template name:  	One Listing
Version:        	1.0
Author Name:        wpWax
---------------------------------------
JS INDEX
=======================================
#. Preloader
#. Search Popup
#. Mobile Menu
#. Magnific Popup
#. Counter Up
#. Click to Copy
#. Average Rating Cont
#. Author Dropdown Activation
#. Listing With map
#. Search Home category Icon
#. Responsive Menu Trigger
#. Bootstrap Tooltip
#. Contact Info
---------------------------------------*/

"use strict";

(function ($) {
    var theme = {

        /* Preloader */
        preloader: function () {
            if ($('#theme-preloader') !== null) {
                $('#theme-preloader').fadeOut('slow', function () {
                    // $(this).remove();
                });
            }
        },

        /* Favourite Button */
        favouriteButton: function () {
            const requireLoggedIn = document.querySelector('.directorist-action-save-wrap .directorist-action-save .atbdp-require-login');
            const saveButton = document.querySelector('.directorist-action-save-wrap .directorist-action-save');
            const saveButtonIcon = document.querySelector('.directorist-action-save-wrap .directorist-action-save .directorist-icon-mask.directorist-added-to-favorite');

            if (requireLoggedIn || saveButton || saveButtonIcon) {
                function favouriteInitial() {
                    if (!requireLoggedIn) {
                        if (saveButtonIcon) {
                            saveButton.classList.add("directorist-added-to-favorite");
                        } else {
                            saveButton.classList.remove("directorist-added-to-favorite");
                        }
                    }
                }

                function favouriteAdded() {
                    const saveButtonIcon = document.querySelector('.directorist-action-save-wrap .directorist-action-save .directorist-icon-mask.directorist-added-to-favorite');
                    if (!requireLoggedIn) {
                        if (saveButtonIcon) {
                            saveButton.classList.remove("directorist-added-to-favorite");
                        } else {
                            saveButton.classList.add("directorist-added-to-favorite");
                        }
                    }
                }

                favouriteInitial()

                $('body').on('click', '.directorist-action-save-wrap .directorist-action-save', function (e) {
                    favouriteAdded()
                });
            }


        },

        /* Search Popup */
        searchPopup: function () {
            const searchButton = document.querySelector('.dspb-search__button');
            let searchOverlay = document.querySelector('.dspb-search__overlay');
            const searchPopup = document.querySelector('.dspb-search__popup');
            const closeSearchButton = document.querySelector('.dspb-search__form-close');
            const responsiveSearchButtons = document.querySelectorAll('.dspb-search .directorist-search-form-action__modal .directorist-modal-btn');
            const responsiveSearchOverlays = document.querySelectorAll('.dspb-search .directorist-search-modal__overlay');
            const closeResponsiveSearchButton = document.querySelector('.dspb-search .directorist-search-modal__contents__btn--close');

            // Function to toggle the search popup and overlay visibility
            function toggleSearchPopup(event) {
                event.preventDefault();
                searchPopup.classList.toggle('show');
                searchOverlay.classList.toggle('show');
                document.body.classList.toggle('dspb-search-hidden');
            }

            // Function to hide the search popup and overlay
            function hideSearchPopup() {
                searchPopup.classList.remove('show');
                searchOverlay.classList.remove('show');
                document.body.classList.remove('dspb-search-hidden');
            }

            // Function to handle responsive dropdown close action
            function closeResponsiveSearch() {
                searchPopup.classList.toggle('responsive-true');
                searchOverlay.classList.remove('show');
                document.body.classList.remove('dspb-search-hidden');
            }

            // Function to reset responsive dropdown to default
            function resetResponsiveSearch() {
                searchOverlay.classList.add('show');
                searchPopup.classList.remove('responsive-true');
                document.body.classList.add('dspb-search-hidden');
            }

            // Check if the search overlay exists; if not, create it
            if (!searchOverlay) {
                searchOverlay = document.createElement('div');
                searchOverlay.className = 'dspb-search__overlay';
                document.body.appendChild(searchOverlay);
            }

            // Add event listeners if elements exist
            if (searchButton && searchOverlay && closeSearchButton) {
                searchButton.addEventListener('click', toggleSearchPopup);
                searchOverlay.addEventListener('click', hideSearchPopup);
                closeSearchButton.addEventListener('click', hideSearchPopup);

                responsiveSearchButtons.forEach(button => {
                    button.addEventListener('click', closeResponsiveSearch);
                });

                responsiveSearchOverlays.forEach(overlay => {
                    overlay.addEventListener('click', resetResponsiveSearch);
                });

                if (closeResponsiveSearchButton) {
                    closeResponsiveSearchButton.addEventListener('click', resetResponsiveSearch);
                }
            }
        },


        /* Swiper Slider */
        swiperSlider: function () {

            /* Check Slider Data */
            let checkData = function (data, value) {
                return typeof data === 'undefined' ? value : data;
            };

            /* Swiper Slider */
            let swiperCarousel = document.querySelectorAll('.theme-swiper');
            let swiperCarouselNested = document.querySelectorAll('.theme-swiper-nested');
            swiperCarousel.forEach(function (el, i) {
                let hasPagination = false;
                let hasNav = false;
                const alllistingSlider = el.closest('.theme-swiper-slider');
                el.classList.add(`theme-swiper-${i}`);

                if (alllistingSlider) {
                    alllistingSlider.querySelector('.theme-swiper-slider__top').childNodes.forEach(elm => {
                        if (elm.className === "theme-swiper-button-nav-wrap") {
                            hasNav = true;
                        }
                    });
                } else if (el.querySelector('.theme-swiper-button-nav-wrap')) {
                    hasNav = true;
                }

                let navBtnPrev = el.querySelectorAll('.theme-swiper-button-prev');
                let navBtnNext = el.querySelectorAll('.theme-swiper-button-next');
                if (alllistingSlider) {
                    navBtnPrev = alllistingSlider.querySelectorAll('.theme-swiper-button-prev');
                    navBtnNext = alllistingSlider.querySelectorAll('.theme-swiper-button-next');
                }

                el.childNodes.forEach(elc => {
                    if (elc.className === "theme-swiper-pagination") {
                        hasPagination = true;
                    }
                    if (elc.className === "theme-swiper-navigation") {

                        hasNav = true;
                    }
                });

                const paginationElement = document.querySelectorAll(".theme-swiper-pagination");
                hasNav && navBtnPrev.forEach((el) => {
                    el.classList.add(`theme-swiper-button-prev-${i}`);
                });
                hasNav && navBtnNext.forEach((el) => {
                    el.classList.add(`theme-swiper-button-next-${i}`);
                });

                hasPagination && paginationElement.forEach((el) => el.classList.add(`theme-swiper-pagination-${i}`));
                
                let swiper1 = new Swiper(`.theme-swiper-${i}`, {
                    slidesPerView: parseInt(checkData(el.dataset.swItems, 4)),
                    spaceBetween: parseInt(checkData(el.dataset.swMargin, 30)),
                    loop: checkData(el.dataset.swLoop, true),
                    slidesPerGroup: parseInt(checkData(el.dataset.swPerslide, 2)),
                    speed: parseInt(checkData(el.dataset.swSpeed, 3000)),
                    autoplay: checkData(el.dataset.swAutoplay, {}),
                    freeMode: checkData(el.dataset.swFreemode, false),
                    watchSlidesProgress: checkData(el.dataset.swWatchprogress, false),
                    slideToClickedSlide: checkData(el.dataset.swslidetoclickedside, false),
                    navigation: {
                        nextEl: `.theme-swiper-button-next-${i}`,
                        prevEl: `.theme-swiper-button-prev-${i}`,
                    },
                    pagination: {
                        el: `.theme-swiper-pagination-${i}`,
                        type: 'bullets',
                        clickable: true,
                    },
                    breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
                });

                var swiper2 = new Swiper(".product-gallery-main", {
                    spaceBetween: 10,
                    loop: true,
                    thumbs: {
                        swiper: swiper1,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        type: "fraction",
                    },
                });

                // Destroy Swiper Slider When Slider Image Are Less Than Minimum Required Image
                function destroySwiperSlider() {
                    var windowScreen = screen.width;

                    var breakpoints = JSON.parse(el.dataset.swResponsive);

                    var breakpointKeys = Object.keys(breakpoints);

                    var legalBreakpointKeys = breakpointKeys.filter(breakpointKey => breakpointKey <= windowScreen);

                    var currentBreakpointKey = legalBreakpointKeys.reduce((prev, acc) => {
                        return Math.abs(acc - windowScreen) < Math.abs(prev - windowScreen) ? acc : prev;
                    });

                    var breakpointValues = Object.entries(breakpoints);
                    var currentBreakpoint = breakpointValues.filter(([key]) => key == currentBreakpointKey);

                    var sliderItemsCount = document.querySelectorAll('.theme-single-listing-slider .theme-swiper-pagination .swiper-pagination-bullet');

                    if (sliderItemsCount.length == '1') {
                        swiper1.loopDestroy();
                        var themeSlider = document.getElementById('theme-single-listing-slider');
                        themeSlider.classList.add('one-last-item');
                    }

                    if (sliderItemsCount.length == '2') {
                        swiper1.loopDestroy();
                        var themeSlider = document.getElementById('theme-single-listing-slider');
                        themeSlider.classList.add('two-column-item');
                    }

                    if (sliderItemsCount.length == '3') {
                        swiper1.loopDestroy();
                        var themeSlider = document.getElementById('theme-single-listing-slider');
                        themeSlider.classList.add('three-column-item');
                    }
                    if (sliderItemsCount.length <= 3) {
                        currentBreakpoint[0].forEach((elm, ind) => {
                            if (swiper1.loopedSlides < elm.slidesPerView) {
                                swiper1.loopDestroy();
                                document.getElementById('theme-single-listing-slider').classList.add('lessItems-enable');
                            }
                        });
                    }

                }

                window.addEventListener('resize', function () {
                    destroySwiperSlider();
                });

                destroySwiperSlider();
            });

            swiperCarouselNested.forEach(function (el, i) {

                let navBtnPrevNested = document.querySelectorAll('.theme-swiper-button-prev-nested');
                let navBtnNextNested = document.querySelectorAll('.theme-swiper-button-next-nested');

                navBtnPrevNested.forEach((el, i) => {
                    el.classList.add(`theme-swiper-button-prev-nested-${i}`);
                });
                navBtnNextNested.forEach((el, i) => {
                    el.classList.add(`theme-swiper-button-next-nested-${i}`);
                });
                el.classList.add(`theme-swiper-nested-${i}`);
                let swiper = new Swiper(`.theme-swiper-nested-${i}`, {
                    slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                    spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                    loop: checkData(el.dataset.swLoop, true),
                    slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
                    speed: checkData(parseInt(el.dataset.swSpeed), 3000),
                    autoplay: checkData(el.dataset.swAutoplay, {}),
                    observer: true,
                    observeParents: true,
                    navigation: {
                        nextEl: `.theme-swiper-button-next-nested`,
                        prevEl: `.theme-swiper-button-prev-nested`,
                    },
                    pagination: {
                        el: `.theme-swiper-pagination-nested`,
                        type: 'bullets',
                        clickable: true,
                    },
                    breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
                });
            });
        },

        /* Mobile Menu */
        mobileMenu(dropDownTrigger, dropDown) {
            $('body').on('click', '.theme-responsive-menu a', function (e) {
                if ($(this).parent().hasClass('menu-item-has-children')) {
                    e.preventDefault();
                }
                if ($(this).closest('.theme-responsive-menu-trigger').length !== 0) {
                    $(this).parent().toggleClass('theme-submenu-open');

                    $(this).siblings(dropDown)
                        .slideToggle().parent().siblings('.sub-menu')
                        .children(dropDown).slideUp().siblings(dropDownTrigger).parent().removeClass('theme-submenu-open');
                }
            });

            $('body').on('click', '.theme-mobile-menu-trigger', function () {
                $('.theme-responsive-menu-trigger .theme-main-navigation-inner').addClass('theme-offcanvas-active');
                $('.theme-mobile-menu-overlay').addClass('theme-mobile-menu-triggered');
            });

            $('body').on('click', '.theme-mobile-menu-close, .theme-mobile-menu-overlay', function (e) {
                e.preventDefault();
                $('.theme-responsive-menu-trigger .theme-main-navigation-inner').removeClass('theme-offcanvas-active');
                $('.theme-mobile-menu-overlay').removeClass('theme-mobile-menu-triggered');
            });
        },

        /* Responsive Menu Trigger */
        responsiveMenuTrigger: function () {

            const menuArea = document.querySelector('.theme-header-menu-area');
            const navCollapse = onelisting_localize_data.resmenuWidth;
            const windowWidth = window.innerWidth;

            if (navCollapse >= windowWidth) {
                document.querySelector('html').classList.add('theme-responsive-menu-trigger');
                menuArea && menuArea.classList.add('theme-responsive-menu');

            } else {
                document.querySelector('html').classList.remove('theme-responsive-menu-trigger');
                menuArea && menuArea.classList.remove('theme-responsive-menu');
            }
        },

        /* Magnific Popup */
        magnificPopup: function () {

            const seeAllButton = document.querySelector('.btn-listing-see-all');

            if (seeAllButton) {
                $('body').on('click', '.btn-listing-see-all', function (e) {
                    e.preventDefault();
                    $('.theme-single-listing-slider__item:not(.swiper-slide-duplicate)').magnificPopup('open');
                });
            }

            if ($('.theme-single-listing-slider__item').length !== 0) {

                $('.theme-single-listing-slider__item:not(.swiper-slide-duplicate)').magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    mainClass: 'mfp-zoom-in',
                    autoFocusLast: false
                });
            }

        },

        /* Counter up */
        counterUpPlugin: function () {
            $(".theme-counter__count").each(function () {
                var options = $(this).data('options');
                $(this).counterUp(options);
            });
        },

        /* Click Copy */
        clickToCopy: function () {

            $('#copyBtn').on('click', function (e) {
                e.preventDefault();
                let copyText = document.getElementById('copyUrl').value;

                // Create Virtual Dom
                let textArea = document.createElement("textarea");
                textArea.value = copyText;
                $(this).append(textArea);
                textArea.select();

                try {
                    document.execCommand("copy");
                    $(this).addClass('copied');
                } catch (err) {
                    alert('Sorry!! Please refresh Your Page to copy again')
                }
                textArea.remove();
            });

        },

        /* Average Rating Count */
        avgRatings: function () {

            const ratingWrapper = document.querySelectorAll('.ratings');
            ratingWrapper.forEach((elm, ind) => {
                const dataRating = elm.getAttribute('data-rating');
                const dataRatingFloat = parseFloat(dataRating);
                switch (dataRatingFloat) {
                    case 1:
                        elm.classList.add('one');
                        break;
                    case 2:
                        elm.classList.add('two');
                        break;
                    case 3:
                        elm.classList.add('three');
                        break;
                    case 4:
                        elm.classList.add('four');
                        break;
                    case 5:
                        elm.classList.add('five');
                        break;
                    default:
                        elm.classList.add('none');
                }

                function fractionClass(v1, v2, classOne, classTwo) {
                    if (dataRatingFloat > v1 && dataRatingFloat < v2) {
                        elm.classList.add(classOne, classTwo);
                    }
                }

                fractionClass(1, 2, 'one', 'one-n-half');
                fractionClass(2, 3, 'two', 'two-n-half');
                fractionClass(3, 4, 'three', 'three-n-half');
                fractionClass(4, 5, 'four', 'four-n-half');
            });
        },

        /* Author Drodown Activation */
        authorDropdownActive: function () {

            const authorTrigger = document.querySelector('.theme-header-action__author--info img');
            const shade = document.querySelector('.theme-white-shade');
            const authorDropdown = document.querySelector('.theme-header-author-navigation');

            if (authorTrigger && authorDropdown) {
                function authorDropdownActive() {

                    authorDropdown.classList.toggle('theme-show');

                    shade.classList.toggle('theme-show');

                }

                function removeDropdown() {

                    authorDropdown.classList.remove('theme-show');

                    shade.classList.remove('theme-show');

                }

                if (authorTrigger) {
                    authorTrigger.addEventListener('click', authorDropdownActive);
                }

                if (shade) {
                    shade.addEventListener('click', removeDropdown);

                    shade.classList.remove('.theme-white-shade');
                }
            }

        },

        /* Listing With Map */
        listingsWithMap: function (param) {
            $('body').on('submit', '#directorist-search-area-form', function (e) {
                var form = $(this);
                $.post(
                    onelisting_localize_data.ajaxurl, {
                        action: 'onelisting_map_header_title',
                        post_id: $('.directorist-listing-map-title').data('post-id'),
                        form: form.serialize(),
                    },
                    function (data) {
                        $('.directorist-listing-map-title').html(data);
                    }
                );

            });
        },

        /* Search Home Category Icon */
        categoriesWithIcon: function () {

            // getOptionIcon
            function getOptionIcon(optionID) {
                var icons = onelisting_localize_data.category_icons || {};
                return icons[optionID] || '';
            }

            function getOptionColor(optionID) {
                var colors = onelisting_localize_data.category_colors || {};
                return colors[optionID] || '000';
            }

            // convertToSelect2
            function convertToSelect2(field) {
                if (!field) {
                    return;
                }
                if (!field.elm) {
                    return;
                }
                if (!field.elm.length) {
                    return;
                }

                const default_args = {
                    allowClear: true,
                    dropdownCssClass: "theme-home-search-category",
                    width: '100%',
                    templateResult: function (data) {
                        // We only really care if there is an element to pull classes from
                        if (!data.element) {
                            return data.text;
                        }
                        var $element = $(data.element);
                        var color = getOptionColor(data.id);
                        var icon = getOptionIcon(data.id);
                        if (icon) {
                            var content = `<i class="directorist-icon-mask theme-category-icon" aria-hidden="true" style="--directorist-icon: url(${icon}); background-color: #${color};"></i> ${data.text}`;

                        } else {
                            let iconURL = directorist.assets_url + 'icons/line-awesome/svgs/tag-solid.svg';
                            var content = `<i class="directorist-icon-mask theme-category-icon" aria-hidden="true" style="--directorist-icon: url(${iconURL}); background-color: #${color};"></i> ${data.text}`;
                        }
                        var $wrapper = $('<span>' + content + '</span>');

                        $wrapper.addClass($element[0].className);

                        return $wrapper;
                    }
                };

                var args = (field.args && typeof field.args === 'object') ? Object.assign(default_args, field.args) : default_args;

                var options = field.elm.find('option');
                var placeholder = (options.length) ? options[0].innerHTML : '';

                if (placeholder.length) {
                    args.placeholder = placeholder;
                }

                field.elm.select2(args);
            }

            // Init Select 2
            if ($('.cat_with_icon').length > 0) {
                convertToSelect2({
                    elm: $('.cat_with_icon')
                });
            }

            if ($('#directorist-search-area-form select').length > 0) {
                convertToSelect2({
                    elm: $('#directorist-search-area-form select')
                });
            }

        },

        /* Search Dropdown */
        searchDropDown: function () {

            $("body").on("click", ".directorist-filter-btn", function () {
                $(this).closest(".directorist-filter-btn").toggleClass("active");
            });

            $("body").on("click", ".theme-search-dropdown .theme-search-dropdown__label", function () {
                $(this).closest(".theme-search-dropdown").toggleClass("active");
            });

            $('body').on('click', function (e) {
                if (!$(e.target).closest('.theme-search-dropdown').length) {
                    $(".theme-search-dropdown").removeClass("active");
                }
            });
        },


        directory_type: function () {
            var deviceType = $("body").attr("data-elementor-device-mode");
            if (deviceType === 'mobile') {
                const typeNavigation = document.querySelectorAll('.directorist-listing-type-selection');
                typeNavigation.forEach((navElement) => {
                    navElement.scrollLeft = 0;
                    let navItems = navElement.querySelectorAll('.search_listing_types');
                    for (let i = 0; i < navItems.length; i++) {
                        if (navItems[i].classList.contains('directorist-listing-type-selection__link--current')) {
                            navElement.scrollLeft = navItems[i].offsetLeft;
                        }
                    }
                });
            }
        },

        taxonomyTypeNav: function () {
            if (window.onelistingTaxonomyTypeNavBound) {
                return;
            }

            window.onelistingTaxonomyTypeNavBound = true;

            document.addEventListener('click', function (event) {
                const target = event.target instanceof Element ? event.target : event.target.parentElement;

                if (!target) {
                    return;
                }

                const taxonomyTypeNav = target.closest('.directorist-type-nav__link');

                if (!taxonomyTypeNav || !taxonomyTypeNav.closest('.directorist-categories') || !taxonomyTypeNav.href) {
                    return;
                }

                // Keep taxonomy directory-type tabs working as real links.
                event.preventDefault();
                event.stopPropagation();

                if (typeof event.stopImmediatePropagation === 'function') {
                    event.stopImmediatePropagation();
                }

                window.location.assign(taxonomyTypeNav.href);
            }, true);
        },

        /* Search Dropdown */
        updateArchiveListings: function () {

            $('.directorist-thumb-listing-author').closest('.directorist-listing-single').addClass('directorist-listing-has-thumb-active');

            $("body").on('click', '.directorist-type-nav__list li', function () {
                $(".directorist-type-nav__list li.current").removeClass("current");
                $(this).addClass("current");
            });

            $('.elementor-page .elementor-widget-wpwaxtheme-all-listings .directorist-archive-contents .directorist-type-nav a.directorist-type-nav__link').click(function (e) {
                e.preventDefault();

                let href = $(e.target).attr('href');
                if (undefined === href) {
                    return;
                }

                let start = href.indexOf("=");
                let type = href.slice(start + 1);
                let parentDiv = e.target.closest('.directorist-archive-contents');
                let atts = $(parentDiv).data('atts');

                $.post(
                    onelisting_localize_data.ajaxurl, {
                        action: 'onelisting_archive_listings',
                        type: type,
                        atts: atts,
                    },
                    function (data) {

                        if (data) {

                            let gridFound = $(parentDiv).find('.directorist-archive-grid-view');
                            if (gridFound) {
                                $(gridFound).remove()
                            }

                            let listFound2 = $(parentDiv).find('.directorist-archive-list-view');
                            if (listFound2) {
                                $(listFound2).remove()
                            }

                            $(parentDiv).append(data);
                        }
                    }
                );
            });

        },

        /* Contact Button  */
        contactButton: function () {

            if ($('#atbdp-contact-form-widget') !== null) {
                $('body').on('submit', '#atbdp-contact-form-widget', function (e) {
                    e.preventDefault();

                    $('#directorist-contact-message-display').append('<div class="atbdp-spinner"></div>');

                    // Post via AJAX
                    const data = {
                        action: 'atbdp_public_send_contact_email',
                        'atbdp-post-id': $('#onelisting-post-id').val(),
                        'atbdp-contact-name': $('#atbdp-contact-name').val(),
                        'atbdp-contact-email': $('#atbdp-contact-email').val(),
                        'atbdp-listing-email': $('#atbdp-listing-email').val(),
                        'atbdp-contact-message': $('#atbdp-contact-message').val(),
                        directorist_nonce: directorist.directorist_nonce,
                    };

                    $.post(
                        onelisting_localize_data.ajaxurl,
                        data,
                        function (response) {
                            if (response.error == 1) {
                                $('#directorist-contact-message-display')
                                    .addClass('text-danger')
                                    .html(response.message);
                            } else {
                                $('#atbdp-contact-message').val('');
                                $('#directorist-contact-message-display')
                                    .addClass('text-success')
                                    .html(response.message);
                            }
                        },
                        'json'
                    );
                });
            }

        },
        loginAlert: function () {
            function showMessage() {
                const themeLoginModal = document.getElementById('theme-login-modal');
                if (themeLoginModal) {
                    const directoristAlertElement = themeLoginModal.querySelector('.directorist-alert');
                    if (directoristAlertElement) {
                        $("#theme-login-modal").modal('show');
                    }
                }
            }
            setTimeout(showMessage, 500);
        }
    }

    theme.responsiveMenuTrigger();
    /* Window Resize */
    $(window).resize(function () {
        theme.responsiveMenuTrigger();
    });

    /* Content Ready Scripts */
    function content_ready_scripts() {
        theme.searchPopup();
        theme.favouriteButton();
        theme.mobileMenu('.menu-item a', '.sub-menu');
        theme.clickToCopy();
        theme.counterUpPlugin();
        theme.avgRatings();
        theme.authorDropdownActive();
        theme.listingsWithMap();
        theme.categoriesWithIcon();
        theme.taxonomyTypeNav();
        theme.updateArchiveListings();
        theme.contactButton();
        theme.responsiveMenuTrigger();
        theme.loginAlert();
    }

    /* Content Load Scripts */
    function content_load_scripts() {
        theme.preloader();
        theme.swiperSlider();
        theme.magnificPopup();
        theme.categoriesWithIcon();
        theme.directory_type();
        theme.responsiveMenuTrigger();
    }

    window.addEventListener('directorist-reload-listings-map-archive', function () {
        // theme.searchDropDown();
        theme.categoriesWithIcon();
    });

    window.addEventListener('directorist-search-form-nav-tab-reloaded', function () {
        // theme.searchDropDown();
        theme.categoriesWithIcon();
    });

    window.addEventListener('directorist-instant-search-reloaded', function () {
        // theme.searchDropDown();
    });

    /* Load Scripts after Change Directory Type*/
    function initSetup() {
        theme.categoriesWithIcon();
        theme.searchDropDown();
        theme.directory_type();
    }
    $(window).on('load', initSetup);
    // $(window).on('directorist-search-form-nav-tab-reloaded', initSetup);

    $(document).ready(function () {
        content_ready_scripts();
    });

    $(window).on('load', function () {
        content_load_scripts();
    });



    /* Elementor Edit Mode */
    $(window).on('elementor/frontend/init', function () {
        setTimeout(() => {
            if (elementorFrontend.isEditMode()) {
                //elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
                content_ready_scripts()
                theme.swiperSlider();
                theme.categoriesWithIcon();
                //});
            }
        }, 6000);
    });

    // Bootstrap Tooltip Initialize
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
})(jQuery);
