<?php
/**
 * PHP Bug lost 0.5a Standard
 * One-file script for debug and monitor scripts.
 * See docs and support forum at http://www.phpbuglost.com
 *
 * PHP Version 5
 *
 * @version 0.5a
 * @author  Jordi Enguídanos <jordifreek@gmail.com>
 * @license MIT Licence
 * @link    http://phpbuglost.com
 */


error_reporting(E_ALL); // show errors...
set_error_handler("bl_error_handler"); // ... and hidden with error_handler

#################################
## - SECURITY OPTIONS

/**
 * Change with any alphanumerical string.
 * It's required if you use delete vars or file browser options.
 * If anyone knows this key, may delete your session vars or
 * view your PHP Files. CAUTION on production sites.
 * @see _bl_delete_vars
 * @see _bl_file_viewer
 * @type string
 */
define('_bl_secret_key', '_pbl_');


/**
 * PBL blocks some functions if you set the website in production mode:
 * File Viewer, delete vars and eval panel will be off is set this true.
 * Also profile functions will be disabled for prevent performance penalization
 * @type string
 */
define('_bl_production', false);

/**
 * Set the name for URL var used in ajax request when delete a
 * session/cookie var
 * @type string
 */
define('_bl_var_del', 'bl_del');

/**
 * Set the name for URL var used in ajax request for file viewer
 * @type string
 */
define('_bl_var_file', 'bl_file');

/**
 * Set the name for URL var used in ajax request for eval code
 * @type string
 */
define('_bl_var_eval', 'bl_eval');

/**
 * show console only to this IP.
 * keep empty for don't use.
 * Comma separated for multiple ip
 * @type string
 */
define('_bl_allow_ip', '');


#################################
## - MONITOR OPTIONS

/** @type bool true|false for activate|deactivate monitor options */
define('_bl_monitor_on', false);

/** @type string Email where to send monitor info */
define('_bl_admin_mail', 'name@domain.com');

/** @type string Description. Appears on email title */
define('_bl_sort_site_description', 'My Site');

/** @type bool Send email on sql fails */
define('_bl_monitor_sql', false);

/** @type bool Send email if match "monitor times" rules (see below) */
define('_bl_monitor_times', false);

/** @type bool Send email if match "monitor memory" rules */
define('_bl_monitor_memory', false);

/** @type int Max allowed time for total load in seconds */
define('_bl_max_load_time', 0);

/** @type int Max allowed time for any query in seconds */
define('_bl_max_sql_time', 0);

/** @type int Max allowed time for any time mark in seconds */
define('_bl_max_any_time', 0);

/** @type int Max allowed total memory usage in bytes */
define('_bl_max_total_memory', 0);

/**
 * Include log messages when send emails to admin
 * Comma separated list: error,warn,info,user
 * Keep empty for don't include nothing
 * @type string
 */
define('_bl_monitor_mail_log', 'error,warn,info,user');


#################################
## - MESSAGES/LOG PANEL

/**
 * Type of messages to show in the console.
 * Set to "all" (default) to include all types or comma separated
 * for individual types.
 * Allowed: error|warn|info|user|all
 * @type string
 */
define('_bl_messages_types', 'all');

/** @type bool show and alert when there're errors in messages */
define('_bl_alert_errors', true);

/** @type bool show backtrace errors */
define('_bl_backtrace', true);


#################################
## - VARS PANEL

/**
 * true for allow delete cookies and session vars.
 * Keep false on production sites (recommended)
 * Remember change the secret key otherwise you will get an error.
 * @see _bl_secret_key
 * @type bool
 */
define('_bl_delete_vars', false);

/** @type bool true for use only sort vars like $_POST, $_GET, $_SESSION */
define('_bl_delete_long_vars', true);

/** @type bool true for use HTML Viewer (for vars on vars panel). */
define('_bl_html_viewer', false);

/**
 * true for get size of objects.
 * Some objects, like PDO instance, can't be serialized
 * and php return error. See Memory section in docs.
 * @type bool
 */
define('_bl_serialize_objects', false);

/** @type bool show methods and properties on internal php classes */
define('_bl_show_internal_classes', true);


#################################
## - SQL PANEL

/**
 * What type of DB uses. One of mysql|sqlite|pdo
 * @type string
 */
define('_bl_db_driver', 'mysql');

/** use explain for show more info on mysql querys */
define('_bl_explain_sql', false);

/** @type object Sqlite3 object */
$bl_sqlite = null;


#################################
## - TIMES PANEL

/** @type bool Add time marks to any event (log messages, querys...) */
define('_bl_create_times', true);


#################################
## - EVAL PANEL

/**
 * Activate/Deactivate eval panel
 * @type bool
 */
define('_bl_eval_active', false);


#################################
## - AJAX PANEL

/**
 * Activate/Deactivate eval panel
 * Require internal JS
 * @type bool
 */
define('_bl_ajax_active', true);


#################################
## - OTHER OPTIONS

/**
 * true to allow view source code on listed files
 * Remember change the secret key otherwise you will get an error.
 * @type bool
 */

define('_bl_file_viewer', true);


define('_bl_hide_phpinfo', false);

/**
 * Use external css file.
 * Multiple whit coma: estyle1.css, estyle2.css, style3.css...
 * Keep empty for use internal css
 * @type string
 */
define('_bl_css_file', '');

/**
 * Use external js file. Multiple with coma.
 * Keep empty for use internal js
 * @type string
 */
define('_bl_js_file', '');

/**
 * Save the console state when reloading page.
 * note: Use cookies
 * @type bool
 */
define('_bl_save_state', false);

/**
 * Enable/disable shortcuts. Only when use internal js.
 * On external js find bl_shortcuts var and set true/false.
 * This is injected to javascript, set "true" or "false" with quotes
 * like a string)
 * @type string
 */
define('_bl_keyboard_shortcuts', 'true');

/** @type bool Show or hide the shortcuts numbers on menu buttons */
define('_bl_show_keyboard_shortcuts', true);


/**
 * bl_create_bookmarklets()
 *
 * This function is fired inside @bl_debug() only if
 * debug is enabled. Don't change or remove it.
 * See inside, you can add more bookmarklets.
 * For add a bookmarklet you need 3 parameters:
 * type: js|css|other
 * title: the title of the bookmarklet
 * url: javascript of the bookmarklet
 *
 * There a fourth optional parameter for set the quote type in the url href.
 * You can set it as "'" or '"' for use simple or double quotes.
 * It is useful depending on what type of quote are using your bookmarklet.
 * href="javascript..." or href='javascript...'
 * Default is double quote.
 */
function bl_create_bookmarklets()
{

    // ** ADD HERE MORE BOOKMARKLETS **
    // Search on the web:
    // https://www.squarefree.com/bookmarklets/
    // http://www.hongkiat.com/blog/100-useful-bookmarklets-for-better-productivity-ultimate-list/

    // See panel about for bookmarklets credits

    bl_add_bookmark('js', 'XRay',
        'javascript:function%20loadScript(scriptURL)%20{%20var%20scriptElem%20=%20document.createElement(\'SCRIPT\');%20scriptElem.setAttribute(\'language\',%20\'JavaScript\');%20scriptElem.setAttribute(\'src\',%20scriptURL);%20document.body.appendChild(scriptElem);}loadScript(\'http://westciv.com/xray/thexray.js\');');

    bl_add_bookmark('js', 'DOM Inspector', 'javascript:prefFile=\'\';void(z=document.body.appendChild(document.createElement(\'script\')));void(z.language=\'javascript\');void(z.type=\'text/javascript\');void(z.src=\'http://slayeroffice.com/tools/modi/v2.0/modi_v2.0.js\');void(z.id=\'modi\');');

    bl_add_bookmark('js', 'Favelet Suite',
        'javascript:s=document.body.appendChild(document.createElement(\'script\'));s.id=\'fs\';s.language=\'javascript\';void(s.src=\'http://slayeroffice.com/tools/suite/suite.js\');');

    bl_add_bookmark('js', 'View Selected Source',
        'javascript:function getSelSource() { x = document.createElement("div"); x.appendChild(window.getSelection().getRangeAt(0).cloneContents()); return x.innerHTML; } function makeHR() { return nd.createElement("hr"); } function makeParagraph(text) { p = nd.createElement("p"); p.appendChild(nd.createTextNode(text)); return p; } function makePre(text) { p = nd.createElement("pre"); p.appendChild(nd.createTextNode(text)); return p; } nd = window.open().document; ndb = nd.body; if (!window.getSelection || !window.getSelection().rangeCount || window.getSelection().getRangeAt(0).collapsed) { nd.title="Generated Source of: " + location.href; ndb.appendChild(makeParagraph("No selection, showing generated source of entire document.")); ndb.appendChild(makeHR()); ndb.appendChild(makePre("<html>\n" + document.documentElement.innerHTML + "\n</html>")); } else { nd.title="Partial Source of: " + location.href; ndb.appendChild(makePre(getSelSource())); }; void 0',
        "'"); // this require a single quote

    bl_add_bookmark('js', 'View all JS',
        "javascript:s=document.getElementsByTagName('SCRIPT'); d=window.open().document; /*140681*/d.open();d.close(); b=d.body; function trim(s){return s.replace(/^\s*\n/, '').replace(/\s*$/, ''); }; function add(h){b.appendChild(h);} function makeTag(t){return d.createElement(t);} function makeText(tag,text){t=makeTag(tag);t.appendChild(d.createTextNode(text)); return t;} add(makeText('style', 'iframe{width:100%;height:18em;border:1px solid;')); add(makeText('h3', d.title='Scripts in ' + location.href)); for (i=0; i<s.length; ++i) { if (s[i].src) { add(makeText('h4','script src=&quot;' + s[i].src + '&quot;')); iframe=makeTag('iframe'); iframe.src=s[i].src; add(iframe); } else { add(makeText('h4','Inline script')); add(makeText('pre', trim(s[i].innerHTML))); } } void 0");

    bl_add_bookmark('js', 'View all vars/functions',
        'javascript:(function(){var x,d,i,v,st; x=open(); d=x.document; d.open(); function hE(s){s=s.replace(/&amp;/g,"&amp;amp;");s=s.replace(/>/g,"&amp;gt;");s=s.replace(/</g,"&amp;lt;");return s;} d.write("<style>td{vertical-align:top; white-space:pre; } table,td,th { border: 1px solid #ccc; } div.er { color:red }</style><table border=1><thead><tr><th>Variable</th><th>Type</th><th>Value as string</th></tr></thead>"); for (i in window) { if (!(i in x) ) { v=window[i]; d.write("<tr><td>" + hE(i) + "</td><td>" + hE(typeof(window[i])) + "</td><td>"); if (v===null) d.write("null"); elseif (v===undefined) d.write("undefined"); else try{st=v.toString(); if (st.length)d.write(hE(v.toString())); else d.write("%C2%A0")}catch(er){d.write("<div class=er>"+hE(er.toString())+"</div>")}; d.write("</pre></td></tr>"); } } d.write("</table>"); d.close(); })();',
        "'"); // this require a single quote

    bl_add_bookmark('css', 'Reload CSS',
        'javascript:function bl_reloadCSS(){var%20qs=\'?\'+new%20Date().getTime(),l,i=0;while(l=document.getElementsByTagName(\'link\')[i++]){if (l.rel&amp;&amp;\'stylesheet\'==l.rel.toLowerCase()){if (!l._h)l._h=l.href;l.href=l._h+qs}}}; bl_reloadCSS();');

    bl_add_bookmark('css', 'MRI (Test CSS Selectors)',
        'javascript:function%20loadScript(scriptURL)%20{%20var%20scriptElem%20=%20document.createElement(\'SCRIPT\');%20scriptElem.setAttribute(\'language\',%20\'JavaScript\');%20scriptElem.setAttribute(\'src\',%20scriptURL);%20document.body.appendChild(scriptElem);}loadScript(\'http://westciv.com/mri/theMRI.js\');');

    bl_add_bookmark('css', 'View all CSS',
        "javascript:s=document.getElementsByTagName('STYLE'); ex=document.getElementsByTagName('LINK'); d=window.open().document; /*set base href*/d.open();d.close(); b=d.body; function trim(s){return s.replace(/^\s*\n/, '').replace(/\s*$/, ''); }; function iff(a,b,c){return b?a+b+c:'';}function add(h){b.appendChild(h);} function makeTag(t){return d.createElement(t);} function makeText(tag,text){t=makeTag(tag);t.appendChild(d.createTextNode(text)); return t;} add(makeText('style', 'iframe{width:100%;height:18em;border:1px solid;')); add(makeText('h3', d.title='Style sheets in ' + location.href)); for (i=0; i<s.length; ++i) { add(makeText('h4','Inline style sheet'  + iff(' title=&quot;',s[i].title,'&quot;'))); add(makeText('pre', trim(s[i].innerHTML))); } for (i=0; i<ex.length; ++i) { rs=ex[i].rel.split(' '); for (j=0;j<rs.length;++j) if (rs[j].toLowerCase()=='stylesheet') { add(makeText('h4','link rel=&quot;' + ex[i].rel + '&quot; href=&quot;' + ex[i].href + '&quot;' + iff(' title=&quot;',ex[i].title,'&quot;'))); iframe=makeTag('iframe'); iframe.src=ex[i].href; add(iframe); break; } } void 0");

    bl_add_bookmark('css', 'View classes',
        'javascript:(function(){var a={},b=[],i,e,c,k,d,s="<table border=1><thead><tr><th>#</th><th>Tag</th><th>className</th></tr></thead>";for (i=0;e=document.getElementsByTagName("*")[i];++i)if (c=e.className){k=e.tagName+"."+c;a[k]=a[k]?a[k]+1:1;}for (k in a)b.push([k,a[k]]);b.sort();for (i in b) s+="<tr><td>"+b[i][1]+"</td><td>"+b[i][0].split(".").join("</td><td>")+"</td></tr>";s+="</table>";d=open().document;d.write(s);d.close();})()',
        "'"); // this require a single quote

    bl_add_bookmark('other', 'Show hidden inputs',
        'javascript:var i, bl_hidden = document.getElementsByTagName(\'input\');for (i=0; i<bl_hidden.length; i++) {if (bl_hidden[i].type == \'hidden\') {bl_hidden[i].type = \'text\';}}');

    bl_add_bookmark('other', 'Check Img Alt',
        'javascript: function injectCSS() {var style = document.createElement(\'STYLE\');style.type = \'text/css\';style.innerHTML = \'img[alt=\\\'\\\'] {border: 2px dotted red;} img:not([alt]) {border: 2px solid red;} img[title=\\\'\\\'] {outline: 2px dotted fuchsia;} img:not([title]) {outline: 2px solid fuchsia;}\';document.getElementsByTagName(\'HEAD\')[0].appendChild(style);} injectCSS()');
}

//
// End Configure
///////////////////////

define('_BL_VERSION', 'standard');

// private. name of this file script. May be you don't need to touch this
define('_bl_filename', basename(__file__));
define('_bl_path', str_replace(
    '//',
    '/',
    str_replace(
        $_SERVER['DOCUMENT_ROOT'],
        '/',
        str_replace(
            '\\',
            '/',
            __file__)
        )
    )
);
define('_bl_root', $_SERVER['DOCUMENT_ROOT']);


/**
 * bl_get_time()
 *
 * @access private
 *
 * @param mixed $time_start for calculate tiem between two times
 *
 * @return int|double The Time
 */
function bl_get_time($time_start = null, $microtime = false)
{
    if (!$microtime) {
        $microtime = microtime();
    }

    $time = explode(' ', $microtime);
    $time = $time[1] + $time[0];

    if ($time_start) {
        $time = $time - $time_start;
    }

    return $time;
}


if (defined('bl_time_start')) {
    define('_bl_time_start', bl_get_time(null, bl_time_start));
} else {
    define('_bl_time_start', bl_get_time());
}

// default shortcuts tags, html markup for top menu
if (_bl_keyboard_shortcuts == true) {
    define('_bl_key_logs', '<sup>1</sup>');
    define('_bl_key_sql', '<sup>2</sup>');
    define('_bl_key_vars', '<sup>3</sup>');
    define('_bl_key_profile', '<sup>4</sup>');
    define('_bl_key_time', '<sup>5</sup>');
    define('_bl_key_memory', '<sup>6</sup>');
    define('_bl_key_ajax', '<sup>7</sup>');
    define('_bl_key_php', '<sup>8</sup>');
    define('_bl_key_eval', '<sup>9</sup>');
    define('_bl_key_jscss', ' (j)');
    define('_bl_key_opacity', ' (o)');
    define('_bl_key_info', ' (i)');
} else {
    define('_bl_key_logs', '');
    define('_bl_key_sql', '');
    define('_bl_key_vars', '');
    define('_bl_key_time', '');
    define('_bl_key_memory', '');
    define('_bl_key_ajax', '');
    define('_bl_key_php', '');
    define('_bl_key_eval', '');
    define('_bl_key_jscss', '');
    define('_bl_key_opacity', '');
    define('_bl_key_info', '');
}

// global vars, internal usage
class _bl
{
    public static $count_msg 	= 0;
    public static $count_querys = 0;
    public static $count_vars 	= 0;
    public static $vars 		= array(); //
    public static $errors 		= false; // used for highlight error alert
    public static $msgs 		= array(); // log messages
    public static $msgs_time 	= array(); // log tiems
    public static $msg_sql 		= array(); // log querys
    public static $profile 		= array(); // log profile messages
    public static $trace   		= array(); // log trace messages
    public static $watch 		= array(); // vars to watch
    public static $watches 		= array(); // log watches messages
    public static $time_start 	= _bl_time_start;
    public static $panel_state 	= 'close'; // default panel state
    public static $panel_active = array(
        "msg" 	  => "bl_debug_panel_active", // default panel active
        "sql" 	  => "",
        "vars" 	  => "",
        "time" 	  => "",
        "profile" => "",
        "memory"  => "",
        "ajax" 	  => "",
        "php" 	  => "",
        "eval" 	  => ""
    );

    public static $max_var_size = array("var" => "", "size" => 0);
    public static $max_file_size = array("var" => "", "size" => 0);
    public static $bookmarklets = array(
        'css' 	=> array(),
        'js' 	=> array(),
        'other' => array());

    private static $initialized = false;
    private static function initialize()
    {
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;
    }
}

if (_bl_save_state == true) {
    // remember panel size
    if (isset($_COOKIE['panel_size_bl'])) {
        _bl::$panel_state = $_COOKIE['panel_size_bl'];
    }
    // remember what panel is active
    if (isset($_COOKIE['__bl_panel_active'])) {
        if (isset(_bl::$panel_active[$_COOKIE['__bl_panel_active']])) {
            _bl::$panel_active['msg'] = '';
            _bl::$panel_active[$_COOKIE['__bl_panel_active']] = 'bl_debug_panel_active';
        }
    }
}

/**
 * bl_link_file()
 * Convert a text file path in a link
 *
 * @param string $file File path in plain text format
 * @param string $line Line number to be added in the result link
 *
 * @retun string Text file path convertend in a HTML link
 */
function bl_link_file($file, $line)
{
    $file_name = '';

    if (_bl_file_viewer != true) {

        $file_name = (!empty($file))
            ? str_replace(_bl_root, '', $file)
            : '&lt;unknown file&gt;';
    } else {
        if (!empty($file)) {
            if (PHP_OS == 'WINNT') {
                $href = str_replace(
                    DIRECTORY_SEPARATOR,
                    DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,
                    $file
                );
                $url  = str_replace(
                    DIRECTORY_SEPARATOR,
                    DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,
                    _bl_path
                );
            } else {
                $href = $file;
                $url  = _bl_path;
            }

            $file_name = '<a href="javascript:void(0);" onclick="bl_load_file(\''.$href.'\', \''.$line.'\', \''.$url.'\', \''._bl_secret_key.'\', \''._bl_var_file.'\')">'.str_replace(_bl_root, '', $file).'</a>';
        } else {
            $file_name = '&lt;unknown file&gt;';
        }
    }

    return $file_name;
}

/**
 * bl_send_mail()
 *
 * Send "monitor" emails to admin
 *
 * @access private
 * @param string $msg   Email body
 * @param string $title Email Title
 * @param array  $data  Message details.
 *
 * @return void
 */
