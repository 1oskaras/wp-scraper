<?php 
/*
Plugin Name: Scraper
Plugin URI:  localhost
Description: This plugin gets the names of the current day from day.lt
Version:     1.0
Author:      Oskaras Riauba
Author URI:  localhost
License:     GPL2 etc
License URI: localhost

Copyright 2020 Oskaras Riauba (email : oskaras.riauba@gmail.com)
Scraper is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Scraper is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Scraper. If not, see (localhost).
*/

/** 
 * Content scraper shortcode 
**/
if ( !defined( 'HDOM_TYPE_ELEMENT' ) ){
 include_once('simple_html_dom.php');
}

class WPScraper {
    public function __construct()
    {
        add_shortcode('scrape_that', array($this, 'content_scraper'));
    }
     
    public function content_scraper( $atts ) 
    {
        $attr = shortcode_atts(
            array(
            'url' => ''
            ), $atts 
        );
    
        $url = $attr['url'];
        $html = file_get_html($url);
        $find = $html->find('[!class]');
        $transient = get_transient('day_names');
    
            if ($transient) {
                return $transient;
            }else {
                foreach($find as $div) {
                    foreach($div->find('.vardadieniai') as $p) {
                        $n = $p->plaintext;
                        $arr = explode(' ', $n);
                        $arr = array_filter($arr);
                        $transient = get_transient('day_names');

                        echo '<ul class="names">';

                            foreach($arr as $element) {
                                $result .= '<li>'.$element.'</li>';  
                            }
                        set_transient('day_names', $result, 7200);
                        return $result;
                        echo '</ul>';
                    }
                }
            }
        }
}
new WPScraper();