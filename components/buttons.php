<?php
/**
 * Cafe Espresso - Reusable Button Utilities
 * Maps CSS class definitions for rapid storefront actions.
 */

function primaryButton($text, $link = '#', $attrs = '') {
    return "<a href='{$link}' class='btn btn-primary' {$attrs}>{$text}</a>";
}

function secondaryButton($text, $link = '#', $attrs = '') {
    return "<a href='{$link}' class='btn btn-secondary' {$attrs}>{$text}</a>";
}

function outlineButton($text, $link = '#', $attrs = '') {
    return "<a href='{$link}' class='btn btn-outline' {$attrs}>{$text}</a>";
}
?>