function bl_send_mail($msg, $title, $data)
{

    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
    $headers .= 'To: <'._bl_admin_mail.'>'."\r\n";
    //$headers .= 'From: Put any thing here <and_any@email.com>'."\r\n";

    $th_style = 'font-weight:bold; background-color:#eee; border-bottom:1px dashed #ccc; padding:5px;';

    $data_th = '';
    $data_td = '';
    foreach ($data as $k => $v) {
        $data_th .= '<th style="'.$th_style.'">' .
            ucfirst($k).'</th>';
        $data_td .= '<td style="padding:5px;">'.$v.'</td>';
    }

    $error_logs = '<p>No log messages</p>';
    if (_bl_monitor_mail_log != '') {
        $error_logs = bl_get_msg( _bl_monitor_mail_log, true );
        $error_logs = '<h3>Log Messages</h3>'.$error_logs;
    }

    $msg = $msg.'
    <table>
        <thead>
            <tr>'.$data_th.'</tr>
        </thead>
        <tbody>
            <tr>'.$data_td.'</tr>
        </tbody>
    </table>'.$error_logs;

    mail(_bl_admin_mail, _bl_sort_site_description.' '.$title, $msg, $headers);
}

/**
 * bl_format_time()
 *
 * Format Time, 4 decimals
 *
 * @access private
 * @param mixed $time Time to format
 *
 * @return string Formated time
 */
function bl_format_time($time)
{
    return round($time, 4).'s';
}

/**
 * bl_msg()
 * Add a message to the list (array _bl::$msgs)
 *
 * @access private
 * @param mixed $msg Text of the message
 * @param string $type Tipe of message: info, error, warn or user (default)
 * @return void
 */
function bl_msg($msg, $file, $line, $type = 'user')
{
    // don't fill memory if we don't need it later
    if (strpos(_bl_messages_types, $type) == false  and _bl_messages_types != 'all') {
        return false;
    }

    $format_msg = $msg;

    if (_bl_create_times) {
        bl_time('Log missage '.substr($msg, 0, 30)).'...';
    }

    $c = count(_bl::$msgs);
    _bl::$msgs[$c]['msg'] = $format_msg;
    _bl::$msgs[$c]['line'] = $line;
    _bl::$msgs[$c]['file'] = $file;
    _bl::$msgs[$c]['time'] = bl_format_time(bl_get_time(_bl::$time_start));
    _bl::$msgs[$c]['type'] = $type;
}

/**
 * bl_get_msg()
 * List of messages (_bl::$msgs) in a HTML table.
 *
 * @access private
 */
function bl_get_msg($type = _bl_messages_types, $styles = false)
{
    if ($type == 'all') {
        $types = array('error', 'warn', 'user', 'info');
    } else {
        $types = explode(',', trim($type, ','));
        $types = array_map('trim', $types);
    }

    // check styles (styles for html in emails (bl_send_mail()))
    $th_style = '';
    $td_style = '';
    $td_colors = array(
        'error' => '',
        'strict' => '',
        'warn' => '',
        'info' => '',
        'user' => ''
    );
    if ($styles == true) {
        $th_style = 'font-weight:bold; background-color:#eee; border-bottom:1px dashed #ccc; padding:5px;';
        $td_style = 'padding:3px; border-bottom:1px dashed #ccc;';
        $td_colors = array(
            'error' => 'background-color:#f33;',
            'warn' => 'background-color:#f90;',
            'info' => 'background-color:#36f;',
            'user' => 'background-color:#333;'
        );
    }

    $result = '
    <table class="bl_msg_table">
        <thead>
            <tr>
                <th style="width:20px;"></th>
                <th style="'.$th_style.'">Message</th>
                <th style="'.$th_style.'">File</th>
                <th style="'.$th_style.'">Line</th>
                <th style="'.$th_style.'">Time</th>
            </tr>
        </thead>
        <tbody>';

    //die('<pre>'.print_r(_bl::$msgs, true).'</pre>');
    $c = 0;
    foreach (_bl::$msgs as $k => $v) {

        if (in_array($v['type'], $types)) {
            if ($v['type'] == 'error') {
                _bl::$errors = true;
            }

            $result .= '
                <tr id="bl_msg_num_'.$c.'" class="bl_normal_tr bl_debug_msg_' .
                $v['type'] .
                ' bl_msg_activo" onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                    <td style="'.$td_colors[$v['type']].'" class="bl_msg_'.$v['type'].'"></td>
                    <td class="bl_td">'.$v['msg'].'</td>
                    <td class="bl_td">'.bl_link_file($v['file'], $v['line']).'</td>
                    <td class="bl_td">'.$v['line'].'</td>
                    <td class="bl_td">'.$v['time'].'</td>
                </tr>';
            $c++;
            _bl::$count_msg = $c;
        }
    }

    $result .= '
        </tbody>
    </table>';

    return $result;
}

/**
 * bl_error()
 * Save new error to the message list
 *
 * @access public
 *
 * @param string $msg Message to send
 *
 * @return void
 */
function bl_error($msg, $file = '', $line = '')
{
    $debug = debug_backtrace();
    $file = ($file == '') ? $debug[0]['file'] : $file;
    $line = ($line == '') ? $debug[0]['line'] : $line;
    bl_msg('<strong>'.$msg.'</strong>', $file, $line, 'error');
}

/**
 * bl_warn()
 * Save new warning to the message list
 *
 * @access public
 *
 * @param string $msg Message to send
 *
 * @return void
 */
function bl_warn($msg)
{
    $debug = debug_backtrace();
    bl_msg($msg, $debug[0]['file'], $debug[0]['line'], 'warn');
}

/**
 * bl_info()
 * Save new info to the message list
 *
 * @access public
 *
 * @param string $msg Message to send
 *
 * @return void
 */
function bl_info($msg)
{
    $debug = debug_backtrace();
    bl_msg($msg, $debug[0]['file'], $debug[0]['line'], 'info');
}

/**
 * bl_var()
 * Log new variable.
 * Get var name thanks to:
 * http://www.php.net/manual/en/language.variables.php#49997
 *
 * @access public
 * @param mixed $var The var to log
 */
function bl_var(&$var, $var_name = null)
{
    if ($var_name == null) {
        $vals = $GLOBALS;
        $old = $var;
        $var = $new = 'UNIQUE'.rand().'VARIABLE';
        $vname = false;

        foreach ($vals as $key => $val) {
            if ($val === $new)
                $vname = $key;
        }

        $var = $old;
    } else {
        $vname = $var_name;
    }

    $c = count(_bl::$vars);

    // using bl_var with an object class always get
    // the same state of the object.
    // Here we register the object with different states
    if (is_object($var)) {
        _bl::$vars['object_'.$vname.'|'.$c] = get_object_vars($var);
    } else {
        _bl::$vars[$vname.'|'.$c] = $var;
    }
}

/**
 * bl_time()
 *
 * Save new mark of time
 *
 * @access public
 * @param string $label Name for the mark
 * @param string $start Start reference.
 * @return void()
 */
function bl_time($label = null, $start = null)
{
    if ($start == null) {
        $start = _bl::$time_start;
    }

    $c = count(_bl::$msgs_time);
    if ($label == null) {
       $label = 'Time mark '.$c;
    }
    _bl::$msgs_time[$c]['label'] = strip_tags($label);
    _bl::$msgs_time[$c]['time'] = bl_format_time(bl_get_time($start));

}

/**
 * bl_log()
 *
 * Log a simple text. You can use html code.
 *
 * @access public
 * @param string $msg Message to log
 */
function bl_log($msg)
{
    $debug = debug_backtrace();
    bl_msg($msg, $debug[0]['file'], $debug[0]['line'], 'user');
}

/**
 * bl_error_handler()
 *
 * Tipical function for error_handler
 *
 * @return void
 */
function bl_error_handler($errno, $errstr, $errfile, $errline, $errcontext)
{
    $trace = debug_backtrace();
    $msg   = '<p><strong>'.$errstr.'</strong></p>';
    $file  = '';

    $trace = array_reverse($trace);

    if (_bl_backtrace == true) {
       if (is_array($trace) and count($trace)) {
            $msg .= '
            <table class="bl_backtrace">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Line</th>
                        <th>Function</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($trace as $item) {

                if (isset($item['file'])) {

                    $function = ($item['function'] != 'bl_error_handler')
                        ? $item['function'].'()'
                        : '-';

                    if (basename($item['file']) != _bl_filename) {
                        $file = bl_link_file($item['file'], $item['line']);

                        $msg .= '
                        <tr>
                            <td>'.$file.'</td>
                            <td>'.$item['line'].'</td>
                            <td>'.$function.'</td>
                        </tr>';

                        $errfile = $item['file'];
                        $errline = $item['line'];
                    }
                }
            }
            $msg .= '</tbody></table>';
        }
    }

    $type = $errno == E_STRICT ? 'strict' : 'error';

    bl_msg($msg, $errfile, $errline, $type);
}

/**
 * bl_query()
 * An alias function for bl_mysql, bl_sqlite and bl_pdo
 *
 * @param string   $query SQL query to execute
 * @param resource $con   An sql connection (optional).
 */
