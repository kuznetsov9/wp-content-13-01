"use strict";

(function () {
  var esnEditorIframe = {};
  if (window.self !== window.top) {
    var $this;
    esnEditorIframe = {
      /*
      * Variables
      */
      html: false,
      body: false,
      post_type: null,
      page_layout: null,
      prevStorageVal: null,
      /*
      * Initialize
      */
      init: function init(e) {
        $this = esnEditorIframe;

        // Find html and wrapper elements.
        $this.html = document.querySelector('html');
        $this.body = document.querySelector('.editor-styles-wrapper');
        if (!$this || !$this.html || !$this.body) {
          return;
        }
        $this.post_type = esnGWrapper.post_type;
        $this.page_layout = esnGWrapper.page_layout;
        $this.rootObserver();

        // Init events.
        if ('undefined' === typeof window.esnEditorIframeInit) {
          $this.events(e);
          window.esnEditorIframeInit = true;
        }
      },
      /*
      * Events
      */
      events: function events(e) {
        // Listen storage from the parent window
        setInterval(function () {
          var currentValue = sessionStorage.getItem('esnIframeContext');
          if (currentValue !== $this.prevStorageVal) {
            $this.prevStorageVal = currentValue;
            if (currentValue) {
              var data = JSON.parse(currentValue);
              if (data.hasOwnProperty('page_layout')) {
                $this.page_layout = data.page_layout;
                $this.setLayout();
              }
            }
          }
        }, 100);

        // Listen HTML and Body elements.
        var observer = new MutationObserver(function (mutations) {
          mutations.forEach(function (mutation) {
            if (mutation.oldValue !== mutation.target.classList.value) {
              $this.rootObserver();
            }
          });
        });
        observer.observe($this.html, {
          attributes: true,
          subtree: false,
          attributeOldValue: true,
          attributeFilter: ["class"]
        });
        observer.observe($this.body, {
          attributes: true,
          subtree: false,
          attributeOldValue: true,
          attributeFilter: ["class"]
        });

        // Listen resize.
        window.addEventListener('resize', function (e) {
          $this.setBreakpoints();
        });
      },
      /**
       * Function for Listener HTML and Body elements.
       */
      rootObserver: function rootObserver() {
        var update = false;
        if (!$this.html.classList.contains('es-editor-iframe')) {
          $this.html.classList.add('es-editor-iframe');
          update = true;
        }
        if (!$this.body.classList.contains('es-editor-styles-wrapper')) {
          $this.body.classList.add('es-editor-styles-wrapper');
          update = true;
        }
        if (update) {
          $this.setBreakpoints();
          $this.setLayout();
        }
      },
      /*
      * Set breakpoints
      */
      setBreakpoints: function setBreakpoints() {
        var breakpoints = esnGWrapper.breakpoints;

        // Update the matching breakpoints on the observed element.
        Object.keys(breakpoints).forEach(function (breakpoint) {
          var minWidth = breakpoints[breakpoint];
          if ($this.body.clientWidth >= minWidth) {
            $this.html.classList.add(breakpoint);
          } else {
            $this.html.classList.remove(breakpoint);
          }
        });
      },
      /**
       * Set layout.
       */
      setLayout: function setLayout() {
        $this.body.classList.remove('es-sidebar-enabled');
        $this.body.classList.remove('es-sidebar-disabled');
        $this.body.classList.add($this.page_layout);
        $this.body.classList.add($this.post_type);
      }
    };

    // Iframe Loaded.
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
      esnEditorIframe.init();
    } else {
      document.addEventListener('DOMContentLoaded', function () {
        esnEditorIframe.init();
      });
    }
  }
})();