/** ----------------------------------------------------------------------------
 * Comment Reply */

/* jshint ignore:start */

/**
 * Handles the addition of the comment form.
 *
 * @since 2.7.0
 * @output wp-includes/js/comment-reply.js
 *
 * @namespace addComment
 *
 * @type {Object}
 */
window.addComment = (function (window) {
    var document = window.document;

    var config = {
        commentReplyClass: 'comment-reply-link',
        commentReplyTitleId: 'reply-title',
        cancelReplyId: 'cancel-comment-reply-link',
        commentFormId: 'commentform',
        temporaryFormClass: 'wp-temp-form-div',
        parentIdFieldId: 'comment_parent',
        postIdFieldId: 'comment_post_ID',
        commentsWrap: 'comments',
    };

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
    var cutsTheMustard = 'querySelector' in document && 'addEventListener' in window;
    var supportsDataset = !!document.documentElement.dataset;

    var cancelElement;
    var commentFormElement;
    var respondElement;
    var observer;

    if (cutsTheMustard && document.readyState !== 'loading') {
        ready();
    } else if (cutsTheMustard) {
        window.addEventListener('DOMContentLoaded', ready, false);
    }

    function ready() {
        init();
        observeChanges();
    }

    function init(context) {
        if (!cutsTheMustard) {
            return;
        }

        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1,
        };

        let currentPost = null;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const post = entry.target;

                    if (currentPost !== post) {
                        currentPost = post;

                        cancelElement = post.querySelector(`#${config.cancelReplyId}`);
                        commentFormElement = post.querySelector(`#${config.commentFormId}`);

                        if (cancelElement) {
                            cancelElement.addEventListener('click', cancelEvent);
                        }

                        if (commentFormElement) {
                            const submitFormHandler = function (e) {
                                if ((e.metaKey || e.ctrlKey) && e.keyCode === 13) {
                                    e.preventDefault();
                                    commentFormElement.submit();
                                    return false;
                                }
                            };

                            commentFormElement.addEventListener('keydown', submitFormHandler);
                        }

                        updateCancelReplyLink(post);

                        const links = replyLinks(post);
                        if (links) {
                            Array.from(links).forEach((link) => {
                                link.addEventListener('click', clickEvent);
                            });
                        }
                    }
                }
            });
        }, observerOptions);

        const posts = document.querySelectorAll('#' + config.commentsWrap);
        posts.forEach((post) => {
            observer.observe(post);
        });
    }

    function replyLinks(context) {
        var selectorClass = config.commentReplyClass;
        var allReplyLinks;

        if (!context || !context.childNodes) {
            context = document;
        }

        if (document.getElementsByClassName) {
            allReplyLinks = context.getElementsByClassName(selectorClass);
        } else {
            allReplyLinks = context.querySelectorAll('.' + selectorClass);
        }

        return allReplyLinks;
    }

    function updateCancelReplyLink(postElement) {
        const cancelLink = postElement.querySelector(`#${config.cancelReplyId}`);
        const postIdField = postElement.querySelector(`#${config.postIdFieldId}`);
        const postId = postIdField ? postIdField.value : null;

        if (cancelLink && postId) {
            const currentPostUrl = `${window.location.origin}${window.location.pathname}#respond-${postId}`;
            cancelLink.setAttribute('href', currentPostUrl);
        }
    }

    function cancelEvent(event) {
        var cancelLink = this;
        var commentsWrapElement = event.target.closest('#' + config.commentsWrap);
        var temporaryElement = commentsWrapElement.querySelector('.' + config.temporaryFormClass);

        if (!temporaryElement || !respondElement) {
            return;
        }

        commentsWrapElement.querySelector('#' + config.parentIdFieldId).value = 0;

        var headingText = temporaryElement.textContent;
        temporaryElement.parentNode.replaceChild(respondElement, temporaryElement);
        cancelLink.style.display = 'none';

        var replyHeadingElement = commentsWrapElement.querySelector('#' + config.commentReplyTitleId);
        var replyHeadingTextNode = replyHeadingElement && replyHeadingElement.firstChild;
        var replyLinkToParent = replyHeadingTextNode && replyHeadingTextNode.nextSibling;

        if (replyHeadingTextNode && replyHeadingTextNode.nodeType === Node.TEXT_NODE && headingText) {
            if (replyLinkToParent && 'A' === replyLinkToParent.nodeName && replyLinkToParent.id !== config.cancelReplyId) {
                replyLinkToParent.style.display = '';
            }

            replyHeadingTextNode.textContent = headingText;
        }

        event.preventDefault();
    }

    function clickEvent(event) {
        var commentsWrapElement = event.target.closest('#' + config.commentsWrap);
        var replyNode = commentsWrapElement.querySelector('#' + config.commentReplyTitleId);
        var defaultReplyHeading = replyNode && replyNode.firstChild.textContent;
        var replyLink = this,
            commId = getDataAttribute(replyLink, 'belowelement'),
            parentId = getDataAttribute(replyLink, 'commentid'),
            respondId = getDataAttribute(replyLink, 'respondelement'),
            postId = getDataAttribute(replyLink, 'postid'),
            replyTo = getDataAttribute(replyLink, 'replyto') || defaultReplyHeading,
            follow;

        if (!commId || !parentId || !respondId || !postId) {
            return;
        }

        follow = window.addComment.moveForm(commId, parentId, respondId, postId, replyTo, event);
        if (false === follow) {
            event.preventDefault();
        }
    }

    function observeChanges() {
        if (!MutationObserver) {
            return;
        }

        var observerOptions = {
            childList: true,
            subtree: true
        };

        observer = new MutationObserver(handleChanges);
        observer.observe(document.body, observerOptions);
    }

    function handleChanges(mutationRecords) {
        var i = mutationRecords.length;

        while (i--) {
            if (mutationRecords[i].addedNodes.length) {
                init();
                return;
            }
        }
    }

    function getDataAttribute(element, attribute) {
        if (supportsDataset) {
            return element.dataset[attribute];
        } else {
            return element.getAttribute('data-' + attribute);
        }
    }

    function getElementById(elementId) {
        return document.getElementById(elementId);
    }

    function moveForm(addBelowId, commentId, respondId, postId, replyTo, event) {
        var commentsWrapElement = event.target.closest('#' + config.commentsWrap);

        var addBelowElement = commentsWrapElement.querySelector('#' + addBelowId);
        respondElement = commentsWrapElement.querySelector('#' + respondId);

        var parentIdField = commentsWrapElement.querySelector('#' + config.parentIdFieldId);
        var postIdField = commentsWrapElement.querySelector('#' + config.postIdFieldId);
        var element, cssHidden, style;

        var replyHeading = commentsWrapElement.querySelector('#' + config.commentReplyTitleId);
        var replyHeadingTextNode = replyHeading && replyHeading.firstChild;
        var replyLinkToParent = replyHeadingTextNode && replyHeadingTextNode.nextSibling;

        // Check if essential elements are present
        if (!addBelowElement || !respondElement || !parentIdField) {
            console.error("Missing required elements for moveForm");
            return; // Prevent moving the form if elements are not present
        }

        if ('undefined' === typeof replyTo) {
            replyTo = replyHeadingTextNode && replyHeadingTextNode.textContent;
        }

        addPlaceHolder(respondElement, event);

        if (postId && postIdField) {
            postIdField.value = postId;
        }

        parentIdField.value = commentId;
        cancelElement.style.display = '';

        // Ensure that the parent element exists before attempting to insert
        if (addBelowElement.querySelector('.comment-body')) {
            addBelowElement.querySelector('.comment-body').insertBefore(respondElement, null);
        } else {
            console.error("The target element for inserting the comment form was not found.");
        }

        if (replyHeadingTextNode && replyHeadingTextNode.nodeType === Node.TEXT_NODE) {
            if (replyLinkToParent && 'A' === replyLinkToParent.nodeName && replyLinkToParent.id !== config.cancelReplyId) {
                replyLinkToParent.style.display = 'none';
            }

            replyHeadingTextNode.textContent = replyTo;
        }

        cancelElement.onclick = function () {
            return false;
        };

        try {
            for (var i = 0; i < commentFormElement.elements.length; i++) {
                element = commentFormElement.elements[i];
                cssHidden = false;

                if ('getComputedStyle' in window) {
                    style = window.getComputedStyle(element);
                } else if (document.documentElement.currentStyle) {
                    style = element.currentStyle;
                }

                if ((element.offsetWidth <= 0 && element.offsetHeight <= 0) || style.visibility === 'hidden') {
                    cssHidden = true;
                }

                if ('hidden' === element.type || element.disabled || cssHidden) {
                    continue;
                }

                element.focus();
                break;
            }
        } catch (e) {
            console.error("Error focusing on comment form: ", e);
        }

        return false;
    }

    function addPlaceHolder(respondElement, event) {
        var commentsWrapElement = event.target.closest('#' + config.commentsWrap);
        var temporaryElement = commentsWrapElement.querySelector('.' + config.temporaryFormClass);
        var replyElement = respondElement.querySelector('#' + config.commentReplyTitleId);
        var initialHeadingText = replyElement ? replyElement.firstChild.textContent : '';

        if (temporaryElement) {
            return;
        }

        temporaryElement = document.createElement('div');
        temporaryElement.classList.add(config.temporaryFormClass);
        temporaryElement.style.display = 'none';
        temporaryElement.textContent = initialHeadingText;

        respondElement.parentNode.insertBefore(temporaryElement, respondElement);
    }

    return {
        init: init,
        moveForm: moveForm
    };

})(window);