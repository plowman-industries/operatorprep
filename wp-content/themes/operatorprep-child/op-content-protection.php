<?php
/**
 * OperatorPrep Content Protection
 * Disables copy/right-click on course, lesson, and quiz pages for logged-in users.
 *
 * @package operatorprep-child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output CSS to disable text selection on course/lesson/quiz pages (logged-in users only).
 */
function op_content_protection_css() {
    if ( ! is_user_logged_in() ) {
        return;
    }
    if ( ! ( is_singular( 'courses' ) || is_singular( 'lesson' ) || is_singular( 'tutor_quiz' ) ) ) {
        return;
    }
    ?>
    <style id="op-content-protection-css">
        .tutor-course-content-wrap,
        .tutor-single-lesson-wrap,
        .entry-content,
        .op-question-card,
        .op-explanation {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        .tutor-course-content-wrap input,
        .tutor-course-content-wrap textarea,
        .tutor-single-lesson-wrap input,
        .tutor-single-lesson-wrap textarea,
        .entry-content input,
        .entry-content textarea,
        .op-question-card input,
        .op-question-card textarea,
        .op-explanation input,
        .op-explanation textarea {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'op_content_protection_css', 99 );

/**
 * Output JS to block right-click, copy, keyboard shortcuts, and image drag
 * on course/lesson/quiz/page content for logged-in users.
 */
function op_content_protection_js() {
    if ( ! is_user_logged_in() ) {
        return;
    }
    if ( ! ( is_singular( 'courses' ) || is_singular( 'lesson' ) || is_singular( 'tutor_quiz' ) || is_page() ) ) {
        return;
    }
    ?>
    <script id="op-content-protection-js">
    (function() {
        'use strict';

        var protectedSelectors = [
            '.tutor-course-content-wrap',
            '.tutor-single-lesson-wrap',
            '.entry-content',
            '.op-question-card',
            '.op-explanation'
        ];

        function isInProtectedArea(el) {
            for (var i = 0; i < protectedSelectors.length; i++) {
                if (el.closest && el.closest(protectedSelectors[i])) {
                    return true;
                }
            }
            return false;
        }

        function isInputField(el) {
            var tag = el.tagName ? el.tagName.toLowerCase() : '';
            return tag === 'input' || tag === 'textarea';
        }

        // Block right-click context menu
        document.addEventListener('contextmenu', function(e) {
            if (isInProtectedArea(e.target) && !isInputField(e.target)) {
                e.preventDefault();
                return false;
            }
        }, true);

        // Block copy, cut, paste
        ['copy', 'cut', 'paste'].forEach(function(evt) {
            document.addEventListener(evt, function(e) {
                if (isInProtectedArea(e.target) && !isInputField(e.target)) {
                    e.preventDefault();
                    return false;
                }
            }, true);
        });

        // Block keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (!isInProtectedArea(e.target) || isInputField(e.target)) {
                return;
            }

            var key = e.key ? e.key.toLowerCase() : '';
            var code = e.code ? e.code : '';

            // F12
            if (code === 'F12') {
                e.preventDefault();
                return false;
            }

            if (e.ctrlKey || e.metaKey) {
                // Ctrl+C, Ctrl+A, Ctrl+X, Ctrl+S, Ctrl+U
                if (['c', 'a', 'x', 's', 'u'].indexOf(key) !== -1) {
                    e.preventDefault();
                    return false;
                }
                // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
                if (e.shiftKey && ['i', 'j', 'c'].indexOf(key) !== -1) {
                    e.preventDefault();
                    return false;
                }
            }
        }, true);

        // Block image drag
        document.addEventListener('dragstart', function(e) {
            if (e.target.tagName && e.target.tagName.toLowerCase() === 'img') {
                if (isInProtectedArea(e.target)) {
                    e.preventDefault();
                    return false;
                }
            }
        }, true);

    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'op_content_protection_js', 99 );
