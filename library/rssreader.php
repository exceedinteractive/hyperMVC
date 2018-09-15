<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Hyper RSS Reader Class
**/

/**
* SimpleXML based feed reader class. A very specific feed reader designed
* to working with no or little configurations.
*
* - This class can read an rss feed into array from a given url.
* 
* Supports following feed types:
*  - RSS 0.92
*  - RSS 2.0
*  - Atom
*
* Configuration Options
*  - array
*      - url: (string)
*          - feed url
*
*      - httpClient: (string)
*          - default SimpleXML
*
*      - type: ([optional] string
*          - auto detect
*          - value rss or rss2 or atom
*
*      - widget: ([optional] string)
*          - feed widget class name for rendering html
**/

/**
* 
* @usage Example Usage or RssReader
*
* // Simple get data from a feed url
*
* $this->loadLibrary('rssreader');
*
* $options = array('url' => 'http://example.com/feed');
*
* $this->rss->setOptions($options);
*
* $feedData = $this->rss->parse()->getData();
*
*
* // Get with html widget option
*
* $options = array(
*               'url'    => 'http://example.com/feed',
*               'widget' => 'Rsswidget',
*            	);
*
* $html = $this->rss->setOptions($options)->parse()->widget();
*
* // OR with widget options.
*
* $widgetOptions = array('widget' => 'detail', 'showTitle' => true);
*
* $html = $this->rss->setOptions($options)->parse()->widget($widgetOptions);
**/

