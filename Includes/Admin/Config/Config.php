<?php

namespace KirilKirkov\GoogleAnalytics;

class Config
{
    const SETTINGS_GET_PARAM = 'kirilkirkov-google-analytics-settings';
    const INPUTS_PREFIX = 'kkga_'; // kkga -> kirilkirkov google analytics
    const SCRIPTS_PREFIX = self::INPUTS_PREFIX;

    const MAIN_UPDATE_OPTIONS = 'kkga-main-update-options';

    // used input fields with group type
    const GROUPS_INPUT_FIELDS = [
        self::MAIN_UPDATE_OPTIONS => [
            'google_analytics_code',
            'exclude_pages',
            'load_html_part',
            'disabled_ips',
            'track_roles',
        ],
    ];

    public static function get_groups_input_fieds()
    {
        return self::GROUPS_INPUT_FIELDS;
    }
}