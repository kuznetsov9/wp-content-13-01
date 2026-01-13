// Render JSON to HTML

// Простая, но эффективная функция экранирования
function escapeHTML(str) {
    if (typeof str !== 'string') return '';
    return str.replace(/[&<>'"]/g, 
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag]));
}

var edjsHTML = function() {
    "use strict";
    var t = {
		
         delimiter: function() {
             return "<hr />";
          },
		  
         header: function(t) {
            var e = t.data;
            var alignmentClass = e.alignment ? 'cdx-header-' + e.alignment : 'cdx-header';
            return "<h" + e.level + " class=" + alignmentClass + ">" + e.text + "</h" + e.level + ">";
          },
		  
         paragraph: function(t) {
            var e = t.data;
            var alignmentClass = e.alignment ? 'cdx-paragraph-' + e.alignment : 'cdx-paragraph';

            return '<p class="' + alignmentClass + '">' + e.text + '</p>';
          },
		  
        list: function(t) {
            var e = t.data,
                n = "unordered" === e.style ? "ul" : "ol",
                r = "";
            if (e.items) {
                r = e.items.map(function(t) {
                    return "<li> " + t + " </li>";
                }).reduce(function(t, e) {
                    return t + e;
                }, "");
            }
            return "<" + n + "> " + r + " </" + n + ">";
         },
		 
        image: function(t) {
             var e = t.data;
             var classes = [];

            if (e.stretched === true) {
               classes.push('esn-image--stretched');
            }
            if (e.withBorder === true) {
               classes.push('esn-image--bordered');
            }
            if (e.withBackground === true) {
               classes.push('esn-image--backgrounded');
            }

           var divClasses = classes.join(' ');

var safeUrl = e.file.url.replace(/"/g, '&quot;'); 
           var safeCaption = e.caption ? e.caption.replace(/"/g, '&quot;') : ''; // Экранируем кавычки для атрибута alt

           var imgHTML = '<figure class="wp-block-image ' + divClasses + ' size-large">';
                  imgHTML += '<img src="' + safeUrl + '" alt="' + safeCaption + '" class="esn_tool__image"/>';
                  imgHTML += '</figure>';

           if (e.caption && e.caption.trim() !== '') {
               imgHTML += '<figcaption>' + e.caption + '</figcaption>';
           }
                  
              return imgHTML;
          },
		  
        quote: function(t) {
            var e = t.data;
            var alignmentClass = e.alignment ? 'cdx-quote-' + e.alignment : '';

            var quoteHtml = e.text ? '<div class="cdx-quote-text">' + e.text + '</div>' : '';
            var captionHtml = e.caption ? '<div class="cdx-quote-caption">' + e.caption + '</div>' : '';

            var quoteBlock = "<blockquote class=\"wp-block-quote cdx-quote " + alignmentClass + "\">" + quoteHtml + captionHtml + "</blockquote>";

            return (quoteHtml || captionHtml) ? quoteBlock : '';
          },
		  
        video: function(t) {
            var e = t.data,
                i = '<video controls src="' + (e.file ? e.file.url : "") + '" alt="' + e.caption + '"></video>';
            if (e.caption) {
                i += '<cite class="module-video-caption">' + e.caption + "</cite>";
            }
            return i;
          },
		  
	    linkButton: function(e) {
           var s = e.data;
           var colorClass = '';

           switch(s.color) {
              case 'green':
                colorClass = 'link-button-green';
              break;
              case 'blue':
                colorClass = 'link-button-blue';
              break;
              case 'red':
                colorClass = 'link-button-brown';
              break;
           }
          return `<a href="${s.url}" class="esn-link-button ${colorClass}" target="_blank" rel="nofollow noindex noreferrer">${s.caption}</a>`;
          },
		  
telegram: function(t) {
    var e = t.data;
    var id = "tg-" + Math.floor(Math.random() * 100000);
    // МЫ ВООБЩЕ УБРАЛИ HEIGHT ИЗ STYLE
    return '<figure class="editor-telegram"><iframe id="' + id + '" src="' + e.url + '?embed=1" width="100%" frameborder="0" scrolling="no" class="telegram-iframe" style="overflow:hidden; border:none; width:100% !important;"></iframe></figure>';
},
		  
	   tiktok: function(t) {
            var e = t.data;
            var videoId = e.url.match(/\/video\/(\d+)/)[1];
            return `
                <div class="editor-tiktok">
                    <blockquote class="tiktok-embed" cite="${e.url}" data-video-id="${videoId}" data-embed-from="embed_page" style="width:325px;">
                        <section>
                            <a target="_blank" title="@${e.username}" href="https://www.tiktok.com/@${e.username}?refer=embed">@${e.username}</a>
                            <p>${e.caption || ''}</p>
                            <a target="_blank" title="${e.sound_name}" href="${e.sound_url}?refer=embed">${e.sound_name}</a>
                        </section>
                    </blockquote>
                    <script async src="https://www.tiktok.com/embed.js"></script>
                </div>`;
        }, 
		  
       linkTool: function(e) {
           var s = e.data;
           var p = s.link || "";

           var domain = p.replace(/^(https?:\/\/)?(www\.)?/, '').split('/')[0];
           var v = [];
           var html = '<div class="esn-link-tool" data-empty="false" data-title="' + s.meta.title + '" data-description="' + s.meta.description + '" data-image="' + s.meta.image.url + '"><a class="link-tool__content link-tool__content--rendered" target="_blank" rel="nofollow noindex noreferrer" href="' + p + '">';

           if (s.meta && s.meta.image && s.meta.image.url) {
             v.push('<div class="link-tool__image" style="background-image: url(' + s.meta.image.url + ');"></div>');
            }
           if (s.meta && s.meta.title) {
             v.push('<div class="link-tool__title">' + s.meta.title + '</div>');
            }
           if (s.meta && s.meta.description) {
             v.push('<p class="link-tool__description">' + s.meta.description + '</p>');
            }
           v.push('<span class="link-tool__anchor">' + domain + '</span>'); 
              html += v.join('') + '</a></div>';
           return html;
          },
		  
       alert: function(t) {
           var e = t.data;    
           var alertTypeClass = 'cdx-alert-' + e.type;
           var alignClass = 'cdx-alert-align-' + (e.align || 'left');

           var titleHtml = e.title ? `<div class="cdx-alert__title">${e.title}</div>` : '';
           var messageHtml = e.message ? `<div class="cdx-alert__message">${e.message}</div>` : '';

           var alertHtml = `
             <div class="cdx-alert ${alertTypeClass} ${alignClass}">
               ${titleHtml}
               ${messageHtml}
             </div>
           `;
          return alertHtml;
          },	
		  
		embed: function(t) {
            var e = t.data;
			var width = e.width ? e.width : '100%';
            var height = e.height ? e.height : '450';
            var i = '<figure class="cdx-embed"><iframe width="' + width + '" height="' + height + '" src="' + e.embed + 
                '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            if (e.caption && e.caption.trim() !== '') {
               return i + '<span class="cdx-caption">' + e.caption + '</span></figure>';
             }
            return i + '</figure>';
             }
			 
			 
          };

    function e(t) {
        return new Error(' Плагин "' + t + '" не определён. ');
    }

    return function(n) {
        return void 0 === n && (n = {}), Object.assign(t, n), {
            parse: function(n) {
                return n.blocks.map(function(n) {
                    return t[n.type] ? t[n.type](n) : e(n.type);
                });
            },
            parseBlock: function(n) {
                return t[n.type] ? t[n.type](n) : e(n.type);
            }
        };
    };
}();
