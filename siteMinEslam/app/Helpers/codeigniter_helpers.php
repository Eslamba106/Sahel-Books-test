<?php

use App\Lib\CI_Security;

/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @param	string
 * @param	int
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */
function character_limiter($str, $n = 500, $end_char = '&#8230;')
{
    if (mb_strlen($str) < $n) {
        return $str;
    }

    // a bit complicated, but faster than preg_replace with \s+
    $str = preg_replace('/ {2,}/', ' ', str_replace(array("\r", "\n", "\t", "\v", "\f"), ' ', $str));

    if (mb_strlen($str) <= $n) {
        return $str;
    }

    $out = '';
    foreach (explode(' ', trim($str)) as $val) {
        $out .= $val . ' ';

        if (mb_strlen($out) >= $n) {
            $out = trim($out);
            return (mb_strlen($out) === mb_strlen($str)) ? $out : $out . $end_char;
        }
    }
}

function html_escape($var = '', $double_encode = TRUE)
{
    if (empty($var)) {
        return $var;
    }

    if (is_array($var)) {
        foreach (array_keys($var) as $key) {
            $var[$key] = html_escape($var[$key], $double_encode);
        }

        return $var;
    }

    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $double_encode);
}

function helper_url_title($str, $separator = '-', $lowercase = FALSE)
{
    $utf8_enabled = '';
    $q_separator = preg_quote($separator, '#');

    $trans = array(
        '&.+?;' => '',
        '[^\w\d _-]' => '',
        '\s+' => $separator,
        '(' . $q_separator . ')+' => $separator,
    );

    $str = strip_tags($str);
    foreach ($trans as $key => $val) {
        $str = preg_replace('#' . $key . '#i' . ($utf8_enabled ? 'u' : ''), $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim(trim($str, $separator));
}


function helper_hash_password($password, $algo = 1, $options = [])
{

    static $func_overload;
    isset($func_overload) or $func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));

    if ($algo !== 1) {
        trigger_error('password_hash(): Unknown hashing algorithm: ' . (int) $algo, E_USER_WARNING);
        return NULL;
    }

    if (isset($options['cost']) && ($options['cost'] < 4 or $options['cost'] > 31)) {
        trigger_error('password_hash(): Invalid bcrypt cost parameter specified: ' . (int) $options['cost'], E_USER_WARNING);
        return NULL;
    }

    if (isset($options['salt']) && ($saltlen = ($func_overload ? mb_strlen($options['salt'], '8bit') : strlen($options['salt']))) < 22) {
        trigger_error('password_hash(): Provided salt is too short: ' . $saltlen . ' expecting 22', E_USER_WARNING);
        return NULL;
    } elseif (!isset($options['salt'])) {
        if (function_exists('random_bytes')) {
            try {
                $options['salt'] = random_bytes(16);
            } catch (Exception $e) {
                // log_message('error', 'compat/password: Error while trying to use random_bytes(): ' . $e->getMessage());
                return FALSE;
            }
        } elseif (defined('MCRYPT_DEV_URANDOM')) {
            $options['salt'] = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        } elseif (DIRECTORY_SEPARATOR === '/' && (is_readable($dev = '/dev/arandom') or is_readable($dev = '/dev/urandom'))) {
            if (($fp = fopen($dev, 'rb')) === FALSE) {
                // log_message('error', 'compat/password: Unable to open ' . $dev . ' for reading.');
                return FALSE;
            }

            // Try not to waste entropy ...
            stream_set_chunk_size($fp, 16);

            $options['salt'] = '';
            for ($read = 0; $read < 16; $read = ($func_overload) ? mb_strlen($options['salt'], '8bit') : strlen($options['salt'])) {
                if (($read = fread($fp, 16 - $read)) === FALSE) {
                    // log_message('error', 'compat/password: Error while reading from ' . $dev . '.');
                    return FALSE;
                }
                $options['salt'] .= $read;
            }

            fclose($fp);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $is_secure = NULL;
            $options['salt'] = openssl_random_pseudo_bytes(16, $is_secure);
            if ($is_secure !== TRUE) {
                // log_message('error', 'compat/password: openssl_random_pseudo_bytes() set the $cryto_strong flag to FALSE');
                return FALSE;
            }
        } else {
            // log_message('error', 'compat/password: No CSPRNG available.');
            return FALSE;
        }

        $options['salt'] = str_replace('+', '.', rtrim(base64_encode($options['salt']), '='));
    } elseif (!preg_match('#^[a-zA-Z0-9./]+$#D', $options['salt'])) {
        $options['salt'] = str_replace('+', '.', rtrim(base64_encode($options['salt']), '='));
    }

    isset($options['cost']) or $options['cost'] = 10;

    return (strlen($password = crypt($password, sprintf('$2y$%02d$%s', $options['cost'], $options['salt']))) === 60)
        ? $password
        : FALSE;
}

