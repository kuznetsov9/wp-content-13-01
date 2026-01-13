/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 2:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   esGetCookie: () => (/* binding */ esGetCookie),
/* harmony export */   esSetCookie: () => (/* binding */ esSetCookie),
/* harmony export */   esn: () => (/* binding */ esn),
/* harmony export */   isInViewport: () => (/* binding */ isInViewport),
/* harmony export */   slideDown: () => (/* binding */ slideDown),
/* harmony export */   slideToggle: () => (/* binding */ slideToggle),
/* harmony export */   slideUp: () => (/* binding */ slideUp)
/* harmony export */ });
// Create esn object.
var esn = {
  addAction: function addAction(x, y, z) {
    return;
  }
};
if ('undefined' !== typeof wp && 'undefined' !== typeof wp.hooks) {
  esn.addAction = wp.hooks.addAction;
}



/**
 * isInViewport
 */

function isInViewport(element) {
  var rect = element.getBoundingClientRect();
  var windowHeight = window.innerHeight || document.documentElement.clientHeight;
  var windowWidth = window.innerWidth || document.documentElement.clientWidth;
  return rect.top <= windowHeight && rect.top + rect.height >= 0 && rect.left <= windowWidth && rect.left + rect.width >= 0;
}

/**
 * slideDown \ slideUp \ slideToggle
 */

function slideDown(element) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 350;
  element.style.display = 'block';
  element.style.overflow = 'hidden';
  var height = element.scrollHeight;
  element.style.height = 0;
  setTimeout(function () {
    element.style.transition = "height ".concat(duration, "ms");
    element.style.height = height + 'px';
    element.addEventListener('transitionend', function te() {
      element.removeEventListener('transitionend', te);
      element.style.removeProperty('height');
      element.style.removeProperty('transition');
      element.style.removeProperty('overflow');
    });
  }, 0);
}
function slideUp(element) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 350;
  element.style.height = element.offsetHeight + 'px';
  element.style.overflow = 'hidden';
  setTimeout(function () {
    element.style.transition = "height ".concat(duration, "ms");
    element.style.height = '0';
    element.addEventListener('transitionend', function te() {
      element.removeEventListener('transitionend', te);
      if (element.style.height === '0px') {
        element.style.display = 'none';
      }
      element.style.removeProperty('height');
      element.style.removeProperty('transition');
      element.style.removeProperty('overflow');
    });
  }, 0);
}
function slideToggle(element) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 350;
  if (window.getComputedStyle(element).display === 'none') {
    return slideDown(element, duration);
  } else {
    return slideUp(element, duration);
  }
}

/**
 * Cookies
 */
