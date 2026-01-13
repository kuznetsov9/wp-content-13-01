// 1. Функция-очистки: оставляет только текст и базовое оформление для ИНЛАЙНОВ.
function cleanContent(element) {
    const allowedTags = ['B', 'STRONG', 'I', 'EM', 'U', 'A', 'BR'];
    let result = '';

    element.childNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) {
            result += node.textContent;
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            // Если это картинка внутри текста (бывает в WP), мы её тут игнорим, 
            // чтобы она не превратилась в текст. Она должна обрабатываться как отдельный блок.
            if (node.tagName === 'IMG') return; 

            if (allowedTags.includes(node.tagName)) {
                let attrs = '';
                if (node.tagName === 'A') {
                    const href = node.getAttribute('href') || '#';
                    attrs = ` href="${href}" target="_blank" rel="nofollow"`;
                }
                result += `<${node.tagName.toLowerCase()}${attrs}>${cleanContent(node)}</${node.tagName.toLowerCase()}>`;
            } else {
                // Если тег неизвестный, просто рекурсивно забираем из него текст, не экранируя в кашу
                result += cleanContent(node);
            }
        }
    });
    return result;
}

let stringToBlocks = function(post) {
    // 1. Санитизация (оставляем как было)
    if (typeof DOMPurify !== 'undefined') {
        post = DOMPurify.sanitize(post, {
            ADD_TAGS: ['iframe', 'figure', 'figcaption', 'div', 'video', 'style'], 
            ADD_ATTR: ['allow', 'allowfullscreen', 'frameborder', 'scrolling', 'target', 'data-placeholder', 'class', 'style', 'src', 'href', 'width', 'height', 'alt', 'cite'],
            FORBID_TAGS: ['script'],
            FORBID_ATTR: ['onerror', 'onload', 'onclick']
        });
    }

    // 2. Создаем временный контейнер для парсинга
    var l = document.createElement("div");
    l.innerHTML = post; 
    let htmlData = l.children;
    let jData = { blocks: [] };

    // 3. Цикл по элементам
    for (let i = 0; i < htmlData.length; i++) {
        let node = htmlData[i];
        let tagName = node.tagName;

        // ВАЖНО: Мы убрали отсюда if (tagName === 'FIGURE'), так как теперь это делает tagsToObj
        // Это и устраняет ошибку "Illegal continue"

        if (tagsToObj[tagName]) {
            let block = tagsToObj[tagName](node);
            
            // Если парсер вернул блок (не null) — добавляем
            if (block) { 
                jData.blocks.push(block);
            }
        }
    } 

    return jData;
}

function getAlignment(obj) {
    if (obj.classList.contains('cdx-header-center') || obj.classList.contains('cdx-paragraph-center')) return 'center';
    if (obj.classList.contains('cdx-header-right') || obj.classList.contains('cdx-paragraph-right')) return 'right';
    if (obj.classList.contains('cdx-header-justify') || obj.classList.contains('cdx-paragraph-justify')) return 'justify';
    return 'left';
}