function random_string($type = 'alnum', $len = 8)
{
    switch ($type) {
        case 'basic':
            return mt_rand();
        case 'alnum':
        case 'numeric':
        case 'nozero':
        case 'alpha':
            switch ($type) {
                case 'alpha':
                    $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum':
                    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric':
                    $pool = '0123456789';
                    break;
                case 'nozero':
                    $pool = '123456789';
                    break;
            }
            return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
        case 'md5':
            return md5(uniqid(mt_rand()));
        case 'sha1':
            return sha1(uniqid(mt_rand(), TRUE));
    }
}

function str_slug($str, $separator = 'dash', $lowercase = TRUE)
{
    $str = trim($str);
    $foreign_characters = array(
        '/ä|æ|ǽ/' => 'ae',
        '/ö|œ/' => 'o',
        '/ü/' => 'u',
        '/Ä/' => 'Ae',
        '/Ü/' => 'u',
        '/Ö/' => 'o',
        '/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|Α|Ά|Ả|Ạ|Ầ|Ẫ|Ẩ|Ậ|Ằ|Ắ|Ẵ|Ẳ|Ặ|А/' => 'A',
        '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|α|ά|ả|ạ|ầ|ấ|ẫ|ẩ|ậ|ằ|ắ|ẵ|ẳ|ặ|а/' => 'a',
        '/Б/' => 'B',
        '/б/' => 'b',
        '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
        '/ç|ć|ĉ|ċ|č/' => 'c',
        '/Д/' => 'D',
        '/д/' => 'd',
        '/Ð|Ď|Đ|Δ/' => 'Dj',
        '/ð|ď|đ|δ/' => 'dj',
        '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ε|Έ|Ẽ|Ẻ|Ẹ|Ề|Ế|Ễ|Ể|Ệ|Е|Э/' => 'E',
        '/è|é|ê|ë|ē|ĕ|ė|ę|ě|έ|ε|ẽ|ẻ|ẹ|ề|ế|ễ|ể|ệ|е|э/' => 'e',
        '/Ф/' => 'F',
        '/ф/' => 'f',
        '/Ĝ|Ğ|Ġ|Ģ|Γ|Г|Ґ/' => 'G',
        '/ĝ|ğ|ġ|ģ|γ|г|ґ/' => 'g',
        '/Ĥ|Ħ/' => 'H',
        '/ĥ|ħ/' => 'h',
        '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Η|Ή|Ί|Ι|Ϊ|Ỉ|Ị|И|Ы/' => 'I',
        '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|η|ή|ί|ι|ϊ|ỉ|ị|и|ы|ї/' => 'i',
        '/Ĵ/' => 'J',
        '/ĵ/' => 'j',
        '/Ķ|Κ|К/' => 'K',
        '/ķ|κ|к/' => 'k',
        '/Ĺ|Ļ|Ľ|Ŀ|Ł|Λ|Л/' => 'L',
        '/ĺ|ļ|ľ|ŀ|ł|λ|л/' => 'l',
        '/М/' => 'M',
        '/м/' => 'm',
        '/Ñ|Ń|Ņ|Ň|Ν|Н/' => 'N',
        '/ñ|ń|ņ|ň|ŉ|ν|н/' => 'n',
        '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ο|Ό|Ω|Ώ|Ỏ|Ọ|Ồ|Ố|Ỗ|Ổ|Ộ|Ờ|Ớ|Ỡ|Ở|Ợ|О/' => 'O',
        '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ο|ό|ω|ώ|ỏ|ọ|ồ|ố|ỗ|ổ|ộ|ờ|ớ|ỡ|ở|ợ|о/' => 'o',
        '/П/' => 'P',
        '/п/' => 'p',
        '/Ŕ|Ŗ|Ř|Ρ|Р/' => 'R',
        '/ŕ|ŗ|ř|ρ|р/' => 'r',
        '/Ś|Ŝ|Ş|Ș|Š|Σ|С/' => 'S',
        '/ś|ŝ|ş|ș|š|ſ|σ|ς|с/' => 's',
        '/Ț|Ţ|Ť|Ŧ|τ|Т/' => 'T',
        '/ț|ţ|ť|ŧ|т/' => 't',
        '/Þ|þ/' => 'th',
        '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ũ|Ủ|Ụ|Ừ|Ứ|Ữ|Ử|Ự|У/' => 'U',
        '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|υ|ύ|ϋ|ủ|ụ|ừ|ứ|ữ|ử|ự|у/' => 'u',
        '/Ý|Ÿ|Ŷ|Υ|Ύ|Ϋ|Ỳ|Ỹ|Ỷ|Ỵ|Й/' => 'Y',
        '/ý|ÿ|ŷ|ỳ|ỹ|ỷ|ỵ|й/' => 'y',
        '/В/' => 'V',
        '/в/' => 'v',
        '/Ŵ/' => 'W',
        '/ŵ/' => 'w',
        '/Ź|Ż|Ž|Ζ|З/' => 'Z',
        '/ź|ż|ž|ζ|з/' => 'z',
        '/Æ|Ǽ/' => 'AE',
        '/ß/' => 'ss',
        '/Ĳ/' => 'IJ',
        '/ĳ/' => 'ij',
        '/Œ/' => 'OE',
        '/ƒ/' => 'f',
        '/ξ/' => 'ks',
        '/π/' => 'p',
        '/β/' => 'v',
        '/μ/' => 'm',
        '/ψ/' => 'ps',
        '/Ё/' => 'Yo',
        '/ё/' => 'yo',
        '/Є/' => 'Ye',
        '/є/' => 'ye',
        '/Ї/' => 'Yi',
        '/Ж/' => 'Zh',
        '/ж/' => 'zh',
        '/Х/' => 'Kh',
        '/х/' => 'kh',
        '/Ц/' => 'Ts',
        '/ц/' => 'ts',
        '/Ч/' => 'Ch',
        '/ч/' => 'ch',
        '/Ш/' => 'Sh',
        '/ш/' => 'sh',
        '/Щ/' => 'Shch',
        '/щ/' => 'shch',
        '/Ъ|ъ|Ь|ь/' => '',
        '/Ю/' => 'Yu',
        '/ю/' => 'yu',
        '/Я/' => 'Ya',
        '/я/' => 'ya'
    );

    $str = preg_replace(array_keys($foreign_characters), array_values($foreign_characters), $str);

    $replace = ($separator == 'dash') ? '-' : '_';

    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '[^a-z0-9\-\._]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE) {
        if (function_exists('mb_convert_case')) {
            $str = mb_convert_case($str, MB_CASE_LOWER, "UTF-8");
        } else {
            $str = strtolower($str);
        }
    }

    $str = preg_replace('#[^' . 'a-z 0-9~%.:_\-' . ']#i', '', $str);

    return trim(stripslashes($str));
}