function bl_query($query, $con = null)
{
    if (_bl_db_driver == 'mysql') {
        return bl_mysql($query, $con, 1);

    } elseif (_bl_db_driver == 'mysqli') {
        return bl_mysqli($query, $con, 1);
		
    } elseif (_bl_db_driver == 'sqlite') {
        if ($con != null) {
            return bl_sqlite($query, $con, 1);
        } else {
            bl_error('
                PHPBugLost: Require a SQlite object in bl_query()
                second parameter. See docs for use sqlite with PBL',
                $_SERVER['SCRIPT_FILENAME'],
                '0'
            );
        }

    } elseif (_bl_db_driver == 'pdo') {
        if ($con != null) {
            return bl_pdo($query, $con, 1);
        } else {
            throw new Exception('PHPBugLost: Require a PDO object. See docs
                for use PDO with PBL');
        }

    } else {
        throw new Exception('PHPBugLost: Error when executing bl_query, check
            _bl_db_driver and set one of bl_mysql, bl_sqlite or bl_pdo');
    }
}


/**
 * bl_mysql()
 * Execute a mysql query and send the data to the log
 *
 * @access public
 *
 * @param string $query The query to run
 * @param resource $con Optinally, connection to mysql
 *
 * @return resource MySQL resource
 */
function bl_mysql($query, $con = null, $debugnum = 0)
{
    if (_bl_create_times) {
        bl_time('Start Query '.substr($query, 0, 30)).'...';
    }

    $debug = debug_backtrace();

    $t_start = $t_end = $error = '';

    // make query and get time
    // WTF! DRY!!
    if ($con) {
        $t_start = bl_get_time();
        $sql = mysql_query($query, $con);
        $t_stop = bl_get_time($t_start);
    } else {
        $t_start = bl_get_time();
        $sql = mysql_query($query);
        $t_stop = bl_get_time($t_start);
    }
    $time = $t_stop;

    if (mysql_error()) {
        $error = mysql_error();
    }

    // check for errros
    $insert_id = $results = '0';
    $explain_info = '';
    $q = trim(strtolower($query));
    $insert_id = $results = '0';
    if (substr($q, 0, 6) == 'insert') { // if is insert get the last id
        $insert_id = mysql_insert_id();
    } else {
        if (substr($q, 0, 6) == 'select') { // if is select get num rows

            // explain query?
            $explain_info = '';
            if (_bl_explain_sql and !$error and _bl_production == false) {
                $sql_explain = mysql_query("EXPLAIN ".$query);
                $explain = mysql_fetch_assoc($sql_explain);

                $explain_info = '
                <p class="bl_explain">
                    <strong>EXPLAIN</strong> -&gt;Table: <em>'.$explain['table'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Type: <em>'.$explain['type'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Possible Keys: <em>'.$explain['possible_keys'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Key: <em>'.$explain['key'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Key len: <em>'.$explain['key_len'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Ref: <em>'.$explain['ref'] .
                    '</em> <span class="bl_msg_separator">|</span>
                    Extra: <em>'.$explain['Extra'].'</em>
                </p>';

                $results = $explain['rows'];
            } else {
                $results = mysql_num_rows($sql);
            }

        }
    }

    // add to the querys array
    _bl::$count_querys++;
    $c = _bl::$count_querys; // :)
    _bl::$msg_sql[$c]['query'] = $query;
    _bl::$msg_sql[$c]['time'] = $time;
    _bl::$msg_sql[$c]['insert'] = $insert_id;
    _bl::$msg_sql[$c]['result'] = $results;
    _bl::$msg_sql[$c]['explain'] = $explain_info;
    _bl::$msg_sql[$c]['error'] = (!empty($error)) ? '<span class="error">'.$error .'</span>' : '';
    _bl::$msg_sql[$c]['file'] = $debug[$debugnum]['file'];
    _bl::$msg_sql[$c]['line'] = $debug[$debugnum]['line'];

    if ($error and _bl_monitor_sql and _bl_monitor_on) {
        bl_send_mail(
            '<p>New MySQL Error from PHPBugLost</p>',
            'New MySQL Error from PHPBugLost',
            _bl::$msg_sql[$c]
        );
    }

    return $sql; // return resource
}



/**
 * bl_mysqli()
 * Execute a mysqli query and send the data to the log
 *
 * @access public
 *
 * @param string $query The query to run
 * @param mysqli $con Required, connection to mysql
 *
 * @return mysqli_result MySQL resource
 */
function bl_mysqli($query, mysqli $con, $debugnum = 0) {
    if (_bl_create_times) {
        bl_time('Start Query ' . substr($query, 0, 30)) . '...';
    }

    $debug = debug_backtrace();

    $error = '';

    // make query and get time
    // WTF! DRY!!
    $t_start = bl_get_time();
    $sql = $con->query($query);
    $time = bl_get_time($t_start);

    if ($con->error) {
        $error = $con->error;
    }

    // check for errros
    $insert_id = $results = '0';
    $explain_info = '';
    $q = trim(strtolower($query));
    if (substr($q, 0, 6) == 'insert') { // if is insert get the last id
        $insert_id = $con->insert_id;
    } else {
        if (substr($q, 0, 6) == 'select') { // if is select get num rows
            // explain query?
            $explain_info = '';
            if (_bl_explain_sql and !$error and _bl_production == false) {
                $sql_explain = $con->query("EXPLAIN " . $query);
                $explain = $sql_explain->fetch_assoc();

                $explain_info = '
                <p class="bl_explain">
                    <strong>EXPLAIN</strong> -&gt;Table: <em>' . $explain['table'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Type: <em>' . $explain['type'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Possible Keys: <em>' . $explain['possible_keys'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Key: <em>' . $explain['key'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Key len: <em>' . $explain['key_len'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Ref: <em>' . $explain['ref'] .
                        '</em> <span class="bl_msg_separator">|</span>
                    Extra: <em>' . $explain['Extra'] . '</em>
                </p>';

                $results = $explain['rows'];
            } else {
                $results = $sql->num_rows;
            }
        }
    }

    // add to the querys array
    _bl::$count_querys++;
    $c = _bl::$count_querys; // :)
    _bl::$msg_sql[$c]['query'] = $query;
    _bl::$msg_sql[$c]['time'] = $time;
    _bl::$msg_sql[$c]['insert'] = $insert_id;
    _bl::$msg_sql[$c]['result'] = $results;
    _bl::$msg_sql[$c]['explain'] = $explain_info;
    _bl::$msg_sql[$c]['error'] = (!empty($error)) ? '<span class="error">' . $error . '</span>' : '';
    _bl::$msg_sql[$c]['file'] = $debug[$debugnum]['file'];
    _bl::$msg_sql[$c]['line'] = $debug[$debugnum]['line'];

    if ($error and _bl_monitor_sql and _bl_monitor_on) {
        bl_send_mail(
                '<p>New MySQLi Error from PHPBugLost</p>', 'New MySQLi Error from PHPBugLost', _bl::$msg_sql[$c]
        );
    }

    return $sql; // return resource
}


/**
 * bl_sqlite()
 * Execute a sqlite query and send the data to the log
 *
 * @access public
 * @param string $query The query to run
 * @param resource connection to sqlite db/file
 * @return resource MySQL resource
 */
function bl_sqlite($query, $con, $debugnum = 0)
{
    $debug = array_reverse(debug_backtrace());
    $error = '';

    if (_bl_create_times) {
        bl_time('Start Query '.substr($query, 0, 30)).'...';
    }

    // make query and get time
    $t_start = bl_get_time();
    $sql = $con->query($query);
    $time = bl_get_time($t_start);


    // check for errros
    if ($sql) {

        $q = trim(strtolower($query));
        $insert_id = '0';
        if (substr($q, 0, 6) == 'insert') { // if is insert get the last id
            $insert_id = $con->lastInsertRowID();
        }

    } else {

        if ($con->lastErrorMsg()) {
            $error = $con->lastErrorMsg();
        } else {
            $error = 'Can\'t complete the query. Unknown Error'; // we need this??... may be...
        }

    }

    // add to the querys array
    _bl::$count_querys++;
    $c = _bl::$count_querys; // :)
    _bl::$msg_sql[$c]['query'] = $query;
    _bl::$msg_sql[$c]['time'] = $time;
    _bl::$msg_sql[$c]['insert'] = $insert_id;
    _bl::$msg_sql[$c]['result'] = '-';
    _bl::$msg_sql[$c]['explain'] = '';
    _bl::$msg_sql[$c]['error'] = (!empty($error)) ? '<span class="error">'.$error .'</span>' : '-';
    _bl::$msg_sql[$c]['file'] = $debug[$debugnum]['file'];
    _bl::$msg_sql[$c]['line'] = $debug[$debugnum]['line'];

    return $sql; // return resource
}


/**
 * bl_pdo()
 * Execute a pdo query and send the data to the log
 *
 * @access public
 * @param string $query The query to run
 * @param resource $con PDO connection
 * @return resource MySQL resource
 */
function bl_pdo($query, $con, $debugnum = 0)
{
    if (_bl_create_times) {
        bl_time('Start Query '.substr($query, 0, 30)).'...';
    }

    $debug = debug_backtrace();

    $t_start = $t_end = $error = '';

    // make query and get time
    $t_start = bl_get_time();
    $sql 	 = $con->query($query);
    $time 	 = bl_get_time($t_start);


    $insert_id = $num_result = '0';
    // check for errros
    if ($sql) {

        $q = trim(strtolower($query));
        if (substr($q, 0, 6) == 'insert') { // if is insert get the last id
            $insert_id = $con->lastInsertId();
        } elseif (substr($q, 0, 6) == 'select') {
            $num_result = $sql->rowCount();
        }

    } else {

        if ($con->errorInfo()) {
            $errorArray = $con->errorInfo();
            $error      = $errorArray[2];
            bl_error($error, $debug[$debugnum]['file'], $debug[$debugnum]['line']);
        } else {
            $error = 'Can\'t complete the query. Unknown Error'; // we need this??... may be...
        }
    }

    // add to the querys array
    _bl::$count_querys++;
    $c = _bl::$count_querys; // :)
    _bl::$msg_sql[$c]['query'] = $query;
    _bl::$msg_sql[$c]['time'] = $time;
    _bl::$msg_sql[$c]['insert'] = $insert_id;
    _bl::$msg_sql[$c]['result'] = $num_result;
    _bl::$msg_sql[$c]['explain'] = '';
    _bl::$msg_sql[$c]['error'] = (!empty($error)) ? '<span class="error">'.$error .'</span>' : '';
    _bl::$msg_sql[$c]['file'] = $debug[0]['file'];
    _bl::$msg_sql[$c]['line'] = $debug[0]['line'];

    return $sql; // return resource
}

/**
 * bl_convert()
 * Convert size in bytes
 *
 * @access private
 * @param mixed $size Size to messure
 *
 * @return mixed Size converted
 */
function bl_convert($size)
{
    if ($size > 0 and is_numeric($size)) {
        $unit = array(
            'b',
            'kb',
            'mb',
            'gb',
            'tb',
            'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) .
            ' <span>'.$unit[$i].'</span>';
    }
    return '0';
}

/**
 * bl_get_type()
 * Because gettype() not is the way?
 *
 * @access private
 *
 * @param mixed $var The var
 * @return string Type of var
 *
 * @return string The type of the var
 */
function bl_get_type($var)
{
    if (is_array($var)) {
        return 'Array';
    } elseif (is_object($var)) {
        return 'Object';
    } elseif (is_resource($var)) {
        return 'Resource';
    } elseif (is_bool($var)) {
        return 'Bool';
    } elseif (is_float($var)) {
        return 'Float';
    } elseif (is_double($var)) {
        return 'Double';
    } elseif (is_int($var)) {
        return 'Int';
    } elseif (is_numeric($var)) {
        return 'Numeric';
    } elseif (is_real($var)) {
        return 'Real';
    } else {
        // para todo lo demas...
        return 'String';
    }
}

/**
 * bl_high_array()
 * Add some colors to the arrays on the vars panel
 * TODO: may be the regex need some fix.
 *
 * @access private
 *
 * @param array $array An array
 * @return string Highlighted print_r array
 *
 * @return string A highlighted array
 */
function bl_high_array($array) {
    $array = preg_replace('/\[(.*?)\]/', '<strong>[$1]</strong>', print_r($array, true));
    $array = preg_replace('/\[/', '<span style="color:#f33">[</span>', $array);
    $array = preg_replace('/\]/', '<span style="color:#f33">]</span>', $array);
    $array = preg_replace('/=>/', '<span style="color:#005500">=></span>', $array);
    return $array;
}

/**
 * bl_high_sql()
 * Highlighter class - highlights SQL with preg and some compromises
 *
 * @access private
 * @author dzver <dzver@abv.bg>
 * @copyright GNU v 3.0
 *
 * @param string $sql SQL query
 *
 * @return string Highlighted sql query
 */
function bl_high_sql($sql)
{
    $colors = array(
        'chars' => 'grey',
        'keywords' => 'blue',
        'joins' => 'gray',
        'functions' => 'violet',
        'constants' => 'red');
    $words = array(
        'keywords' => array(
            'SELECT',
            'UPDATE',
            'INSERT',
            'DELETE',
            'REPLACE',
            'INTO',
            'CREATE',
            'ALTER',
            'TABLE',
            'DROP',
            'TRUNCATE',
            'FROM',
            'ADD',
            'CHANGE',
            'COLUMN',
            'KEY',
            'WHERE',
            'ON',
            'CASE',
            'WHEN',
            'THEN',
            'END',
            'ELSE',
            'AS',
            'USING',
            'USE',
            'INDEX',
            'CONSTRAINT',
            'REFERENCES',
            'DUPLICATE',
            'LIMIT',
            'OFFSET',
            'SET',
            'SHOW',
            'STATUS',
            'BETWEEN',
            'AND',
            'IS',
            'NOT',
            'OR',
            'XOR',
            'INTERVAL',
            'TOP',
            'GROUP BY',
            'ORDER BY',
            'DESC',
            'ASC',
            'COLLATE',
            'NAMES',
            'UTF8',
            'DISTINCT',
            'DATABASE',
            'CALC_FOUND_ROWS',
            'SQL_NO_CACHE',
            'MATCH',
            'AGAINST',
            'LIKE',
            'REGEXP',
            'RLIKE',
            'PRIMARY',
            'AUTO_INCREMENT',
            'DEFAULT',
            'IDENTITY',
            'VALUES',
            'PROCEDURE',
            'FUNCTION',
            'TRAN',
            'TRANSACTION',
            'COMMIT',
            'ROLLBACK',
            'SAVEPOINT',
            'TRIGGER',
            'CASCADE',
            'DECLARE',
            'CURSOR',
            'FOR',
            'DEALLOCATE'),
        'joins' => array(
            'JOIN',
            'INNER',
            'OUTER',
            'FULL',
            'NATURAL',
            'LEFT',
            'RIGHT'),
        'chars' => '/([\\.,\\(\\)<>:=`]+)/i',
        'functions' => array(
            'MIN',
            'MAX',
            'SUM',
            'COUNT',
            'AVG',
            'CAST',
            'COALESCE',
            'CHAR_LENGTH',
            'LENGTH',
            'SUBSTRING',
            'DAY',
            'MONTH',
            'YEAR',
            'DATE_FORMAT',
            'CRC32',
            'CURDATE',
            'SYSDATE',
            'NOW',
            'GETDATE',
            'FROM_UNIXTIME',
            'FROM_DAYS',
            'TO_DAYS',
            'HOUR',
            'IFNULL',
            'ISNULL',
            'NVL',
            'NVL2',
            'INET_ATON',
            'INET_NTOA',
            'INSTR',
            'FOUND_ROWS',
            'LAST_INSERT_ID',
            'LCASE',
            'LOWER',
            'UCASE',
            'UPPER',
            'LPAD',
            'RPAD',
            'RTRIM',
            'LTRIM',
            'MD5',
            'MINUTE',
            'ROUND',
            'SECOND',
            'SHA1',
            'STDDEV',
            'STR_TO_DATE',
            'WEEK'),
        'constants' => '/(\'[^\']*\'|[0-9]+)/i');

    $sql = str_replace('\\\'', '\\&#039;', $sql);
    foreach ($colors as $key => $color) {
        if (in_array($key, array('constants', 'chars'))) {
            $regexp = $words[$key];
        } else {
            $regexp = '/\\b('.join("|", $words[$key]).')\\b/i';
        }
        $sql = preg_replace($regexp, '<span style="color:'.$color."\">$1</span>",
            $sql);
    }

    return $sql;
}

/**
 * bl_get_querys()
 * Used for generate the HTML table of sql querys
 *
 * Developer Info: Using {@link mysql_q()} function we create an array whith
 * info for each query. This array is a global array and is
 * called _bl::$msg_sql. On bl_debug() function we call to this function
 * ({@link bl_get_querys()}) using this global array.
 * Then we iterate _bl::$msg_sql for get all the sql messages created
 * {@link mysql_q()}. Other functions {@link _bl_get_times},
 * {@link _bl_get_msg}, {@link _bl_get_memory}... are similar to this
 *
 * @access private
 *
 * @param array $bl_msg_sql The global array for SQL querys info
 *
 * @return string An HTML table whith each query info
 */
function bl_get_querys($bl_msg_sql)
{
    $result = '';

    // be sure $bl_msg_sql not is empty
    if (is_array($bl_msg_sql) and count($bl_msg_sql)) {

        // HTML table header
        $result = '
        <table class="bl_table_querys">
            <thead>
                <tr>
                    <th>Query</th>
                    <th>Type</th>
                    <th>Time</th>
                    <th>Insert ID</th>
                    <th>Num Results</th>
                    <th>Error</th>
                    <th>File</th>
                    <th>Line</th>
                </tr>
            </thead>
            <tbody>';

        // add rows to the table
        foreach ($bl_msg_sql as $k => $v) {

            $sql = trim($v['query']);
            $sql_type = substr(strtolower($sql), 0, 6);
            $type = '&nbsp;';

            if ($sql_type == 'select') {
                $type = '<span style="color:purple">SELECT</span>';

            } elseif ($sql_type == 'insert') {
                $type = '<span style="color:orange">INSERT</span>';

            } elseif ($sql_type == 'update') {
                $type = '<span style="color:olive">UPDATE</span>';

            }

            $results = (empty($v['result'])) ? '&nbsp;' : $v['result'];

            $explain = ($v['explain']) ? $v['explain'] : '';
            $result .= '
                <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                    <td>'.bl_high_sql($v['query']).$explain.'</td>
                    <td>'.$type.'</td>
                    <td>'.bl_format_time($v['time']).'</td>
                    <td>'.$v['insert'].'</td>
                    <td>'.$v['result'].'</td>
                    <td>'.$v['error'].'</td>
                    <td>'.bl_link_file($v['file'], $v['line']).'</td>
                    <td>'.$v['line'].'</td>
                </tr>';
        }

        // Close HTML table
        $result .= '
            </tbody>
        </table>';
    }

    return $result;
}

/**
 * bl_get_vars()
 * Used for generate the HTML table of vars (error, info, warn and log)
 *
 * @access private
 * @param mixed $array      Array of vars (generally with get_defined_vars())
 * @param mixed $array_name Name or type the array: user, special, post,
 *                          get, session... etc.
 *
 * @return void
 */
function bl_get_vars($array, $array_name, $id_prefix = '', $caption = '')
{
    $result = '';
    $count = $results = 0;

    if (count($array)) {

        $extra_cols = '';
        if ((_bl_delete_vars == true) and ($array_name == '_SESSION' or $array_name == '_COOKIE')) {
            $extra_cols = '
                    <th></th>';
        }

        if ($caption) {
            $caption = '<caption>'.$caption.'</caption>';
        }

        $thead = '';
        if ($id_prefix != 'watch') {
            $thead = '
            <thead>
                <tr>
                    <th>Var</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Size</th>
                    '.$extra_cols.'
                </tr>
            </thead>';
        }

        $result = '
        <table id="bl_table'.strtolower($array_name).'">
            '.$caption.'
            '.$thead.'
            <tbody>';

        $count = 0;
        foreach ($array as $k => $v) {

            // $k the name of the var
            // $v the value

            if (substr($k, 0, 7) == 'object_') {
                $k = str_replace('object_', '', $k);
            }

            // if is special var, the name has a simbol |
            // this simbol is for diferentiate more than once the same var
            // we need the second part, after |
            $explode = explode('|', $k);
            $k = $explode[0];

            $error = false;
            $count++;

            if (substr($k, 0, 3) == 'bl_' or substr($k, 0, 4) == '_bl_') {
                $error = true;
            } else {

                // Some versions of php use this type of vars (long name) and too the sort name.
                // Check if delete the long name for prevent duplicated vars.
                if (_bl_delete_long_vars) {
                    $delete_vars = array(
                        '_ENV',
                        'HTTP_ENV_VARS',
                        'HTTP_POST_VARS',
                        'HTTP_GET_VARS',
                        'HTTP_COOKIE_VARS',
                        'HTTP_SERVER_VARS',
                        'HTTP_POST_FILES',
                        '_REQUEST',
                        'HTTP_SESSION_VARS');
                    if (in_array($k, $delete_vars)) {
                        $error = true;
                    }
                }
            }

            if (!$error) {

                if ($array_name == '_USER') {
                    _bl::$count_vars++;
                }

                $var_type = bl_get_type($v);
                $toggle = $html_button = '';
                if ($var_type == 'Array') {

                    $var_type_name = ($var_type == 'Array') ? 'Array' : 'Object';

                    $valor = '
                    <a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_'.$array_name .
                        '_'.$count.'\')" id="'.$id_prefix.'a_'.$array_name.'_'.$count .
                        '" style="color:#008000">'.$var_type_name.'(...</a>
                    <div style="display:none;" id="'.$id_prefix.'div_'.$array_name.'_'.$count.'">
                        <p class="bl_close_array"><a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_' .
                        $array_name.'_'.$count.'\')">Close</a></p>
                        <pre>'.bl_high_array($v).'</pre>
                    </div>';

                } elseif ($var_type == 'Object') {

                    // methods
                    $valor = '
                    <a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_'.$array_name .
                        '_'.$count.'1\')" id="'.$id_prefix.'a_'.$array_name.'_'.$count .
                        '1" style="color:#008000">Methods</a>
                    <div style="display:none; margin-bottom:10px;" id="'.$id_prefix.'div_'.$array_name .
                        '_'.$count.'1">
                        <p class="bl_close_array"><a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_' .
                        $array_name.'_'.$count.'1\')">Close Methods</a></p>
                        <pre>'.bl_high_array(get_class_methods($v)).'</pre>
                    </div>';

                    $valor .= '
                    <a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_'.$array_name .
                        '_'.$count.'3\')" id="'.$id_prefix.'a_'.$array_name.'_'.$count .
                        '3" style="color:#008000">Object(...</a>
                    <div style="display:none; margin-bottom:10px;" id="'.$id_prefix.'div_'.$array_name .
                        '_'.$count.'3">
                        <p class="bl_close_array"><a href="javascript:void(0);" onclick="view_array(\''.$id_prefix.'div_' .
                        $array_name.'_'.$count.'3\')">Close Object</a></p>
                        <pre>'.bl_high_array($v).'</pre>
                    </div>';

                } elseif ($var_type == 'Bool') {
                    if ($v) {
                        $valor = '<span style="color:#0000ff">True</span>';
                    } else {
                        $valor = '<span style="color:#0000ff">False</span>';
                    }

                } elseif ($var_type == 'Int' or is_numeric($v)) {
                    $valor = '<span style="color:#f33">'.$v.'</span>';

                } elseif ($var_type == 'Float') {
                    $valor = '<span style="color:#f33">'.$v.'</span>';

                } elseif ($var_type == 'Resource') {
                    $valor = '['.get_resource_type($v).']';
                } else {

                    $valor = $v;

                    if ($var_type == 'String') {
                        $valor = htmlspecialchars($v);
                        $valor_html = '';

                        if (_bl_html_viewer == true) {
                            $valor_html = $v;
                            if ($valor_html != strip_tags($valor_html)) {
                                $html_button = '<a href="javascript:bl_view_html('._bl::$count_vars.')">[html]</a>';
                                $valor_html = '
                                <div id="bl_view_html_'._bl::$count_vars .
                                    '" style="display:none;" class="bl_view_html">
                                    <div class="bl_view_html_title">HTML Viewer</div>
                                    <div class="bl_view_html_content">
                                    '.$valor_html.'
                                    </div>
                                </div>';

                                $var_type = 'String|HTML';

                            } else {
                                $valor_html = '';
                            }
                        }

                        if (strlen($valor) > 200) {
                            $valor = '<div id="bl_view_'._bl::$count_vars.'">'.substr($valor, 0,
                                200).' [...]</div>
                            <div id="bl_view_more_'._bl::$count_vars.'" style="display:none;">
                                '.$valor.'
                                <p><a href="javascript:bl_toggle(\'bl_view_more_'._bl::$count_vars .
                                '\');bl_toggle(\'bl_view_'._bl::$count_vars.'\');">Close</a></p>
                            </div>
                            '.$valor_html;
                            $toggle = '<br /><a href="javascript:bl_toggle(\'bl_view_more_'._bl::$count_vars .
                                '\');bl_toggle(\'bl_view_'._bl::$count_vars.'\');">[...]</a>';
                        }

                    }

                }

                // Add content to empty vars for better render on html table
                if (empty($valor)) {
                    $valor = '&nbsp;';
                }

                // get var size (memory)
                if ($var_type == 'Object') {
                    $var_size = 0;
                    if (_bl_serialize_objects == true) {
                        $var_size = strlen(serialize($v));
                    }

                } else {
                    $var_size = strlen(serialize($v));
                }

                $prefix = '$';
                if ($array_name == '_CONSTANTS')
                    $prefix = '';
                $results++;

                $tr_id = 'bl_var'.$array_name.'_'.$count.'';

                $extra_cols = '';
                if ((_bl_delete_vars == true)
                    and ($array_name == '_SESSION' or $array_name == '_COOKIE')
                ) {
                    // bl_del_var(var_name, url, type, key)
                    $extra_cols = '
                        <td>
                            <a href="javascript:bl_del_var(\''.$k.'\', \'' .
                        _bl_path.'\', \''.$array_name.'\', \''._bl_secret_key.'\', \'' .
                        $tr_id.'\', \''._bl_var_del.'\');">delete</a>
                        </td>';
                }

                $result .= '
                    <tr id="'.$tr_id.'" onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                        <td class="bl_col_title">$'.$k.$toggle.$html_button.'</td>
                        <td class="bl_var_value"><pre>'.$valor.'</pre></td>
                        <td class="bl_var_type">'.$var_type.'</td>
                        <td class="bl_right bl_var_syze">'.bl_convert($var_size).'</td>
                        '.$extra_cols.'
                    </tr>';

                if ($var_size > _bl::$max_var_size['size']) {
                    _bl::$max_var_size['var'] = $k;
                    _bl::$max_var_size['size'] = $var_size;
                }

                $count++;
            }
        }
        $result .= '
            </tbody>
        </table>';

    }

    if ($results == 0) {
        $result = '<div class="bl_nothing"><p>Array '.$array_name.' is empty</p></div>';
    }

    return $result;
}

/**
 * bl_get_comments()
 * Use reflection class for get Comments of a class, method or function
 *
 * @param object $reflection A reflection object
 *
 * @return string The comment if there're any or a text "No phpDocs" if not.
 */
function bl_get_comments($reflection)
{
    $result = '';
    $comments = $reflection->getDocComment();
    if (empty($comments)) {
        $result = 'No phpDocs';
    } else {
        $comments = htmlspecialchars($comments);
        $result = '<span class="bl_orange">'.str_replace("\n", '<br />', $comments) .
            '</span>';
    }
    return $result;
}

/**
 * bl_get_functions()
 * Get a list of declared functions and generate an HTML table
 *
 * @return string An HTML table with a list of functions
 */
function bl_get_functions()
{
    $functions = get_defined_functions();
    $functions = $functions['user'];

    if (count($functions)) {
        $table = '
            <table id="bl_table_functions">
                <thead>
                    <tr>
                        <th>Function</th>
                        <th>File</th>
                        <th>Line</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tobdy>';
        $tr = '';

        foreach ($functions as $k => $function) {
            if (substr($function, 0, 3) == 'bl_') {
                unset($functions[$k]);
            } else {
                $reflection = new ReflectionFunction($function);
                $num_required_params = $reflection->getNumberOfRequiredParameters();
                $params = $reflection->getParameters();
                $function_params = '';
                $count = 1;

                foreach ($params as $param) {
                    if ($count > $num_required_params) {
                        $function_params .= '[$'.$param->name.'], ';
                    } else {
                        $function_params .= '$'.$param->name.', ';
                    }
                    $count++;
                }

                $comments = $reflection->getDocComment();
                if (empty($comments)) {
                    $comments = 'No phpDocs';
                }

                $tr .= '<tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                        <td><strong>'.$function.'</strong> ( '.rtrim($function_params,', ').' )</td>
                        <td>'.bl_link_file($reflection->getFileName(), $reflection->getStartLine()).'</td>
                        <td>'.$reflection->getStartLine().'</td>
                        <td>'.bl_get_comments($reflection).'</td>
                    </tr>';

            }
        }

        $table .= $tr.'</tbody></table>';

        $functions = $table;
    } else {
        $functions = '<div class="bl_nothing"><p>There aren\'t user functions</p></div>';
    }

    return $functions;
}

/**
 * bl_get_class_methods()
 * Get methods of a class (reflection)
 *
 * @param object $reflection A reflection object
 * @param string $count      Identifier for HTML class
 *
 * @return string
 */
function bl_get_class_methods($reflection, $count)
{
    $result = '';
    $methods = $reflection->getMethods();
    if (count($methods)) {
        foreach ($methods as $k => $method) {
            $method_params_text = '';
            $method_params = $reflection->getMethod($method->name)->getParameters();

            $access = '';
            if ($reflection->getMethod($method->name)->isPublic()) {
                $access = '<span class="bl_grey">public</span> ';
            }
            if ($reflection->getMethod($method->name)->isPrivate()) {
                $access = '<span class="bl_grey">private</span> ';
            }
            if ($reflection->getMethod($method->name)->isProtected()) {
                $access = '<span class="bl_grey">protected</span> ';
            }
            if ($reflection->getMethod($method->name)->isStatic()) {
                $access = '<span class="bl_grey">static</span> ';
            }

            foreach ($method_params as $param) {
                $method_params_text .= '$'.$param->name.', ';
            }
            $result .= '<span class="bl_class_'.$count.'">'.$access .
                '<span class="bl_blue"><strong>'.$method->name.'</strong></span>('.
                rtrim($method_params_text, ', ').');</span> ';
        }
    }

    if (empty($result)) {
        $result = 'nothing';
    } else {
        $result = rtrim($result, ' - ');
    }

    return $result;
}

/**
 * bl_get_class_methods()
 * Return properties of a class
 *
 * @param object $reflection A reflection object
 * @param string $count      Identifier for HTML class
 *
 * @return string
 */
function bl_get_class_properties($reflection, $count)
{
    $result = '';
    $properties = $reflection->getProperties();
    if (count($properties)) {
        foreach ($properties as $prop) {

            $access = '';
            if ($reflection->getProperty($prop->name)->isPublic()) {
                $access = '<span class="bl_grey">public</span> ';
            }
            if ($reflection->getProperty($prop->name)->isPrivate()) {
                $access = '<span class="bl_grey">private</span> ';
            }
            if ($reflection->getProperty($prop->name)->isProtected()) {
                $access = '<span class="bl_grey">protected</span> ';
            }
            if ($reflection->getProperty($prop->name)->isStatic()) {
                $access = '<span class="bl_grey">static</span> ';
            }

            $result .= '<span class="bl_class_'.$count.'">'.$access .
                '<span class="bl_blue"><strong>'.$prop->name.';</strong></span></span> ';
        }
    }

    if (empty($result)) {
        $result = 'nothing';
    } else {
        $result = rtrim($result, ' - ');
    }

    return $result;
}

/**
 * Get declared vars and create the html code for the vars panel
 */
function bl_get_classes()
{

    $classes = get_declared_classes();

    $result = array("user" => "", "internal" => "");

    $table = '
        <table id="bl_table_{mode}">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Methods</th>
                    <th>Properties</th>
                    <th>File</ht>
                    <th>Line</ht>
                    <th>Comments</th>
                </tr>
            </thead>
            <tobdy>';
    $utr = $itr = '';

    $count = '0';
    if (count($classes)) {
        foreach ($classes as $class) {

            if (substr($class, 0, 3) != 'bl_' and substr($class, 0, 3) != '_bl') {
                $reflection = new ReflectionClass($class);
                $methods = $properties = '';

                if ($reflection->isInternal()) {
                    if (_bl_show_internal_classes == true) {
                        $methods = bl_get_class_methods($reflection, $count);
                        $properties = bl_get_class_properties($reflection, $count);
                        $itr .= '
                            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                                <td><strong>'.$class.'</strong><br />
                                    <a href="#" onclick="bl_expand(\''.$count.'\')">expand</a></td>
                                <td>'.$methods.'</td>
                                <td>'.$properties.'</td>
                                <td> - </td>
                                <td>
                                    <div id="bl_method_comments_expand_'.$count .
                           '"></div>
                                    <div id="bl_method_comments_'.$count .
                            '" style="display:none;"></div></td>
                            </tr>';
                    }

                } else {

                    $comments = bl_get_comments($reflection);
                    $comments_expand = 'no phpDocs';
                    if ($comments != 'No phpDocs') {
                        $comments_expand = '<span class="bl_orange">expand for comments</span>';
                    } else {
                        $comments_expand = 'no phpDocs';
                    }

                    $methods = bl_get_class_methods($reflection, $count);
                    $properties = bl_get_class_properties($reflection, $count);
                    $utr .= '
                        <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                            <td><strong>'.$class.'</strong><br />
                                <a href="#" onclick="bl_expand(\''.$count.'\')">expand</a></td>
                            <td>'.$methods.'</td>
                            <td>'.$properties.'</td>
                            <td>'.bl_link_file($reflection->getFileName(), $reflection->getStartLine()).'</td>
                            <td>'.$reflection->getStartLine().'</td>
                            <td>
                                <div id="bl_method_comments_expand_'.$count.'">' .
                        $comments_expand.'</div>
                                <div id="bl_method_comments_'.$count .
                        '" style="display:none;">'.$comments.'</div>
                            </td>
                        </tr>';
                }

                $count++;
            }
        }

    }

    $result['user'] = str_replace('{mode}', 'uclasses', $table).$utr .
        '</tbody></table>';
    $result['internal'] = str_replace('{mode}', 'iclasses', $table).$itr .
        '</tbody></table>';

    return $result;

}


/**
 * Return usage info
 */
function bl_get_usage()
{
    if (PHP_OS == 'Linux') {
        $usage = getrusage();

        $tr = '';

        if (isset($usage['ru_oublock'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_oublock</td>
                <td>block output operations</td>
                <td>'.$usage['ru_oublock'].'</td>
            </tr>';
        }

        if (isset($usage['ru_inblock'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_inblock</td>
                <td>block input operations</td>
                <td>'.$usage['ru_inblock'].'</td>
            </tr>';
        }

        if (isset($usage['ru_msgsnd'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_msgsnd</td>
                <td>messages sent</td>
                <td>'.$usage['ru_msgsnd'].'</td>
            </tr>';
        }

        if (isset($usage['ru_msgrcv'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_msgrcv</td>
                <td>messages received</td>
                <td>'.$usage['ru_msgrcv'].'</td>
            </tr>';
        }

        if (isset($usage['ru_maxrss'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_maxrss</td>
                <td>maximum resident set size</td>
                <td>'.$usage['ru_maxrss'].'</td>
            </tr>';
        }

        if (isset($usage['ru_ixrss'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_ixrss</td>
                <td>integral shared memory size</td>
                <td>'.$usage['ru_ixrss'].'</td>
            </tr>';
        }

        if (isset($usage['ru_idrss'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_idrss</td>
                <td>integral unshared data size</td>
                <td>'.$usage['ru_idrss'].'</td>
            </tr>';
        }

        if (isset($usage['ru_minflt'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_minflt</td>
                <td>page reclaims</td>
                <td>'.$usage['ru_minflt'].'</td>
            </tr>';
        }

        if (isset($usage['ru_majflt'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_majflt</td>
                <td>page faults</td>
                <td>'.$usage['ru_majflt'].'</td>
            </tr>';
        }

        if (isset($usage['ru_nsignals'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_nsignals</td>
                <td>signals received</td>
                <td>'.$usage['ru_nsignals'].'</td>
            </tr>';
        }

        if (isset($usage['ru_nvcsw'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_nvcsw</td>
                <td>voluntary context switches</td>
                <td>'.$usage['ru_nvcsw'].'</td>
            </tr>';
        }

        if (isset($usage['ru_nivcsw'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_nivcsw</td>
                <td>involuntary context switches</td>
                <td>'.$usage['ru_nivcsw'].'</td>
            </tr>';
        }

        if (isset($usage['ru_nswap'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_nswap</td>
                <td>swaps</td>
                <td>'.$usage['ru_nswap'].'</td>
            </tr>';
        }

        if (isset($usage['ru_utime.tv_usec'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_utime.tv_usec</td>
                <td>user time used (microseconds)</td>
                <td>'.$usage['ru_utime.tv_usec'].'</td>
            </tr>';
        }

        if (isset($usage['ru_utime.tv_sec'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_utime.tv_sec</td>
                <td>user time used (seconds)</td>
                <td>'.$usage['ru_utime.tv_sec'].'</td>
            </tr>';
        }

        if (isset($usage['ru_stime.tv_usec'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_stime.tv_usec</td>
                <td>system time used (microseconds)</td>
                <td>'.$usage['ru_stime.tv_usec'].'</td>
            </tr>';
        }

        if (isset($usage['ru_stime.tv_sec'])) {
            $tr .= '
            <tr onmouseover="bl_highlight_row(true, this)" onmouseout="bl_highlight_row(false, this)">
                <td>ru_stime.tv_sec</td>
                <td>system time used (seconds)</td>
                <td>'.$usage['ru_stime.tv_sec'].'</td>
            </tr>';
        }

        $result = '
        <table>
            '.$tr.'
        </table>';
    } else {
        $result = '<div class="bl_nothing"><p>Only for Linux systems. You are using ' .
            PHP_OS.'</p></div>';
    }

    return $result;
}

/**
 * bl_included_files()
 *
 * get php included files and generate a table
 *
 * @access private
 * @return HTML table with included files
 */
function bl_included_files()
{
    $result = '';

    $files = get_included_files();
    asort($files);
    if (is_array($files) and count($files)) {

        $result = '<table>
            <thead>
                <tr>
                    <th>File</th>
                    <th class="bl_right">Size</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($files as $file) {

            $filesize = filesize($file);

            if ($filesize > _bl::$max_file_size['size']) {
                _bl::$max_file_size['size'] = $filesize;
                $separate_path = explode(DIRECTORY_SEPARATOR, $file); // separate...
                _bl::$max_file_size['file'] = end($separate_path); // and get only the name of the file
            }

            if ($filesize > 0) {
                $filesize = bl_convert($filesize);
            }

            $result .= '
                <tr>
                    <td class="bl_col_title">'.bl_link_file($file, 0) .
                '</td>
                    <td class="bl_right">'.$filesize.'</td>
                </tr>';

        }
        $result .= '
            </tbody>
        </table>';
    }

    return $result;

}

/**
 * bl_get_times()
 *
 * Get html table from time marks
 *
 * @access private
 * @return HTML table with time marks
 */
function bl_get_times($times)
{
    $result = '';

    if (is_array($times) and count($times)) {

        $result = '<table>
            <thead>
                <tr>
                    <th>Label</th>
                    <th class="bl_right">Value</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($times as $time) {

            $result .= '
                <tr>
                    <td>'.$time['label'].'</td>
                    <td class="bl_right">'.$time['time'].'</td>
                </tr>';
        }
        $result .= '
            </tbody>
        </table>';
    }

    return $result;

}

/**
 * bl_media();
 *
 * Get css and js tags
 *
 * @access private
 * @param string $source List of files separated by comma
 * @param string $type Type of files (css|js)
 * @return string HTML code (<link> or <script>)
 */
function bl_media($source, $type)
{
    $result = '';

    $files = explode(',', $source);
    foreach ($files as $file) {
        if ($type == 'css') {
            $result .= '<link rel="stylesheet" href="'.$file.'" type="text/css" />';
        } else {
            $result .= '<script src="'.$file.'" type="text/javascript"></script>';
        }
    }

    return $result;
}

/**
 * bl_css()
 *
 * Get css. Use internal css or external if _bl_css_file has a file list
 *
 * @access private
 * @return string The HTML code (<style> or <link>)
 */
function bl_css()
{

    $result = "<style type=\"text/css\">
    #bl_debug *{margin:0;padding:0;color:#111;z-index:100000; background-color:transparent;font-size:100%;text-align:left}#bl_debug a{text-decoration:none}#bl_debug_wrap{width:95%;margin:0 auto 0;position:fixed;bottom:0;margin-left:2%;margin-right:5px; font-family:'Lucida Sans Unicode','Lucida Grande',sans-serif;font-size:13px;z-index:999999999;position:fixed;left:0px;bottom:0px}body >div#bl_debug_wrap{position:fixed;left:0px;bottom:0px}.bl_opacity{opacity:0.1}#bl_debug span.error{color:#f00}#bl_debug_header{padding:10px;color:#fff;height:20px;overflow:hidden;background:#ea0105;background:-moz-linear-gradient(top,#ea0105 1%,#ad0008 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ea0105),color-stop(100%,#ad0008));background:-webkit-linear-gradient(top,#ea0105 1%,#ad0008 100%);background:-o-linear-gradient(top,#ea0105 1%,#ad0008 100%);background:-ms-linear-gradient(top,#ea0105 1%,#ad0008 100%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#ea0105',endColorstr='#ad0008',GradientType=0 );background:linear-gradient(top,#ea0105 1%,#ad0008 100%)}#bl_debug_menu{float:left}#bl_debug_menu *{color:#fff !important}#bl_debug_menu ul{overflow:hidden;list-style-type:none}#bl_debug_menu li,#bl_debug_menu a{display:block;float:left}#bl_debug_menu a{padding:3px;float:left;margin:0 10px;color:#fff !important;outline:none}#bl_debug_menu a.bl_debug_activo,#bl_debug_toggle a{background-color:#BA0106;border:1px solid #222;border-radius:5px}#bl_debug_menu sup{font-size:8px}#bl_debug_toggle{float:right;width:250px;font-size:10px}#bl_debug_toggle_buttons a{float:right;margin:0 3px;padding:2px 3px;color:#fff;text-decoration:none; outline:none}#bl_debug_toggle_buttons a{text-decoration:none}#bl_tool_box{position:absolute;bottom:40px;right:100px;width:460px; height:400px;border:3px solid #E80005;background-color:#fff; border-bottom:none;overflow:auto;overflow:hidden;opacity:0.9}#bl_debug_toggle #bl_tool_box a{background:none;border:none;text-decoration:none}#bl_debug_toggle #bl_tool_box a{color:#f33;font-size:12px} #bl_debug_toggle #bl_tool_box a:hover{color:#f00;text-decoration:underline}#bl_debug_toggle #bl_tool_box ul{margin:0 0 10px 5px}#bl_debug_toggle #bl_tool_box h3{margin-bottom:5px;font-size:14px}#bl_js_css{width:200px;word-wrap:break-word;float:left; border-left:1px solid #ccc;padding:15px}#bl_bookmarks{width:200px;float:left;padding:10px}.bl_half_panel{height:300px}.bl_close_panel{height:0;display:none}.bl_full_panel{height:600px}#bl_debug_content{border:5px solid #EA0105;border-bottom:none;background-color:#fff}#bl_debug table{font-size:11px}#bl_debug table th{text-align:left;padding:10px;background-color:#ddf1fb; border-bottom:1px dashed #ccc}#bl_debug table td{padding:5px;border-bottom:1px dashed #555; vertical-align:top;text-align:left}#bl_debug table td.bl_col_title{background-color:#f6f6f6;font-weight:bold;border-right:1px dashed #ccc}#bl_debug_panels{background-color:#fff;overflow:auto}.bl_half_panel #bl_debug_panels{height:300px}.bl_full_panel #bl_debug_panels{height:600px}#bl_debug_panels div.bl_debug_panel_active{display:block}#bl_debug .bl_panel_info a{color:#008000;text-decoration:none}.bl_panel_info{width:85%;float:left}.bl_debug_panel{display:none;overflow:auto}.bl_full_panel .bl_debug_panel{display:none; height:600px}#bl_debug_var_panels,#bl_debug_php_panels, #bl_debug_profile_panels#bl_debug_eval_panels{background-color:#fff;overflow:auto}.bl_debug_var_panel,.bl_debug_php_panel,.bl_debug_profile_panel,.bl_debug_eval_panel{display:none;overflow:auto}#bl_debug_var_panels div.bl_debug_var_panel_activo, #bl_debug_php_panels div.bl_debug_php_panel_activo,#bl_debug_profile_panels div.bl_debug_profile_panel_activo,#bl_debug_eval_panels div.bl_debug_eval_panel_activo {display:block}#bl_debug #bl_debug_info h3,#bl_debug #bl_debug_info p,#bl_debug #bl_debug_info ul{margin-bottom:15px}#bl_debug #bl_debug_info ul{margin-left:15px !important}#bl_debug .bl_menu_vertical{background-color:#880400;width:auto; overflow:hidden;min-width:100px;float:left}#bl_debug .bl_menu_vertical li,.bl_menu_vertical a{display:block}#bl_debug .bl_menu_vertical a{display:block;background-color:#DA1010;padding:3px;border-bottom:1px solid #ea0105;color:#fff}#bl_debug .bl_menu_vertical a:hover{background-color:#C92929}#bl_debug_msg_menu a.bl_debug_msg_btn_activo, #bl_debug_var_menu a.bl_debug_var_btn_activo, #bl_debug_php_menu a.bl_debug_php_btn_activo, #bl_debug_profile_menu a.bl_debug_profile_btn_activo,#bl_debug_eval_menu a.bl_debug_eval_btn_activo{ background-color:#fff;color:#333}#bl_debug_msg_menu a.bl_debug_msg_btn_activo:hover, #bl_debug_var_menu a.bl_debug_var_btn_activo:hover, #bl_debug_php_menu a.bl_debug_php_btn_activo:hover, #bl_debug_profile_menu a.bl_debug_profile_btn_activo:hover,#bl_debug_eval_menu a.bl_debug_eval_btn_activo:hover{ background-color:#fff;color:#333}#bl_debug .in20{padding:20px}#bl_debug .bl_debug_var_content .no_top_in{padding-top:0 !important}#bl_debug .in10{padding:10px}#bl_debug .bl_border_top{border-top-left-radius:0.8ex;border-top-right-radius:0.8ex}#bl_debug .bl_right{text-align:right}#bl_debug .bl_nothing p{color:#666 !important;font-size:3em;text-align:center;padding:20px}#bl_debug .bl_opacity{opacity:0.3;filter:alpha(opacity = 30)}.bl_vars_box{width:250px;height:250px;float:left;margin:20px}.bl_vars_box_title{background-color:#222;color:#fff}#bl_debug td.bl_msg_error{background-color:#f33;color:#000}#bl_debug td.bl_msg_warn{background-color:#F90;color:#000}#bl_debug td.bl_msg_info{background-color:#36F;color:#000}#bl_debug td.bl_msg_user{background-color:#333;color:#000}#bl_debug .bl_normal_td .bl_td{background-color:transparent}#bl_debug .bl_hover_td .bl_td{background-color:#999}#bl_debug .bl_msg_info{font-weight:bold}#bl_debug .bl_msg_file{font-style:italic}#bl_debug .bl_msg_line{font-style:italic}#bl_debug .bl_msg_separator{color:#f00;margin:0 10px}#bl_debug .bl_msg_table tbody tr{display:none}#bl_debug .bl_msg_table tr.bl_highlight_row td.bl_td{background-color:#eee}#bl_debug .bl_msg_table tbody tr.bl_msg_activo{display:table-row}#bl_debug table.bl_backtrace tr{display:table-row}#bl_debug table.bl_backtrace th,#bl_debug table.bl_backtrace td{border-bottom:none;padding:2px}#bl_memory_box{width:200px}#bl_memory_box,#bl_included{padding:5px;background-color:#f6f6f6;border:1px solid #ccc;float:left;margin:0 10px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px}#bl_memory_box h3,#bl_includedh3{padding:5px;border-bottom:1px dashed #666;margin-bottom:10px}#bl_memory_box span{display:block}#bl_debug .bl_view_html{display:none;margin:10px}#bl_debug .bl_view_html_title{width:100px;margin-right:20px;padding:5px;background-color:#ccc}#bl_debug .bl_view_html_content{border:1px solid #ccc;padding:10px}#bl_debug_heatmap{text-align:left}#bl_debug .bl_ajax_msg{margin-bottom:10px;border-bottom:1px dashed #ccc; padding:5px}#bl_debug .bl_ajax_msg a{color:#00f;margin:0 20px}#bl_debug .bl_ajax_msg span{font-size:small;color:#444;font-family:monospace}#bl_debug .bl_ajax_msg span.bl_highlight_error{color:#f33}#bl_debug .bl_ajax_request{margin:0 10px 20px}#bl_debug .bl_ajax_response{border:1px solid #ccc;overflow:auto;padding:10px;margin:10px}#bl_debug .bl_ajax_menu{margin:0;padding:0;list-style-type:none;overflow:hidden;margin-top:10px}#bl_debug .bl_ajax_menu li,#bl_debug .bl_ajax_menu a{float:left;display:block}#bl_debug .bl_ajax_menu a{margin:0 5px;padding:3px;background-color:#eee;color:#333;text-decoration:none}#bl_debug .bl_ajax_menu a.bl_active{background-color:#ccc;color:#111}#bl_debug .bl_ajax_header{padding:3px;background-color:#dfefff}#bl_debug .bl_box{width:200px;margin-bottom:15px}#bl_debug .bl_box2{width:400px}#bl_debug .bl_box,#bl_debug .bl_box2,#bl_included{padding:5px;background-color:#f6f6f6;border:1px solid #ccc;float:left;margin:0 10px 15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px}#bl_debug .bl_box h3,#bl_debug .bl_box2 h3,#bl_includedh3{ padding:5px;border-bottom:1px dashed #666;margin-bottom:10px}#bl_debug .bl_box span,#bl_debug .bl_box2 span{display:block}#bl_debug .bl_view_html{display:none;margin:10px}#bl_debug .bl_view_html_title{width:100px;margin-right:20px;padding:5px;background-color:#ccc}#bl_debug .bl_view_html_content{border:1px solid #ccc;padding:10px}#bl_debug .bl_filter_box{background-color:#f3f3f3;border-radius:3px; width:200px;padding:5px;margin-bottom:5px;border:1px solid #ccc}#bl_debug .bl_filter_box input{border:1px solid #444}#bl_show_errors{padding:5px;background-color:#fff;border:2px solid #f00; position:fixed;top:10px;right:10px;display:block;font-size:14px; font-weight:bold}#bl_debug .bl_blue{color:#00F}#bl_debug .bl_blue strong{color:#00F}#bl_debug .bl_grey{color:#999}#bl_debug .bl_orange{color:#F60}#bl_debug #bl_file_container{position:fixed;width:90%;height:80%; top:50px;left:50px; border:4px solid #333;background-color:#fff; display:none;overflow:hidden; -webkit-box-shadow:0px 0px 30px rgba(15,15,15,1); -moz-box-shadow: 0px 0px 30px rgba(15,15,15,1); box-shadow: 0px 0px 30px rgba(15,15,15,1)}#bl_debug #bl_file_explorer{overflow:auto;height:95%}#bl_debug #bl_header_browser{overflow:hidden;height:5%;background-color:#333; font-size:1.2em;font-weight:bold;color:#eee !important}#bl_debug #bl_header_browser p,#bl_debug #bl_header_browser a{color:#fff !important}#bl_debug #bl_file_container .highlight_line{background-color:#C3E9FF}#bl_debug #bl_loading{display:none;padding:5px; background-color:#fff;color:#fff;font-weight:bold; position:fixed;margin-left:45%;width:100px;text-align:center; border:4px solid #ea0105;border-top:none}#bl_debug #bl_eval_code{width:95%}#bl_debug .bl_half_panel #bl_eval_code{height:240px}#bl_debug .bl_full_panel #bl_eval_code{height:500px}#bl_eval_submit{padding:5px 15px;background-color:#333;cursor:pointer;color:#fff;border-radius:5px;border:1px solid #000}#bl_eval_submit:hover{background-color#555}#bl_debug .bl_trace{margin-bottom:10px}#bl_debug .bl_trace_head a.bl_trace_link{display:block;padding:2px 5px;background-color:#eee;color:#222;border:1px solid #ccc;border-radius:5px}#bl_debug .bl_trace_title {font-weight:bold;font-size:1.2em}#bl_debug .bl_trace_time {font-weight:bold;color:green}#bl_debug .bl_trace_memory {font-weight:bold;color:red}#bl_debug .bl_trace_info{display:none;margin:-1px 0 0 20px}#bl_debug .bl_trace_info table{border:1px solid #ccc;border-top:0}#bl_debug .bl_trace_info caption{background-color:#eee;border-left:1px solid #ccc;border-right:1px solid #ccc;padding:3px}#bl_debug .bl_trace_info th{background-color:#eee;padding:3px;font-weight:normal}
    </style>";

    // WTF Parse error... expecting T_PAAMAYIM_NEKUDOTAYIM
    $x = _bl_css_file;
    if (!empty($x)) {
        $result = bl_media(_bl_css_file, 'css');
    }

    return $result;
}

/**
 * bl_js()
 *
 * Like (@link bl_css())
 *
 * @access private
 * @return string HTML code (<style> or <link>)
 */
function bl_js()
{
    $result = "
    <script type=\"text/javascript\">
    var bl_shortcuts=true,bl_key_msg=49,bl_key_sql=50,bl_key_vars=51,bl_key_profile=52,bl_key_time=53,bl_key_memory=54,bl_key_ajax=55,bl_key_php=56,bl_key_eval=57,bl_key_jscss=74,bl_key_opacity=79,bl_key_info=73,bl_key_plus=77,bl_key_close=88;var \$bl=function(id){return document.getElementById(id)};String.prototype.trim=function(){return this.replace(/^\s+|\s+\$/g,\"\")};String.prototype.ltrim=function(){return this.replace(/^\s+/,\"\")};String.prototype.rtrim=function(){return this.replace(/\s+\$/,\"\")};Element.prototype.hasClass=function(class_name){this.className=this.className.replace(/^\s+|\s+\$/g,\"\");this.className=\" \"+this.className+\" \";if(this.className.search(\" \"+class_name+\" \")!==-1){return true}this.className=this.className.replace(/^\s+|\s+\$/g,\"\");return false};Element.prototype.removeClass=function(class_name){this.className=this.className.replace(class_name,'');this.className=this.className.replace(/^\s+|\s+\$/g,\"\")};Element.prototype.addClass=function(class_name){this.className=this.className+' '+class_name;this.className=this.className.replace(/^\s+|\s+\$/g,\"\")};function bl_toggle(obj,mode){var el=document.getElementById(obj);if(mode==='more'){document.getElementById(\"bl_debug_content\").style.display='block';if(el.className==='bl_full_panel'){el.className='bl_half_panel'}else{el.className='bl_full_panel'}}else{if(el.style.display!=='block'){el.style.display='block'}else{el.style.display='none'}}}function randomString(length){var str,i,chars='abcdefghiklmnopqrstuvwxyz'.split('');if(!length){length=Math.floor(Math.random()*chars.length)}for(i=0;i<length;i+=1){str+=chars[Math.floor(Math.random()*chars.length)]}return str}function time(ms){var t=ms/1000;return Math.round(t*100)/100}function bl_listen(event,elem,func,id){if(id){elem=\$bl(elem)}else{elem=document}if(elem){if(elem.addEventListener){elem.addEventListener(event,func,false)}else if(elem.attachEvent){var r=elem.attachEvent(\"on\"+event,func);return r}else{throw'No es posible añadir evento';}}}bl_listen('keyup','body',bl_keydown);function bl_keydown(e){var target;if(!bl_shortcuts){return}if(navigator.appName==='Microsoft Internet Explorer'){e=window.event;target=e.srcElement.nodeName.toLowerCase()}else{target=e.target.localName}if(target==='html'||target==='body'){if(e.keyCode===bl_key_msg){bl_debug_set_panel('msg')}else if(e.keyCode===bl_key_sql){bl_debug_set_panel('sql')}else if(e.keyCode===bl_key_profile){bl_debug_set_panel('profile')}else if(e.keyCode===bl_key_vars){bl_debug_set_panel('vars')}else if(e.keyCode===bl_key_time){bl_debug_set_panel('time')}else if(e.keyCode===bl_key_memory){bl_debug_set_panel('memory')}else if(e.keyCode===bl_key_ajax){bl_debug_set_panel('ajax')}else if(e.keyCode===bl_key_php){bl_debug_set_panel('php')}else if(e.keyCode===bl_key_eval){bl_debug_set_panel('eval')}else if(e.keyCode===bl_key_jscss){bl_toggle('bl_tool_box')}else if(e.keyCode===bl_key_opacity){bl_opacity()}else if(e.keyCode===bl_key_info){bl_debug_set_panel('info')}else if(e.keyCode===bl_key_plus){bl_setPanelSize('plus')}else if(e.keyCode===bl_key_close){bl_setPanelSize('close')}}}function bl_view_html(el){var el1=document.getElementById('bl_view_html_'+el),el2=document.getElementById('bl_view_'+el),el3=document.getElementById('bl_view_more_'+el);if(el1.style.display==='block'){el1.style.display='none';el2.style.display='block';el3.style.display='none'}else{el1.style.display='block';el2.style.display='none';el3.style.display='none'}}function bl_show_errors(){bl_toggle('bl_show_errors')}function bl_alert_errors(){var bl_interval=setInterval(bl_show_errors(),500);setTimeout(\"clearInterval(\"+bl_interval+\")\",3000)}function bl_opacity(){var el=\$bl('bl_debug');if(el.hasClass('bl_opacity')){el.removeClass('bl_opacity')}else{el.addClass('bl_opacity')}}function bl_setPanelSize(size){var panel_size='close';if(size==='plus'){if(\$bl('bl_debug_content').className==='bl_half_panel'){\$bl('bl_debug_content').className='bl_full_panel';panel_size='full'}else{\$bl('bl_debug_content').className='bl_half_panel';panel_size='half'}}else if(size==='close'){\$bl('bl_debug_content').className='bl_close_panel';panel_size='close'}else{\$bl('bl_debug_content').className='bl_'+size+'_panel';panel_size='half'}if(panel_size==='close'){bl_setCookie('__bl_panel_active','none',1)}bl_setCookie('panel_size_bl',panel_size,1)}function bl_debug_set_panel(panel){var c1=\"bl_debug_panel\",c2=\"bl_debug_panel_active\",c3=\"bl_debug_btn\",c4=\"bl_debug_activo\";if(\$bl(\"bl_debug_\"+panel).hasClass(\"bl_debug_panel_active\")){\$bl(\"bl_debug_\"+panel).className=c1;\$bl(\"bl_debug_content\").className='bl_close_panel';\$bl(c3+\"_\"+panel).className=c3;bl_setPanelSize('close')}else{\$bl(\"bl_debug_msg\").className=c1;\$bl(\"bl_debug_sql\").className=c1;\$bl(\"bl_debug_vars\").className=c1;\$bl(\"bl_debug_time\").className=c1;\$bl(\"bl_debug_memory\").className=c1;\$bl(\"bl_debug_ajax\").className=c1;\$bl(\"bl_debug_info\").className=c1;\$bl(\"bl_debug_php\").className=c1;\$bl(\"bl_debug_eval\").className=c1;\$bl(\"bl_debug_profile\").className=c1;\$bl(\"bl_debug_\"+panel).className=c1+\" \"+c2;\$bl(\"bl_debug_btn_msg\").className=c3;\$bl(c3+\"_sql\").className=c3;\$bl(c3+\"_vars\").className=c3;\$bl(c3+\"_time\").className=c3;\$bl(c3+\"_memory\").className=c3;\$bl(c3+\"_ajax\").className=c3;\$bl(c3+\"_eval\").className=c3;\$bl(c3+\"_php\").className=c3;\$bl(c3+\"_profile\").className=c3;\$bl(c3+\"_\"+panel).className=c3+\" \"+c4;if(\$bl(\"bl_debug_content\").hasClass('bl_close_panel')){\$bl(\"bl_debug_content\").className='bl_half_panel';bl_setPanelSize('half')}}bl_setCookie('__bl_panel_active',panel,1)}function bl_debug_set_msg(type){var i,bl_search,bl_search2,e,allHTMLTags=document.getElementsByTagName(\"tr\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_normal_tr')!==-1){allHTMLTags[i].className=allHTMLTags[i].className.replace('bl_msg_activo','');bl_search=allHTMLTags[i].className.search('bl_debug_msg_'+type);bl_search2=allHTMLTags[i].className.search('bl_msg_activo');if(bl_search!==-1){if(bl_search2===-1){allHTMLTags[i].className=allHTMLTags[i].className+' bl_msg_activo'}}else{if(type==='all'){if(bl_search2===-1){allHTMLTags[i].className=allHTMLTags[i].className+' bl_msg_activo'}}}}}allHTMLTags=document.getElementsByTagName(\"a\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_msg_btn')!==-1){allHTMLTags[i].className='bl_debug_msg_btn'}}e=document.getElementById('bl_debug_msg_btn_'+type);e.addClass('bl_debug_msg_btn_activo')}function bl_debug_set_var(panel){var i,e,allHTMLTags=document.getElementsByTagName(\"div\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_var_panel')!==-1){allHTMLTags[i].className='bl_debug_var_panel'}}allHTMLTags=document.getElementsByTagName(\"a\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_var_btn')!==-1){allHTMLTags[i].className='bl_debug_var_btn'}}e=document.getElementById('bl_debug_var_btn_'+panel);e.addClass('bl_debug_var_btn_activo');e=document.getElementById('bl_debug_var_'+panel);e.addClass('bl_debug_var_panel_activo')}function bl_debug_set_php(panel){var i,e,allHTMLTags=document.getElementsByTagName(\"a\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_php_btn')!==-1){allHTMLTags[i].className='bl_debug_php_btn'}}allHTMLTags=document.getElementsByTagName(\"div\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_php_panel')!==-1){allHTMLTags[i].className='bl_debug_php_panel'}}e=document.getElementById('bl_debug_php_btn_'+panel);e.addClass('bl_debug_php_btn_activo');e=document.getElementById('bl_debug_php_'+panel);e.addClass('bl_debug_php_panel_activo')}function bl_debug_set_eval(panel){var i,e,allHTMLTags=document.getElementsByTagName(\"a\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_eval_btn')!==-1){allHTMLTags[i].className='bl_debug_eval_btn'}}allHTMLTags=document.getElementsByTagName(\"div\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_eval_panel')!==-1){allHTMLTags[i].className='bl_debug_eval_panel'}}e=document.getElementById('bl_debug_eval_btn_'+panel);e.addClass('bl_debug_eval_btn_activo');e=document.getElementById('bl_debug_eval_'+panel);e.addClass('bl_debug_eval_panel_activo')}function bl_debug_set_profile(panel){var i,e,allHTMLTags=document.getElementsByTagName(\"a\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_profile_btn')!==-1){allHTMLTags[i].className='bl_debug_profile_btn'}}allHTMLTags=document.getElementsByTagName(\"div\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_debug_profile_panel')!==-1){allHTMLTags[i].className='bl_debug_profile_panel'}}e=document.getElementById('bl_debug_profile_btn_'+panel);e.addClass('bl_debug_profile_btn_activo');e=document.getElementById('bl_debug_profile_'+panel);e.addClass('bl_debug_profile_panel_activo')}function bl_expand(count){var i,allHTMLTags=document.getElementsByTagName(\"span\");for(i=0;i<allHTMLTags.length;i+=1){if(allHTMLTags[i].className.search('bl_class_'+count)!==-1){if(allHTMLTags[i].style.display!=='block'){allHTMLTags[i].style.display='block';\$bl('bl_method_comments_expand_'+count).style.display='none';\$bl('bl_method_comments_'+count).style.display='block'}else{allHTMLTags[i].style.display='inline';\$bl('bl_method_comments_expand_'+count).style.display='block';\$bl('bl_method_comments_'+count).style.display='none'}}}}function filter(phrase,id){var words=\$bl(phrase).value.toLowerCase().split(\" \"),table=document.getElementById(id),ele,r,i,displayStyle;for(r=1;r<table.rows.length;r+=1){ele=table.rows[r].innerHTML.replace(/<[^>]+>/g,\"\");displayStyle=\"none\";for(i=0;i<words.length;i+=1){if(ele.toLowerCase().indexOf(words[i])>=0){displayStyle=\"\"}else{displayStyle=\"none\";break}}table.rows[r].style.display=displayStyle}}function filterUser(){filter('bl_filter_user','bl_table_user')}function filterSpecial(){filter('bl_filter_special','bl_table_special')}function filterFunctions(){filter('bl_filter_functions','bl_table_functions')}function filterUclasses(){filter('bl_filter_uclasses','bl_table_uclasses')}function filterIclasses(){filter('bl_filter_iclasses','bl_table_iclasses')}function filterConstants(){filter('bl_filter_constants','bl_table_constants')}function filterGet(){filter('bl_filter_get','bl_table_get')}function filterPost(){filter('bl_filter_post','bl_table_post')}function filterSession(){filter('bl_filter_session','bl_table_session')}function filterCookie(){filter('bl_filter_cookie','bl_table_cookie')}function filterFiles(){filter('bl_filter_files','bl_table_files')}function filterServer(){filter('bl_filter_server','bl_table_server')}bl_listen('keyup','bl_filter_user',filterUser,true);bl_listen('keyup','bl_filter_special',filterSpecial,true);bl_listen('keyup','bl_filter_functions',filterFunctions,true);bl_listen('keyup','bl_filter_uclasses',filterUclasses,true);bl_listen('keyup','bl_filter_iclasses',filterIclasses,true);bl_listen('keyup','bl_filter_constants',filterConstants,true);bl_listen('keyup','bl_filter_get',filterGet,true);bl_listen('keyup','bl_filter_post',filterPost,true);bl_listen('keyup','bl_filter_session',filterSession,true);bl_listen('keyup','bl_filter_cookie',filterCookie,true);bl_listen('keyup','bl_filter_files',filterFiles,true);bl_listen('keyup','bl_filter_server',filterServer,true);function bl_ajax(){var xmlhttp=false;try{xmlhttp=new ActiveXObject(\"Msxml2.XMLHTTP\")}catch(e){try{xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\")}catch(E){xmlhttp=false}}if(!xmlhttp&&typeof XMLHttpRequest!=='undefined'){xmlhttp=new XMLHttpRequest()}return xmlhttp}function bl_del_var(var_name,url,type,key,tr_id,url_var_name){var ajax;url=url+'?'+url_var_name+'=1&var='+var_name+'&type='+type+'&bl_key='+key;\$bl('bl_loading').style.display='block';ajax=bl_ajax();ajax.open(\"GET\",url,true);ajax.onreadystatechange=function(){if(ajax.readyState===4){\$bl('bl_loading').style.display='none';if(ajax.responseText==='ok'){var tr=\$bl(tr_id);tr.innerHTML='<td colspan=\"5\">var \$'+type+'[\"'+var_name+'\"]  deleted</td>'}else if(ajax.responseText==='error-key'){alert('There\'re a problem with your secret key')}else if(ajax.responseText==='error-cookie'){alert('Sorry, I can\t delete this cookie.')}else{alert('Error. No vars deleted!')}}};ajax.send(null)}function bl_load_file(file,line,url,key,url_var_name){if(!line){line=0}url=url+\"?\"+url_var_name+\"=\"+file+\"&line=\"+line+\"&key=\"+key;\$bl('bl_loading').style.display='block';\$bl('bl_file_container').innerHTML='';ajax=bl_ajax();ajax.open(\"GET\",url,true);ajax.onreadystatechange=function(){var scroll;if(ajax.readyState===4){\$bl('bl_loading').style.display='none';if(ajax.responseText==='error-key'){alert('There\'re a problem with your secret key')}else if(ajax.responseText==='error-file'){alert('File not found.')}else if(ajax.responseText==='error'){alert('Error...')}else{\$bl('bl_file_container').scrollTop=0;\$bl('bl_file_container').innerHTML=ajax.responseText;\$bl('bl_file_container').style.display='block';scroll=parseInt(line)-10;\$bl('line_'+scroll).scrollIntoView()}}};ajax.send(null)}bl_listen('submit','bl_eval_form',bl_eval_code,'bl_eval_form');function bl_eval_code(){var content=\$bl('bl_eval_code').value,url=\$bl('bl_url').value,key=\$bl('bl_secret_key').value,url_var_name=\$bl('bl_eval_url_name').value;url=url+\"?\"+url_var_name+\"=\"+content+\"&bl_key=\"+key;\$bl('bl_loading').style.display='block';ajax=bl_ajax();ajax.open(\"POST\",url,true);ajax.onreadystatechange=function(){if(ajax.readyState===4){\$bl('bl_loading').style.display='none';if(ajax.responseText==='error-key'){alert('There\'re a problem with your secret key')}else if(ajax.responseText==='error'){alert('Unknow Error...')}else{\$bl('bl_debug_eval_result').innerHTML=ajax.responseText;bl_debug_set_eval('result')}}};ajax.send(null);return false}function bl_highlight_row(highlight,el){if(highlight===true){el.addClass('bl_highlight_row')}else{el.removeClass('bl_highlight_row')}}function view_array(id){var div=document.getElementById(id),a=document.getElementById(id.replace('div_','a_'));if(div.style.display==='block'){div.style.display='none';a.style.display='block'}else{div.style.display='block';a.style.display='none'}}function htmlentities(str){return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\"/g,'&quot;')}function bl_get_js(){var html='',i,filename,viewSource='',js=document.getElementsByTagName('script');if(js.length>0){if(navigator.appName==='Netscape'){viewSource='view-source:'}for(i=0;i<js.length;i+=1){if(js[i].src.length){filename=js[i].src.substring(js[i].src.lastIndexOf('/')+1);html=html+'<li><a href=\"'+viewSource+js[i].src+'\" target=\"_blank\">'+filename+'</a></li>'}}\$bl('bl_js').innerHTML='<h3>Javascript Files</h3><ul>'+html+'</ul>'}}function bl_get_css(){var html='',i,filename,viewSource='',css=document.getElementsByTagName('link');if(css.length>0){\$bl('bl_css').innerHTML=\"\";if(navigator.appName==='Netscape'){viewSource='view-source:'}for(i=0;i<css.length;i+=1){if(css[i].href.length&&css[i].rel==='stylesheet'){filename=css[i].href.substring(css[i].href.lastIndexOf('/')+1);html=html+'<li><a href=\"'+viewSource+css[i].href+'\" target=\"_blank\">'+filename+'</a></li>'}}\$bl('bl_css').innerHTML='<h3>CSS Files</h3><ul>'+html+'</ul>'}}bl_get_js();bl_get_css();function bl_setCookie(c_name,value,exdays){var c_value,exdate=new Date();exdate.setDate(exdate.getDate()+exdays);c_value=escape(value)+((exdays===null)?\"\":\"; expires=\"+exdate.toUTCString());document.cookie=c_name+\"=\"+c_value+'; path=/'}
    </script>";

    if (_bl_ajax_active) {
        $result .= "
        <script type=\"text/javascript\">
          function bl_params_to_html(params,id){if(params){var param,i,p,ps;ps=params.split('&');param='<table>';for(i in ps){p=ps[i].split('=');param=param+'<tr><td><strong>'+p[0]+'</strong></td><td>'+p[1]+'</td>'}param=param+'</table>';return param}}function bl_msg_ajax(data,el_id){var params=data.params,e=document.getElementById('bl_debug_ajax_box');e.innerHTML='<div id=\"bl_'+el_id+'\" class=\"bl_ajax_msg\"><div class=\"bl_ajax_header\"><strong>'+data.method+'</strong> <a href=\"javascript:bl_ajax_response(\''+el_id+'\');\">'+data.url+'</a> <span id=\"bl_c_'+el_id+'\" class=\"\">[loading...]</span></div> <div class=\"bl_ajax_request\" id=\"bl_ajax_resume_'+el_id+'\" style=\"display:none;\"><ul class=\"bl_ajax_menu\"><li><a href=\"javascript:bl_set_ajax_view(\''+el_id+'\',\'params\');\">Params</a></li><li> <a href=\"javascript:bl_set_ajax_view(\''+el_id+'\',\'response\');\" class=\"bl_active\">Response</a></li></ul><div id=\"bl_d_'+el_id+'\" class=\"bl_ajax_response\">  </div><div id=\"bl_p_'+el_id+'\" style=\"display:none;\" class=\"bl_ajax_response\">'+bl_params_to_html(params,el_id)+'</div></div> </div>'+e.innerHTML}function bl_msg_ajax_end(data,el_id){var span_error,div,span=document.getElementById('bl_c_'+el_id);span.innerHTML='<span id=\"bl_c_c_'+el_id+'\">'+data.status+' '+data.statusText+'</span> '+time(data.time)+'s';span_error=document.getElementById('bl_c_c_'+el_id);if(data.status==='500'||data.status==='403'||data.status==='404'||data.status==='301'){span_error.addClass('bl_highlight_error')}if(data.params!==null){document.getElementById('bl_p_'+el_id).innerHTML=bl_params_to_html(data.params)}div=document.getElementById('bl_d_'+el_id);div.innerHTML='<pre>'+htmlentities(data.response)+'</pre>'}function bl_set_ajax_view(el_id,type){var params=document.getElementById('bl_p_'+el_id),response=document.getElementById('bl_d_'+el_id);if(type==='params'){params.style.display='block';response.style.display='none'}else{params.style.display='none';response.style.display='block'}}function bl_ajax_response(el_id){var e=document.getElementById('bl_ajax_resume_'+el_id);if(e.style.display==='block'){e.style.display='none'}else{e.style.display='block'}}var random,el_id,s_ajaxListener={};s_ajaxListener.tempOpen=XMLHttpRequest.prototype.open;s_ajaxListener.tempSend=XMLHttpRequest.prototype.send;s_ajaxListener.callback=function(){};(function(){function fReadyStateChange(oRequest){if(cXMLHttpRequest.onreadystatechange){cXMLHttpRequest.onreadystatechange.apply(oRequest)}oRequest.dispatchEvent({'type':\"readystatechange\",'bubbles':false,'cancelable':false,'timeStamp':new Date()+0})}function fGetDocument(oRequest){var oDocument=oRequest.responseXML,sResponse=oRequest.responseText,bIE;if(bIE&&sResponse&&oDocument&&!oDocument.documentElement&&oRequest.getResponseHeader(\"Content-Type\").match(/[^\/]+\/[^\+]+\+xml/)){oDocument=new window.ActiveXObject(\"Microsoft.XMLDOM\");oDocument.async=false;oDocument.validateOnParse=false;oDocument.loadXML(sResponse)}if(oDocument){if((bIE&&oDocument.parseError!==0)||!oDocument.documentElement||(oDocument.documentElement&&oDocument.documentElement.tagName===\"parsererror\")){return null}}return oDocument}function fSynchronizeValues(oRequest){try{oRequest.responseText=oRequest._object.responseText}catch(e){}try{oRequest.responseXML=fGetDocument(oRequest._object)}catch(e){}try{oRequest.status=oRequest._object.status}catch(e){}try{oRequest.statusText=oRequest._object.statusText}catch(e){}}function fCleanTransport(oRequest){oRequest._object.onreadystatechange=new window.Function()}var oXMLHttpRequest=window.XMLHttpRequest,bGecko=!!window.controllers,bIE=window.document.all&&!window.opera,bIE7=bIE&&window.navigator.userAgent.match(/MSIE 7.0/);function fXMLHttpRequest(){this._object=oXMLHttpRequest&&!bIE7?new oXMLHttpRequest():new window.ActiveXObject(\"Microsoft.XMLHTTP\");this._listeners=[]}function cXMLHttpRequest(){return new fXMLHttpRequest()}cXMLHttpRequest.prototype=fXMLHttpRequest.prototype;if(bGecko&&oXMLHttpRequest.wrapped){cXMLHttpRequest.wrapped=oXMLHttpRequest.wrapped}cXMLHttpRequest.UNSENT=0;cXMLHttpRequest.OPENED=1;cXMLHttpRequest.HEADERS_RECEIVED=2;cXMLHttpRequest.LOADING=3;cXMLHttpRequest.DONE=4;cXMLHttpRequest.prototype.readyState=cXMLHttpRequest.UNSENT;cXMLHttpRequest.prototype.responseText='';cXMLHttpRequest.prototype.responseXML=null;cXMLHttpRequest.prototype.status=0;cXMLHttpRequest.prototype.statusText='';cXMLHttpRequest.prototype.priority=\"NORMAL\";cXMLHttpRequest.prototype.onreadystatechange=null;cXMLHttpRequest.onreadystatechange=null;cXMLHttpRequest.onopen=null;cXMLHttpRequest.onsend=null;cXMLHttpRequest.onabort=null;cXMLHttpRequest.prototype.open=function(sMethod,sUrl,bAsync,sUser,sPassword){var d1=new Date(),el_id=randomString(12),data={},bl_url_ex,oRequest,fOnUnload,nState,el_count,el_count_now,el_count_sum;el_count=document.getElementById('bl_num_request');el_count_now=parseInt(el_count.innerHTML,10);if(el_count_now===0){document.getElementById('bl_debug_ajax_box').innerHTML=''}el_count_sum=el_count_now+1;el_count.innerHTML=el_count_sum;data.url=sUrl;data.method=sMethod;data.async=bAsync;data.params='';bl_url_ex=sUrl.split(\"?\");if(bl_url_ex[1]!=='undefined'){data.params=bl_url_ex[1]}bl_msg_ajax(data,el_id);delete this._headers;if(arguments.length<3){bAsync=true}this._async=bAsync;oRequest=this;nState=this.readyState;if(bIE&&bAsync){fOnUnload=function(){if(nState!==cXMLHttpRequest.DONE){fCleanTransport(oRequest);oRequest.abort()}};window.attachEvent(\"onunload\",fOnUnload)}if(cXMLHttpRequest.onopen){cXMLHttpRequest.onopen.apply(this,arguments)}if(arguments.length>4){this._object.open(sMethod,sUrl,bAsync,sUser,sPassword)}else if(arguments.length>3){this._object.open(sMethod,sUrl,bAsync,sUser)}else{this._object.open(sMethod,sUrl,bAsync)}this.readyState=cXMLHttpRequest.OPENED;fReadyStateChange(this);this._object.onreadystatechange=function(dd){var d2=new Date(),d3=d2-d1,params='',data={};if(oRequest._data!=='undefined'){params=oRequest._data}if(this.readyState===4){data.time=d3;data.response=this.response;data.status=this.status;data.statusText=this.statusText;data.params=params;bl_msg_ajax_end(data,el_id)}if(bGecko&&!bAsync){return}oRequest.readyState=oRequest._object.readyState;fSynchronizeValues(oRequest);if(oRequest._aborted){oRequest.readyState=cXMLHttpRequest.UNSENT;return}if(oRequest.readyState===cXMLHttpRequest.DONE){delete oRequest._data;if(bIE&&bAsync){window.detachEvent(\"onunload\",fOnUnload)}}if(nState!==oRequest.readyState){fReadyStateChange(oRequest)}nState=oRequest.readyState}};function fXMLHttpRequest_send(oRequest){oRequest._object.send(oRequest._data);if(bGecko&&!oRequest._async){oRequest.readyState=cXMLHttpRequest.OPENED;fSynchronizeValues(oRequest);while(oRequest.readyState<cXMLHttpRequest.DONE){oRequest.readyState++;fReadyStateChange(oRequest);if(oRequest._aborted){return}}}}cXMLHttpRequest.prototype.send=function(vData){if(cXMLHttpRequest.onsend){cXMLHttpRequest.onsend.apply(this,arguments)}if(!arguments.length){vData=null}if(vData&&vData.nodeType){vData=window.XMLSerializer?new window.XMLSerializer().serializeToString(vData):vData.xml;if(!oRequest._headers[\"Content-Type\"]){oRequest._object.setRequestHeader(\"Content-Type\",\"application/xml\")}}this._data=vData;fXMLHttpRequest_send(this)};cXMLHttpRequest.prototype.abort=function(){if(cXMLHttpRequest.onabort){cXMLHttpRequest.onabort.apply(this,arguments)}if(this.readyState>cXMLHttpRequest.UNSENT){this._aborted=true}this._object.abort();fCleanTransport(this);this.readyState=cXMLHttpRequest.UNSENT;delete this._data};cXMLHttpRequest.prototype.getAllResponseHeaders=function(){return this._object.getAllResponseHeaders()};cXMLHttpRequest.prototype.getResponseHeader=function(sName){return this._object.getResponseHeader(sName)};cXMLHttpRequest.prototype.setRequestHeader=function(sName,sValue){if(!this._headers){this._headers={}}this._headers[sName]=sValue;return this._object.setRequestHeader(sName,sValue)};cXMLHttpRequest.prototype.dispatchEvent=function(oEvent){var nIndex=0,oListener,oEventPseudo={'type':oEvent.type,'target':this,'currentTarget':this,'eventPhase':2,'bubbles':oEvent.bubbles,'cancelable':oEvent.cancelable,'timeStamp':oEvent.timeStamp,'stopPropagation':function(){},'preventDefault':function(){},'initEvent':function(){}};if(oEventPseudo.type===\"readystatechange\"&&this.onreadystatechange){(this.onreadystatechange.handleEvent||this.onreadystatechange).apply(this,[oEventPseudo])}for(oListener;oListener=this._listeners[nIndex];nIndex++){if(oListener[0]===oEventPseudo.type&&!oListener[2]){(oListener[1].handleEvent||oListener[1]).apply(this,[oEventPseudo])}}};cXMLHttpRequest.prototype.toString=function(){return'['+\"object\"+' '+\"XMLHttpRequest\"+']'};cXMLHttpRequest.toString=function(){return'['+\"XMLHttpRequest\"+']'};window.XMLHttpRequest=cXMLHttpRequest})();
        </script>";
    }

    $x = _bl_js_file;
    if (!empty($x)) {
        $result = bl_media(_bl_js_file, 'js');
    }

    return $result;
}

// from http://www.phpf1.com/tutorial/get-current-page-url.html
function bl_get_url()
{
    $protocol = strpos(
        strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false
            ? 'http' : 'https';

    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $params = $_SERVER['QUERY_STRING'];

    $currentUrl = $protocol.'://'.$host.$script.'?'.$params;

    return $currentUrl;
}


/**
 * Encoded loading image
 */
function bl_loading_image()
{
    return 'data:image/gif;base64,R0lGODlhFwAXAIcAAP////n5+by8vKioqPv7++rq6qenp/j4+PDw8Ofn5+/v793d3enp6dzc3MrKysHBwcXFxejo6J+fn66uruvr6+bm5r+/v7q6usLCwuzs7OLi4qKiopWVlZ6entvb28bGxu3t7aysrKSkpNbW1vz8/NfX18PDw9/f3/7+/tDQ0KWlpdPT0/39/dXV1ff398zMzM3Nzc7OzsjIyIqKioSEhIWFhd7e3q2trfLy8vb29vX19e7u7uDg4Pr6+nV1dYmJidnZ2bm5uZubm7CwsHZ2doaGhtHR0eTk5OHh4W5ubmdnZ42NjfHx8fT09GZmZoGBgeXl5YKCguPj44yMjMnJyfPz87a2tru7u8/Pz7KystTU1L29vb6+vrOzs7e3t9LS0rGxsdjY2JaWlpqamq+vr3x8fHh4eKOjo7W1tZOTk4CAgH19fcvLy3Nzc5SUlI+Pj3R0dJCQkKGhoZiYmMDAwIuLi5mZmYiIiNra2nt7e7S0tKCgoMfHx5eXl3l5eW1tbQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgCAACwAAAAAFwAXAAAI/wABCBw4MICAAQIIEFzIcGABAxAPNFyIIIGCgQsgGpAokEGDAhQdPIAQQWDGiAIbGJAwgQLBChYuXMCQAcDJjQA0bODAoYMHggg+yJyJAEQIAyEOMBDBk8MABgsTPBg6gkSJDyVIWGi64SdDCiZkngCAggQKACl4qtAwEQCCFScCLDyQAgNIgmVJ6DXblgCDES1GVHBR4oVhGDESTGQhYwYNGjVoiDBM+YWNswwD3HjMmcaIyoknkmgc+bEIAjgYUCiQg0VbEjp2KNiBg2PBAjsUEtSB4QbbiTk08OBxkUWPsw98+PgBpCEO4cMjEAgi5AKBIUSUFzGyMMeR4Ug0HFbAkURJEgpHfignsuS3QCbQkTQBQEGJEycOAAApovxJCoIuQKHBETgIlMF9SuSH1hJRcHDEQi7g4MJAGShhoYI5GSFFWwMpYJ8TNnA40QFTKDHFhBMFBAAh+QQFCgCAACwAAAAAFwAXAAAI/wABCRw4kICDIFRIEFzIcKCCC0GC9Gi4sEoGHAOlXNg4USAIJCAqlkjRIoNAKUE2BhCIxMqEKyEHUsAC40UKBIA0qgRUIYsBAxN4EMSh5YVRIzqYbLnAJQCILj8NeDFJEMSXmi9OoLDxRauDqGA0NETwxWgCgSwElvjZ5SzFKjYSdCQY5kVMgihY6NVLERCJDA0aLCgQAAoSHoh5KKCIIoUQDpA5DEl8mAcFFA17BIksBrJhyosbssAypnNnMihyIFi9si8g1aurzGVp4EoVhjKsVOirpYwSJxcAoSAgkEoNGhxsNHxhRsnvMyQeqHgACA2N42lKLLShxon3MjZ0rFEhsgZEBQ400o+BQpBNm99mWgACQcSHjxSAbLhJ/0b+QB4/wPEEGx7Zd59AI4wRhxy7EcRDVgPRZx9+AkFRQoOuMVEfEWK51lAAcxAxxwF9BQQAIfkEBQoAgAAsAAAAABcAFwAACP8AAQkcSHDEixEEEyokyOSFQwILE7rA4WIgA4cvIApEkICJxAoajlQRePGhwAgQ6DhAwFADEiQamgBiAMNkBgwXLtCpQDCHlJcwXTSJ8QILASY4g1z4wJIgDpc8eDBgkeAETy05LzxIsFAHVAWAAAw8kRMDhYiAXETYQSJhgBNaPBI8UKVuFR0aF6JAIKXvDkB1nChR4iTJBRQRw4QwwNiAAMGDBdvpERFC48aBnQg2jFghihGLGwvI4QHCBwgR8np2gaC1zIRHwDx4PfBACjoM0AL54cPHA0AserAAlEIMBxUaFhqpQaT3DUAyJsgggUGM8TMeEmpY0tzHnZh1aNRNQcDgDIfzA3IPTPGkdw08gHbQqEGjBSANG85LyD7wCIcoS6QgkAI0FGgfIA0MIMEEBcBmRHIDFljDgTM1cBZaA81XAxQYoiUCDSJgGBAAIfkEBQoAgAAsAAAAABcAFwAACP8AAQkcOJAFFCRHWBBcyHCgDh4QSTRcyOOFjYEIkGiUKBAHiCoUf8BRw0agAo08OILQkqIEyIFs4ChxYqYFoIwRbxp58SJFBoI2nigZWgZJAI0aWOjYyVPLS5hmZioZwGIHBQUoFvB88QVEQy1lZl4YCABQghcwviCYCOjEhitPBRJIcCIuoAA68urIwXFilQKAcRCY46MwkTwY2J7YciHIhQtUDBMpLGIiiRWPGz8mbLjMA8UCHD+m4qIiTwp9GwaogqNKDgILK1iRkYNhGAdeJ9rgUKOGDLkoAJUwYEBPgoYj0tBYjgZQjCApALEhPiALj9hjltPgACWHEA5CPHZJIW7Ays+BI+IsT7PgJof3DcxmIT4BCcEKe96MGSEQwXsO8QGChBVkCJDbQAmMAAVGYsA3UAYaHMhWFe+JwQBbbPVAhhhkBMBWQAAh+QQFCgCAACwAAAAAFwAXAAAI/wABCRw4MMcUJVMOEFzIcGADJRBxNFwoxYiGgRCUOFFSZaALHC4ocoiyJIVACBuddARU5YiGCiEHpnjiw0cNPIAyapTYRAMPHhqYENSwhEjNO1A0JFGSpMkBnz+l6FhopIZRIlkIXLATpEeEnz8lMgTywygGQCx6oACk4KeGqRM13MAAl+AOBjEJ5tibY6JeBTsUdDxDozCNOjJYNGSRIMaLxy9G1KhhuAYZAhNtQIYsgnLhGYknVoCxecQBKV9WfAHhV2CPJrAP9FjI4EEKhQQDnNAiNK4KDhxMoiCh+MSFCxgyNPSwAbgYC4BG8CkBqMXxCw8S0B7AQQwHEQwOhEwwEEIHEwzXPyggiEdC9w0XmRiYfzEDneN0KhAsMEHCgAYCyUefQAl88IADCCxUwAIMDMTEAAMGmECCrQGiw3wG7FDhRFsYsMVsEwUEACH5BAUKAIAALAAAAAAXABcAAAj/AAEJHDjQRR8fcwIQXMhw4AkiEKs0XJigBJSBDnxobOLwBY+FFeTEGVNCIBuNRDCqgXPn48ARb2jUSLMA0MmIgFqYceIEjgOCUMbUkMmhAoU1PtYAspFHiRInT2wsHJGGhlU0JOiIeEBiA08lftg0tCFmqAxAKAZeeFpGy0RAFazIyLGwygUDLgf2OOCi74G3Agno0JEjAIkhHBJzGBMjLUMACpDwmMyjgmLFQSayoECZ8hAxicWMweKYYeTOUAIkGFGiBAIWb1GQyEE4AGyCO2CEYUggwQKJExN0MWCgJAAUAAAlePHiC4KGGsAQN+AAxQktJ1icYN4cxMIMVqbrUwERgMsFLk2afOGuheNAJBOIZ6kACMeF+wwAITDCHEsGgiAIQIYVGgiEQxD4CQSCFimUANxAICBBwUD2XRBEfgJVkcGDgFl4wXOANUQAFRf89FZAACH5BAUKAIAALAAAAAAXABcAAAj/AAEJHDjwgAgaIggqXEhQCo0aNXQwVFigAYOBRmholChQgxEpFCdIGNBAYMYaNDgaWRKFwxGCHjpwELNBA6CTKQHhqeHDxxMjBBkY4EBUBIUKdWjUyaHhR08fS2zCPDOTAwZAMsjwAXTjaQ2gCzWIIIoFEAuzgB70vANkIqAIdFIcUNjkwRCQBAkE2LsXhVsCGtiwcWAjhwADiA2ECDORRZA/Spw4UVInceIPJBgGsKOks2QlWywvbvw4suQpgCjYWLBgh9+JBKpQyEBBQQ6FTEqcCKCQxI4ILtxmMHHhgg2FCngg0cBRYQIMQYprYZGARwJADJTz0FAFN4TiFzAgUugR4wWWAC40IFEu5fbACnSK06EAqMmL+zvqr1eOgyACB3R8EIFA9uEnEA5HaABFcP4lgMBA9sHwggIDuVAFg24BcoCELzSR4UQkjPDCCJlNFBAAIfkEBQoAgAAsAAAAABcAFwAACP8AAQkcSJAMBzI9CCpcOLACh4c5GCoEgQTEwBJixHCIKBDKiAoTr5CxokHgCI0bBZYY80YOyIE8yBgwkCUBoJMaI9pIQ4PGmxYEM1iZaUAPiAJCOAhxAYVDTxpjXsLMQvQFoBQXUgBC8zRNCYYJ9AwwMEIgARSAZNSgIcaGREAgHIRZmEOGFakCSfTYSyDhW7hfjBjRcIDKhcNBBPBgIfFBHh+Qfcw5TDnIl7dyIhPZbPhCkAtbTry1UGYz5Dk9dhxZjeAviSYgYjM5oLAJjwQEal84I1oigi8vXtgEBEDgBSVOymhhCMLICxgvFqBAAEIBoAFKspthU1tL8BdfdBBQ0IBEAwEkebI7eeJ2YIYUwY1Y14GEBw8dgFr4yd6G+8AmJaTQQgYC0WcfRw6oAccPPNSWQRUD5VDffQPZ8AISf8FkXwAZMsTCEUhAgZZEAQEAOw%3D%3D';
}

/**
 * bl_check_load_time();
 *
 * @param double $load_time Max load time calculated in {@link bl_debug()}
 * @access private
 */
function bl_check_load_time($load_time)
{
    if (_bl_monitor_times and _bl_max_load_time) {

        if ($load_time > _bl_max_load_time) {
            $data = array();
            $data['Time Max'] = strip_tags(bl_format_time(_bl_max_load_time));
            $data['Time Load'] = '<span style="color:#f00;">'.strip_tags(bl_format_time
                ($load_time)).'</span>';
            $data['Exceded'] = strip_tags(bl_format_time($load_time - _bl_max_load_time));
            $data['Url'] = bl_get_url();

            $msg = '<h3>Max Load time exceded</h3>';
            $title = 'Max Load time exceded';

            bl_send_mail($msg, $title, $data);
        }
    }
}

/**
 * bl_check_memory();
 *
 * @param double $total_memory Total memory calculated in {@link bl_debug()}
 * @access private
 */
function bl_check_total_memory($total_memory)
{
    if (_bl_monitor_memory and _bl_max_total_memory > 0) {

        if ($total_memory > _bl_max_total_memory) {

            $exceded = $total_memory - _bl_max_total_memory;

            $data = array();
            $data['Memory Max'] = strip_tags(bl_convert(_bl_max_total_memory));
            $data['Memory Used'] = '<span style="color:#f00;">'.strip_tags(bl_convert($total_memory)) .
                '</span>';
            $data['Exceded'] = strip_tags(bl_convert($exceded));
            $data['Url'] = bl_get_url();

            $msg = '<h3>Total memory ammount exceded</h3>';
            $title = 'Memory exceded';

            bl_send_mail($msg, $title, $data);
        }
    }
}

/**
 * bl_check_querys()
 * Check error on sql querys and sen mail
 *
 * @param array $bl_msg_sql List of querys
 * @access private
 */
function bl_check_querys($bl_msg_sql)
{
    if (_bl_monitor_sql) {
        foreach ($bl_msg_sql as $v) {
            if (!empty($v['error'])) {
                $v['time'] = bl_format_time($v['time']);
                $v['error'] = '<span style="color:#f00;">'.$v['error'].'</span>';
                $msg = '<h3>SQL Crash</h3> <p>Ther\'re a problem with this query:</p>';
                $title = 'SQL Fail';

                bl_send_mail($msg, $title, $v);
            }

            // monitor, max time for querys
            if (_bl_max_sql_time > 0) {
                if ($v['time'] > _bl_max_sql_time) {

                    $v['time'] = '<span style="color:#f00;">'.bl_format_time($v['time']) .
                        '</span>';
                    $v['Max Time'] = _bl_max_sql_time;
                    $msg = '<h3>A SQL Query has exceded the max time for querys </h3>';
                    $title = 'SQL Query exceded max time for querys';
                    bl_send_mail($msg, $title, $v);
                }
            }
        }
    }
}

/**
 * bl_check_times();
 *
 * @param array $bl_msg_time List of time marks
 * @access private
 */
function bl_check_times($bl_msg_time)
{
    // monitor, check any max time
    if (_bl_monitor_times and _bl_max_any_time > 0) {

        foreach ($bl_msg_time as $v) {
            $the_time = rtrim($v['time'], 's');
            if ($the_time > _bl_max_any_time) {
                $data = array();
                $data['Label'] = $v['label'];
                $data['Max Time'] = bl_format_time(_bl_max_any_time);
                $data['Time'] = bl_format_time($the_time);
                $data['Exceded'] = bl_format_time($the_time - _bl_max_any_time);
                $data['Url'] = bl_get_url();

                $msg = '<h3>A Time Mark has exceded the max time</h3>';
                $title = 'Time Mark Exceded';

                bl_send_mail($msg, $title, $data);
            }
        }
    }
}

/**
 * bl_get_bookmarklets()
 * Render bookmarklet panel
 * @var string $type The type/categorie of bookmarklet to reder, may be js|css|other
 */
function bl_get_bookmarklets($type) {
    $result = '';

    if ($type == 'css' and count(_bl::$bookmarklets['css'])) {
        $result = '<h3>CSS Bookmarklets</h3><ul>';
        foreach (_bl::$bookmarklets['css'] as $bookmark) {
            $quote = (isset($bookmark['quote'])) ? $bookmark['quote'] : '"';
            $result .= '<li><a href='.$quote.$bookmark['url'].$quote .
                ' class="bl_bookmark">'.$bookmark['title'].'</a></li>';
        }
        $result .= '</ul>';

    } elseif ($type == 'js' and count(_bl::$bookmarklets['js'])) {
        $result = '<h3>JS Bookmarklets</h3><ul>';
        foreach (_bl::$bookmarklets['js'] as $bookmark) {
            $quote = (isset($bookmark['quote'])) ? $bookmark['quote'] : '"';
            $result .= '<li><a href='.$quote.$bookmark['url'].$quote .
                ' class="bl_bookmark">'.$bookmark['title'].'</a></li>';
        }
        $result .= '</ul>';

    }
    if ($type == 'other' and count(_bl::$bookmarklets['other'])) {
        $result = '<h3>Other Bookmarklets</h3><ul>';
        foreach (_bl::$bookmarklets['other'] as $bookmark) {
            $quote = (isset($bookmark['quote'])) ? $bookmark['quote'] : '"';
            $result .= '<li><a href='.$quote.$bookmark['url'].$quote .
                ' class="bl_bookmark">'.$bookmark['title'].'</a></li>';
        }
        $result .= '</ul>';
    }

    return $result;
}

/**
 * Add new bookmarklets
 *
 * @param string $type Type of bookmark: css|js|other
 * @param string $title Title for the bookmark
 * @param string $url Url of the bookmark
 * @param string $colon URL link. Set " for <a href=""> or ' for <a href=''>. Default "
 */
function bl_add_bookmark($type, $title, $url, $quote = '"')
{
    if (isset(_bl::$bookmarklets[$type])) {
        $count = count(_bl::$bookmarklets[$type]);
        _bl::$bookmarklets[$type][$count]['title'] = $title;
        _bl::$bookmarklets[$type][$count]['url'] = $url;
        _bl::$bookmarklets[$type][$count]['quote'] = $quote;

        return true;
    }
    return false;
}

/**
 * Get phpinfo
 * Extracted from xn.Debug. Copyright Rouven Volk (http://xn-debug.sourceforge.net/)
 * @return string
 */
function bl_phpinfo()
{
    if (_bl_hide_phpinfo) {
        return '<div class="bl_nothing"><p>phpingo() is disabled. <br />Enable with _bl_hide_phpinfo constant</p></div>';
    }

    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();

    return substr($phpinfo, strpos($phpinfo, '<body>') + 6, strpos($phpinfo,
        '</body>') - strpos($phpinfo, '<body>') - 6);
}


#####################################
### trace functions


/**
 * bl_trace()
 * Get declared vars in the scope when is called
 * @var string $trace_name The name for the trace
 * @var string $defined_vars Pass an array of vars, requierd inside local scopes
 * return void
 */
function bl_trace($trace_name = '', $defined_vars = null)
{
    /* Memory at this point */
    $memory = memory_get_peak_usage();
    $memory_num = strip_tags(bl_convert($memory));

    /* Load Time */
    $total_load_time = bl_format_time(bl_get_time(_bl::$time_start));

    if (_bl_production == true) return false;

    $trace = array(
        'memory' => $memory_num,
        'time'   => $total_load_time,
        'vars'   => array(),
        'name'   => $trace_name,
        'debug' => debug_backtrace()
    );

    $trace_vars = array();

    if ($defined_vars == null) {
        $defined_vars = $GLOBALS;
    }

    // temp var, only for check array keys on $defined_vars
    // TODO: check if any php version or config have more predefined vars
    $array_vars_names = array(
        '_SERVER',
        'GLOBALS',
        '_POST',
        '_GET',
        '_SESSION',
        '_FILES',
        '_COOKIE',
        '_REQUEST',
        '_GLOBALS',
        '_ENV',
        'REQUEST',
        'ENV');

    foreach ($defined_vars as $key => $value) {
        if (!in_array($key, $array_vars_names)) {
            $trace['vars'][$key] = $value;
        }
    }

    _bl::$trace[] = $trace;
}


/**
 * bl_get_trace()
 * Render trace panel
 * @return string The html code for the panel
 */
function bl_get_trace()
{
    $result = '';

    if (count(_bl::$trace)) {
        $count = 0;
        foreach (_bl::$trace as $trace_key => $trace) {

            ++$trace_id;

            $vars = bl_get_vars(
                $trace['vars'], '
                _USER',
                'trace_'.$trace_id.'_',
                bl_link_file($trace['debug'][0]['file'], $trace['debug'][0]['line']).' '.$trace['debug'][0]['line']
            );

            $trace_name = '';
            if ($trace['name']) {
                $trace_name = $trace['name'];
            } else {
                $trace_name = 'Trace '.$trace_id;
            }

            $result .= '
            <div class="bl_trace">
                <div class="bl_trace_head">
                    <a href="javascript:void(0)" class="bl_trace_link" onclick="bl_toggle(\'bl_trace_info_'.$trace_id.'\')">
                        <span class="bl_trace_title">'.$trace_name.'</span> |
                        <span class="bl_trace_time">'.$trace['time'].'</span> |
                        <span class="bl_trace_memory">'.$trace['memory'].'</span>
                    </a>
                </div>
                <div class="bl_trace_info" id="bl_trace_info_'.$trace_id.'">
                    '.$vars.'
                </div>
            </div>
            ';
        }

    }

    return $result;
}


#####################################
### watch functions

/**
 * bl_tick()
 * Fired in every tick. Check for changes on registered vars
 */
function bl_tick()
{
    $result = '';

    $debug = debug_backtrace();

    static $buffer = array(); // save last state of a var (to prevent duplicate info)
    static $defined = array(); // to check if is the first tick for a var

    if (count(_bl::$watch)) {
        foreach (_bl::$watch as $watch) {

            // check if the var exists
            if (isset($GLOBALS[$watch])) {
                $w = $GLOBALS[$watch];
            } else {
                $w = 'undefined';
            }

            // first time
            if (!isset($buffer[$watch])) {
                $defined[$watch] = true;
                $buffer[$watch] = $w;
                if ($w != 'undefined') {
                    _bl::$watches[$watch][] = array($w, $debug[0]['file'], $debug[0]['line']);
                }
            } else {
                // check if the var has a different value at this tick
                if ($buffer[$watch] != $w) {
                    // check the var not is undefined
                    $pathinfo = pathinfo($debug[0]['file']);
                    if ($defined[$watch] == true and $w != 'undefined'
                        and $pathinfo['basename'] != _bl_filename
                    ) {
                        _bl::$watches[$watch][] = array($w, $debug[0]['file'], $debug[0]['line']);
                    }
                }
            }

            $buffer[$watch] = $w;
        }
    } else {
        bl_error('PHP Bug Lost: bl_watch() has been initialized without variables');
    }
}


function bl_watch()
{
    if (_bl_production == true) {
        return false;
    }

    register_tick_function('bl_tick');

    $args = func_get_args();
    foreach ($args as $arg) {
        _bl::$watch[] = $arg;
    }
}


function bl_get_watches()
{
    $result = '';

    if (count(_bl::$watches)) {
        $count = 0;
        foreach (_bl::$watches as $watch_key => $watch) {

            ++$watch_id;
            $watch_details = '';
            foreach ($watch as $detail) {
                if (is_array($detail)) {
                    $d = array($watch_key => $detail[0]);
                    $watch_details .= '
                        <tr>
                            <td>'.bl_get_vars($d, '_USER', 'watch').'</td>
                            <td>'.bl_link_file($detail[1], $detail[2]).':'.$detail[2].'</td>
                        </tr>';
                }
            }

            $result .= '
            <div class="bl_trace">
                <div class="bl_trace_head">
                    <a href="javascript:void(0)" class="bl_trace_link" onclick="bl_toggle(\'bl_watch_info_'.$watch_id.'\')">
                        <span class="bl_trace_title">$'.$watch_key.'</span>
                    </a>
                </div>
                <div class="bl_trace_info" id="bl_watch_info_'.$watch_id.'">
                    <table>
                        <thead>
                            <tr>
                                <th class="bl_watch_value">value</th>
                                <th class="bl_watch_file">file</th>
                            </tr>
                        </thead>
                        <tbody>
                            '.$watch_details.'
                        </tbody>
                    </table>
                </div>
            </div>';
        }
    } else {
        $result = '<div class="bl_nothing"><p>There aren\'t watches</p></div>';
    }

    return $result;
}


#####################################
### Profile functions


/**
 * bl_get_profile()
 * Call to bl_profile_function and get profile messages.
 * If there aren't messages return a string with text "no profile messages"
 * @return string An HTML table with message or a text saying "no profile message"
 */
function bl_get_profile()
{
    $result = '';
    $profile = _bl::$profile;

    if (count($profile)) {

        $result = '<table>
            <thead>
                <tr>
                    <th>method</th>
                    <th>arguments</th>
                    <th>class</th>
                    <th>invocations</th>
                    <th>total time</th>
                    <th>avarage</th>
                    <th>duration worst</th>
                </tr>
            </thead>
            <tbody>';

        $count = 0;
        $array_name = 'profile';
        $arguments = '';
        foreach ($profile as $msg) {

            if (count($msg['arguments'])) {
                $arguments = '
                    <a href="javascript:void(0);" onclick="view_array(\'div_'.$array_name .
                        '_'.$count.'\')" id="a_'.$array_name.'_'.$count .
                        '" style="color:#008000">Array(...</a>
                    <div style="display:none;" id="div_'.$array_name.'_'.$count.'">
                        <p class="bl_close_array"><a href="javascript:void(0);" onclick="view_array(\'div_' .
                        $array_name.'_'.$count.'\')">Close</a></p>
                        <pre>'.bl_high_array($msg['arguments']).'</pre>
                    </div>';
                $count++;
            }

            $result .= '
                <tr>
                    <td>'.$msg['method'].'</td>
                    <td>'.$arguments.'</td>
                    <td>'.$msg['class'].'</td>
                    <td>'.$msg['invocations'].'</td>
                    <td>'.$msg['duration']['total'].'</td>
                    <td>'.$msg['duration']['average'].'</td>
                    <td>'.$msg['duration']['worst'].'</td>
                </tr>';
        }

        $result .= '</tbody></table>';

    } else {
        $result = '<div class="bl_nothing"><p>There aren\'t profile info</p></div>';
    }

    return $result;
}

function bl_profile_method($classname, $methodname, $args = '', $invocations = 1)
{

    $details = $durations = $call_args = array();
    $instance = NULL;

    if (class_exists($classname) != TRUE) {
        bl_error('PHP Bug Lost says: Hey! class &quote;'.$classname.'&quote; doesn\'t exists, I can\'t do profile.');
    }

    if (_bl_create_times) {
        bl_time('Start Time Mark for profile method '.$methodname);
    }

    $method = new ReflectionMethod($classname, $methodname);

    if (!$method->isStatic()) 		{
        $class = new ReflectionClass($classname);
        $instance = $class->newInstance();
    }

    if ($args != '') {

        if (!is_array($args)) {
            $call_args = array($args);
        } else {
            $call_args = $args;
        }

        for ($i = 0; $i < $invocations; $i++) {
            $start = microtime(true);
            $method->invokeArgs($instance, $call_args);
            $durations[] = microtime(true) - $start;
        }
    } else {
        for ($i = 0; $i < $invocations; $i++) {
            $start = microtime(true);
            $method->invoke($instance);
            $durations[] = microtime(true) - $start;
        }
    }

    $duration['total'] = round(array_sum($durations), 4);
    $duration['average'] = round($duration['total'] / count($durations), 4);
    $duration['worst'] = round(max($durations), 4);

    $details = array(
        'method' => $methodname,
        'arguments' => $call_args,
        'class' => $classname,
        'duration' => $duration,
        'invocations' => $invocations
    );

    // add this profile info
    _bl::$profile[] = $details;

    return;
}

function bl_profile_function($function, $args = '', $invocations = 1)
{

    if (!function_exists($function)) {
        bl_error('PHP Bug Lost says: Hey! function &quote;'.$function.'&quote; doesn\'t exists, I can\'t do profile.');
        return;
    }

    if (_bl_create_times) {
        bl_time('Start Time Mark for profile funtion '.$function);
    }

    $invoke = new ReflectionFunction($function);
    $duration = array();
    $durations = array();
    $details = array();
    $call_args = array();

    if (!empty($args)) {
        // invokeArgs

        if (!is_array($args)) {
            $call_args = array($args);
        } else {
            $call_args = $args;
        }

        for ($i = 0; $i < $invocations; $i++) {
            $start = microtime(true);
            $invoke->invokeArgs($call_args);
            $durations[] = microtime(true) - $start;
        }

    } else {
        // invoke
        for ($i = 0; $i < $invocations; $i++) {
            $start = microtime(true);
            $invoke->invoke();
            $durations[] = microtime(true) - $start;
        }
    }

    $duration['total'] = round(array_sum($durations), 4);
    $duration['average'] = round($duration['total'] / count($durations), 4);
    $duration['worst'] = round(max($durations), 4);

    $details = array(
        'method' => $function,
        'arguments' => $call_args,
        'class' => '<span style="color:#ddd;">no-class</span>',
        'invocations' => $invocations,
        'duration' => $duration
    );

    // add this profile info
    _bl::$profile[] = $details;


    return;
}

#####################################
### render eval panel

function bl_get_eval()
{
    if (_bl_eval_active == true) {
        return '
            <div class="in10 bl_debug_eval_panel  bl_debug_eval_panel_activo"  id="bl_debug_eval_code">
                <form method="post" action="#" id="bl_eval_form" onsubmit="return false;">
                    <p><label for="bl_eval_code"></label>
                        <textarea name="bl_eval_code" id="bl_eval_code" cols="40" rows="8"></textarea></p>
                    <p><input type="submit" onclick="" name="bl_eval_submit" id="bl_eval_submit" value="eval" />
                        Write your code and press
                        <input type="hidden" name="bl_url" id="bl_url" value="'._bl_path.'" />
                        <input type="hidden" name="bl_eval_url_name" id="bl_eval_url_name" value="'._bl_var_eval.'" />
                        <input type="hidden" name="bl_secrect_key" id="bl_secret_key" value="'._bl_secret_key.'" /></p>
                </form>
            </div>
            <div class="in10 bl_debug_eval_panel"  id="bl_debug_eval_result"><p>Results</p>
            </div>';
    } else {
        return '
        <div class="in20">
            <div class="bl_nothing"><p>Eval panel is deactivate</p></div>
        </div>';
    }

}

#####################################
### IP functions


/**
 * bl_get_ip()
 * return the ip address
 */
function bl_get_ip()
{
    $ip = 'ups! no IP';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){  //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];

    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}


/**
 * Check if the client ip is an allowed ip
 */
function bl_check_ip()
{
    // get ips from _bl_allow_ip
    $allow_ip = trim(_bl_allow_ip);
    if (empty($allow_ip)) return true;

    $extract_ips = explode(',', _bl_allow_ip);
    $extract_ips = array_map('trim', $extract_ips);
    if (in_array(bl_get_ip(), $extract_ips)) {
        return true;
    }

    return false;
}

#####################################
### fuck yeah!

/**
 * bl_debug()
 * Generate the debug panel.
 * Use global vars and public functions for get the contents of the debug panel
 *
 * @param bool $active True/False for show/hide console
 * @return string All HTML code for the debug panel.
 */
function bl_debug($active = false, $return = false)
{
    // get memory
    $memory = memory_get_peak_usage();
    $memory_num = strip_tags(bl_convert($memory));

    /* get load time */
    $total_time = bl_get_time(_bl::$time_start);
    $total_load_time = bl_format_time($total_time);
    $c = count(_bl::$msgs_time);
    _bl::$msgs_time[$c]['label'] = '<strong>Total Load Time</strong>';
    _bl::$msgs_time[$c]['time'] = $total_load_time;

    // defined vars
    $defined_vars = $GLOBALS;

    // check monitor
    if (_bl_monitor_on) {
        bl_check_total_memory($memory); // monitor check memory
        bl_check_load_time($total_time); // monitor check load time
        bl_check_times(_bl::$msgs_time); // monitor check time marks
        bl_check_querys(_bl::$msg_sql); // monitor check querys
    }

    ///////////////////////
    // security
    // check secret key
    if (_bl_delete_vars == true and _bl_secret_key == '_pbl_') {
        echo '<strong>ERROR FROM PHP BUG LOST:</strong> Sorry for this error!
            but you need to change your secret key
            otherwise it is not secret! Open you PHP Bug Lost file,
            search for _bl_secret_key constant and change with any word, number or
            alphanumeric string.';
        exit();
    }

    if ($active == false) {
        return '';
    }

    // check IP
    if (bl_check_ip() == false) {
        return '';
    }
    // end security
    ///////////////////////

    // create bookmarklets
    bl_create_bookmarklets();

    /* get vars */
    $post     = bl_get_vars($_POST, '_POST');
    $get      = bl_get_vars($_GET, '_GET');
    $server   = bl_get_vars($_SERVER, '_SERVER');
    $files    = bl_get_vars($_FILES, '_FILES');
    $cookie   = bl_get_vars($_COOKIE, '_COOKIE');
    $user     = '';
    $specials = bl_get_vars(_bl::$vars, '_SPECIAL');
    if (isset($_SESSION)) {
        $session = bl_get_vars($_SESSION, '_SESSION');
    } else {
        $session = '<div class="bl_nothing"><p>Array _SESSION is empty</p></div>';
    }

    // get functions and classes
    $functions = bl_get_functions();
    $classes = bl_get_classes();
    $uclasses = $classes['user'];
    $iclasses = (_bl_show_internal_classes == true)
        ? $classes['internal']
        : '<div class="bl_nothing"><p>To view PHP internal classes set
            <em>_bl_show_internal_classes</em> to true</p></div>';

    // user constants
    $user_constant = get_defined_constants(true);
    $constants = '<div class="bl_nothing"><p>Array _CONSTANTS is empty</p></div>';
    if (isset($user_constant['user']) and count($user_constant['user'])) {
        $constants = bl_get_vars($user_constant['user'], '_CONSTANTS');
    }

    /* GET USER VARS */
    if (is_array($defined_vars)) {

        // temp var, only for check array keys on $defined_vars
        // TODO: check if any php version or config have more predefined vars
        $array_vars_names = array(
            '_SERVER',
            '_POST',
            '_GET',
            '_GLOBALS',
            '_SESSION',
            '_FILES',
            '_COOKIE',
            'GLOBALS',
            'REQUEST',
            'ENV');
        foreach ($array_vars_names as $v) {
            if (isset($defined_vars[$v])) {
                unset($defined_vars[$v]); // delete global var
            }
        }
        unset($array_vars_names); // don't need any more

        // by deleting all global vars, we get the user defined vars
        $user = bl_get_vars($defined_vars, '_USER');
        unset($defined_vars); // don't need any more
    }

    /* Memory usage */
    $memory_usage = '
    <div id="bl_included">
        <h3>PHP Included Files</h3>
        '.bl_included_files().'
    </div>';
    $memory_usage .= '
    <div class="bl_box">
        <h3>Max File Size</h3>
        <span class="bl_memory_usage"><strong>'.str_ireplace(_bl_root, '', _bl::$max_file_size['file']) .
        ' : '.strip_tags(bl_convert(_bl::$max_file_size['size'])) .
        '</strong></span>
    </div>
    <div class="bl_box">
        <h3>Max Var Size</h3>
        <span class="bl_memory_usage"><strong>$'._bl::$max_var_size['var'].' : ' .
        strip_tags(bl_convert(_bl::$max_var_size['size'])).'</strong></span>
    </div>
    <div class="bl_box">
        <h3>PHP Memory</h3>
        <span class="bl_memory_total">Available: '.ini_get('memory_limit').'</span>
        <span class="bl_memory_usage">Using: <strong>'.$memory_num .
        '</strong></span>
    </div>';

    /* sql querys */
    $querys = '<div class="bl_nothing"><p>There aren\'t querys</p></div>';
    if (is_array(_bl::$msg_sql) and count(_bl::$msg_sql)) {
        $querys = bl_get_querys(_bl::$msg_sql);
    }

    /* List of Messages */
    $message_list = bl_get_msg();
    if (!_bl::$count_msg) {
        $message_list = '<div class="bl_nothing"><p>There aren\'t messages</p></div>';
    }

    $extensions = '';
    foreach (get_loaded_extensions() as $i => $ext) {
        $extensions .= $ext.' => '.phpversion($ext).'<br/>';
    }
    if (empty($extensions)) {
        $extensions .= '<div class="bl_nothing"><p>There aren\'t extensions</p></div>';
    }

    // Finally, generate and return the HTML code for the debug panel.
    $result = '
    <div id="bl_debug_wrap">
        <div id="bl_debug" class="bl_border_top bl_half bl_resize">
            <div id="bl_debug_content" class="bl_'._bl::$panel_state.'_panel">
                <span id="bl_loading"><img src="'.bl_loading_image().'" alt="Loading" /> loading...</span>
                <div id="bl_debug_panels">
                    <div id="bl_debug_msg" class="bl_debug_panel '._bl::$panel_active['msg'].'">

                        <div id="bl_debug_msg_menu" class="bl_menu_vertical">
                            <ul>
                                <li><a href="javascript:bl_debug_set_msg(\'all\');" id="bl_debug_msg_btn_all" class="bl_debug_msg_btn bl_debug_msg_btn_activo">all</a></li>
                                <li><a href="javascript:bl_debug_set_msg(\'info\');" id="bl_debug_msg_btn_info" class="bl_debug_msg_btn">info</a></li>
                                <li><a href="javascript:bl_debug_set_msg(\'warn\');" id="bl_debug_msg_btn_warn" class="bl_debug_msg_btn">warn</a></li>
                                <li><a href="javascript:bl_debug_set_msg(\'error\');" id="bl_debug_msg_btn_error" class="bl_debug_msg_btn">error</a></li>
                                <li><a href="javascript:bl_debug_set_msg(\'user\');" id="bl_debug_msg_btn_user" class="bl_debug_msg_btn">user</a></li>
                            </ul>
                        </div>

                        <div class="in20 bl_panel_info">
                            '.$message_list.'
                        </div>
                    </div>

                    <div id="bl_debug_sql" class="bl_debug_panel '._bl::$panel_active['sql'].'">
                        <div class="in20 bl_panel_info">
                            '.$querys.'
                        </div>
                    </div>

                    <div id="bl_debug_time" class="bl_debug_panel '._bl::$panel_active['time'].'">
                        <div class="in20 bl_panel_info">
                            '.bl_get_times(_bl::$msgs_time).'
                        </div>
                    </div>

                    <div id="bl_debug_memory" class="bl_debug_panel '._bl::$panel_active['memory'].'">
                        <div class="in20 bl_panel_info">
                            <div id="bl_debug_memory_box">
                                '.$memory_usage.'
                            </div>
                        </div>
                    </div>

                    <div id="bl_debug_ajax" class="bl_debug_panel '._bl::$panel_active['ajax'].'">
                        <div class="in20 bl_panel_info">
                            <div id="bl_debug_ajax_box">
                                <div class="bl_nothing"><p>No Ajax Requests</p></div>
                            </div>
                        </div>
                    </div>

                    <div id="bl_debug_php" class="bl_debug_panel '._bl::$panel_active['php'].'">

                        <div id="bl_debug_php_box">
                            <div id="bl_debug_php_menu" class="bl_menu_vertical">
                                <ul>
                                    <li><a href="javascript:bl_debug_set_php(\'ext\');" id="bl_debug_php_btn_ext" class="bl_debug_php_btn bl_debug_php_btn_activo">Extensions</a></li>
                                    <li><a href="javascript:bl_debug_set_php(\'cpu\');" id="bl_debug_php_btn_cpu" class="bl_debug_php_btn">CPU Usage</a></li>
                                    <li><a href="javascript:bl_debug_set_php(\'phpinfo\');" id="bl_debug_php_btn_phpinfo" class="bl_debug_php_btn">PHP Info</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="in10 no_top_in  bl_panel_info">
                            <div id="bl_debug_php_panels">
                                <div class="in10 bl_debug_php_panel  bl_debug_php_panel_activo"  id="bl_debug_php_ext">
                                    <div class="bl_box">
                                        <p>'.$extensions.'</p>
                                    </div>
                                </div>
                                <div class="in10 bl_debug_php_panel"  id="bl_debug_php_cpu">
                                    '.bl_get_usage().'
                                </div>
                                <div class="in10 bl_debug_php_panel"  id="bl_debug_php_phpinfo">
                                    '.bl_phpinfo().'
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="bl_debug_eval" class="bl_debug_panel '._bl::$panel_active['eval'].'">
                        <div id="bl_debug_eval_box">
                            <div id="bl_debug_eval_menu" class="bl_menu_vertical">
                                <ul>
                                    <li><a href="javascript:bl_debug_set_eval(\'code\');" id="bl_debug_eval_btn_code" class="bl_debug_eval_btn bl_debug_eval_btn_activo">Code</a></li>
                                    <li><a href="javascript:bl_debug_set_eval(\'result\');" id="bl_debug_eval_btn_result" class="bl_debug_eval_btn">Results</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="in10 no_top_in  bl_panel_info">
                            <div id="bl_debug_eval_panels">
                                '.bl_get_eval().'
                            </div>
                        </div>
                    </div>

                    <div id="bl_debug_profile" class="bl_debug_panel '._bl::$panel_active['profile'].'">
                        <div id="bl_debug_profile_box">
                            <div id="bl_debug_profile_menu" class="bl_menu_vertical">
                                <ul>
                                    <li><a href="javascript:bl_debug_set_profile(\'profile\');" id="bl_debug_profile_btn_profile" class="bl_debug_profile_btn bl_debug_profile_btn_activo">Profile</a></li>
                                    <li><a href="javascript:bl_debug_set_profile(\'trace\');" id="bl_debug_profile_btn_trace" class="bl_debug_profile_btn">Traces</a></li>
                                    <li><a href="javascript:bl_debug_set_profile(\'watch\');" id="bl_debug_profile_btn_watch" class="bl_debug_profile_btn">Watches</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="in10 no_top_in  bl_panel_info">
                            <div id="bl_debug_profile_panels">
                                <div class="in10 bl_debug_profile_panel bl_debug_profile_panel_activo"  id="bl_debug_profile_profile">
                                    '.bl_get_profile().'
                                </div>
                                <div class="in10 bl_debug_profile_panel" id="bl_debug_profile_trace">
                                    '.bl_get_trace().'
                                </div>
                                <div class="in10 bl_debug_profile_panel" id="bl_debug_profile_watch">
                                    '.bl_get_watches().'
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="bl_debug_info" class="bl_debug_panel '._bl::$panel_active['info'].'">
                        <div class="in20 bl_panel_info">
                            <h3>About...</h3>
                            <p>v@0.5a Standard</p>
                            <p><strong>PHP Bug Lost</strong> is Open Source. Original idea from
                                <a href="http://particletree.com/features/php-quick-profiler/">Php Quick Profiler</a>.</p>
                            <h3>Thanks To:</h3>
                            <ul>
                                <li>Ryan Campbell from <a href="http://particletree.com">particletree.com</a> for his <strong>Php Quick Profiler</strong>,
                                    the first inspiration for <strong>PHP Bug Lost</strong>.</li>
                                <li>Sergey Ilinsky from <a href="http://www.ilinsky.com">ilinsky.com</a> for his fantastic
                                    <a href="http://code.google.com/p/xmlhttprequest/">XMLHttpRequest.js</a> library. Used in Ajax panel.</li>
                                <li><a href="http://www.vonloesch.de">vonloesch.de</a> for his javascript table filter function</li>
                            </ul>

                            <h3>Thirt Party Bookmarklets</h3>
                            <p>
                            <a href="http://westciv.com/mri/">MRI (Test CSS Selector)</a> from
                                <a href="http://westciv.com">westciv.com</a>
                            <br />
                            <a href="http://westciv.com/xray">XRay</a> from
                                <a href="http://westciv.com/xray">westciv.com</a>
                            <a href="http://slayeroffice.com/?c=/content/tools/modi.html">Dom Inspector</a> from
                                    <a href="http://slayeroffice.com">slayeroffice.com</a><br />
                            <a href="http://slayeroffice.com/?c=/content/tools/suite.html">Favelet Suite</a> from
                                    <a href="http://slayeroffice.com">slayeroffice.com</a><br />
                            View Selected Source, View all JS, View al vars/functions, View all CSS, View Classes and Check Img Alt from
                                    <a href="https://www.squarefree.com/bookmarklets/">squarefree.com</a><br />
                            </p>


                            <p><strong>PHP Bug Lost</strong> by Jordi Enguídanos <small>(at gmail.com)</small>.
                                See docs and support at <a href="http://phpbuglost.com">phpbuglost.com</a></p>
                        </div>
                    </div>

                    <div id="bl_debug_vars" class="bl_debug_panel '._bl::$panel_active['vars'].'">

                        <div id="bl_debug_var_content">
                            <div id="bl_debug_var_menu" class="bl_menu_vertical">
                                <ul>
                                    <li><a href="javascript:bl_debug_set_var(\'user\');" id="bl_debug_var_btn_user" class="bl_debug_var_btn bl_debug_var_btn_activo">user</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'special\');" id="bl_debug_var_btn_special" class="bl_debug_var_btn">special</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'functions\');" id="bl_debug_var_btn_functions" class="bl_debug_var_btn">functions</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'uclasses\');" id="bl_debug_var_btn_uclasses" class="bl_debug_var_btn">classes(user)</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'iclasses\');" id="bl_debug_var_btn_iclasses" class="bl_debug_var_btn">classes(internal)</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'constants\');" id="bl_debug_var_btn_constants" class="bl_debug_var_btn">constants</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'get\');" id="bl_debug_var_btn_get" class="bl_debug_var_btn">get</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'post\');" id="bl_debug_var_btn_post" class="bl_debug_var_btn">post</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'session\');" id="bl_debug_var_btn_session" class="bl_debug_var_btn">session</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'cookie\');" id="bl_debug_var_btn_cookie" class="bl_debug_var_btn">cookie</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'files\');" id="bl_debug_var_btn_files" class="bl_debug_var_btn">files</a></li>
                                    <li><a href="javascript:bl_debug_set_var(\'server\');" id="bl_debug_var_btn_server" class="bl_debug_var_btn">server</a></li>
                                </ul>
                            </div>

                            <div class="in10 no_top_in  bl_panel_info">
                                <div id="bl_debug_var_panels" class="bl_panel">
                                    <div class="in10 bl_debug_var_panel  bl_debug_var_panel_activo"  id="bl_debug_var_user">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_user" name="filter" type="text" />
                                        </div>
                                        '.$user.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_special">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_special" name="filter" type="text" />
                                        </div>
                                        '.$specials.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_functions">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_functions" name="filter" type="text" />
                                        </div>
                                        '.$functions.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_uclasses">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_uclasses" name="filter" type="text" />
                                        </div>
                                        '.$uclasses.'
                                    </div>
                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_iclasses">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_iclasses" name="filter" type="text" />
                                        </div>
                                        '.$iclasses.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_constants">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_constants" name="filter" type="text" />
                                        </div>
                                        '.$constants.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_get">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_get" name="filter" type="text" />
                                        </div>
                                        '.$get.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_post">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_post" name="filter" type="text" />
                                        </div>
                                        '.$post.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_session">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_session" name="filter" type="text" />
                                        </div>
                                        '.$session.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_cookie">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_cookie" name="filter" type="text" />
                                        </div>
                                        '.$cookie.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_files">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_files" name="filter" type="text" />
                                        </div>
                                        '.$files.'
                                    </div>

                                    <div class="in10 bl_debug_var_panel" id="bl_debug_var_server">
                                        <div class="bl_filter_box">
                                            Filter <input id="bl_filter_server" name="filter" type="text" />
                                        </div>
                                        '.$server.'
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bl_debug_header">
                <div id="bl_debug_menu">
                    <ul>
                        <li><a href="javascript:bl_debug_set_panel(\'msg\');" id="bl_debug_btn_msg" class="bl_debug_btn">
                            <span>'._bl::$count_msg.' logs '._bl_key_logs.'</span></a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'sql\');" id="bl_debug_btn_sql" class="bl_debug_btn">
                            <span>'._bl::$count_querys.' Sql</span> '._bl_key_sql.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'vars\');" id="bl_debug_btn_vars" class="bl_debug_btn">
                            <span>Vars</span> '._bl_key_vars.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'profile\');" id="bl_debug_btn_profile" class="bl_debug_btn">
                            <span>Profile</span> '._bl_key_profile.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'time\');" id="bl_debug_btn_time" class="bl_debug_btn">
                            <span>'.$total_load_time.'</span> '._bl_key_time.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'memory\');" id="bl_debug_btn_memory" class="bl_debug_btn">
                            <span>'.$memory_num.'</span> '._bl_key_memory.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'ajax\');" id="bl_debug_btn_ajax" class="bl_debug_btn">
                            <span><em id="bl_num_request">0</em> Ajax</span> '._bl_key_ajax.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'php\');" id="bl_debug_btn_php" class="bl_debug_btn">
                            <span>PHP</span> '._bl_key_php.'</a></li>
                        <li><a href="javascript:bl_debug_set_panel(\'eval\');" id="bl_debug_btn_eval" class="bl_debug_btn">
                            <span>Eval</span> '._bl_key_eval.'</a></li>
                    </ul>
                </div>

                <div id="bl_debug_toggle">
                    <div id="bl_debug_toggle_buttons">
                        <a href="javascript:bl_setPanelSize(\'close\')" title="Close console">X</a>
                        <a href="javascript:bl_setPanelSize(\'plus\')" title="Open or maximize console">M</a>
                        <a href="javascript:bl_debug_set_panel(\'info\');" id="bl_debug_btn_info" title="Info Panel">about'._bl_key_info.'</a>
                        <a href="javascript:bl_opacity();" title="Opacity">opacity'._bl_key_opacity.'</a>
                        <a href="javascript:bl_toggle(\'bl_tool_box\');" title="JS/CSS files and bookmarklets">js/css'._bl_key_jscss.'</a>
                    </div>
                    <div id="bl_tool_box" style="display:none;">
                        <div id="bl_js_css">
                            <div id="bl_js"></div>
                            <div id="bl_css"></div>
                        </div>
                        <div id="bl_bookmarks">
                            '.bl_get_bookmarklets('js').'
                            '.bl_get_bookmarklets('css').'
                            '.bl_get_bookmarklets('other').'
                        </div>
                    </div>
                </div>
            </div>

            <div id="bl_file_container"></div>

        </div>
    </div>'; // the end...

    // add js / css
    $result .= bl_css().bl_js();

    // alert for errors
    if (_bl::$errors == true and _bl_alert_errors == true) {
        $result .= '<div id="bl_show_errors">PHP Bug Lost: There are errors.</div>';
        $result .= '
            <script type="text/javascript">
                bl_alert_errors();
            </script>';
    }

    if ($return == true) {
        echo $result;
        return true;
    } else {
        return $result;
    }

}


if (_bl_production == false) {

    ////////////////////////////////////
    // AJAX DELETE VARS
    if (isset($_GET[_bl_var_del]) and _bl_delete_vars == true) {

        // check secret key and IP
        if (!isset($_GET['bl_key']) or $_GET['bl_key'] != _bl_secret_key) {
            die('error-key');

        } elseif (bl_check_ip() == false) {
           die('error');
        }

        // check if exists $_SESSION
        $session_id = session_id();
        if (empty($session_id)) {
            session_start();
        }

        if ($_GET['type'] == '_COOKIE') {
            if (isset($_COOKIE[$_GET['var']])) {
                // delete cookie
                if (setcookie($_GET['var'], '', time() - 3600, '/')) {
                    // check if cookie is deleted.
                    if (isset($_COOKIE[$_GET['var']])) {
                        die('ok');
                    } else {
                        die('error-cookie');
                    }
                }
            }
        } elseif ($_GET['type'] == '_SESSION') {
            if (isset($_SESSION[$_GET['var']])) {
                unset($_SESSION[$_GET['var']]);
                die('ok');
            }
        }

        // problem with $_GET['type']? or $_GET['var']?
        die('error');
    }


    ////////////////////////////////////
    // FILE BROWSER

    function bl_highlight_file($file, $lineSelected = 0)
    {
        $result = '';

        //Strip code and first span
        $code = highlight_file($file, true);
        //Split lines
        $lines = explode('<br />', $code);
        //Count
        $lineCount = count($lines);
        //Calc pad length
        $padLength = strlen($lineCount);

        //Loop lines
        $n = '';
        $l = '';
        foreach ($lines as $i => $line) {
            //Create line number
            $lineNum = $lineNumber;
            $lineNumber = str_pad($i + 1,  $padLength, '0', STR_PAD_LEFT);

            $style = '';
            if ($lineSelected == $lineNumber ) {
                $style = 'background-color:#f22;';
            }
            $n .= '<div mouseover="this.style.backgroundColor=\'#ccc\'" style="'.$style.'width:40px;" id="line_'.$lineNum.'">'.$lineNumber .'</div>';
            $l .= $line .'<br />';
        }

        //Close span
        $result .= '<table>
            <tr>
                <td style="font-family:monospace">'.$n.'</td>
                <td style="font-family:monospace">'.$l.'</td>
            </tr>
        </table>';

        return $result;
    }

    if (isset($_GET[_bl_var_file]) and _bl_file_viewer == true) {

        // check secret key and IP
        if (!isset($_GET['key']) or $_GET['key'] != _bl_secret_key) {
            die('error-key');
        } elseif (bl_check_ip() == false) {
            die('error');
        }

        $file = strip_tags(trim($_GET[_bl_var_file]));
        $file_line = strip_tags(trim($_GET['line']));

        if (file_exists($file)) {


            $header = '<div id="bl_header_browser">
                <p style="float:right;"><a href="javascript:void(0);" onclick="document.getElementById(\'bl_file_container\').style.display = \'none\'; return false;">[cerrar]</a><p>
            </div>';

            die($header.'<div id="bl_file_explorer" style="overflow:scroll">'.bl_highlight_file($file, $file_line).'</div>');

        } else {
            die('error-file');
        }

        die('error');
    }

    // eval panel
    if (isset($_GET[_bl_var_eval]) and _bl_eval == true) {
        // check secret key and IP
        if (!isset($_GET['bl_key']) or $_GET['bl_key'] != _bl_secret_key) {
            die('error-key');
        } elseif (bl_check_ip() == false) {
            die('error');
        }

        eval($_GET[_bl_var_eval]);
    }
}

