<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Hyper Feed Widget Interface Class
**/

interface Rsswidget_Interface
{
    
    /**
    * Public widget method
    *
    * @param <type> $data
    * @param  $options
    * @return string html
    **/

    public function widget($data, $options = array());
}

/**
* Widget Plugin Class for Rsswidget
*
* Render html widget for feed reader based on options
*
* config options
*  - array
*      - widget:
*          - optional string
*          - value 'brief' or 'compact' or 'detail'
*      - showTitle
*          - boolean
*          - whether add blog title or not.
**/

class Rsswidget implements Rsswidget_Interface
{

    /**
    * HTML widget structure
    *
    * @var array
    * @access public
    **/

    public $html = array('brief' => "<div class=\"feed-item feed-brief\">\n
                                        <div class=\"feed-item-title\">\n
                                            <h3><a href='%s'>%s</a></h3>\n
                                            <div class='feed-item-date'>%s</div>\n
                                        </div>\n
                                        <div class=\"feed-item-description\">%s</div>\n
                                     </div>",
        
                        'compact' => "<div class=\"feed-item feed-compact\">\n
                                        <div class=\"feed-item-title\">\n
                                            <h3><a href='%s'>%s</a></h3>\n
                                            <div class='feed-item-date'>%s</div>\n
                                        </div>\n
                                     </div>",

                        'detail' => "<div class=\"feed-item feed-detail\">\n
                                        <div class=\"feed-item-title\">\n
                                            <h3><a href='%s'>%s</a></h3>\n
                                            <div class='feed-item-date'>%s</div>\n
                                        </div>\n
                                        <div class=\"feed-item-content\">%s</div>\n
                                     </div>",);
    
    /**
    * Return html widget based rendered by widget class
    *
    * @param  $data
    * @param  $options
    * @return 
    **/

    function widget($data, $options = array('widget' => 'brief')) 
    {
        switch ($options['widget']) {
        case 'compact':
            return $this->widgetCompact($data, $options);
        break;

        case 'detail':
            return $this->widgetDetail($data, $options);
        break;
        
        case 'brief':
        default:
            return $this->widgetBrief($data, $options);
        break;
        }
    }

    /**
    * Render feed widget with title and date only
    *
    * @param  $data
    * @param  $options
    * @return  
    **/

    function widgetCompact($data, $options = array()) 
    {
        if (empty($data['items'])) {
          return false;
        }

        $out = array();
        foreach ($data['items'] as $item) {
            if (empty($item['date'])) {
                $item['date'] = '';
            }
            $out[] = sprintf($this->html['compact'], $item['link'], $item['title'], $item['date']);

        }

        $title = '';
        if (empty($options['showtitle'])) {
            $title = sprintf("<div class='feed-title'><h2>%s</h2><hr>%s</div>\n", $data['title'], $data['description']);
        }

        $out = "<div class='feed-container'>\n"  . $title . join(" \n", $out) . "</div>";

        return $out;
    }

    /**
    * Render feed widget with title, date & description
    *
    * @param  $data
    * @param  $options
    * @return  
    **/

    function widgetBrief($data, $options = array()) 
    {
        if (empty($data['items'])) {
          return false;
        }

        $out = array();

        foreach ($data['items'] as $item) {
            if (empty($item['date'])) {
                $item['date'] = '';
            }

            if (empty($item['description'])) {
                    $item['description'] = $item['content'];
            }
            
            $out[] = sprintf($this->html['brief'], $item['link'], $item['title'], $item['date'], $item['description']);

        }
        
        $title = '';
        if (empty($options['showtitle'])) {
            $title = sprintf("<div class='feed-title'><h2>%s</h2><hr>%s</div>\n", $data['title'], $data['description']);
        }

        $out = "<div class='feed-container'>\n"  . $title . join(" \n", $out) . "</div>";
        

        return $out;
    }

    /**
    * Render blog widget with title, date & content
    *
    * @param  $data
    * @param  $options
    * @return  
    **/

    function widgetDetail($data, $options = array()) 
    {
        if (empty($data['items'])) {
          return false;
        }

        $out = array();
        foreach ($data['items'] as $item) {
            if (empty($item['date'])) {
                $item['date'] = '';
            }
            if (empty($item['content'])) {
                $item['content'] = $item['description'];
            }
            $out[] = sprintf($this->html['detail'], $item['link'], $item['title'], $item['date'], $item['content']);

        }
        
        $title = '';
        if (empty($options['showtitle'])) {
            $title = sprintf("<div class='feed-title'><h2>%s</h2><hr>%s</div>\n", $data['title'], $data['description']);
        }

        $out = "<div class='feed-container'>\n"  . $title . join(" \n", $out) . "</div>";

        return $out;
    }
}