Class Rssreader
{

	/**
	* Self Instance for Singleton Pattern
	*
	* @var object
	* @access protected
	**/

    static private $__instance;

	/**
	* Instance of Parser Class.
	*
	* @var object Parser Class
	* @access protected
	**/

    Protected $_Parser;

	/**
	* Feed Url
	*
	* @var string feed url
	* @access protected
	**/

    protected $_url;

	/**
	* Runtime Options for reader
	*
	* @var array
	* @access protected
	**/

    protected $_options = array('rayHttp' => array());

	/**
	* Type of feed to be parsed.
	*
	* @var string
	* @access protected
	**/

    protected $_type = "rss";

	/**
	* HttpClient to be used for loading feed content.
	*
	*  - default SimpleXML
	*
	* @var string 'SimpleXML' or 'rayHttp'
	* @access protected
	**/

    protected $_httpClient = "SimpleXML";
    
	/**
	* Widget Class Name
	*
	* @var string
	* @access protected
	**/

    protected $_widget;

	/**
	* Parsed result data
	* 
	* @var array
	* @access protected
	**/

    protected $_content;

    /**
    * Container for the rss class
    **/

    public $rss;

	/**
	* Class construct
	*
	* @param array $options
	**/

    function __construct($options = array()) 
    {
        $this->setOptions($options);
    }

	/**
	* Get Instance of the class.
	*
	* @param array $options
	* @return object self instance.
	* @access public
	* @static
	**/

    static function &getInstance($options = array()) 
    {
        if (is_null(self::$__instance)) {
            self::$__instance = new self($options);
        }
        return self::$__instance;
    }

	/**
	* Set Options for the class
	* 
	* 
	* @param array $options
	* @return object self instance
	* @access public
	**/

    function &setOptions($options) 
    {
        if (!empty($options['url'])) {
            $this->_url = $options['url'];
        }

        if (!empty($options['type'])) {
            $this->_type = $options['type'];
        }

        if (!empty($options['httpClient'])) {
            $this->_httpClient = $options['httpClient'];
        }

        if (!empty($options['widget'])) {
            $this->_widget = $options['widget'];
        }

        $this->_options = array_merge($this->_options, $options);

        return $this;
    }

	/**
	* Parse feed contents into an array and return self object
	* 
	* @return object self instance
	* @access public
	**/

    function &parse() 
    {
        /**
         * Get/load content
         */
         switch ($this->_httpClient) {
         case 'SimpleXML':
         	$content = new SimpleXMLElement($this->_url, LIBXML_NOCDATA, true);
         break;
		 case 'rayHttp':
                 
			$content = RayHttp::getInstance()->setOptions($this->_options['rayHttp'])->get($this->_url);

			if (!empty($content)) {
			$content = new SimpleXMLElement($content, LIBXML_NOCDATA);
			}
         break;
         }

         if (empty($content)) {
             trigger_error("XML format is invalid or broken.", E_USER_ERROR);
         }

		/**
		* Detect Feed Type
		**/

         if (empty($this->_type)) {
                
                switch ($content->getName()) {
                case 'rss':
					foreach ($content->attributes() as $attribute) {
					    if ($attribute->getName() == 'version') {
					        if ('2.0' == $attribute) {
					            self::setOptions(array('type' => 'rss2'));
					        } elseif (in_array($attribute, array('0.92', '0.91'))) {
					            self::setOptions(array('type' => 'rss'));
					        }
					    }
					}
                break;
                case 'feed':                            
                    self::setOptions(array('type' => 'atom'));    
                break;
                }
             
         }
         
         if (!in_array($this->_type, array('rss', 'rss2', 'atom'))) {

              trigger_error("Feed type is either invalid or not supported.", E_USER_ERROR);
              
              return false;
         }


		/**
		* Parse Feed Content
		**/

        switch ($this->_type) {
        case 'rss':
            $content = $this->parseRss($content);
        break;

        case 'rss2':
            $content = $this->parseRss2($content);
        break;

        case 'atom':
            $content = $this->parseAtom($content);
        break;
        }

         if (empty($content)) {                 
             trigger_error("No content is found.", E_USER_ERROR);
         }

         $this->_content = $content;
         
         return $this;

    }

	/**
	* Get Array of Parsed XML feed data.
	*
	* @return array parsed feed content.
	* @access public
	**/

    function getData() 
    {
        return $this->_content;
    }

	/**
	* Return html widget based rendered by widget class
	*
	*
	* @param array $options for html widget class
	* @return string html widget
	* @access public
	**/

    function widget($options = array('widget' => 'brief')) 
    {
        if (!empty($this->_widget) && !empty($this->_content)) {

        	require('rsswidget.php');

            $Widget = new $this->_widget;
            
            return $Widget->widget($this->_content, $options);
            
         } else {
             return false;
         }
    }
    
	/**
	* Parse feed xml into an array.
	*
	* @param object $feedXml SimpleXMLElementObject
	* @return array feed content
	* @access public
	**/

    function parseRss($feedXml) 
    {
        $data = array();

        $data['title']       = $feedXml->channel->title . '';
        $data['link']        = $feedXml->channel->link . '';
        $data['description'] = $feedXml->channel->description . '';
        $data['parser']      = __CLASS__;
        $data['type']        = 'rss';

        foreach ($feedXml->channel->item as $item) {
            $data['items'][] = array(
                                    'title'       => $item->title . '',
                                    'link'        => $item->link . '',
                                    'description' => $item->description . '',
                                );
        }
        
        return $data;
    }

    
	/**
	* Parse feed xml into an array.
	*
	* @param object $feedXml SimpleXMLElementObject
	* @return array feed content
	* @access public
	**/

    function parseRss2($feedXml) 
    {
        $data = array();

        $data['title']       = $feedXml->channel->title . '';
        $data['link']        = $feedXml->channel->link . '';
        $data['description'] = $feedXml->channel->description . '';
        $data['parser']      = __CLASS__;
        $data['type']        = 'rss2';

        $namespaces = $feedXml->getNamespaces(true);
        foreach ($namespaces as $namespace => $namespaceValue) {
            $feedXml->registerXPathNamespace($namespace, $namespaceValue);
        }

        foreach ($feedXml->channel->item as $item) {
            $categories = array();
            foreach ($item->children() as $child) {
                if ($child->getName() == 'category') {
                    $categories[] = (string) $child;
                } 
            }

            $author = null;
            if (!empty($namespaces['dc']) && $creator = $item->xpath('dc:creator')) {
                $author = (string) $creator[0];
            }

            $content = null;
            if (!empty($namespaces['encoded']) && $encoded = $item->xpath('content:encoded')) {
                $content = (string) $encoded[0];
            }

            $data['items'][] = array(
                                    'title'       => $item->title . '',
                                    'link'        => $item->link . '',
                                    'date'        => date('Y-m-d h:i:s A', strtotime($item->pubDate . '')),
                                    'description' => $item->description . '',
                                    'categories'  => $categories,
                                    'author'      => array( 'name' => $author),
                                    'content'     => $content,
                                    
                                );
            
        }

        return $data;
    }

	/**
	* Parse feed xml into an array.
	*
	* @param object $feedXml SimpleXMLElementObject
	* @return array feed content
	* @access public
	**/

    function parseAtom($feedXml) 
    {
        $data = array();

        $data['title'] = $feedXml->title . '';
        foreach ($feedXml->link as $link) {
                $data['link'] = $link['href'] . '';
            break;
        }

        $data['description'] = $feedXml->subtitle . '';
        $data['parser'] = __CLASS__;
        $data['type'] = 'atom';

        foreach ($feedXml->entry as $item) {
            foreach ($item->link as $link) {
                $itemLink = $link['href'] . '';
                break;
            }

            $categories = array();
            foreach ($item->category as $category) {
                $categories[] = $category['term'] . '';
            }

            $data['items'][] = array(
                                    'title'       => $item->title . '',
                                    'link'        => $itemLink . '',
                                    'date'        => date('Y-m-d h:i:s A', strtotime($item->published . '')),
                                    'description' => $item->summary . '',
                                    'content'     => $item->content . '',
                                    'categories'  => $categories,
                                    'author'      => array('name' => $item->author->name . '', 'url' => $item->author->uri . ''),
                                    'extra'       => array('contentType' => $item->content['type'] . '', 'descriptionType' => $item->summary['type'] . '')
                                );
        }

        return $data;
    }

}

$this->rss = Rssreader::getInstance();