function force_download($filename = '', $data = '', $set_mime = FALSE)
{
    if ($filename === '' or $data === '') {
        return;
    } elseif ($data === NULL) {
        // Is $filename an array as ['local source path' => 'destination filename']?
        if (is_array($filename)) {
            if (count($filename) !== 1) {
                return;
            }

            reset($filename);
            $filepath = key($filename);
            $filename = current($filename);

            if (is_int($filepath)) {
                return;
            }
        } else {
            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        }

        if (!@is_file($filepath) or ($filesize = @filesize($filepath)) === FALSE) {
            return;
        }
    } else {
        $filesize = strlen($data);
    }

    // Set the default MIME type to send
    $mime = 'application/octet-stream';

    $x = explode('.', $filename);
    $extension = end($x);

    if ($set_mime === TRUE) {
        if (count($x) === 1 or $extension === '') {
            /* If we're going to detect the MIME type,
				 * we'll need a file extension.
				 */
            return;
        }

        // Load the mime types
        $mimes =
            array(
                'hqx'    =>    array('application/mac-binhex40', 'application/mac-binhex', 'application/x-binhex40', 'application/x-mac-binhex40'),
                'cpt'    =>    'application/mac-compactpro',
                'csv'    =>    array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'),
                'bin'    =>    array(
                    'application/macbinary', 'application/mac-binary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'
                ),
                'dms'    =>    'application/octet-stream',
                'lha'    =>    'application/octet-stream',
                'lzh'    =>    'application/octet-stream',
                'exe'    =>    array('application/octet-stream', 'application/x-msdownload'),
                'class'    =>    'application/octet-stream',
                'psd'    =>    array('application/x-photoshop', 'image/vnd.adobe.photoshop'),
                'so'    =>    'application/octet-stream',
                'sea'    =>    'application/octet-stream',
                'dll'    =>    'application/octet-stream',
                'oda'    =>    'application/oda',
                'pdf'    =>    array('application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream'),
                'ai'    =>    array('application/pdf', 'application/postscript'),
                'eps'    =>    'application/postscript',
                'ps'    =>    'application/postscript',
                'smi'    =>    'application/smil',
                'smil'    =>    'application/smil',
                'mif'    =>    'application/vnd.mif',
                'xls'    =>    array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword'),
                'ppt'    =>    array('application/powerpoint', 'application/vnd.ms-powerpoint', 'application/vnd.ms-office', 'application/msword'),
                'pptx'    =>     array('application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip', 'application/zip'),
                'wbxml'    =>    'application/wbxml',
                'wmlc'    =>    'application/wmlc',
                'dcr'    =>    'application/x-director',
                'dir'    =>    'application/x-director',
                'dxr'    =>    'application/x-director',
                'dvi'    =>    'application/x-dvi',
                'gtar'    =>    'application/x-gtar',
                'gz'    =>    'application/x-gzip',
                'gzip'  =>    'application/x-gzip',
                'php'    =>    array('application/x-httpd-php', 'application/php', 'application/x-php', 'text/php', 'text/x-php', 'application/x-httpd-php-source'),
                'php4'    =>    'application/x-httpd-php',
                'php3'    =>    'application/x-httpd-php',
                'phtml'    =>    'application/x-httpd-php',
                'phps'    =>    'application/x-httpd-php-source',
                'js'    =>    array('application/x-javascript', 'text/plain'),
                'swf'    =>    'application/x-shockwave-flash',
                'sit'    =>    'application/x-stuffit',
                'tar'    =>    'application/x-tar',
                'tgz'    =>    array('application/x-tar', 'application/x-gzip-compressed'),
                'z'    =>    'application/x-compress',
                'xhtml'    =>    'application/xhtml+xml',
                'xht'    =>    'application/xhtml+xml',
                'zip'    =>    array('application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'),
                'rar'    =>    array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
                'mid'    =>    'audio/midi',
                'midi'    =>    'audio/midi',
                'mpga'    =>    'audio/mpeg',
                'mp2'    =>    'audio/mpeg',
                'mp3'    =>    array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
                'aif'    =>    array('audio/x-aiff', 'audio/aiff'),
                'aiff'    =>    array('audio/x-aiff', 'audio/aiff'),
                'aifc'    =>    'audio/x-aiff',
                'ram'    =>    'audio/x-pn-realaudio',
                'rm'    =>    'audio/x-pn-realaudio',
                'rpm'    =>    'audio/x-pn-realaudio-plugin',
                'ra'    =>    'audio/x-realaudio',
                'rv'    =>    'video/vnd.rn-realvideo',
                'wav'    =>    array('audio/x-wav', 'audio/wave', 'audio/wav'),
                'bmp'    =>    array('image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'),
                'gif'    =>    'image/gif',
                'jpeg'    =>    array('image/jpeg', 'image/pjpeg'),
                'jpg'    =>    array('image/jpeg', 'image/pjpeg'),
                'jpe'    =>    array('image/jpeg', 'image/pjpeg'),
                'jp2'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'j2k'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'jpf'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'jpg2'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'jpx'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'jpm'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'mj2'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'mjp2'    =>    array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
                'png'    =>    array('image/png',  'image/x-png'),
                'tiff'    =>    'image/tiff',
                'tif'    =>    'image/tiff',
                'css'    =>    array('text/css', 'text/plain'),
                'html'    =>    array('text/html', 'text/plain'),
                'htm'    =>    array('text/html', 'text/plain'),
                'shtml'    =>    array('text/html', 'text/plain'),
                'txt'    =>    'text/plain',
                'text'    =>    'text/plain',
                'log'    =>    array('text/plain', 'text/x-log'),
                'rtx'    =>    'text/richtext',
                'rtf'    =>    'text/rtf',
                'xml'    =>    array('application/xml', 'text/xml', 'text/plain'),
                'xsl'    =>    array('application/xml', 'text/xsl', 'text/xml'),
                'mpeg'    =>    'video/mpeg',
                'mpg'    =>    'video/mpeg',
                'mpe'    =>    'video/mpeg',
                'qt'    =>    'video/quicktime',
                'mov'    =>    'video/quicktime',
                'avi'    =>    array('video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'),
                'movie'    =>    'video/x-sgi-movie',
                'doc'    =>    array('application/msword', 'application/vnd.ms-office'),
                'docx'    =>    array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip'),
                'dot'    =>    array('application/msword', 'application/vnd.ms-office'),
                'dotx'    =>    array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword'),
                'xlsx'    =>    array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip'),
                'word'    =>    array('application/msword', 'application/octet-stream'),
                'xl'    =>    'application/excel',
                'eml'    =>    'message/rfc822',
                'json'  =>    array('application/json', 'text/json'),
                'pem'   =>    array('application/x-x509-user-cert', 'application/x-pem-file', 'application/octet-stream'),
                'p10'   =>    array('application/x-pkcs10', 'application/pkcs10'),
                'p12'   =>    'application/x-pkcs12',
                'p7a'   =>    'application/x-pkcs7-signature',
                'p7c'   =>    array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
                'p7m'   =>    array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
                'p7r'   =>    'application/x-pkcs7-certreqresp',
                'p7s'   =>    'application/pkcs7-signature',
                'crt'   =>    array('application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'),
                'crl'   =>    array('application/pkix-crl', 'application/pkcs-crl'),
                'der'   =>    'application/x-x509-ca-cert',
                'kdb'   =>    'application/octet-stream',
                'pgp'   =>    'application/pgp',
                'gpg'   =>    'application/gpg-keys',
                'sst'   =>    'application/octet-stream',
                'csr'   =>    'application/octet-stream',
                'rsa'   =>    'application/x-pkcs7',
                'cer'   =>    array('application/pkix-cert', 'application/x-x509-ca-cert'),
                '3g2'   =>    'video/3gpp2',
                '3gp'   =>    array('video/3gp', 'video/3gpp'),
                'mp4'   =>    'video/mp4',
                'm4a'   =>    'audio/x-m4a',
                'f4v'   =>    array('video/mp4', 'video/x-f4v'),
                'flv'    =>    'video/x-flv',
                'webm'    =>    'video/webm',
                'aac'   =>    'audio/x-acc',
                'm4u'   =>    'application/vnd.mpegurl',
                'm3u'   =>    'text/plain',
                'xspf'  =>    'application/xspf+xml',
                'vlc'   =>    'application/videolan',
                'wmv'   =>    array('video/x-ms-wmv', 'video/x-ms-asf'),
                'au'    =>    'audio/x-au',
                'ac3'   =>    'audio/ac3',
                'flac'  =>    'audio/x-flac',
                'ogg'   =>    array('audio/ogg', 'video/ogg', 'application/ogg'),
                'kmz'    =>    array('application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'),
                'kml'    =>    array('application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'),
                'ics'    =>    'text/calendar',
                'ical'    =>    'text/calendar',
                'zsh'    =>    'text/x-scriptzsh',
                '7zip'    =>    array('application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
                'cdr'    =>    array('application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'),
                'wma'    =>    array('audio/x-ms-wma', 'video/x-ms-asf'),
                'jar'    =>    array('application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed'),
                'svg'    =>    array('image/svg+xml', 'application/xml', 'text/xml'),
                'vcf'    =>    'text/x-vcard',
                'srt'    =>    array('text/srt', 'text/plain'),
                'vtt'    =>    array('text/vtt', 'text/plain'),
                'ico'    =>    array('image/x-icon', 'image/x-ico', 'image/vnd.microsoft.icon'),
                'odc'    =>    'application/vnd.oasis.opendocument.chart',
                'otc'    =>    'application/vnd.oasis.opendocument.chart-template',
                'odf'    =>    'application/vnd.oasis.opendocument.formula',
                'otf'    =>    'application/vnd.oasis.opendocument.formula-template',
                'odg'    =>    'application/vnd.oasis.opendocument.graphics',
                'otg'    =>    'application/vnd.oasis.opendocument.graphics-template',
                'odi'    =>    'application/vnd.oasis.opendocument.image',
                'oti'    =>    'application/vnd.oasis.opendocument.image-template',
                'odp'    =>    'application/vnd.oasis.opendocument.presentation',
                'otp'    =>    'application/vnd.oasis.opendocument.presentation-template',
                'ods'    =>    'application/vnd.oasis.opendocument.spreadsheet',
                'ots'    =>    'application/vnd.oasis.opendocument.spreadsheet-template',
                'odt'    =>    'application/vnd.oasis.opendocument.text',
                'odm'    =>    'application/vnd.oasis.opendocument.text-master',
                'ott'    =>    'application/vnd.oasis.opendocument.text-template',
                'oth'    =>    'application/vnd.oasis.opendocument.text-web'
            );

        // Only change the default MIME if we can find one
        if (isset($mimes[$extension])) {
            $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
        }
    }

    /* It was reported that browsers on Android 2.1 (and possibly older as well)
		 * need to have the filename extension upper-cased in order to be able to
		 * download it.
		 *
		 * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
		 */
    if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
        $x[count($x) - 1] = strtoupper($extension);
        $filename = implode('.', $x);
    }

    // Clean output buffer
    if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
        @ob_clean();
    }

    $utf8_filename = $filename;
    isset($utf8_filename[0]) && $utf8_filename = " filename*=UTF-8''" . rawurlencode($utf8_filename);

    // Generate the server headers
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $filename . '";' . $utf8_filename);
    header('Expires: 0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . $filesize);
    header('Cache-Control: private, no-transform, no-store, must-revalidate');

    // If we have raw data - just dump it
    if ($data !== NULL) {
        exit($data);
    }

    // Flush the file
    if (@readfile($filepath) === FALSE) {
        return;
    }

    exit;
}

