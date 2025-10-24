<?php
if (!isset($_SESSION)) { session_start(); }
if (empty($_SESSION['lang'])) { $_SESSION['lang'] = 'fr'; }
function current_lang(){ return $_SESSION['lang'] ?? 'fr'; }
function set_lang($l){ $_SESSION['lang'] = ($l === 'mg') ? 'mg' : 'fr'; }
function t($key, $fallback = null){
    static $cache = [];
    $lang = current_lang();
    if (!isset($cache[$lang])) {
        $file = __DIR__ . '/../lang/' . $lang . '.php';
        $cache[$lang] = file_exists($file) ? (require $file) : [];
    }
    $dict = $cache[$lang];
    if (isset($dict[$key])) return $dict[$key];
    return $fallback !== null ? $fallback : $key;
}
