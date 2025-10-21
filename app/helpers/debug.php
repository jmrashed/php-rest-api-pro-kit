<?php

use App\DebugBar\DebugBar;

if (!function_exists('debug')) {
    function debug($message, $level = 'info') {
        $debugBar = DebugBar::getInstance();
        if ($debugBar->isEnabled()) {
            $messageCollector = $debugBar->getCollector('messages');
            if ($messageCollector) {
                $messageCollector->addMessage($message, $level);
            }
        }
    }
}

if (!function_exists('timer_start')) {
    function timer_start($name) {
        $debugBar = DebugBar::getInstance();
        if ($debugBar->isEnabled()) {
            $timerCollector = $debugBar->getCollector('timers');
            if ($timerCollector) {
                $timerCollector->start($name);
            }
        }
    }
}

if (!function_exists('timer_stop')) {
    function timer_stop($name) {
        $debugBar = DebugBar::getInstance();
        if ($debugBar->isEnabled()) {
            $timerCollector = $debugBar->getCollector('timers');
            if ($timerCollector) {
                $timerCollector->stop($name);
            }
        }
    }
}