function url_title($str, $separator = '-', $lowercase = FALSE)
{
    if ($separator === 'dash') {
        $separator = '-';
    } elseif ($separator === 'underscore') {
        $separator = '_';
    }

    $q_separator = preg_quote($separator, '#');

    $trans = array(
        '&.+?;'            => '',
        '[^\w\d _-]'        => '',
        '\s+'            => $separator,
        '(' . $q_separator . ')+'    => $separator
    );

    $str = strip_tags($str);
    foreach ($trans as $key => $val) {
        // 'u' = enable utf8 , '' = disable utf8
        $str = preg_replace('#' . $key . '#i' . ('u'), $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim(trim($str, $separator));
}

function helper_password_verify($password, $hash)
{
    if (strlen($hash) !== 60 or strlen($password = crypt($password, $hash)) !== 60) {
        return FALSE;
    }

    $compare = 0;
    for ($i = 0; $i < 60; $i++) {
        $compare |= (ord($password[$i]) ^ ord($hash[$i]));
    }

    return ($compare === 0);
}
/**
 * Determines if the current version of PHP is equal to or greater than the supplied value
 *
 * @param	string
 * @return	bool	TRUE if the current version is $version or higher
 */
function is_php($version)
{
    static $_is_php;
    $version = (string) $version;

    if (!isset($_is_php[$version])) {
        $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
    }

    return $_is_php[$version];
}
/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @param	string
 * @param	bool
 * @return	string
 */
function remove_invisible_characters($str, $url_encoded = TRUE)
{
    $non_displayables = array();

    // every control character except newline (dec 10),
    // carriage return (dec 13) and horizontal tab (dec 09)
    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/i';    // url encoded 16-31
        $non_displayables[] = '/%7f/i';    // url encoded 127
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}

function helper_xss_clean($str, $is_image = FALSE)
{
    $s = new CI_Security();
    return $s->xss_clean($str, $is_image);
}
