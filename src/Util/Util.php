<?php
function hasKeys($arr) {
    if(is_array($arr)) {
        if(count(array_filter(array_keys($arr), 'is_string')) > 0) {
            return true;
        }
    }
    return false;
}