let tagsToObj = {
    H1: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 1, alignment: getAlignment(obj) } }; },
    H2: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 2, alignment: getAlignment(obj) } }; },
    H3: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 3, alignment: getAlignment(obj) } }; },
    H4: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 4, alignment: getAlignment(obj) } }; },
    H5: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 5, alignment: getAlignment(obj) } }; },
    H6: function(obj) { return { type: "header", data: { text: cleanContent(obj), level: 6, alignment: getAlignment(obj) } }; },
    
    P: function(obj) { 
        return { type: "paragraph", data: { text: cleanContent(obj), alignment: getAlignment(obj) } }; 
    },

    IMG: function(obj) {
        return {
            type: "image",
            data: {
                file: { url: obj.src },
                caption: obj.alt || '',
                withBorder: false, withBackground: false, stretched: false
            }
        };
    },

    BLOCKQUOTE: function(obj) {
        const textEl = obj.querySelector('.cdx-quote-text');
        const capEl = obj.querySelector('.cdx-quote-caption');
        return {
            type: "quote",
            data: {
                text: textEl ? cleanContent(textEl) : cleanContent(obj),
                caption: capEl ? cleanContent(capEl) : '',
                alignment: obj.className.includes('cdx-quote-center') ? 'center' : 'left'
            }
        };
    },
    
    // ВАЖНО: Новая логика для FIGURE, которая теперь вызывается из цикла
    FIGURE: function(obj) {
        // 1. Картинка
        const img = obj.querySelector('img');
        if (img) {
            const cap = obj.querySelector('figcaption');
            return {
                type: "image",
                data: {
                    file: { url: img.src },
                    caption: cap ? cap.innerHTML : (img.alt || ''),
                    withBorder: false, withBackground: false, stretched: false
                }
            };
        }

        // 2. Телеграм
        const telegramIframe = obj.querySelector('iframe[src*="t.me"]');
        if (telegramIframe) {
            return {
                type: 'telegram',
                data: {
                    url: telegramIframe.src.split('?')[0], 
                }
            };
        }

        // 3. Другие Iframe внутри figure (например YouTube)
        const anyIframe = obj.querySelector('iframe');
        if (anyIframe) {
            return tagsToObj.IFRAME(anyIframe); 
        }

        return null;
    },

    UL: function(obj) {
        return { type: "list", data: { style: "unordered", items: Array.from(obj.children).map(li => cleanContent(li)) } };
    },
    OL: function(obj) {
        return { type: "list", data: { style: "ordered", items: Array.from(obj.children).map(li => cleanContent(li)) } };
    },

    VIDEO: function(obj) {
        return {
            type: 'video',
            data: {
                file: { url: `${obj.src}` },
                caption: `${obj.innerHTML}`,
            }
        };
    },

    DIV: function(obj) {
       let url = obj.src || obj.querySelector('blockquote')?.getAttribute('cite') || '';
            
       if (obj.classList.contains('cdx-alert')) {
          const messageElement = obj.querySelector('.cdx-alert__message');
          const titleElement = obj.querySelector('.cdx-alert__title');
          const type = Array.from(obj.classList).find(cls => cls.startsWith('cdx-alert-')).replace('cdx-alert-', '');
          const alignment = Array.from(obj.classList).find(cls => cls.startsWith('cdx-alert-align-')).replace('cdx-alert-align-', '') || 'left';

          return {
            type: 'alert',
            data: {
               type: type,
               align: alignment,
               title: titleElement ? titleElement.innerHTML : '', 
               message: messageElement ? messageElement.innerHTML : '',
            }
          };
        } 
        
        if (typeof url === 'string' && url.includes('tiktok.com')) {
           return {
               type: 'tiktok',
               data: { url: `${url}` },
           };
        }

        // Link Tool
        const link = obj.querySelector('.link-tool__content');
        if (link) {
            const image = obj.querySelector('.link-tool__image');
            const title = obj.querySelector('.link-tool__title');
            const description = obj.querySelector('.link-tool__description');
            
            return {
                type: 'linkTool',
                data: {
                    link: link.href,
                    meta: {
                        title: title ? title.innerHTML : '',
                        description: description ? description.innerHTML : '',
                        image: {
                            url: image ? image.style.backgroundImage.replace('url("', '').replace('")', '') : ''
                        }
                    } 
                }
            };
        }
        return null;
    },
    
    // ИСПРАВЛЕННАЯ ЛОГИКА IFRAME
    IFRAME: function(obj) {
        // Телеграм (на случай если он без figure)
        if (obj.src.includes('t.me')) {
            return {
                type: 'telegram',
                data: { url: `${obj.src}` }
            };
        } 
        
        let src = obj.src || '';
        let service = 'youtube'; // дефолт

        // Нормальная проверка сервисов
        if (src.includes('youtube.com') || src.includes('youtu.be')) service = 'youtube';
        else if (src.includes('vk.com')) service = 'vk';
        else if (src.includes('vimeo.com')) service = 'vimeo';
        else if (src.includes('coub.com')) service = 'coub';
        else if (src.includes('imgur.com')) service = 'imgur';
        else if (src.includes('codepen.io')) service = 'codepen';
        else if (src.includes('instagram.com')) service = 'instagram';
        else if (src.includes('twitter.com')) service = 'twitter';
        else if (src.includes('pinterest.com')) service = 'pinterest';
        else if (src.includes('facebook.com')) service = 'facebook';
        else if (src.includes('github.com')) service = 'github';        
        
        return {
            type: 'embed',
            data: {
                service: service,
                embed: src,
                caption: obj.nextElementSibling && obj.nextElementSibling.innerHTML ? obj.nextElementSibling.innerHTML : '',
                width: obj.width || "100%",
                height: obj.height || "450"
            }
        };        
    },

    A: function(obj) {
        let colorClass = "";
        if (obj.classList.contains('link-button-green')) colorClass = "green";
        else if (obj.classList.contains('link-button-blue')) colorClass = "blue";
        else if (obj.classList.contains('link-button-brown')) colorClass = "brown";

        let linkHref = obj.href;
        if (!linkHref.startsWith('http')) linkHref = 'http://' + linkHref;

        return {
            type: 'linkButton',
            data: {
                url: linkHref,
                caption: obj.innerHTML,
                color: colorClass
            }
        };
    },
}