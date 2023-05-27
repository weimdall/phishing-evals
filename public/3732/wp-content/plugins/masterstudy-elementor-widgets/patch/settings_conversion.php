<?php

class CEW_Patch_Widget_Settings
{


    static function section($settings, $map = array())
    {

        $settings = (!empty($settings)) ? $settings : array();

        $settings = CEW_Patch_Widget_Settings_Parser::parser($settings, $map);

        if (!empty($settings['full_width']) && ($settings['full_width'] === 'stretch_row_content_no_spaces')) {
            $settings['layout'] = 'full_width';
            $settings['stretch_section'] = 'section-stretched';
            $settings['gap'] = 'no';
        }

        if (!empty($settings['el_class'])) $settings['css_classes'] = $settings['el_class'];

        CEW_Patch_Widget_Settings_Parser::remove_css_prefix($settings);

        if (!empty($settings['video_bg']) and $settings['video_bg'] === 'yes') {
            $video_url = (!empty($settings['video_bg_url'])) ? $settings['video_bg_url'] : 'https://www.youtube.com/watch?v=L14nXRxJILg';

            $settings['background_background'] = 'video';
            $settings['background_video_link'] = $video_url;

        }

        if(!empty($settings['parallax_image'])) {
            $settings['background_image'] = $settings['parallax_image'];
            $settings['background_attachment'] = 'fixed';
        }

        return $settings;
    }

    static function column($settings, $map = array())
    {
        $settings = (!empty($settings)) ? $settings : array();

        $settings = CEW_Patch_Widget_Settings_Parser::parser($settings, $map);

        if (!empty($settings['width'])) {
            $settings['_column_size'] = $settings['_inline_size'] = $settings['_inline_size_tablet'] = self::calc_column_width($settings['width']);
        } else {
            $settings['_column_size'] = $settings['_inline_size'] = $settings['_inline_size_tablet'] = 100;
        }

        if (!empty($settings['el_class'])) $settings['css_classes'] = $settings['el_class'];

        CEW_Patch_Widget_Settings_Parser::remove_css_prefix($settings);

        /*Column Responsive*/
        if (!empty($settings['offset'])) {
            $offset_data = explode(' ', $settings['offset']);
            $offset = array();

            if (!empty($offset_data)) {
                foreach ($offset_data as $offset_datum) {

                    if (strpos($offset_datum, '-offset-') !== false) continue;

                    if (strpos($offset_datum, 'vc_col-lg-') !== false) {
                        $offset['lg'] = str_replace('vc_col-lg-', '', $offset_datum);
                    }

                    if (strpos($offset_datum, 'vc_col-sm-') !== false) {
                        $offset['sm'] = str_replace('vc_col-sm-', '', $offset_datum);
                    }

                    if (strpos($offset_datum, 'vc_col-xs-') !== false) {
                        $offset['xs'] = str_replace('vc_col-xs-', '', $offset_datum);
                    }

                    if (strpos($offset_datum, 'vc_hidden-lg') !== false) {
                        $offset['hidden_lg'] = 'yes';
                    }

                    if (strpos($offset_datum, 'vc_hidden-sm') !== false) {
                        $offset['hidden_sm'] = 'yes';
                    }

                    if (strpos($offset_datum, 'vc_hidden-xs') !== false) {
                        $offset['hidden_xs'] = 'yes';
                    }


                }
            }

            if (!empty($offset['lg'])) $settings['_inline_size'] = self::calc_column_width("{$offset['lg']}/12");
            if (!empty($offset['xs'])) $settings['_inline_size_mobile'] = self::calc_column_width("{$offset['xs']}/12");

            /*Responsive hidden*/
            if (!empty($offset['hidden_lg'])) $settings['hide_desktop'] = 'hidden-desktop';
            if (!empty($offset['hidden_sm'])) $settings['hide_tablet'] = 'hidden-tablet';
            if (!empty($offset['hidden_xs'])) $settings['hide_mobile'] = 'hidden-phone';

        }

        return $settings;
    }

    static function widget($settings, $map = array())
    {

        $settings = CEW_Patch_Widget_Settings_Parser::parser($settings, $map);

        return $settings;
    }

    static function calc_column_width($col)
    {

        return floor(eval('return ' . $col . ' * 100 ;'));
    }

}