function esGetCookie(name) {
  var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function esSetCookie(name, value) {
  var props = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  props = {
    path: '/'
  };
  if (props.expires instanceof Date) {
    props.expires = props.expires.toUTCString();
  }
  var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
  for (var optionKey in props) {
    updatedCookie += "; " + optionKey;
    var optionValue = props[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }
  document.cookie = updatedCookie;
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Comments Toggle */

(function () {
  var commentsElement = document.querySelector('.es-entry__comments');
  var headToggles = document.querySelectorAll('.es-entry__header .es-meta-comments');

  document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('es-entry__comments-view-toggle')) {
      e.preventDefault();
      var commentsWrap = e.target.closest('.es-entry__wrap');
      var _commentsElement = commentsWrap.querySelector('.es-entry__comments');
      _commentsElement.classList.toggle('es-entry__comments-visible');
    }
  });
  headToggles.forEach(function (headToggle) {
    headToggle.addEventListener('click', function (e) {
      e.preventDefault();
      commentsElement.classList.add('es-entry__comments-visible');
      commentsElement.scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
  var scrollToHash = function scrollToHash() {
    if (window.location.hash && window.location.hash !== "#") {
      var target = document.querySelector(window.location.hash);
      if (target && target === commentsElement) {
        commentsElement.classList.add('es-entry__comments-visible');
      }
    }
  };
  window.addEventListener('load', scrollToHash);
  document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (event) {
      var replyLink = event.target.closest('.comment-reply-link');
      if (replyLink) {
        event.preventDefault();
        var commentsWrap = replyLink.closest('.es-entry__wrap');
        var _commentsElement2 = commentsWrap.querySelector('.es-entry__comments');

        if (_commentsElement2 && _commentsElement2.classList.contains('es-entry__comments-form-visible')) {
          _commentsElement2.classList.remove('es-entry__comments-form-visible');
        }
        var cancelLink = commentsWrap.querySelector('#cancel-comment-reply-link');
        var targetSpan = commentsWrap.querySelector('.form-submit .es-cancel-reply-button');
        var commentBody = commentsWrap.querySelector('.comment-body');

        if (cancelLink && targetSpan) {
          targetSpan.appendChild(cancelLink);
          cancelLink.style.display = 'inline';
        }

        if (commentBody) {
          setTimeout(function () {
            var commentRespond = commentBody.querySelector('.comment-respond');
            if (commentRespond) {
              commentRespond.style.display = 'block';
            }
          }, 50);
        }
      }
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Footer Nav Menu */


(function () {
  HTMLElement.prototype.responsiveNav = function () {
    this.classList.remove('menu-item-expanded');
    var previousElement = this.previousElementSibling;
    if (previousElement && previousElement.classList.contains('submenu-visible')) {
      previousElement.classList.remove('submenu-visible');
      (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideUp)(previousElement);
      this.parentElement.classList.remove('menu-item-expanded');
    } else {
      var parentOfParent = this.parentElement.parentElement;
      parentOfParent.querySelectorAll('.menu-item .sub-menu').forEach(function (subMenu) {
        subMenu.classList.remove('submenu-visible');
        (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideUp)(subMenu);
      });
      parentOfParent.querySelectorAll('.menu-item-expanded').forEach(function (item) {
        item.classList.remove('menu-item-expanded');
      });
      if (previousElement) {
        previousElement.classList.toggle('submenu-visible');
        (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideToggle)(previousElement);
      }
      this.parentElement.classList.toggle('menu-item-expanded');
    }
  };
  document.addEventListener('DOMContentLoaded', function () {
    var menuItems = document.querySelectorAll('.es-footer__nav .menu-item-has-children');
    menuItems.forEach(function (menuItem) {
      var span = document.createElement('span');
      menuItem.appendChild(span);
      span.addEventListener('click', function (e) {
        e.preventDefault();
        this.responsiveNav();
      });
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Header Scroll Scheme */

document.addEventListener('DOMContentLoaded', function () {
  var transitionTimer;
  document.addEventListener('nav-stick', function (event) {
    if (document.body.classList.contains('es-display-header-overlay')) {
      var headerSmart = document.querySelector('.es-navbar-smart-enabled .es-header, .es-navbar-sticky-enabled .es-header');
      var headerAlt = headerSmart.querySelector('.es-container');
      headerAlt.classList.add("es-header-elements-no-transition");
      clearTimeout(transitionTimer);
      transitionTimer = setTimeout(function () {
        headerAlt.classList.remove("es-header-elements-no-transition");
      }, 300);
    }
  });
  document.addEventListener('nav-unstick', function (event) {
    if (document.body.classList.contains('es-display-header-overlay')) {
      var headerSmart = document.querySelector('.es-navbar-smart-enabled .es-header, .es-navbar-sticky-enabled .es-header');
      var headerAlt = headerSmart.querySelector('.es-container');
      headerAlt.classList.add("es-header-elements-no-transition");
      clearTimeout(transitionTimer);
      transitionTimer = setTimeout(function () {
        headerAlt.classList.remove("es-header-elements-no-transition");
      }, 300);
    }
  });
});
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Header Smart Streatch */

document.addEventListener('DOMContentLoaded', function () {
  document.addEventListener('header-smart-stretch-scroll-sticky-scroll-init', function (event) {
    var headerParams = event.detail;
    window.addEventListener('scroll', function () {
      var scrolled = window.scrollY;
      var headerSmart = document.querySelector('.es-navbar-smart-enabled .es-header, .es-navbar-sticky-enabled .es-header');
      headerParams.headerSmartPosition = headerSmart ? headerSmart.offsetTop : 0;
      if (scrolled > headerParams.smartStart + headerParams.scrollPoint + 10 && scrolled > headerParams.scrollPrev) {
        if (scrolled > headerParams.smartStart + headerParams.headerLargeHeight + 200) {
          document.dispatchEvent(new CustomEvent('sticky-nav-hide', {
            detail: headerParams
          }));
        }
      } else {
        if (headerParams.scrollUpAmount >= headerParams.scrollPoint || scrolled === 0) {
          document.dispatchEvent(new CustomEvent('sticky-nav-visible', {
            detail: headerParams
          }));
        }
      }
      if (scrolled > headerParams.smartStart + headerParams.headerLargeHeight) {
        document.dispatchEvent(new CustomEvent('nav-stick', {
          detail: headerParams
        }));
      } else if (headerParams.headerSmartPosition <= headerParams.smartStart) {
        document.dispatchEvent(new CustomEvent('nav-unstick', {
          detail: headerParams
        }));
      }
      if (scrolled < headerParams.scrollPrev) {
        headerParams.scrollUpAmount += headerParams.scrollPrev - scrolled;
      } else {
        headerParams.scrollUpAmount = 0;
      }
      var wpAdminBar = document.querySelector('#wpadminbar');
      if (wpAdminBar && window.innerWidth <= 600 && scrolled >= headerParams.wpAdminBarHeight) {
        document.dispatchEvent(new CustomEvent('adminbar-mobile-scrolled', {
          detail: headerParams
        }));
      } else {
        document.dispatchEvent(new CustomEvent('adminbar-mobile-no-scrolled', {
          detail: headerParams
        }));
      }
      headerParams.scrollPrev = scrolled;
    });
  });
});
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Hero */


(function () {
  var section = 'es-hero-type-2';
  var sliders = document.querySelectorAll(".".concat(section, "__slider"));
  if (sliders.length === 0) {
    return;
  }
  var executed = false;
  var isMouseOnControl = false; // Track if the mouse is on the controls (next/prev/pagination)
  var isMouseOnSlider = false; // Track if the mouse is on the slider
  var isSliderVisible = true; // Track if the slider is fully visible on the screen

  var initHero = function initHero() {
    if (!executed && (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.isInViewport)(sliders[0])) {
      executed = true;
      var head = document.getElementsByTagName('head')[0];
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
      script.onload = function () {
        function setParallaxAttributes(element, duration) {
          element.setAttribute('data-swiper-parallax-x', '-400');
          element.setAttribute('data-swiper-parallax-duration', duration);
        }
        sliders.forEach(function (slider) {
          // Section data
          var autoplayValue = slider.getAttribute('data-es-autoplay');
          var autoplayDelayValue = slider.getAttribute('data-es-autoplay-delay');
          var parallaxValue = slider.getAttribute('data-es-parallax');
          var gapValue = slider.getAttribute('data-es-gap');
          var postContentContainer = '.es-entry__content';
          // Data manipulation
          var autoplayDelay = !isNaN(parseInt(autoplayDelayValue)) ? parseInt(autoplayDelayValue) : 5000;
          var autoplay = autoplayValue ? {
            delay: autoplayDelay,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
          } : false;
          var parallax = !!parallaxValue ? true : false;
          var gap = !isNaN(parseInt(gapValue)) ? parseInt(gapValue) : 0;
          var postContents = slider.querySelectorAll(postContentContainer);
          if (parallax) {
            if (postContents.length > 0) {
              postContents.forEach(function (postContent) {
                setParallaxAttributes(postContent, '800');
              });
            }
          }
          // Swiper settings
          var wrapperClass = "".concat(section, "__wrapper");
          var slideClass = "".concat(section, "__item");
          var prevEl = slider.querySelector(".".concat(section, "__button-prev"));
          var nextEl = slider.querySelector(".".concat(section, "__button-next"));
          var paginationContainer = slider.querySelector(".".concat(section, "__pagination"));
          var bulletClass = "".concat(section, "__pagination-bullet");
          var bulletClassActive = "".concat(section, "__pagination-bullet-active");
          var swiper = new Swiper(slider, {
            scrollbar: false,
            wrapperClass: wrapperClass,
            slideClass: slideClass,
            direction: 'horizontal',
            loop: true,
            speed: 800,
            parallax: parallax,
            autoplay: autoplay,
            slidesPerView: 1,
            spaceBetween: gap,
            a11y: {
              slideRole: 'article',
              slideLabelMessage: 'Slide {{index}} of {{slidesLength}}'
            },
            pagination: {
              el: paginationContainer,
              clickable: true,
              bulletElement: 'span',
              bulletClass: bulletClass,
              bulletActiveClass: bulletClassActive
            },
            navigation: {
              nextEl: nextEl,
              prevEl: prevEl
            },
            on: {
              init: function init() {
                var _this = this;
                setTimeout(function () {
                  var initialSlide = _this.slides[_this.activeIndex];
                  if (initialSlide) {
                    var initialContent = initialSlide.querySelector(postContentContainer);
                    if (initialContent) {
                      initialContent.style.transform = 'none';
                    }
                  }
                }, 100);
              },
              slideChange: function slideChange() {
                var currentSlide = this.slides[this.activeIndex];
                postContents.forEach(function (postContent) {
                  if (postContent === currentSlide.querySelector(postContentContainer)) {
                    postContent.style.transform = 'none';
                  }
                });
              }
            }
          });
          if (autoplayValue) {
            var controls = [nextEl, prevEl, paginationContainer];
            controls.forEach(function (control) {
              if (control) {
                control.addEventListener('mouseenter', function () {
                  swiper.autoplay.stop();
                  swiper.el.classList.add('swiper-paused');
                  isMouseOnControl = true; // Track if mouse is on a control

                  // Pause button animation
                  if (nextEl) {
                    nextEl.style.animationPlayState = "paused";
                  }
                  if (prevEl) {
                    prevEl.style.animationPlayState = "paused";
                  }
                });
                control.addEventListener('mouseleave', function () {
                  isMouseOnControl = false; // Mouse is no longer on a control

                  // Resume autoplay and remove 'swiper-paused' class if mouse is not on the slider
                  if (!isMouseOnSlider && isSliderVisible) {
                    swiper.autoplay.start();
                    swiper.el.classList.remove('swiper-paused');
                  }

                  // Resume button animation
                  if (nextEl) {
                    nextEl.style.animationPlayState = "running";
                  }
                  if (prevEl) {
                    prevEl.style.animationPlayState = "running";
                  }
                });
                control.addEventListener('click', function () {
                  swiper.autoplay.stop();
                  swiper.el.classList.add('swiper-paused');

                  // Pause button animation on click
                  if (nextEl) {
                    nextEl.style.animationPlayState = "paused";
                  }
                  if (prevEl) {
                    prevEl.style.animationPlayState = "paused";
                  }
                });
              }
            });
            slider.addEventListener('mouseenter', function () {
              isMouseOnSlider = true;
              swiper.autoplay.stop();
              swiper.el.classList.add('swiper-paused');
            });
            slider.addEventListener('mouseleave', function () {
              isMouseOnSlider = false;
              if (!isMouseOnControl && isSliderVisible) {
                swiper.autoplay.start();
                swiper.el.classList.remove('swiper-paused');
              }
            });
            var observer = new IntersectionObserver(function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                  isSliderVisible = true;
                  if (!isMouseOnControl && !isMouseOnSlider) {
                    swiper.autoplay.start();
                    swiper.el.classList.remove('swiper-paused');
                  }
                } else {
                  isSliderVisible = false;
                  swiper.autoplay.stop();
                  swiper.el.classList.add('swiper-paused');
                }
              });
            }, {
              threshold: 0.50
            });
            observer.observe(slider);
          }
        });
      };
      head.appendChild(script);
    }
  };
  document.addEventListener('DOMContentLoaded', function () {
    initHero();
  });
  window.addEventListener('resize', function () {
    initHero();
  });
  window.addEventListener('scroll', function () {
    initHero();
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Latest Posts Widget */


(function () {
  var section = 'es-latest-posts-slider';
  var sliders = document.querySelectorAll(".".concat(section, " .is-style-es-tile-layout"));
  if (sliders.length === 0) {
    return;
  }
  var executed = false;
  var initLatestPosts = function initLatestPosts() {
    if (!executed && (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.isInViewport)(sliders[0])) {
      executed = true;
      var head = document.getElementsByTagName('head')[0];
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
      script.onload = function () {
        sliders.forEach(function (slider) {
          // Swiper settings
          var wrapperClass = "".concat(section, "__wrapper");
          var slideClass = "".concat(section, "__item");
          var prevEl = slider.querySelector(".".concat(section, "__button-prev"));
          var nextEl = slider.querySelector(".".concat(section, "__button-next"));
          var paginationContainer = document.querySelector(".es-latest-posts-slider__pagination");
          var bulletClass = "".concat(section, "__pagination-bullet");
          var bulletClassActive = "".concat(section, "__pagination-bullet-active");
          new Swiper(slider, {
            scrollbar: false,
            wrapperClass: wrapperClass,
            slideClass: slideClass,
            direction: 'horizontal',
            loop: true,
            speed: 800,
            parallax: false,
            autoplay: false,
            slidesPerView: 1,
            a11y: {
              slideRole: 'article',
              slideLabelMessage: 'Slide {{index}} of {{slidesLength}}'
            },
            pagination: {
              el: paginationContainer,
              clickable: true,
              bulletElement: 'span',
              bulletClass: bulletClass,
              bulletActiveClass: bulletClassActive
            },
            navigation: {
              nextEl: nextEl,
              prevEl: prevEl
            }
          });
        });
      };
      head.appendChild(script);
    }
  };
  document.addEventListener('DOMContentLoaded', function () {
    initLatestPosts();
  });
  window.addEventListener('resize', function () {
    initLatestPosts();
  });
  window.addEventListener('scroll', function () {
    initLatestPosts();
  });
})();
})();
















































// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/**
 * AJAX Load More.
 *
 * Contains functions for AJAX Load More.
 */

if (typeof window.load_more_query === 'undefined') {
  window.load_more_query = [];
}





function esn_ajax_get_posts(object) {
  var container = object.closest('.es-posts-area');
  
  // Безопасный парсинг настроек из кнопки
  var settings = {};
  try {
      settings = JSON.parse(object.dataset.settings || '{}');
  } catch(e) {
      settings = esn_ajax_pagination || {};
  }
  
  // Если в кнопке пусто, берем глобальный конфиг
  if (!settings.root) settings = esn_ajax_pagination || {};
  
  var page = parseInt(object.dataset.page) || 2;
  
  if (object.dataset.loading === "true" || !settings.root) return;
  
  object.dataset.loading = "true";
  object.textContent = settings.translation ? settings.translation.loading : 'Загрузка...';

  fetch(`${settings.root}esn/v1/more-posts`, {
    method: 'POST',
    headers: {
      'X-WP-Nonce': settings.nonce,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      page: page,
      is_popular: settings.is_popular || false,
      timeframe: settings.timeframe || '',
      query_data: settings.query_data || {},
      options: settings.options || {}
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success && res.data.content) {
      var mainContainer = container.querySelector('.es-posts-area__main');
      var tempDiv = document.createElement('div');
      tempDiv.innerHTML = res.data.content;
      
      while (tempDiv.firstChild) {
        mainContainer.appendChild(tempDiv.firstChild);
      }

      object.dataset.page = (page + 1).toString();
      object.textContent = settings.translation ? settings.translation.load_more : 'Показать еще';
      object.dataset.loading = "false";

      // Если посты кончились — удаляем кнопку
      if (res.data.posts_end) {
        object.parentElement.remove(); // Удаляем обертку пагинации
      }
      document.body.dispatchEvent(new Event('post-load'));
    } else {
      object.parentElement.remove();
    }
  })
  .catch(err => {
    console.error('REST Error:', err);
    object.dataset.loading = "false";
  });
}








function esn_load_more_init(infinite) {
  var areas = document.querySelectorAll('.es-posts-area');
  areas.forEach(function (area) {
    if (area.dataset.init) {
      return;
    }
    var esn_ajax_settings;
    var archive_data = area.dataset.postsArea;
    if (archive_data) {
      esn_ajax_settings = JSON.parse(window.atob(archive_data));
    } else if (typeof esn_ajax_pagination !== 'undefined' && typeof archive_data === 'undefined') {
      esn_ajax_settings = esn_ajax_pagination;
    }
    if (esn_ajax_settings) {
      if (!infinite && esn_ajax_settings.infinite_load) {
        return;
      }
      var pagination = document.createElement('div');
      pagination.className = 'es-posts-area__pagination';
      var btn = document.createElement('button');
      btn.className = 'es-load-more';
      btn.textContent = esn_ajax_settings.translation.load_more;
      pagination.appendChild(btn);
      area.appendChild(pagination);
      btn.dataset.settings = JSON.stringify(esn_ajax_settings);
      btn.dataset.page = '2';
      btn.dataset.loading = "false";
      btn.dataset.scrollHandling = JSON.stringify({
        allow: JSON.parse(esn_ajax_settings.infinite_load),
        delay: 400
      });
    }
    area.dataset.init = true;
  });
}


esn_load_more_init(true);

// Elementor Init
window.addEventListener('elementor/frontend/init', function () {
  esn_load_more_init(true);
});






// On Scroll Event.
window.addEventListener('scroll', function () {
  var buttons = document.querySelectorAll('.es-posts-area .es-load-more');
  buttons.forEach(function (btn) {
    var loading = btn.dataset.loading === "true";
    var scrollHandling = JSON.parse(btn.dataset.scrollHandling || '{}');
    if (loading || !scrollHandling.allow) {
      return;
    }
    scrollHandling.allow = false;
    btn.dataset.scrollHandling = JSON.stringify(scrollHandling);
    setTimeout(function () {
      scrollHandling.allow = true;
      btn.dataset.scrollHandling = JSON.stringify(scrollHandling);
    }, scrollHandling.delay);
    var offset = btn.getBoundingClientRect().top - window.innerHeight;
    if (offset < 4000) {
      esn_ajax_get_posts(btn);
    }
  });
 
  
});









// On Click Event.
document.body.addEventListener('click', function (event) {
  if (event.target.classList.contains('es-load-more')) {
    var loading = event.target.dataset.loading === "true";
    if (!loading) {
      esn_ajax_get_posts(event.target);
    }
  }
});
})();



// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
// Check if Load Nextpost is defined by the wp_localize_script
if (typeof esn_ajax_nextpost !== 'undefined') {
  var objNextparent = document.querySelector('.es-site-primary > .es-site-content');
  var objNextsect = '.es-nextpost-section';
  var objNextpost = null;
  var currentNTitle = document.title;
  var currentNLink = window.location.href;
  var loadingNextpost = false;
  var scrollNextpost = {
    allow: true,
    reallow: function reallow() {
      scrollNextpost.allow = true;
    },
    delay: 400 //(milliseconds) adjust to the highest acceptable value
  };

  // Init.
  if (esn_ajax_nextpost.next_post) {
    objNextparent.insertAdjacentHTML('afterend', '<div class="es-nextpost-inner"></div>');
    objNextpost = document.querySelector('.es-nextpost-inner');
  }

  // Remove previous & next post elements from the Floating Header.
  var floatingHeader = document.querySelector('.es-floating-header__content');
  if (floatingHeader) {
    var nextElement = floatingHeader.querySelector('.es-floating-header__next');
    if (nextElement) {
      nextElement.remove();
    }
    var prevElement = floatingHeader.querySelector('.es-floating-header__prev');
    if (prevElement) {
      prevElement.remove();
    }
    var currentElement = floatingHeader.querySelector('.es-floating-header__current');
    if (currentElement) {
      currentElement.classList.add('fullwidth');
    }
  }
}

/**
 * Update Floating Header
 */
function updateFloatingHeader(title) {
  var floatingHeader = document.querySelector('.es-floating-header__content');
  if (floatingHeader && title) {
    var currentPostTitle = floatingHeader.querySelector('.es-floating-header__current span');
    if (currentPostTitle) {
      currentPostTitle.textContent = title || ''; // Fallback to empty string if title is missing
    }
  }
}

/**
 * Get next post
 */
function esn_ajax_get_nextpost() {
  loadingNextpost = true;

  // Set class loading.
  var data = {
    action: 'esn_ajax_load_nextpost',
    not_in: esn_ajax_nextpost.not_in,
    current_user: esn_ajax_nextpost.current_user,
    nonce: esn_ajax_nextpost.nonce,
    next_post: esn_ajax_nextpost.next_post
  };

  // Request Url.
  var esn_ajax_nextpost_url;
  if ('ajax_restapi' === esn_ajax_nextpost.type) {
    esn_ajax_nextpost_url = esn_ajax_nextpost.rest_url;
  } else {
    esn_ajax_nextpost_url = esn_ajax_nextpost.url;
  }
  function encodeFormData(data) {
    return Object.keys(data).map(function (key) {
      return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
    }).join('&');
  }

  // Send Request.
  fetch(esn_ajax_nextpost_url, {
    method: 'POST',
    body: encodeFormData(data),
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  }).then(function (response) {
    return response.json();
  }).then(function (res) {
    esn_ajax_nextpost.next_post = false;
    if (res.success) {
      // Get the posts.
      var tempDiv = document.createElement('div');
      tempDiv.innerHTML = res.data.content;
      var newPosts = Array.from(tempDiv.children);

      // Check if there're any posts.
      if (newPosts.length) {
        // Set the loading state.
        loadingNextpost = false;

        // Set not_in.
        esn_ajax_nextpost.not_in = res.data.not_in;

        // Set next data.
        esn_ajax_nextpost.next_post = res.data.next_post;

        // Remove loader.
        var loadingElement = objNextpost.nextElementSibling;
        if (loadingElement && loadingElement.classList.contains('es-nextpost-loading')) {
          loadingElement.remove();
        }

        // Append new post.
        newPosts.forEach(function (post) {
          return objNextpost.appendChild(post.cloneNode(true));
        });

        // Update Floating Header with the new title.
        if (objNextpost.children.length === newPosts.length) {
          updateFloatingHeader(res.data.title || '');
        }
        if (document.getElementById('fb-root') && (typeof FB === "undefined" ? "undefined" : _typeof(FB)) === 'object') {
          FB.XFBML.parse();
        }
        document.body.dispatchEvent(new Event('post-load'));
      }
    } else {
      // console.log(res);
    }
  })["catch"](function (error) {
    // console.log(error);
  });
}

/**
 * Check if Load Nextpost is defined by the wp_localize_script
 */
if (typeof esn_ajax_nextpost !== 'undefined') {
  // On Scroll Event.
  window.addEventListener('scroll', function () {
    var scrollTop = window.scrollY || window.pageYOffset;

    // Init nextpost.
    if (esn_ajax_nextpost.next_post) {
      if (objNextpost && !loadingNextpost && scrollNextpost.allow) {
        scrollNextpost.allow = false;
        setTimeout(scrollNextpost.reallow, scrollNextpost.delay);
        // Calc current offset.
        var offset = objNextpost.offsetTop + objNextpost.offsetHeight - scrollTop;
        // Load nextpost.
        if (4000 > offset) {
          objNextpost.insertAdjacentHTML('afterend', '<div class="es-nextpost-loading"></div>');
          esn_ajax_get_nextpost();
        }
      }
    }

    // Reset browser data link.
    var objFirst = document.querySelector(objNextsect + ':first-child');
    if (objFirst) {
      var firstTop = objFirst.offsetTop;
      // If there has been a change.
      if (scrollTop < firstTop && window.location.href !== currentNLink) {
        document.title = currentNTitle;
        history.pushState(null, currentNTitle, currentNLink);
        updateFloatingHeader(currentNTitle); // Update header when scrolling back to the top
      }
    }

    // Set browser data link.
    document.querySelectorAll(objNextsect).forEach(function (elem) {
      var elemTop = elem.offsetTop;
      var elemHeight = elem.offsetHeight;
      if (scrollTop > elemTop && scrollTop < elemTop + elemHeight) {
        // If there has been a change.
        if (window.location.href !== elem.dataset.url) {
          // New title.
          document.title = elem.dataset.title;
          // New link.
          history.pushState(null, elem.dataset.title, elem.dataset.url);

          // Update Floating Header with the new title.
          updateFloatingHeader(elem.dataset.title);

          // Google Analytics.
          if (typeof gtag === 'function' && _typeof(window.gaData) === 'object') {
            var trackingId = Object.keys(window.gaData)[0];
            if (trackingId) {
              gtag('config', trackingId, {
                'page_title': elem.dataset.title,
                'page_location': elem.dataset.url
              });
              gtag('event', 'page_view', {
                'send_to': trackingId
              });
            }
          }
        }
      }
    });
  });
}
})();










































// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Metabar */

(function () {
  var ticking = false;
  var update = function update() {
    document.querySelectorAll('.es-site-content .es-content-area').forEach(function (mainContent) {
      var content = mainContent.querySelector('.entry-content');
      if (!content) {
        return;
      }
      var sidebar = mainContent.querySelector('.post-sidebar-inner');
      if (!sidebar) {
        return;
      }
      var offsetTop = 20;
      var offsetBottom = -20;
      var elements = ['.alignfull', '.alignwide'];
      var layouts = Array.from(content.querySelectorAll(elements.join(',')));
      if (!sidebar || layouts.length === 0) {
        return;
      }
      var disabled = false;
      var sidebarTop = sidebar.getBoundingClientRect().top + window.scrollY;
      var sidebarHeight = sidebar.offsetHeight;
      layouts.forEach(function (layout) {
        if (getComputedStyle(layout).transform === 'none') {
          return;
        }
        var layoutTop = layout.getBoundingClientRect().top + window.scrollY;
        var layoutHeight = layout.offsetHeight;
        var pointTop = layoutTop - offsetTop;
        var pointBottom = layoutTop + layoutHeight + offsetBottom;
        if (sidebarTop + sidebarHeight >= pointTop && sidebarTop <= pointBottom) {
          disabled = true;
        }
      });
      sidebar.style.opacity = disabled ? '0' : '1';
    });
    ticking = false;
  };
  var requestTick = function requestTick() {
    if (!ticking) {
      window.requestAnimationFrame(update);
      ticking = true;
    }
  };
  var onProcess = function onProcess() {
    requestTick();
  };
  window.addEventListener('scroll', onProcess);
  window.addEventListener('resize', onProcess);
  window.addEventListener('load', onProcess);
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
/** ----------------------------------------------------------------------------
 * Navigation */
var CscoNavigation = /*#__PURE__*/function () {
  function CscoNavigation() {
    _classCallCheck(this, CscoNavigation);
    this.headerParams = {
      headerLargeHeight: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-initial-height')),
      headerCompactHeight: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-height')),
      headerSmart: document.querySelector('.es-navbar-smart-enabled .es-header, .es-navbar-sticky-enabled .es-header'),
      wpAdminBar: document.querySelector('#wpadminbar'),
      headerBefore: document.querySelector('.es-header-before'),
      headerStretch: document.querySelector('.es-navbar-smart-enabled .es-header-stretch'),
      wpAdminBarHeight: null,
      smartStart: null,
      scrollPoint: 200,
      scrollPrev: 200,
      scrollUpAmount: 0,
      headerSmartPosition: 0
    };
    this.initialize();
  }
  _createClass(CscoNavigation, [{
    key: "initialize",
    value: function initialize() {
      if (document.body.classList.contains('wp-admin')) {
        return;
      }
      this.bindEvents();
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;
      document.addEventListener('DOMContentLoaded', function () {
        _this.smartLevels();
        _this.adaptTablet();
        _this.stickyScroll();
        _this.headerClassesChange();
      });
      window.addEventListener('resize', function () {
        _this.smartLevels();
        _this.adaptTablet();
        _this.stickyScroll();
      });
    }
  }, {
    key: "smartLevels",
    value: function smartLevels() {
      var windowWidth = window.innerWidth;

      // Reset Calc.
      document.querySelectorAll('.es-header__nav-inner li').forEach(function (el) {
        el.classList.remove('es-sm__level', 'es-sm-position-left', 'es-sm-position-right');
      });
      document.querySelectorAll('.es-header__nav-inner li .sub-menu').forEach(function (el) {
        el.classList.remove('es-mm__position-init');
      });

      // Set Settings.
      document.querySelectorAll('.es-header__nav-inner > li.menu-item:not(.es-mm)').forEach(function (parent) {
        var position = 'es-sm-position-right'; //default
        var objPrevWidth = 0;
        parent.querySelectorAll('.sub-menu').forEach(function (el) {
          // Reset child levels.
          if (el.parentElement.nextElementSibling) {
            el.parentElement.nextElementSibling.classList.add('es-sm__level');
          }
          if (el.parentElement.classList.contains('es-sm__level')) {
            el.parentElement.classList.remove('es-mm-level');
            position = 'es-sm-position-right'; //reset
            objPrevWidth = 0;
          }

          // Find out position items.
          var offset = el.getBoundingClientRect().left;
          if (position === 'es-sm-position-right' && el.offsetWidth + offset > windowWidth) {
            position = 'es-sm-position-left';
          }
          if (position === 'es-sm-position-left' && offset - (el.offsetWidth + objPrevWidth) < 0) {
            position = 'es-sm-position-right';
          }
          objPrevWidth = el.offsetWidth;
          el.classList.add('es-sm-position-init');
          el.parentElement.classList.add(position);
        });
      });
    }
  }, {
    key: "adaptTablet",
    value: function adaptTablet() {
      // Click outside.
      document.addEventListener('touchstart', function (e) {
        if (!e.target.closest('.es-header__nav-inner')) {
          document.querySelectorAll('.es-header__nav-inner .menu-item-has-children').forEach(function (el) {
            el.classList.remove('submenu-visible');
          });
        } else {
          var parentMenuItem = e.target.closest('.menu-item');
          if (parentMenuItem) {
            if (parentMenuItem.previousElementSibling) {
              parentMenuItem.previousElementSibling.querySelectorAll('.menu-item').forEach(function (el) {
                el.classList.remove('submenu-visible');
              });
            }
            if (parentMenuItem.nextElementSibling) {
              parentMenuItem.nextElementSibling.classList.remove('submenu-visible');
            }
          }
        }
      });
      document.querySelectorAll('.es-header__nav-inner .menu-item-has-children').forEach(function (el) {
        // Reset class.
        el.classList.remove('submenu-visible');

        // Remove expanded if exists.
        var expandedElem = el.querySelector('a > .expanded');
        if (expandedElem) {
          expandedElem.remove();
        }

        // Add a caret.
        if ('ontouchstart' in document.documentElement) {
          var aTag = el.querySelector('a');
          if (aTag) {
            var span = document.createElement('span');
            span.className = 'expanded';
            aTag.appendChild(span);
          }
        }

        // Check touch device.
        if ('ontouchstart' in document.documentElement) {
          el.classList.add('touch-device');
        }
        var expandedElement = el.querySelector('a .expanded');
        if (expandedElement) {
          expandedElement.addEventListener('touchstart', function (e) {
            e.preventDefault();
            el.classList.toggle('submenu-visible');
          }, {
            passive: true
          });
        }
        var anchor = el.querySelector('a');
        if (anchor && anchor.getAttribute('href') === '#') {
          anchor.addEventListener('touchstart', function (e) {
            e.preventDefault();
            if (!e.target.classList.contains('expanded')) {
              el.classList.toggle('submenu-visible');
            }
          }, {
            passive: true
          });
        }
      });
    }
  }, {
    key: "stickyScroll",
    value: function stickyScroll() {
      var _this2 = this;
      this.headerParams = {
        headerLargeHeight: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-initial-height')),
        headerCompactHeight: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-height')),
        headerSmart: document.querySelector('.es-navbar-smart-enabled .es-header, .es-navbar-sticky-enabled .es-header'),
        wpAdminBar: document.querySelector('#wpadminbar'),
        headerBefore: document.querySelector('.es-header-before'),
        headerStretch: document.querySelector('.es-navbar-smart-enabled .es-header-stretch'),
        wpAdminBarHeight: null,
        smartStart: null,
        scrollPoint: 200,
        scrollPrev: 200,
        scrollUpAmount: 0,
        headerSmartPosition: 0
      };
      this.headerParams.wpAdminBarHeight = this.headerParams.wpAdminBar ? this.headerParams.wpAdminBar.offsetHeight : 0;
      if (this.headerParams.headerBefore) {
        this.headerParams.smartStart = this.headerParams.headerBefore.offsetTop;
      } else {
        this.headerParams.smartStart = this.headerParams.wpAdminBarHeight + (this.headerParams.headerSmart ? this.headerParams.headerSmart.offsetTop : 0);
      }
      window.addEventListener('scroll', function () {
        var scrolled = window.scrollY;
        _this2.headerParams.headerSmartPosition = _this2.headerParams.headerSmart ? _this2.headerParams.headerSmart.offsetTop : 0;
        if (scrolled > _this2.headerParams.smartStart + _this2.headerParams.scrollPoint + 10 && scrolled > _this2.headerParams.scrollPrev) {
          if (scrolled > _this2.headerParams.smartStart + _this2.headerParams.headerCompactHeight + 200) {
            document.dispatchEvent(new Event('sticky-nav-hide'));
          }
        } else {
          if (_this2.headerParams.scrollUpAmount >= _this2.headerParams.scrollPoint || scrolled === 0) {
            document.dispatchEvent(new Event('sticky-nav-visible'));
          }
        }
        if (_this2.headerParams.headerSmart) {
          if (scrolled > _this2.headerParams.smartStart + _this2.headerParams.headerCompactHeight) {
            document.dispatchEvent(new Event('nav-stick', {
              detail: _this2.headerParams
            }));
          } else if (_this2.headerParams.headerSmartPosition <= _this2.headerParams.smartStart) {
            document.dispatchEvent(new Event('nav-unstick', {
              detail: _this2.headerParams
            }));
          }
        }
        if (scrolled < _this2.headerParams.scrollPrev) {
          _this2.headerParams.scrollUpAmount += _this2.headerParams.scrollPrev - scrolled;
        } else {
          _this2.headerParams.scrollUpAmount = 0;
        }
        if (_this2.headerParams.wpAdminBar && window.innerWidth <= 600 && scrolled >= _this2.headerParams.wpAdminBarHeight) {
          document.dispatchEvent(new Event('adminbar-mobile-scrolled'));
        } else {
          document.dispatchEvent(new Event('adminbar-mobile-no-scrolled'));
        }
        _this2.headerParams.scrollPrev = scrolled;
      });
    }
  }, {
    key: "headerClassesChange",
    value: function headerClassesChange() {
      var _this3 = this;
      document.addEventListener("sticky-nav-visible", function (event) {
        _this3.headerParams.headerSmart.classList.add('es-header-smart-visible');
      });
      document.addEventListener("sticky-nav-hide", function (event) {
        _this3.headerParams.headerSmart.classList.remove('es-header-smart-visible');
      });
      document.addEventListener("nav-stick", function (event) {
        _this3.headerParams.headerSmart.classList.add('es-scroll-sticky');
      });
      document.addEventListener("nav-unstick", function (event) {
        _this3.headerParams.headerSmart.classList.remove('es-scroll-sticky', 'es-header-smart-visible');
      });
      document.addEventListener("adminbar-mobile-scrolled", function (event) {
        document.body.classList.add('es-adminbar-mobile-scrolled');
      });
      document.addEventListener("adminbar-mobile-no-scrolled", function (event) {
        document.body.classList.remove('es-adminbar-mobile-scrolled');
      });
    }
  }]);
  return CscoNavigation;
}();
new CscoNavigation();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Offcanvas */

(function () {
  var toggles = document.querySelectorAll('.es-header__offcanvas-toggle, .es-site-overlay, .es-offcanvas__toggle');
  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      var body = document.body;
      if (!body.classList.contains('es-offcanvas-active')) {
        body.classList.add('es-offcanvas-transition');
      } else {
        setTimeout(function () {
          body.classList.remove('es-offcanvas-transition');
        }, 400);
      }
      body.classList.toggle('es-offcanvas-active');
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Reading Time Progress */

(function () {
  var initReadingTimeProgress = function initReadingTimeProgress(postElement) {
    var section = 'es-post-reading-time';
    var readingTimeProgress = postElement.querySelector(".".concat(section));
    var content = postElement.querySelector('.entry-content');
    if (readingTimeProgress && content) {
      var progressPath = readingTimeProgress.querySelector(".".concat(section, "__progress path"));
      var pathLength = progressPath.getTotalLength();
      progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
      progressPath.style.strokeDasharray = "".concat(pathLength, " ").concat(pathLength);
      progressPath.style.strokeDashoffset = pathLength;
      progressPath.getBoundingClientRect();
      progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
      var updateProgress = function updateProgress() {
        var scroll = window.scrollY || window.scrollTop || document.documentElement.scrollTop;
        var contentHeight = content.offsetHeight;
        var contentOffsetTop = content.getBoundingClientRect().top + window.scrollY;
        var windowHeight = window.innerHeight;

        // Calculate the total scrollable height for the content of the post
        var scrollableHeight = contentHeight - windowHeight;

        // Check if the post is currently being viewed
        if (scrollableHeight > 0) {
          // Determine how much we've scrolled within the bounds of this post
          var relativeScroll = Math.max(0, Math.min(scroll - contentOffsetTop, scrollableHeight));

          // Calculate the progress
          var progress = pathLength - relativeScroll * pathLength / scrollableHeight;

          // Update the strokeDashoffset
          progressPath.style.strokeDashoffset = Math.max(progress, 0);
        } else {
          // Reset progress if the content is not scrollable
          progressPath.style.strokeDashoffset = pathLength;
        }
      };

      // Immediately call updateProgress to handle visible content
      updateProgress();
      window.addEventListener('scroll', updateProgress);
      window.addEventListener('resize', updateProgress);
    }
  };
  document.addEventListener('DOMContentLoaded', function () {
    var firstPost = document.querySelector('.es-content-area'); // Change the selector based on your post structure
    if (firstPost) {
      initReadingTimeProgress(firstPost);
    }
  });
  var observePosts = function observePosts() {
    var posts = document.querySelectorAll('.es-nextpost-section');
    var observer = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          initReadingTimeProgress(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      threshold: 0.1
    });
    posts.forEach(function (post) {
      observer.observe(post);
    });
  };
  document.addEventListener('DOMContentLoaded', observePosts);
  document.body.addEventListener('post-load', function (e) {
    observePosts();
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Responsive Embeds */

(function () {
  /**
   * Add max-width & max-height to <iframe> elements, depending on their width & height props.
   */
  function initResponsiveEmbeds() {
    var proportion, parentWidth;
    var iframes = document.querySelectorAll('.entry-content iframe');

    // Loop through iframe elements.
    iframes.forEach(function (iframe) {
      // Don't handle if the parent automatically resizes itself.
      if (iframe.closest('div[data-video-start], div[data-video-end]')) {
        return;
      }
      // Only continue if the iframe has a width & height defined.
      if (iframe.width && iframe.height) {
        // Calculate the proportion/ratio based on the width & height.
        proportion = parseFloat(iframe.width) / parseFloat(iframe.height);
        // Get the parent element's width.
        parentWidth = parseFloat(window.getComputedStyle(iframe.parentElement).width);
        // Set the max-width & height.
        iframe.style.maxWidth = '100%';
        iframe.style.maxHeight = Math.round(parentWidth / proportion) + 'px';
      }
    });
  }

  // Document ready.
  document.addEventListener('DOMContentLoaded', function () {
    initResponsiveEmbeds();
  });

  // Post load. This assumes you have an event "post-load" being dispatched on the body element.
  document.body.addEventListener('post-load', function () {
    initResponsiveEmbeds();
  });

  // Window resize.
  window.addEventListener('resize', function () {
    initResponsiveEmbeds();
  });

  // Run on initial load.
  initResponsiveEmbeds();
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Color Scheme Toogle */


var esnDarkMode = {
  init: function init() {
    var _this = this;
    this.initMode();
    window.matchMedia('(prefers-color-scheme: dark)').addListener(function (e) {
      _this.initMode();
    });
    document.querySelectorAll('.es-site-scheme-toggle').forEach(function (toggle) {
      toggle.onclick = function () {
        var siteScheme = document.body.getAttribute('data-scheme');
        if ('dark' === siteScheme) {
          _this.changeScheme('light', true);
        } else if ('light' === siteScheme) {
          _this.changeScheme('dark', true);
        } else if ('auto' === siteScheme) {
          var systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (systemDark) {
            _this.changeScheme('light', true);
          } else {
            _this.changeScheme('dark', true);
          }
        }
      };
    });
  },
  detectColorScheme: function detectColorScheme(color) {
    var level = 190;
    var alpha = 1;
    var rgba = [255, 255, 255];
    var color_rgba = false;
    color = color.trim();

    // Excludes.
    if (['#0e131a'].includes(color)) {
      return 'dark';
    }
    if ('#' === color[0]) {
      color = color.replace('#', '').trim();
      if (3 === color.length) {
        color = color[0] + color[0] + color[1] + color[1] + color[2] + color[2];
      }
      rgba[0] = parseInt(color.substr(0, 2), 16);
      rgba[1] = parseInt(color.substr(2, 2), 16);
      rgba[2] = parseInt(color.substr(4, 2), 16);
    } else if (color_rgba = color.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i)) {
      rgba[0] = parseInt(color_rgba[1]);
      rgba[1] = parseInt(color_rgba[2]);
      rgba[2] = parseInt(color_rgba[3]);
      if (color_rgba[4] !== undefined) {
        alpha = parseFloat(color_rgba[4]);
      }
    }
    rgba.forEach(function myFunction(channel, key, stack) {
      stack[key] = String(channel + Math.ceil((255 - channel) * (1 - alpha))).padStart(2, '0');
    });
    var scheme = 'light';
    var brightness = (rgba[0] * 299 + rgba[1] * 587 + rgba[2] * 114) / 1000;
    if (rgba[0] === rgba[1] && rgba[1] === rgba[2]) {
      if (brightness < level) {
        scheme = 'dark';
      }
    } else {
      if (brightness < level) {
        scheme = 'inverse';
      }
    }
    return scheme;
  },
  setIndividualScheme: function setIndividualScheme() {
    var list = {
      '.es-header': '--es-header-background',
      '.es-header__nav-inner .sub-menu': '--es-header-submenu-background'
    };
    function createClosure(key) {
      return function (element) {
        var color = getComputedStyle(element).getPropertyValue(list[key]);
        var scheme = esnDarkMode.detectColorScheme(color);
        element.setAttribute('data-scheme', scheme);
      };
    }
    for (var key in list) {
      if (list.hasOwnProperty(key)) {
        var elements = document.querySelectorAll(key);
        if (elements.length <= 0) {
          continue;
        }
        elements.forEach(createClosure(key));
      }
    }
  },
  initMode: function initMode() {
    var siteScheme = false;
    switch (esLocalize.siteSchemeMode) {
      case 'dark':
        siteScheme = 'dark';
        break;
      case 'light':
        siteScheme = 'light';
        break;
      case 'system':
        siteScheme = 'auto';
        break;
    }
    if (esLocalize.siteSchemeToogle) {
      if ('light' === (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.esGetCookie)('_color_schema')) {
        siteScheme = 'light';
      }
      if ('dark' === (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.esGetCookie)('_color_schema')) {
        siteScheme = 'dark';
      }
    }
    this.setIndividualScheme();
    if (siteScheme && siteScheme !== document.body.getAttribute('data-scheme')) {
      this.changeScheme(siteScheme, false);
    }
  },
  changeScheme: function changeScheme(siteScheme, cookie) {
    document.body.classList.add('es-scheme-toggled');
    document.body.setAttribute('data-scheme', siteScheme);
    this.setIndividualScheme();
    if (cookie) {
      (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.esSetCookie)('_color_schema', siteScheme, {
        expires: 2592000
      });
    }
    setTimeout(function () {
      document.body.classList.remove('es-scheme-toggled');
    }, 100);
  }
};
esnDarkMode.init();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Scroll to top */

(function () {
  var section = 'es-scroll-top';
  var scrollToTopButton = document.querySelector(".".concat(section));
  if (scrollToTopButton) {
    var activeClass = 'is-active';
    var offset = 200;
    var scrollToTop = function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    };
    var progressPath = scrollToTopButton.querySelector(".".concat(section, "-progress path"));
    var pathLength = progressPath.getTotalLength();
    progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
    progressPath.style.strokeDasharray = "".concat(pathLength, " ").concat(pathLength);
    progressPath.style.strokeDashoffset = pathLength;
    progressPath.getBoundingClientRect();
    progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
    var updateProgress = function updateProgress() {
      var scroll = window.scrollY || window.scrollTop || document.documentElement.scrollTop;
      var docHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight, document.body.offsetHeight, document.documentElement.offsetHeight, document.body.clientHeight, document.documentElement.clientHeight);
      var windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
      var height = docHeight - windowHeight;
      var progress = pathLength - scroll * pathLength / height;
      progressPath.style.strokeDashoffset = progress;
    };
    updateProgress();
    if (scrollToTopButton) {
      scrollToTopButton.addEventListener('click', scrollToTop);
    }
    window.addEventListener('scroll', function () {
      updateProgress();
      var scrollPos = window.scrollY || window.scrollTop || document.getElementsByTagName('html')[0].scrollTop;
      if (scrollPos > offset) {
        scrollToTopButton.classList.add(activeClass);
      } else {
        scrollToTopButton.classList.remove(activeClass);
      }
    });
  }
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Search Dropdown */

(function () {
  var toggles = document.querySelectorAll('.es-header__search-toggle, .es-search-overlay, .es-search__close');
  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      var body = document.body;
      var headerElement = document.querySelector('.es-header');
      if (!body.classList.contains('es-search-active')) {
        body.classList.add('es-search-transition');
      } else {
        setTimeout(function () {
          body.classList.remove('es-search-transition');
        }, 400);
      }
      body.classList.toggle('es-search-active');
      headerElement.classList.toggle('es-search-visible');
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Metabar Share copy link */


(function () {
  function copyToClipboard(str) {
    var area = document.createElement('textarea');
    document.body.appendChild(area);
    area.value = str;
    area.select();
    document.execCommand('copy');
    document.body.removeChild(area);
  }
  function showTooltip(button, message) {
    var tooltip = document.createElement('div');
    tooltip.className = 'es-share__copy-link-tooltip';
    tooltip.textContent = message;
    button.appendChild(tooltip);
    setTimeout(function () {
      tooltip.classList.add('hide');
    }, 1500);
    setTimeout(function () {
      button.removeChild(tooltip);
    }, 2000);
  }
  var copyButtons = document.querySelectorAll('.es-share__copy-link');
  copyButtons.forEach(function (button) {
    button.addEventListener('click', function (event) {
      event.preventDefault();
      var pageURL = this.getAttribute('href');
      copyToClipboard(pageURL);
      showTooltip(this, 'URL copied');
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
var __webpack_exports__ = {};
__webpack_require__.r(__webpack_exports__);
/** ----------------------------------------------------------------------------
 * Sticky Sidebar */

(function () {
  var stickyElementsSmart = [],
    stickyElements = [];
  stickyElementsSmart.push('.es-navbar-smart-enabled.es-stick-to-top .es-single-product .entry-summary', '.es-sticky-sidebar-enabled.es-navbar-smart-enabled.es-stick-to-top .es-sidebar__inner', '.es-sticky-sidebar-enabled.es-navbar-smart-enabled.es-stick-last .es-sidebar__inner .widget:last-child');
  stickyElements.push('.es-navbar-sticky-enabled.es-stick-to-top .es-single-product .entry-summary', '.es-sticky-sidebar-enabled.es-navbar-sticky-enabled.es-stick-to-top .es-sidebar__inner', '.es-sticky-sidebar-enabled.es-navbar-sticky-enabled.es-stick-last .es-sidebar__inner .widget:last-child');
  document.addEventListener("DOMContentLoaded", function () {
    var headerStick = document.querySelector('.es-header'),
      wpAdminBar = document.querySelector('#wpadminbar'),
      headerStickHeight = headerStick ? headerStick.offsetHeight : 0,
      wpAdminBarHeight = wpAdminBar ? wpAdminBar.offsetHeight : 0,
      headerStretch = document.querySelector('.es-header-stretch'),
      headerStretchHeight = headerStretch ? headerStretch.offsetHeight : 0,
      allHeight = headerStickHeight + wpAdminBarHeight + 20,
      windowWidth = window.innerWidth;
    if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
      stickyElementsSmart.push('.es-sticky-sidebar-enabled.es-stick-to-bottom .es-sidebar__inner');
      stickyElements.push('.es-sticky-sidebar-enabled.es-stick-to-bottom .es-sidebar__inner');
    }
    stickyElementsSmart = stickyElementsSmart.join(',');
    stickyElements = stickyElements.join(',');
    document.addEventListener('sticky-nav-visible', function () {
      headerStickHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-height'));
      allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      document.querySelectorAll(stickyElementsSmart).forEach(function (el) {
        el.style.top = allHeight + 'px';
      });
    });
    document.addEventListener('sticky-nav-hide', function () {
      headerStickHeight = 0;
      allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      document.querySelectorAll(stickyElementsSmart).forEach(function (el) {
        el.style.top = allHeight + 'px';
      });
    });
    document.addEventListener('stretch-nav-to-small', function () {
      headerStretchHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-height'));
      allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      if (headerStretch && headerStretch.classList.contains("es-scroll-sticky") && !headerStretch.classList.contains("es-scroll-active")) {
        document.querySelectorAll(stickyElementsSmart).forEach(function (el) {
          el.style.top = allHeight + 'px';
        });
      }
    });
    document.addEventListener('stretch-nav-to-big', function () {
      headerStretchHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--es-header-initial-height'));
    });
    if (document.body.classList.contains('es-navbar-smart-enabled') && windowWidth >= 1020) {
      allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      document.querySelectorAll(stickyElementsSmart).forEach(function (el) {
        el.style.top = allHeight + 'px';
      });
    } else if (document.body.classList.contains('es-navbar-sticky-enabled') && windowWidth >= 1020) {
      allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      document.querySelectorAll(stickyElements).forEach(function (el) {
        el.style.top = allHeight + 'px';
      });
    }
    window.addEventListener('resize', function () {
      var windowWidthResize = window.innerWidth;
      if (windowWidthResize < 1020) {
        document.querySelectorAll(stickyElements).forEach(function (el) {
          el.removeAttribute('style');
        });
        document.querySelectorAll(stickyElementsSmart).forEach(function (el) {
          el.removeAttribute('style');
        });
      }
    });
  });
})();
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Widget Nav Menu */


(function () {
  HTMLElement.prototype.responsiveNav = function () {
    this.classList.remove('menu-item-expanded');
    var previousElement = this.previousElementSibling;
    if (previousElement && previousElement.classList.contains('submenu-visible')) {
      previousElement.classList.remove('submenu-visible');
      (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideUp)(previousElement);
      this.parentElement.classList.remove('menu-item-expanded');
    } else {
      var parentOfParent = this.parentElement.parentElement;
      parentOfParent.querySelectorAll('.menu-item .sub-menu').forEach(function (subMenu) {
        subMenu.classList.remove('submenu-visible');
        (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideUp)(subMenu);
      });
      parentOfParent.querySelectorAll('.menu-item-expanded').forEach(function (item) {
        item.classList.remove('menu-item-expanded');
      });
      if (previousElement) {
        previousElement.classList.toggle('submenu-visible');
        (0,_utility_js__WEBPACK_IMPORTED_MODULE_0__.slideToggle)(previousElement);
      }
      this.parentElement.classList.toggle('menu-item-expanded');
    }
  };
  document.addEventListener('DOMContentLoaded', function () {
    var menuItems = document.querySelectorAll('.widget_nav_menu .menu-item-has-children');
    menuItems.forEach(function (menuItem) {
      var span = document.createElement('span');
      menuItem.appendChild(span);
      span.addEventListener('click', function (e) {
        e.preventDefault();
        this.responsiveNav();
      });
      var anchor = menuItem.children[0];
      if (anchor && anchor.tagName === 'A' && anchor.getAttribute('href') === '#') {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          this.nextElementSibling.nextElementSibling.responsiveNav();
        });
      }
    });
  });
})();
})();

/******/ })()
;





/**
 * show hide password (input forms}
*/

function togglePass() {
  var x = document.getElementById("user_pass");
  var l1 = document.getElementById("view");
  var l2 = document.getElementById("no_view");
  if (x.type === "password") {
    x.type = "text";
    l1.setAttribute("hidden", true);
    l2.removeAttribute("hidden");
  } else {
    x.type = "password";
    l1.removeAttribute("hidden");
    l2.setAttribute("hidden", true);
  }
}


 


    (function ($) {
        $("input#keyword").keyup(function() {
            if ($(this).val().length > 2) {
              $("#datafetch").show();
            } else {
              $("#datafetch").hide();
            }
          });
    });







/**
 * Link active pages (nav)
*/
$(function() {
		var pathname_url = window.location.pathname;
		var href_url = window.location.href;
		var pattern = "/page";
if(pathname_url.indexOf(pattern) !== -1) {
	
	var from = pathname_url.search(pattern);
	var pathname_url = pathname_url.substring(0, from);       

}
		$('.menu li').each(function () {
			var link = $(this).find('a').attr('href');
			if (pathname_url == link || href_url == link) {
			$(this).removeClass("menu-item menu-item-type-custom menu-item-object-custom esn-menu-item-style-none esn-menu-item-has-icon");
		    $(this).addClass("menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item esn-menu-item-style-none esn-menu-item-has-icon");
            $(".menu li a").attr('aria-current', 'page');	
			}
		});
}); 

/**
 * Link active home (nav)
*/
$(function() {	
	var pathname_url = window.location.pathname;
	var href_url = window.location.href;
	var pattern = "/page";
if(pathname_url.indexOf(pattern) !== -1) {
	
	var from = pathname_url.search(pattern);
	var pathname_url = pathname_url.substring(1, from);       

}
$(".menu li").each(function () {	
		var link = $(this).find("a").attr("href");		
		if(pathname_url == link || href_url == link) {	
		    $(this).removeClass("menu-item menu-item-type-custom menu-item-object-custom esn-menu-item-style-none esn-menu-item-has-icon");
		    $(this).addClass("menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item esn-menu-item-style-none esn-menu-item-has-icon");
            $(".menu li a").attr('aria-current', 'page');		
		}		
	});	
});


$(function(){
	$(".edit-profile .alert").delay(3000).slideUp(300);
	$(".modal__window .alert").delay(3000).slideUp(300);
});



// Share custom text (post and commments)
document.addEventListener('mouseup', function(event) {
    let selectedText = window.getSelection().toString().trim();
    let target = event.target;

    if (selectedText.length > 0 && (target.closest('.es-post') || target.closest('.comment-text'))) {
        showShareToolbar(selectedText, event);
    } else {
        hideShareToolbar();
    }
});

function showShareToolbar(selectedText, event) {
    let toolbar = document.getElementById('share-toolbar');

    if (toolbar) { 
        toolbar.style.display = 'block';

        const selection = window.getSelection().getRangeAt(0);
        const rect = selection.getBoundingClientRect();

        const toolbarWidth = 80; 
        const toolbarHeight = 40; 

        const top = rect.top + window.scrollY - toolbarHeight - 10; 
        const left = rect.left + window.scrollX + (rect.width / 2) - (toolbarWidth / 2); 

        toolbar.style.top = `${top}px`;
        toolbar.style.left = `${left}px`;

        toolbar.setAttribute('data-text', encodeURIComponent(selectedText));
    }
}

function hideShareToolbar() {
    let toolbar = document.getElementById('share-toolbar');

    if (toolbar) { 
        toolbar.style.display = 'none';
    }
}

function shareTelegram() {
    let toolbar = document.getElementById('share-toolbar');
    let text = toolbar ? toolbar.getAttribute('data-text') : '';
    let url = `https://t.me/share/url?url=${encodeURIComponent(window.location.href)}&text=${text}`;
    window.open(url, '_blank');
}

function shareVK() {
    let toolbar = document.getElementById('share-toolbar');
    let text = toolbar ? toolbar.getAttribute('data-text') : '';
    let url = `https://vk.com/share.php?url=${encodeURIComponent(window.location.href)}&title=${text}`;
    window.open(url, '_blank');
}



// Smooth page transition
jQuery(document).ready(function($) {
    $("a").not("[href*='#']").each(function() {
        var link = $(this).attr("href");


        if (link && (link.startsWith(window.location.origin) || link.startsWith('/'))) {
            $(this).on("click", function(event) {
                event.preventDefault();
                $("#loading-bar").fadeIn(0);
                $("#loading-bar").css("width", "0%").css("box-shadow", "none");

                let startTime = Date.now();

                $("#loading-bar").animate({ width: "100%" }, {
                    duration: 200, 
                    step: function(now) {
                        var elapsedTime = Date.now() - startTime;
                        var duration = Math.max(200 - elapsedTime, 100);
                        $(this).finish();
                        $("#loading-bar").stop().animate({ width: "100%" }, { duration: duration });
                    },
                    complete: function() {
                     
                        window.location.href = link;
                    }
                });
            });
        }
    });
});


// Popup subscribe list
 jQuery(document).ready(function($) {
    $('#subscriptions_count').on('click', function() {
                $('.popup-overlay-subscriptions').fadeIn();
            });

            $('.close-popup, .popup-overlay-subscriptions').on('click', function() {
                $('.popup-overlay-subscriptions').fadeOut();
            });

    $('#subscribers_count').on('click', function() {
                $('.popup-overlay-subscribers').fadeIn();
            });

            $('.close-popup, .popup-overlay-subscribers').on('click', function() {
                $('.popup-overlay-subscribers').fadeOut();
            });
});


// Global modal Open/Close
function closeModal() {
    const modal = document.querySelector('.modal:target');
    if (modal) {
        history.pushState("", document.title, window.location.pathname + window.location.search);
        modal.style.display = 'none'; 
    }
}
document.querySelectorAll('.esn-modal-open').forEach(button => {
    button.addEventListener('click', function () {
        const target = this.getAttribute('href').substring(1);
        document.getElementById(target).style.display = 'flex';
    });
});
document.querySelectorAll('.modal__close').forEach(closeBtn => {
    closeBtn.addEventListener('click', closeModal);
});

// Auto BG-BLUR
document.addEventListener('DOMContentLoaded', function () {
    const figures = document.querySelectorAll('figure.wp-block-image');

    figures.forEach(figure => {
        const img = figure.querySelector('img');

        if (img && img.complete) {
            const figureWidth = figure.offsetWidth;
            const imgWidth = img.offsetWidth;

            if (imgWidth < figureWidth) {
                figure.classList.add('es-image-blur');

                const bgBlurDiv = document.createElement('div');
                bgBlurDiv.classList.add('bg-blur');
                bgBlurDiv.style.backgroundImage = `url("${img.src}")`;
                figure.insertBefore(bgBlurDiv, img);
            }
        }
    });
});




// Responsive full height IFRAME Telegram (for plugin Front EditorJS)
document.addEventListener('DOMContentLoaded', function() {
    function getDynamicHeight(iframe) {
        return '';
    }

    function updateIframes() {
        const figures = document.querySelectorAll('figure.editor-telegram');

        figures.forEach(figure => {
            const iframe = figure.querySelector('iframe');
            if (iframe) {
                const url = iframe.src;
                const idMatch = url.match(/\/([a-zA-Z0-9_]+)\/(\d+)/);
                if (idMatch) {
                    const id = `telegram-post-${idMatch[1]}-${idMatch[2]}`;
                    iframe.setAttribute('id', id);

                    const dynamicHeight = getDynamicHeight(iframe);
                    iframe.setAttribute('style', `overflow: hidden; border: none; height: ${dynamicHeight};`);
                }
            }
        });
    }

    window.addEventListener('message', event => {
        if (event.origin !== 'https://t.me') return;
        const data = JSON.parse(event.data);
        if (data.event === 'resize') {
            const figures = document.querySelectorAll('figure.editor-telegram');
            figures.forEach(figure => {
                const iframe = figure.querySelector('iframe');
                if (iframe && event.source === iframe.contentWindow) {
                    iframe.style.height = `${data.height}px`;
                }
            });
        }
    });
	
    updateIframes();
});




// Embed TikTok (for plugin Front EditorJS)
document.addEventListener('DOMContentLoaded', function() {
    function getDynamicHeight(iframe) {
        return '';
    }

    function updateTiktokEmbeds() {
        const tiktokEmbeds = document.querySelectorAll('div.editor-tiktok');

        tiktokEmbeds.forEach(embed => {
            const blockquote = embed.querySelector('blockquote.tiktok-embed');
            const scriptTag = document.createElement('script');
            scriptTag.async = true;
            scriptTag.src = "https://www.tiktok.com/embed.js";

            if (blockquote) {
                const cite = blockquote.getAttribute('cite');
                const videoIdMatch = cite.match(/\/video\/(\d+)/);
                if (videoIdMatch) {
                    blockquote.setAttribute('data-video-id', videoIdMatch[1]);
                    blockquote.setAttribute('data-embed-from', 'embed_page');
                }
                embed.appendChild(scriptTag);
            }
        });
    }
    updateTiktokEmbeds();
});


jQuery(document).ready(function($) {
    console.log('ESN: Скрипт комментариев v3.0 (REST API Optimization)');

    // Используем данные из локализации rfplDataComm
    var restRoot = (typeof rfplDataComm !== 'undefined') ? rfplDataComm.root : '/wp-json/';
    
    var currentSort = 'popular'; 
    var currentPage = 1;
    var maxPages = 1; 
    var isLoading = false;

    // 1. Управление выпадашкой (оставляем без изменений)
    $(document).on('click', '.sort-trigger', function(e) {
        e.preventDefault(); e.stopPropagation();
        var $wrapper = $(this).closest('.comments-sorting-wrapper');
        $('.comments-sorting-wrapper').not($wrapper).removeClass('is-open');
        $wrapper.toggleClass('is-open');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.comments-sorting-wrapper').length) {
            $('.comments-sorting-wrapper').removeClass('is-open');
        }
    });

    // 2. Клик по пункту сортировки
    $(document).on('click', '.sort-options li', function(e) {
        var $this = $(this);
        if ($this.hasClass('active')) return;

        $('.current-sort-label').text($this.text());
        $('.sort-options li').removeClass('active');
        $this.addClass('active');
        $this.closest('.comments-sorting-wrapper').removeClass('is-open');

        currentSort = $this.data('sort');
        currentPage = 1;
        
        loadComments(true); // Заменяем список
    });

    // 3. Кнопка "Загрузить еще"
    $(document).on('click', '#esn-load-more-btn', function(e) {
        e.preventDefault();
        if(!isLoading) {
            currentPage++;
            loadComments(false); // Добавляем в конец
        }
    });

    // 4. Infinite Scroll (оставляем твою логику)
    $(window).scroll(function() {
        if ($('.es-load-more-wrap').is(':visible') && !isLoading) {
             var $btn = $('#esn-load-more-btn');
             if ($btn.length) {
                 var btnTop = $btn.offset().top;
                 var wBottom = $(window).scrollTop() + $(window).height();
                 if (wBottom > btnTop - 100) {
                     $btn.click();
                 }
             }
        }
    });

    /**
     * ГЛАВНАЯ ФУНКЦИЯ: Загрузка через REST API
     */
    function loadComments(replace) {
        isLoading = true;
        var $list = $('.comment-list');
        var $btn = $('#esn-load-more-btn');
        var $loader = $('#esn-loader');
        
        // Берем ID поста. Убедись, что в comments.php у дива #comments есть data-post-id
        var postID = $('#comments').data('post-id'); 
        if (!postID) {
            console.error('ESN Error: post-id not found on #comments element');
            return;
        }

        $btn.hide();
        $loader.show();
        if(replace) $list.css('opacity', '0.5');

        // Стучимся в наш новый REST эндпоинт
        var apiUrl = restRoot + 'rfpl/v1/comments/' + postID + '?sort=' + currentSort + '&page=' + currentPage;

        $.ajax({
            url: apiUrl,
            type: 'GET', // REST эндпоинты на чтение всегда GET
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', rfplDataComm.nonce);
            },
            success: function(res) {
                // В REST API ответ приходит напрямую в res, без res.data
                if(res.html) {
                    maxPages = res.max_pages;

                    if(replace) {
                        $list.html(res.html);
                        // Скролл к началу комментов при смене сортировки
                        $('html, body').animate({ scrollTop: $("#comments").offset().top - 100 }, 300);
                    } else {
                        $list.append(res.html);
                    }

                    if(currentPage < maxPages) {
                        $('.es-load-more-wrap').show();
                        $btn.show();
                    } else {
                        $('.es-load-more-wrap').hide();
                    }
                }
            },
            error: function(err) {
                console.error('REST API Error:', err);
            },
            complete: function() {
                isLoading = false;
                $list.css('opacity', '1');
                $loader.hide();
            }
        });
    }
});





jQuery(document).ready(function($) {
    // 1. Открытие/закрытие только для турнирных лиг
    $(document).on('click', '.league-sorting-wrapper .sort-trigger', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $wrapper = $(this).closest('.league-sorting-wrapper');
        
        // Закрываем другие турнирные менюшки, если их несколько
        $('.league-sorting-wrapper').not($wrapper).removeClass('is-open');
        $wrapper.toggleClass('is-open');
    });

    // 2. Закрытие при клике по ссылке внутри меню
    $(document).on('click', '.league-sorting-wrapper .sort-options li a', function() {
        $(this).closest('.league-sorting-wrapper').removeClass('is-open');
    });

    // 3. Закрытие при клике мимо
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.league-sorting-wrapper').length) {
            $('.league-sorting-wrapper').removeClass('is-open');
        }
    });
});

jQuery(document).ready(function($) {
    // Вешаемся на документ, чтобы ловить клики даже после перерисовок
    $(document).on('click', '.esn-cat-subscribe-wrapper button', function(e) {
        
        // Чтобы не уйти в бесконечную рекурсию при программном клике
        if (e.isTrigger) return;

        var $clickedBtn = $(this);
        var $wrapper = $clickedBtn.closest('.esn-cat-subscribe-wrapper');
        var catId = $wrapper.data('cat-id');

        console.log('Кликнули по категории:', catId);

        // Ждем 1.2 секунды (с запасом на лаги сервера), чтобы плагин обновил кнопку
        setTimeout(function() {
            var $updatedContainer = $wrapper.find('.cs-subscription-container');
            var newContent = $updatedContainer.html();
            
            console.log('Копируем новый контент для всех кнопок cat-id:', catId);

            // Находим все ТАКИЕ ЖЕ контейнеры на странице
            $('.esn-cat-subscribe-wrapper[data-cat-id="' + catId + '"]').each(function() {
                var $targetContainer = $(this).find('.cs-subscription-container');
                
                // Если это не тот контейнер, по которому кликнули — вливаем в него новый HTML
                if ($targetContainer[0] !== $updatedContainer[0]) {
                    $targetContainer.html(newContent);
                    
                    // Дополнительно форсим классы, если плагин их вешает на саму обертку
                    var btnClasses = $updatedContainer.find('button').attr('class');
                    $targetContainer.find('button').attr('class', btnClasses);
                }
            });
        }, 1200); 
    });
});

