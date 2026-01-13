! function(t, e) {
	"object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.Header = e() : t.Header = e()
}(window, (function() {
	return function(t) {
		var e = {};

		function n(r) {
			if (e[r]) return e[r].exports;
			var i = e[r] = {
				i: r,
				l: !1,
				exports: {}
			};
			return t[r].call(i.exports, i, i.exports, n), i.l = !0, i.exports
		}
		return n.m = t, n.c = e, n.d = function(t, e, r) {
			n.o(t, e) || Object.defineProperty(t, e, {
				enumerable: !0,
				get: r
			})
		}, n.r = function(t) {
			"undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
				value: "Module"
			}), Object.defineProperty(t, "__esModule", {
				value: !0
			})
		}, n.t = function(t, e) {
			if (1 & e && (t = n(t)), 8 & e) return t;
			if (4 & e && "object" == typeof t && t && t.__esModule) return t;
			var r = Object.create(null);
			if (n.r(r), Object.defineProperty(r, "default", {
					enumerable: !0,
					value: t
				}), 2 & e && "string" != typeof t)
				for (var i in t) n.d(r, i, function(e) {
					return t[e]
				}.bind(null, i));
			return r
		}, n.n = function(t) {
			var e = t && t.__esModule ? function() {
				return t.default
			} : function() {
				return t
			};
			return n.d(e, "a", e), e
		}, n.o = function(t, e) {
			return Object.prototype.hasOwnProperty.call(t, e)
		}, n.p = "/", n(n.s = 0)
	}([function(t, e, n) {
		function r(t) {
			return (r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
				return typeof t
			} : function(t) {
				return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
			})(t)
		}

		function i(t, e) {
			for (var n = 0; n < e.length; n++) {
				var r = e[n];
				r.enumerable = r.enumerable || !1, r.configurable = !0, "value" in r && (r.writable = !0), Object.defineProperty(t, r.key, r)
			}
		}
		n(1).toString();
		/**
		 * Header block for the Editor.js.
		 *
		 * @author CodeX (team@ifmo.su)
		 * @copyright CodeX 2018
		 * @license MIT
		 * @version 2.0.0
		 */
		var a = function() {
			function t(e) {
				var n = e.data,
					r = e.config,
					i = e.api,
					a = e.readOnly;
				! function(t, e) {
					if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
				}(this, t), this.api = i, this.readOnly = a, this._CSS = {
					block: this.api.styles.block,
					settingsButton: this.api.styles.settingsButton,
					settingsButtonActive: this.api.styles.settingsButtonActive,
					wrapper: "ce-header",
					alignment: {
						left: "ce-header--left",
						center: "ce-header--center",
						right: "ce-header--right",
						justify: "ce-header--justify"
					}
				}, this.CSS = {
					baseClass: this.api.styles.block,
					loading: this.api.styles.loader,
					input: this.api.styles.input,
					settingsButton: this.api.styles.settingsButton,
					settingsButtonActive: this.api.styles.settingsButtonActive
				}, this.inlineToolSettings = [{
					name: "left",
					icon: '<svg xmlns="http://www.w3.org/2000/svg" id="Layer" enable-background="new 0 0 64 64" height="20" viewBox="0 0 64 64" width="20"><path d="m54 8h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 52h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m10 23h28c1.104 0 2-.896 2-2s-.896-2-2-2h-28c-1.104 0-2 .896-2 2s.896 2 2 2z"/><path d="m54 30h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m10 45h28c1.104 0 2-.896 2-2s-.896-2-2-2h-28c-1.104 0-2 .896-2 2s.896 2 2 2z"/></svg>'
				}, {
					name: "center",
					icon: '<svg xmlns="http://www.w3.org/2000/svg" id="Layer" enable-background="new 0 0 64 64" height="20" viewBox="0 0 64 64" width="20"><path d="m54 8h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 52h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m46 23c1.104 0 2-.896 2-2s-.896-2-2-2h-28c-1.104 0-2 .896-2 2s.896 2 2 2z"/><path d="m54 30h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m46 45c1.104 0 2-.896 2-2s-.896-2-2-2h-28c-1.104 0-2 .896-2 2s.896 2 2 2z"/></svg>'
				}, {
					name: "right",
					icon: '<svg xmlns="http://www.w3.org/2000/svg" id="Layer" enable-background="new 0 0 64 64" height="20" viewBox="0 0 64 64" width="20"><path d="m54 8h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 52h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 19h-28c-1.104 0-2 .896-2 2s.896 2 2 2h28c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 30h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"/><path d="m54 41h-28c-1.104 0-2 .896-2 2s.896 2 2 2h28c1.104 0 2-.896 2-2s-.896-2-2-2z"/></svg>'
				}/* , {
					name: "justify",
					icon: '<svg viewBox="0 0 64 64" width="20" height="20"><path d="m54 8h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"></path><path d="m54 52h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"></path><path d="M 52.867 19 L 10.914 19 C 9.26 19 7.918 19.896 7.918 21 C 7.918 22.104 9.26 23 10.914 23 L 52.867 23 C 54.522 23 55.863 22.104 55.863 21 C 55.863 19.896 54.522 19 52.867 19 Z" style=""></path><path d="m54 30h-44c-1.104 0-2 .896-2 2s.896 2 2 2h44c1.104 0 2-.896 2-2s-.896-2-2-2z"></path><path d="M 52.779 41 L 11.113 41 C 9.469 41 8.136 41.896 8.136 43 C 8.136 44.104 9.469 45 11.113 45 L 52.779 45 C 54.421 45 55.754 44.104 55.754 43 C 55.754 41.896 54.421 41 52.779 41 Z" style=""></path></svg>'
				} */], this._settings = r, this._data = this.normalizeData(n), this.settingsButtons = [], this._element = this.getTag()
			}
			var e, a, s;
			return e = t, s = [{
				key: "conversionConfig",
				get: function() {
					return {
						export: "text",
						import: "text"
					}
				}
			}, {
				key: "sanitize",
				get: function() {
					return {
						level: !1,
						text: {}
					}
				}
			}, {
				key: "isReadOnlySupported",
				get: function() {
					return !0
				}
			}, {
				key: "pasteConfig",
				get: function() {
					return {
						tags: ["H1", "H2", "H3", "H4", "H5", "H6"]
					}
				}
			}, {
				key: "ALIGNMENTS",
				get: function() {
					return {
						left: "left",
						center: "center",
						right: "right",
						justify: "justify"
					}
				}
			}, {
				key: "DEFAULT_ALIGNMENT",
				get: function() {
					return t.ALIGNMENTS.left
				}
			}, {
				key: "toolbox",
				get: function() {
					return {
						icon: n(6).default,
						title: "Heading"
					}
				}
			}], (a = [{
				key: "normalizeData",
				value: function(e) {
					var n = {};
					return "object" !== r(e) && (e = {}), n.text = e.text || "", n.level = parseInt(e.level) || this.defaultLevel.number, n.alignment = e.alignment || t.DEFAULT_ALIGNMENT, n
				}
			}, {
				key: "render",
				value: function() {
					return this._element
				}
			}, {
				key: "_toggleTune",
				value: function(t) {
					this._data.alignment = t
				}
			}, {
				key: "renderSettings",
				value: function() {
					var t = this,
						e = document.createElement("DIV");
					return this.levels.length <= 1 || (this.inlineToolSettings.map((function(n) {
						var r = document.createElement("div");
						return r.classList.add(t._CSS.settingsButton), r.innerHTML = n.icon, r.classList.toggle(t.CSS.settingsButtonActive, n.name === t.data.alignment), e.appendChild(r), r
					})).forEach((function(e, n, r) {
						e.addEventListener("click", (function() {
							t._toggleTune(t.inlineToolSettings[n].name), r.forEach((function(e, n) {
								var r = t.inlineToolSettings[n].name;
								e.classList.toggle(t.CSS.settingsButtonActive, r === t.data.alignment), t._element.classList.toggle(t._CSS.alignment[r], r === t.data.alignment)
							}))
						}))
					})), this.levels.forEach((function(n) {
						var r = document.createElement("SPAN");
						r.classList.add(t._CSS.settingsButton), t.currentLevel.number === n.number && r.classList.add(t._CSS.settingsButtonActive), r.innerHTML = n.svg, r.dataset.level = n.number, r.addEventListener("click", (function() {
							t.setLevel(n.number)
						})), e.appendChild(r), t.settingsButtons.push(r)
					}))), e
				}
			}, {
				key: "setLevel",
				value: function(t) {
					var e = this;
					this.data = {
						level: t,
						text: this.data.text,
						alignment: this.data.alignment
					}, this.settingsButtons.forEach((function(n) {
						n.classList.toggle(e._CSS.settingsButtonActive, parseInt(n.dataset.level) === t)
					}))
				}
			}, {
				key: "merge",
				value: function(t) {
					var e = {
						text: this.data.text + t.text,
						level: this.data.level,
						alignment: this.data.alignment
					};
					this.data = e
				}
			}, {
				key: "validate",
				value: function(t) {
					return "" !== t.text.trim()
				}
			}, {
				key: "save",
				value: function(t) {
					return {
						text: t.innerHTML,
						level: this.currentLevel.number,
						alignment: this.data.alignment
					}
				}
			}, {
				key: "getTag",
				value: function() {
					var t = document.createElement(this.currentLevel.tag);
					return t.innerHTML = this._data.text || "", t.classList.add(this._CSS.wrapper, this._CSS.alignment[this._data.alignment]), t.contentEditable = this.readOnly ? "false" : "true", t.dataset.placeholder = this.api.i18n.t(this._settings.placeholder || ""), t
				}
			}, {
				key: "onPaste",
				value: function(e) {
					var n = e.detail.data,
						r = this.defaultLevel.number;
					switch (n.tagName) {
						case "H1":
							r = 1;
							break;
						case "H2":
							r = 2;
							break;
						case "H3":
							r = 3;
							break;
						case "H4":
							r = 4;
							break;
						case "H5":
							r = 5;
							break;
						case "H6":
							r = 6
					}
					this._settings.levels && (r = this._settings.levels.reduce((function(t, e) {
						return Math.abs(e - r) < Math.abs(t - r) ? e : t
					}))), this.data = {
						level: r,
						text: n.innerHTML,
						alignment: this._settings.defaultAlignment || t.DEFAULT_ALIGNMENT
					}
				}
			}, {
				key: "data",
				get: function() {
					return this._data.text = this._element.innerHTML, this._data.level = this.currentLevel.number, this._data.alignment = this._data.alignment || this._settings.defaultAlignment || t.DEFAULT_ALIGNMENT, this._data
				},
				set: function(t) {
					if (this._data = this.normalizeData(t), void 0 !== t.level && this._element.parentNode) {
						var e = this.getTag();
						e.innerHTML = this._element.innerHTML, this._element.parentNode.replaceChild(e, this._element), this._element = e
					}
					void 0 !== t.text && (this._element.innerHTML = this._data.text || "")
				}
			}, {
				key: "currentLevel",
				get: function() {
					var t = this,
						e = this.levels.find((function(e) {
							return e.number === t._data.level
						}));
					return e || (e = this.defaultLevel), e
				}
			}, {
				key: "defaultLevel",
				get: function() {
					var t = this;
					if (this._settings.defaultLevel) {
						var e = this.levels.find((function(e) {
							return e.number === t._settings.defaultLevel
						}));
						if (e) return e;
						console.warn("(ง'̀-'́)ง Heading Tool: the default level specified was not found in available levels")
					}
					return this.levels[1]
				}
			}, {
				key: "levels",
				get: function() {
					var t = this,
						e = [{
							number: 1,
							tag: "H1",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M19 17V10.2135C19 10.1287 18.9011 10.0824 18.836 10.1367L16 12.5"/></svg>'
						}, {
							number: 2,
							tag: "H2",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 11C16 10 19 9.5 19 12C19 13.9771 16.0684 13.9997 16.0012 16.8981C15.9999 16.9533 16.0448 17 16.1 17L19.3 17"/></svg>'
						}, {
							number: 3,
							tag: "H3",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 11C16 10.5 16.8323 10 17.6 10C18.3677 10 19.5 10.311 19.5 11.5C19.5 12.5315 18.7474 12.9022 18.548 12.9823C18.5378 12.9864 18.5395 13.0047 18.5503 13.0063C18.8115 13.0456 20 13.3065 20 14.8C20 16 19.5 17 17.8 17C17.8 17 16 17 16 16.3"/></svg>'
						}, {
							number: 4,
							tag: "H4",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18 10L15.2834 14.8511C15.246 14.9178 15.294 15 15.3704 15C16.8489 15 18.7561 15 20.2 15M19 17C19 15.7187 19 14.8813 19 13.6"/></svg>'
						}, {
							number: 5,
							tag: "H5",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 15.9C16 15.9 16.3768 17 17.8 17C19.5 17 20 15.6199 20 14.7C20 12.7323 17.6745 12.0486 16.1635 12.9894C16.094 13.0327 16 12.9846 16 12.9027V10.1C16 10.0448 16.0448 10 16.1 10H19.8"/></svg>'
						}, {
							number: 6,
							tag: "H6",
							svg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 7L6 12M6 17L6 12M6 12L12 12M12 7V12M12 17L12 12"/><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M19.5 10C16.5 10.5 16 13.3285 16 15M16 15V15C16 16.1046 16.8954 17 18 17H18.3246C19.3251 17 20.3191 16.3492 20.2522 15.3509C20.0612 12.4958 16 12.6611 16 15Z"/></svg>'
						}];
					return this._settings.levels ? e.filter((function(e) {
						return t._settings.levels.includes(e.number)
					})) : e
				}
			}]) && i(e.prototype, a), s && i(e, s), t
		}();
		t.exports = a
	}, function(t, e, n) {
		var r = n(2);
		"string" == typeof r && (r = [
			[t.i, r, ""]
		]);
		var i = {
			hmr: !0,
			transform: void 0,
			insertInto: void 0
		};
		n(4)(r, i);
		r.locals && (t.exports = r.locals)
	}, function(t, e, n) {
		(t.exports = n(3)(!1)).push([t.i, '/**\r\n * Plugin styles\r\n */\r\n.ce-header {\r\n  padding: 0.6em 0 3px;\r\n  margin: 0;\r\n  line-height: 1.25em;\r\n  outline: none;\r\n}\r\n\r\n.ce-header p,\r\n.ce-header div {\r\n  padding: 0 !important;\r\n  margin: 0 !important;\r\n}\r\n\r\n/**\r\n * Styles for Plugin icon in Toolbar\r\n */\r\n.ce-header__icon {\r\n}\r\n\r\n.ce-header[contentEditable="true"][data-placeholder]::before {\r\n  position: absolute;\r\n  content: attr(data-placeholder);\r\n  color: #707684;\r\n  font-weight: normal;\r\n  display: none;\r\n  cursor: text;\r\n}\r\n\r\n.ce-header[contentEditable="true"][data-placeholder]:empty::before {\r\n  display: block;\r\n}\r\n\r\n.ce-header[contentEditable="true"][data-placeholder]:empty:focus::before {\r\n  display: none;\r\n}\r\n\r\n/* Alignment*/\r\n.ce-header--right {\r\n  text-align: right;\r\n}\r\n.ce-header--center {\r\n  text-align: center;\r\n}\r\n.ce-header--left {\r\n  text-align: left;\r\n}\r\n.ce-header--justify {\r\n  text-align: justify;\r\n}\r\n', ""])
	}, function(t, e) {
		t.exports = function(t) {
			var e = [];
			return e.toString = function() {
				return this.map((function(e) {
					var n = function(t, e) {
						var n = t[1] || "",
							r = t[3];
						if (!r) return n;
						if (e && "function" == typeof btoa) {
							var i = (s = r, "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(s)))) + " */"),
								a = r.sources.map((function(t) {
									return "/*# sourceURL=" + r.sourceRoot + t + " */"
								}));
							return [n].concat(a).concat([i]).join("\n")
						}
						var s;
						return [n].join("\n")
					}(e, t);
					return e[2] ? "@media " + e[2] + "{" + n + "}" : n
				})).join("")
			}, e.i = function(t, n) {
				"string" == typeof t && (t = [
					[null, t, ""]
				]);
				for (var r = {}, i = 0; i < this.length; i++) {
					var a = this[i][0];
					"number" == typeof a && (r[a] = !0)
				}
				for (i = 0; i < t.length; i++) {
					var s = t[i];
					"number" == typeof s[0] && r[s[0]] || (n && !s[2] ? s[2] = n : n && (s[2] = "(" + s[2] + ") and (" + n + ")"), e.push(s))
				}
			}, e
		}
	}, function(t, e, n) {
		var r, i, a = {},
			s = (r = function() {
				return window && document && document.all && !window.atob
			}, function() {
				return void 0 === i && (i = r.apply(this, arguments)), i
			}),
			o = function(t) {
				return document.querySelector(t)
			},
			c = function(t) {
				var e = {};
				return function(t) {
					if ("function" == typeof t) return t();
					if (void 0 === e[t]) {
						var n = o.call(this, t);
						if (window.HTMLIFrameElement && n instanceof window.HTMLIFrameElement) try {
							n = n.contentDocument.head
						} catch (t) {
							n = null
						}
						e[t] = n
					}
					return e[t]
				}
			}(),
			l = null,
			u = 0,
			h = [],
			d = n(5);

		function f(t, e) {
			for (var n = 0; n < t.length; n++) {
				var r = t[n],
					i = a[r.id];
				if (i) {
					i.refs++;
					for (var s = 0; s < i.parts.length; s++) i.parts[s](r.parts[s]);
					for (; s < r.parts.length; s++) i.parts.push(b(r.parts[s], e))
				} else {
					var o = [];
					for (s = 0; s < r.parts.length; s++) o.push(b(r.parts[s], e));
					a[r.id] = {
						id: r.id,
						refs: 1,
						parts: o
					}
				}
			}
		}

		function p(t, e) {
			for (var n = [], r = {}, i = 0; i < t.length; i++) {
				var a = t[i],
					s = e.base ? a[0] + e.base : a[0],
					o = {
						css: a[1],
						media: a[2],
						sourceMap: a[3]
					};
				r[s] ? r[s].parts.push(o) : n.push(r[s] = {
					id: s,
					parts: [o]
				})
			}
			return n
		}

		function g(t, e) {
			var n = c(t.insertInto);
			if (!n) throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
			var r = h[h.length - 1];
			if ("top" === t.insertAt) r ? r.nextSibling ? n.insertBefore(e, r.nextSibling) : n.appendChild(e) : n.insertBefore(e, n.firstChild), h.push(e);
			else if ("bottom" === t.insertAt) n.appendChild(e);
			else {
				if ("object" != typeof t.insertAt || !t.insertAt.before) throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
				var i = c(t.insertInto + " " + t.insertAt.before);
				n.insertBefore(e, i)
			}
		}

		function v(t) {
			if (null === t.parentNode) return !1;
			t.parentNode.removeChild(t);
			var e = h.indexOf(t);
			e >= 0 && h.splice(e, 1)
		}

		function m(t) {
			var e = document.createElement("style");
			return void 0 === t.attrs.type && (t.attrs.type = "text/css"), y(e, t.attrs), g(t, e), e
		}

		function y(t, e) {
			Object.keys(e).forEach((function(n) {
				t.setAttribute(n, e[n])
			}))
		}

		function b(t, e) {
			var n, r, i, a;
			if (e.transform && t.css) {
				if (!(a = e.transform(t.css))) return function() {};
				t.css = a
			}
			if (e.singleton) {
				var s = u++;
				n = l || (l = m(e)), r = L.bind(null, n, s, !1), i = L.bind(null, n, s, !0)
			} else t.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa ? (n = function(t) {
				var e = document.createElement("link");
				return void 0 === t.attrs.type && (t.attrs.type = "text/css"), t.attrs.rel = "stylesheet", y(e, t.attrs), g(t, e), e
			}(e), r = _.bind(null, n, e), i = function() {
				v(n), n.href && URL.revokeObjectURL(n.href)
			}) : (n = m(e), r = S.bind(null, n), i = function() {
				v(n)
			});
			return r(t),
				function(e) {
					if (e) {
						if (e.css === t.css && e.media === t.media && e.sourceMap === t.sourceMap) return;
						r(t = e)
					} else i()
				}
		}
		t.exports = function(t, e) {
			if ("undefined" != typeof DEBUG && DEBUG && "object" != typeof document) throw new Error("The style-loader cannot be used in a non-browser environment");
			(e = e || {}).attrs = "object" == typeof e.attrs ? e.attrs : {}, e.singleton || "boolean" == typeof e.singleton || (e.singleton = s()), e.insertInto || (e.insertInto = "head"), e.insertAt || (e.insertAt = "bottom");
			var n = p(t, e);
			return f(n, e),
				function(t) {
					for (var r = [], i = 0; i < n.length; i++) {
						var s = n[i];
						(o = a[s.id]).refs--, r.push(o)
					}
					t && f(p(t, e), e);
					for (i = 0; i < r.length; i++) {
						var o;
						if (0 === (o = r[i]).refs) {
							for (var c = 0; c < o.parts.length; c++) o.parts[c]();
							delete a[o.id]
						}
					}
				}
		};
		var w, x = (w = [], function(t, e) {
			return w[t] = e, w.filter(Boolean).join("\n")
		});

		function L(t, e, n, r) {
			var i = n ? "" : r.css;
			if (t.styleSheet) t.styleSheet.cssText = x(e, i);
			else {
				var a = document.createTextNode(i),
					s = t.childNodes;
				s[e] && t.removeChild(s[e]), s.length ? t.insertBefore(a, s[e]) : t.appendChild(a)
			}
		}

		function S(t, e) {
			var n = e.css,
				r = e.media;
			if (r && t.setAttribute("media", r), t.styleSheet) t.styleSheet.cssText = n;
			else {
				for (; t.firstChild;) t.removeChild(t.firstChild);
				t.appendChild(document.createTextNode(n))
			}
		}

		function _(t, e, n) {
			var r = n.css,
				i = n.sourceMap,
				a = void 0 === e.convertToAbsoluteUrls && i;
			(e.convertToAbsoluteUrls || a) && (r = d(r)), i && (r += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(i)))) + " */");
			var s = new Blob([r], {
					type: "text/css"
				}),
				o = t.href;
			t.href = URL.createObjectURL(s), o && URL.revokeObjectURL(o)
		}
	}, function(t, e) {
		t.exports = function(t) {
			var e = "undefined" != typeof window && window.location;
			if (!e) throw new Error("fixUrls requires window.location");
			if (!t || "string" != typeof t) return t;
			var n = e.protocol + "//" + e.host,
				r = n + e.pathname.replace(/\/[^\/]*$/, "/");
			return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, (function(t, e) {
				var i, a = e.trim().replace(/^"(.*)"$/, (function(t, e) {
					return e
				})).replace(/^'(.*)'$/, (function(t, e) {
					return e
				}));
				return /^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(a) ? t : (i = 0 === a.indexOf("//") ? a : 0 === a.indexOf("/") ? n + a : r + a.replace(/^\.\//, ""), "url(" + JSON.stringify(i) + ")")
			}))
		}
	}, function(t, e, n) {
		"use strict";
		n.r(e), e.default = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 7L9 12M9 17V12M9 12L15 12M15 7V12M15 17L15 12"/>\r\n</svg>\r\n'	
	}])
}));