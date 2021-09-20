<?php
use App\Models\Customer;
use App\Models\Item;
use App\Models\Language;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Preference;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseTax;
use App\Models\SaleOrderDetail;
use App\Models\SaleTax;
use App\Models\TaxType;
use App\Models\File;
use App\Models\Task;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request as request;

function d($var, $a = false)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    if ($a) exit;
}

function selectDatabase()
{
    $host = \Session::get('host');
    $db_user = \Session::get('db_user');
    $db_password = \Session::get('db_password');
    $db_name = \Session::get('db_name');

    if (!empty($host) && !empty($db_user) && !empty($db_name)) {
        selectDatabase1($host, $db_user, $db_password, $db_name);
    }
}

function dateRange($startDate, $endDate, $step = '+1 day', $format = 'Y-m-d')
{
    $dates   = array();
    $current = strtotime($startDate);
    $endDate = strtotime($endDate);
    if ($current > $endDate) {
        return $dates;
    }
    while( $current <= $endDate ) {

        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}


function selectDatabase1($host, $db_user, $db_password, $db_name)
{
    \Config::set('database.connections.mysql.host', $host);
    \Config::set('database.connections.mysql.username', $db_user);
    \Config::set('database.connections.mysql.password', $db_password);
    \Config::set('database.connections.mysql.database', $db_name);
    \Config::set('database.default', 'mysql');
    \DB::reconnect('mysql');
}


function objectToArray($data)
{
    if (is_array($data) || is_object($data)) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = objectToArray($value);
        }
        return $result;
    }
    return $data;
}

function dbConnect($host, $db_user, $db_password, $db_name)
{
    error_reporting(0);
    $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

    /* check if server is alive */
    if ($mysqli->ping()) {
        return true;
    } else {
        return false;
    }
    /* close connection */
    $mysqli->close();
}

function setDbConnect($db_id)
{
    $companyData = \DB::table('company')->where('company_id', $db_id)->first();
    $companyData = objectToArray($companyData);

    selectDatabase1($companyData['host'], $companyData['db_user'], $companyData['db_password'], $companyData['db_name']);
}


/*
 * Function to Encrypt user sensitive data for storing in the database
 *
 * @param string    $value      The text to be encrypted
 * @param           $encodeKey  The Key to use in the encryption
 * @return                      The encrypted text
 */
function encryptIt($value)
{
    // The encodeKey MUST match the decodeKey
    $encodeKey = 'Li1KUqJ4tgX14dS,A9ejk?uwnXaNSD@fQ+!+D.f^`Jy';
    $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encodeKey), $value, MCRYPT_MODE_CBC, md5(md5($encodeKey))));
    return ($encoded);
}

/*
 * Function to decrypt user sensitive data for displaying to the user
 *
 * @param string    $value      The text to be decrypted
 * @param           $decodeKey  The Key to use for decryption
 * @return                      The decrypted text
 */
function decryptIt($value)
{
    // The decodeKey MUST match the encodeKey
    $decodeKey = 'Li1KUqJ4tgX14dS,A9ejk?uwnXaNSD@fQ+!+D.f^`Jy';
    $decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($decodeKey), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($decodeKey))), "\0");
    return ($decoded);
}

function emptyDatabase()
{
    foreach (\DB::select('SHOW TABLES') as $table) {
        $table_array = get_object_vars($table);

        if (!empty($table_array)) {
            \Schema::drop($table_array[key($table_array)]);
        }
    }
}

function uniqueMultidimArray($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function AssColumn($a = array(), $column = 'id')
{
    $two_level = func_num_args() > 2 ? true : false;
    if ($two_level) $scolumn = func_get_arg(2);

    $ret = array();
    settype($a, 'array');
    if (false == $two_level) {
        foreach ($a AS $one) {
            if (is_array($one))
                $ret[@$one[$column]] = $one;
            else
                $ret[@$one->$column] = $one;
        }
    } else {
        foreach ($a AS $one) {
            if (is_array($one)) {
                if (false == isset($ret[@$one[$column]])) {
                    $ret[@$one[$column]] = array();
                }
                $ret[@$one[$column]][@$one[$scolumn]] = $one;
            } else {
                if (false == isset($ret[@$one->$column]))
                    $ret[@$one->$column] = array();

                $ret[@$one->$column][@$one->$scolumn] = $one;
            }
        }
    }
    return $ret;
}

function formatDate($value)
{
    $prefData['preference'] = Preference::getAll()->where('category', 'preference')->pluck('value', 'field')->toArray();

    if ($prefData['preference']['date_format'] == '0') {
        // yyyy-mm-dd
        $format = 'Y' . $prefData['preference']['date_sepa'] . 'm' . $prefData['preference']['date_sepa'] . 'd';

        $date = date($format, strtotime(strtr($value, $prefData['preference']['date_sepa'], '-')));

    } elseif ($prefData['preference']['date_format'] == '1') {
        // dd-mm-yyyy
        $format = 'd' . $prefData['preference']['date_sepa'] . 'm' . $prefData['preference']['date_sepa'] . 'Y';

        $date = date($format, strtotime(strtr($value, $prefData['preference']['date_sepa'], '-')));

    } elseif ($prefData['preference']['date_format'] == '2') {
        // mm-dd-yyyy
        $format = 'm' . $prefData['preference']['date_sepa'] . 'd' . $prefData['preference']['date_sepa'] . 'Y';
        $date = date($format, strtotime(strtr($value, $prefData['preference']['date_sepa'], '-')));
    } elseif ($prefData['preference']['date_format'] == '3') {
        // D-M-yyyy
        $format = 'd' . $prefData['preference']['date_sepa'] . 'M' . $prefData['preference']['date_sepa'] . 'Y';
        $date = date($format, strtotime(strtr($value, $prefData['preference']['date_sepa'], '-')));
    } elseif ($prefData['preference']['date_format'] == '4') {
        // yyyy-mm-D
        $format = 'Y' . $prefData['preference']['date_sepa'] . 'M' . $prefData['preference']['date_sepa'] . 'd';
        $date = date($format, strtotime(strtr($value, $prefData['preference']['date_sepa'], '-')));
    }
    return $date;
}

function timeZoneformatDate($value)
{
    $middleware = str_replace('auth:', '', request()->route()->middleware()[1]);
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    if ($middleware == 'customer') {
        $timezone = Customer::getTimezone();
    } else {
        $timezone = $preference['default_timezone'];
    }
    if (empty($timezone)) {
        $timezone = config('app.timezone');
    }

    $today = new DateTime($value, new DateTimeZone(config('app.timezone')));
    $today->setTimezone(new DateTimeZone($timezone));
    $value = $today->format('Y-m-d h:m:s');

    $date_format_type = $preference['date_format_type'];
    $separator = $preference['date_sepa'];

    $data = str_replace(['/', '.', ' ', '-'], $separator, $date_format_type);
    $data = explode($separator, $data);

    $first = $data[0];
    $second = $data[1];
    $third = $data[2];

    if ($first == 'yyyy' && $second == 'mm' && $third == 'dd') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], $separator, $value);
        $datas = explode($separator, $dateInfo);
        $year = $datas[0];
        $month = $datas[1];
        $day = $datas[2];
        $value = $year . $separator . $month . $separator . $day;
    } elseif ($first == 'dd' && $second == 'mm' && $third == 'yyyy') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], $separator, $value);
        $datas = explode($separator, $dateInfo);
        $year = $datas[0];
        $month = $datas[1];
        $day = $datas[2];
        $value = $day . $separator . $month . $separator . $year;
    } elseif ($first == 'mm' && $second == 'dd' && $third == 'yyyy') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], $separator, $value);
        $datas = explode($separator, $dateInfo);
        $year = $datas[0];
        $month = $datas[1];
        $day = $datas[2];
        $value = $month . $separator . $day . $separator . $year;
    } elseif ($first == 'dd' && $second == 'M' && $third == 'yyyy') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], $separator, $value);
        $datas = explode($separator, $dateInfo);
        $year = $datas[0];
        $month = $datas[1];
        $day = $datas[2];

        $dateObj = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('M');

        $value = $day . $separator . $monthName . $separator . $year;
    } elseif ($first == 'yyyy' && $second == 'M' && $third == 'dd') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], $separator, $value);
        $datas = explode($separator, $dateInfo);
        $year = $datas[0];
        $month = $datas[1];
        $day = $datas[2];

        $dateObj = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('M');
        $value = $year . $separator . $monthName . $separator . $day;
    }
    return $value;
}

function DbDateFormat($value)
{
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    $preference = $preference['date_format_type'];
    $data = str_replace(['/', '.', ' ', '-'], ['-', '-', '-', '-'], $preference);
    $data = explode('-', $data);
    $mm = $data[0];
    if ($mm == 'mm') {
        $dateInfo = str_replace(['/', '.', ' ', '-'], ['-', '-', '-', '-'], $value);
        $datas = explode('-', $dateInfo);
        $month = $datas[0];
        $day = $datas[1];
        $year = $datas[2];
        $value = $day . '-' . $month . '-' . $year;
    } else {
        $value = str_replace(['/', '.', ' ', '-'], ['-', '-', '-', '-'], $value);
    }
    $value = date('Y-m-d', strtotime($value));

    return $value;
}

function formatCurrencyAmount($value, $currency = null)
{
    $preference = Preference::getAll()->where('category', 'preference')->whereIn('field', ['symbol_position', 'decimal_digits', 'thousand_separator'])->pluck('value', 'field')->toArray();
    if (!is_int($value)) {
        $array = explode('.', $value);
        $value = substr($value, 0, (strlen($array[0]) + 1 + $preference['decimal_digits']));

    }
    if ($preference['thousand_separator'] == ".") {
        $amount = number_format((float) $value, $preference['decimal_digits'], ',', '.');
    } else {
        $amount = number_format((float) $value, $preference['decimal_digits'], '.', ',');
    }
    if (!empty($currency)) {
        if ($preference['symbol_position'] == 'before') {
            return $currency.$amount;
        }
        return $amount.$currency;
    }
    return $amount;
}

function validateNumbers($number)
{
    $preference = Preference::getAll()->where('category', 'preference')->where('field', 'thousand_separator')->pluck('value', 'field')->toArray();
    if ($preference['thousand_separator'] == ".") {
        $number = str_replace(".", "", $number);
    } else {
        $number = str_replace(",", "", $number);
    }
    $number = floatval(str_replace(",", ".", $number));
    return $number;
}

function isInt ($x)
{
    return (is_numeric($x) ? intval($x) == $x : false);
}

function DbDateTimeFormat($value)
{
    return date('Y-m-d H:i:s', strtotime($value));
}

function backup_tables($host, $user, $pass, $name, $tables = '*')
{
    $path ='';

    try {
        $con = mysqli_connect($host, $user, $pass, $name);
    } catch (Exception $e) {

    }

    if (mysqli_connect_errno()) {
        \Session::flash('fail', "Failed to connect to MySQL: " . mysqli_connect_error());
        return 0;
    }

    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $result = mysqli_query($con, 'SHOW TABLES');
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    //cycle through
    $return = '';
    foreach ($tables as $table) {
        $result = mysqli_query($con, 'SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);


        $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE ' . $table));
        $return .= "\n\n" . str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $row2[1]) . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }

        $return .= "\n\n\n";
    }

    $backup_name = date('Y-m-d-His') . '.sql';
    //save file
    /**
     * [$path description]
     * @var [type]
     * in here it will check the directory in storage
     * before make a file in the laravel-backups Directory
     */
    $path = storage_path("laravel-backups");
    if (!file_exists(storage_path("laravel-backups"))) {
        if (is_writable(storage_path())) {
            mkdir($path, 0777, true);
        } else {
            \Session::flash('error', __('Enable write permission'));
            return redirect()->back();
        }
    }
    
    $handle = fopen(storage_path("laravel-backups") . '/' . $backup_name, 'w+');
    fwrite($handle, $return);
    fclose($handle);

    return $backup_name;
}

function makeExpenseReportGraph($datas)
{
    $graphData = [];
    $i = 0;
    $j = 0;
    foreach ($datas as $key => $value) {
        $graphData[$i][$j++] = $key;
        $sm = 0;
        foreach ($value as $v) {
            $sm += abs($v);
        }
        $graphData[$i][$j++] = $sm;
        $j = 0;
        $i++;
    }
    return $graphData;
}

function previousDate()
{
    $preDate = date("Y-m-d", strtotime("-5 month"));
    $preday = date("d", strtotime($preDate)) - 1;
    $newdate = strtotime("-$preday day", strtotime($preDate));
    $newdate = date('Y-m-j', $newdate);
    return $newdate;
}

function getMonthList ()
{
    $monthList = array(
                        '01' => 'January',
                        '02' => 'February',
                        '03' => 'March',
                        '04' => 'April',
                        '05' => 'May',
                        '06' => 'June',
                        '07' => 'July',
                        '08' => 'August',
                        '09' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December'
                    );
    return $monthList;
}

function getMonthNumber($val)
{
    $monthList = array(
                        '0' => 'Jan',
                        '1' => 'Feb',
                        '2' => 'Mar',
                        '3' => 'Apr',
                        '4' => 'May',
                        '5' => 'Jun',
                        '6' => 'Jul',
                        '7' => 'Aug',
                        '8' => 'Sep',
                        '9' => 'Oct',
                        '10' => 'Nov',
                        '11' => 'Dec'
                    );
    for ($i=0; $i <= count($monthList); $i++) { 
        if ($monthList[$i] == $val) {
            return $i;
        }
    }
}

function getLastSixMonthName()
{
    $data = array();
    for ($j = 5; $j >= 0; $j--) {
        $data[5 - $j] = date("F-Y", strtotime(" -$j month"));
    }
    return $data;
}

function getLastSixMonthNumber()
{
    $data = array();
    for ($j = 5; $j >= 0; $j--) {
        $data[5 - $j] = date("n-Y", strtotime(" -$j month"));
    }
    return $data;
}

function getExpenseArrayList($sixMonthExpense, $getLastSixMonthNumber)
{

    $data_map = [];
    foreach ($sixMonthExpense as $key => $value) {
        $data_map[$value->month][$value->year] = $value->amount;
    }
    $final = [];
    $i = 0;
    foreach ($getLastSixMonthNumber as $key => $value) {
        $date = explode('-', $value);
        $tm = (int)$date[0];
        $ty = (int)$date[1];
        $final[$i]['month'] = $date[0];
        $final[$i]['year'] = $date[1];
        if (isset($data_map[$tm][$ty])) $final[$i]['amount'] = $data_map[$tm][$ty];
        else $final[$i]['amount'] = 0;
        $i++;
    }

    return $final;

}

function getProfitArrayList($expenseArrayList, $incomeArrayList)
{

    $data = array();
    for ($i = 0; $i < 6; $i++) {

        $incomeAmount = isset($incomeArrayList[$i]['amount']) ? abs($incomeArrayList[$i]['amount']) : 0;
        $expenseAmpunt = isset($expenseArrayList[$i]['amount']) ? abs($expenseArrayList[$i]['amount']) : 0;
        if ($incomeAmount > 0 && $incomeAmount > $expenseAmpunt) {
            $data[$i] = ($incomeAmount - $expenseAmpunt);
        } else {
            $data[$i] = 0;
        }
    }
    return $data;
}

function getLastOneMonthDates()
{
    $data = array();
    for ($j = 30; $j > -1; $j--) {
        $data[30 - $j] = date("d-m", strtotime(" -$j day"));
    }
    return $data;
}

function thirtyDaysNameList()
{
    $data = array();
    for ($j = 30; $j > -1; $j--) {
        $data[30 - $j] = date("d M", strtotime("-$j day"));
    }
    return $data;
}

function lastThirtyDaysProfit($income, $expense)
{
    $profit = [];
    for ($i = 0; $i <= 30; $i++) {
        $incomeAmount = !empty($income) ? abs($income[$i]) : 0;
        $expenseAmpunt = !empty($expense) ? abs($expense[$i]) : 0;
        if ($incomeAmount > 0 && $incomeAmount > $expenseAmpunt) {
            $profit[$i] = $incomeAmount - $expenseAmpunt;
        } else {
            $profit[$i] = 0;
        }
    }
    return $profit;

}

function timeZonegetTime($date)
{
    $middleware = str_replace('auth:', '', request()->route()->middleware()[1]);
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    if ($middleware == 'customer') {
        $timezone = Customer::getTimezone();
    } else {
        $timezone = $preference['default_timezone'];
    }
    if (!$timezone) {
        $timezone = date_default_timezone_get();
    }
    $userTimezone = new DateTimeZone($timezone);
    $gmtTimezone = new DateTimeZone('GMT');
    $myDateTime = new DateTime($date, $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    $myInterval = DateInterval::createFromDateString((string)$offset . 'seconds');
    $myDateTime->add($myInterval);
    $time = $myDateTime->format('h:i A');
    return $time;
}

function subtractZonegetTime($date)
{
    $middleware = str_replace('auth:', '', request()->route()->middleware()[1]);
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    if ($middleware == 'customer') {
        $timezone = Customer::getTimezone();
    } else {
        $timezone = $preference['default_timezone'];
    }

    $userTimezone = new DateTimeZone($timezone);
    $gmtTimezone = new DateTimeZone('GMT');
    $myDateTime = new DateTime($date, $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    $myInterval = DateInterval::createFromDateString((string)$offset . 'seconds');
    $myDateTime->sub($myInterval);
    $time = $myDateTime->format('Y-m-d H:i:s');

    return $time;

}

function getTime($date)
{
    $time = date("h:i A", strtotime($date));
    return $time;
}

function changeEnvironmentVariable($key, $value)
{
    $path = base_path('.env');

    if (is_bool(env($key))) {
        $old = env($key) ? 'true' : 'false';
    } elseif (env($key) === null) {
        $old = 'null';
    } else {
        $old = env($key);
    }

    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            "$key=" . $old, "$key=" . $value, file_get_contents($path)
        ));
    }
}

function array2string($data)
{
    $log_a = "";
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $log_a .= "\r\n'" . $key . "' => [\r\n" . array2string($value) . "\r\n],";
        } else {
            $log_a .= "'" . $key . "'" . " => " . "'" . str_replace("'", "\\'", $value) . "',\r\n";
        }

    }
    return $log_a;
}

function timeZoneList()
{
    $zones_array = array();
    $timestamp = time();

    foreach (timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
    }
    return $zones_array;
    return $timezones;
}

function custom_sort($a,$b)
{
    return $a['transaction_date'] > $b['transaction_date'];
}

function array_group_by(array $array, $key)
{
    if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key) ) {
        trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
        return null;
    }
    $func = (!is_string($key) && is_callable($key) ? $key : null);
    $_key = $key;
    // Load the new array, splitting by the target key
    $grouped = [];
    foreach ($array as $value) {
        $key = null;
        if (is_callable($func)) {
            $key = call_user_func($func, $value);
        } elseif (is_object($value) && isset($value->{$_key})) {
            $key = $value->{$_key};
        } elseif (isset($value[$_key])) {
            $key = $value[$_key];
        }
        if ($key === null) {
            continue;
        }
        $grouped[$key][] = $value;
    }
    // Recursively build a nested grouping if more parameters are supplied
    // Each grouped array value is grouped according to the next sequential key
    if (func_num_args() > 2) {
        $args = func_get_args();
        foreach ($grouped as $key => $value) {
            $params = array_merge([ $value ], array_slice($args, 2, func_num_args()));
            $grouped[$key] = call_user_func_array('array_group_by', $params);
        }
    }
    return $grouped;
}

/**
 * getFileIcon method
 * Get awesome font (varsion 5) icon for files
 * @param  string $file
 * @return string
 */
function getFileIcon($file = null)
{
    if (empty($file)) {
        return null;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'docx':
        case 'doc':
            return 'far fa-file-word';
            break;
        case 'pdf':
            return 'far fa-file-pdf';
            break;
        case 'xlsx':
        case 'xls':
        case 'csv':
            return 'far fa-file-excel';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'far fa-image';
            break;
        default:
            return 'far fa-file';
    }
}

/**
 * getFileIconAF4 method
 * Get awesome font (varsion 4) icon for files
 * @param  string $file
 * @return string
 */
function getFileIconAF4($file =  null)
{

    if (empty($file)) {
        return null;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $icon = 'fa-paperclip';
    $icons = array(
        'audio' => 'fa-file-audio-o',
        'mp3' => 'fa-file-audio-o',
        'mp4' => 'fa-file-video-o',
        'video' => 'fa-file-video-o',
        'swf' => 'fa-film',
        'image' => 'fa-file-image-o',
        'gif' => 'fa-file-image-o',
        'jpg' => 'fa-file-image-o',
        'jpeg' => 'fa-file-image-o',
        'png' => 'fa-file-image-o',
        'pdf' => 'fa-file-pdf-o',
        'text' => 'fa-file-text-o',
        'txt' => 'fa-file-text-o',
        'word' => 'fa-file-word-o',
        'doc' => 'fa-file-word-o',
        'docx' => 'fa-file-word-o',
        'ppt' => 'fa-file-powerpoint-o',
        'pptx' => 'fa-file-powerpoint-o',
        'xls' => 'fa-file-excel-o',
        'xlsx' => 'fa-file-excel-o',
        'excel' => 'fa-file-excel-o',
        'csv' => 'fa-file-excel-o',
        'flv' => 'fa-film',
        'avi' => 'fa-file-video-o',
        'wmv' => 'fa-file-video-o',
        'asf' => 'fa-file-video-o',
        'mov' => 'fa-file-video-o',
        'webm' => 'fa-file-video-o',
        'm4v' => 'fa-file-video-o',
        'ogg' => 'fa-file-video-o',
        'ogv' => 'fa-file-video-o',
        'mkv' => 'fa-file-video-o',
        '3gp' => 'fa-file-video-o',
        'zip' => 'fa-file-archive-o',
        'rar' => 'fa-file-archive-o'
    );

    if (in_array($ext, array_keys($icons))) {
        $icon = $icons[$ext];
    }

    return '<i class="fa '. $icon .'"></i>';
}

function checkFileValidationOne($ext)
{
    return in_array($ext, getFileExtensions()) ? true : false;
}

function checkFileValidationTwo($ext)
{
    return in_array($ext, getFileExtensions(1)) ? true : false;
}
function checkFileValidationThree($ext)
{
    return in_array($ext, getFileExtensions(2)) ? true : false;
}

function getFileExtensions($type = 0)
{
    $extensions = array(
        0 => ['jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'pdf'],
        1 => ['jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'pdf'],
        2 => ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
    );
    return $extensions[$type];
}

function getSVGFlag($code = 'en')
{
    $code = strtolower($code);
    $flags = array(
                'ar' => 'sa',
                'zh' => 'cn',
                'en' => 'gb',
                'hn' => 'in',
                'rs' => 'ru',
                'bn' => 'bd',
                'ur' => 'pk',
                'cs' => 'cz',
                'zu' => 'za',
                'aa' => 'dj',
                'am' => 'et',
                'ab' => 'ru',
                'as' => 'in',
                'ay' => 'bo',
                'ba' => 'ru',
                'ba' => 'ru',
                'bh' => 'in',
                'bi' => 'vu',
                'br' => 'fr',
                'ca' => 'es',
                'co' => 'fr',
                'cy' => 'gb',
                'da' => 'dk',
                'dz' => 'bt',
                'el' => 'gr',
                'et' => 'ee',
                'fa' => 'ir',
                'fy' => 'nl',
                'ga' => 'ie',
                'gd' => 'gb-sct',
                'gn' => 'py',
                'gu' => 'in',
                'ha' => 'ne',
                'hi' => 'in',
                'hy' => 'am',
                'in' => 'id',
                'iw' => 'il',
                'ja' => 'jp',
                'ka' => 'ge',
                'kk' => 'kz',
                'kl' => 'gl',
                'km' => 'kh',
                'kn' => 'in',
                'ko' => 'kr',
                'ku' => 'iq',
                'ky' => 'kg',
                'la' => 'va',
                'ln' => 'ca',
                'mi' => 'nz',
                'ml' => 'in',
                'mo' => 'md',
                'mr' => 'in',
                'ms' => 'bn',
                'my' => 'mm',
                'ne' => 'np',
                'oc' => 'es-ct',
                'om' => 'et',
                'pa' => 'in',
                'ps' => 'af',
                'rm' => 'ch',
                'rn' => 'bl',
                'sd' => 'pk',
                'sg' => 'cf',
                'sh' => 'rs',
                'sl' => 'si',
                'si' => 'lk',
                'sm' => 'ws',
                'sn' => 'zw',
                'sq' => 'al',
                'sr' => 'rs',
                'ss' => 'sz',
                'st' => 'ls',
                'su' => 'id',
                'sv' => 'se',
                'sw' => 'tz',
                'ta' => 'in',
                'te' => 'in',
                'tg' => 'tj',
                'ti' => 'er',
                'tk' => 'tm',
                'tl' => 'ph',
                'tn' => 'bw',
                'tw' => 'gh',
                'uk' => 'ua',
                'ur' => 'pk',
                'vi' => 'vn',
                'wo' => 'sn',
                'xh' => 'za',
                'yo' => 'ng',
            );
    if (in_array($code, array_keys($flags))) {
        return $flags[$code];
    }
    return $code;
}

function getShortLanguageName($allLang = false, $languages = null)
{
    $shortList = array(
        'en' => 'English',
        'aa' => 'Afar',
        'ab' => 'Abkhazian',
        'af' => 'Afrikaans',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'as' => 'Assamese',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'be' => 'Byelorussian',
        'bg' => 'Bulgarian',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bn' => 'Bengali',
        'br' => 'Breton',
        'ca' => 'Catalan',
        'co' => 'Corsican',
        'cs' => 'Czech',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'dz' => 'Bhutani',
        'el' => 'Greek',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'fi' => 'Finnish',
        'fj' => 'Fiji',
        'fo' => 'Faeroese',
        'fr' => 'French',
        'fy' => 'Frisian',
        'ga' => 'Irish',
        'gd' => 'Scots/Gaelic',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ha' => 'Hausa',
        'hi' => 'Hindi',
        'hr' => 'Croatian',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'in' => 'Indonesian',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'iw' => 'Hebrew',
        'ja' => 'Japanese',
        'ka' => 'Georgian',
        'kk' => 'Kazakh',
        'kl' => 'Greenlandic',
        'km' => 'Cambodian',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'ku' => 'Kurdish',
        'ky' => 'Kirghiz',
        'la' => 'Latin',
        'ln' => 'Lingala',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian/Lettish',
        'mg' => 'Malagasy',
        'mi' => 'Maori',
        'mk' => 'Macedonian',
        'ml' => 'Malayalam',
        'mn' => 'Mongolian',
        'mo' => 'Moldavian',
        'mr' => 'Marathi',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'my' => 'Burmese',
        'ne' => 'Nepali',
        'nl' => 'Dutch',
        'no' => 'Norwegian',
        'oc' => 'Occitan',
        'om' => '(Afan)/Oromoor/Oriya',
        'pa' => 'Punjabi',
        'pl' => 'Polish',
        'ps' => 'Pashto/Pushto',
        'pt' => 'Portuguese',
        'rm' => 'Rhaeto-Romance',
        'rn' => 'Kirundi',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'rw' => 'Kinyarwanda',
        'sd' => 'Sindhi',
        'sg' => 'Sangro',
        'sh' => 'Serbo-Croatian',
        'si' => 'Singhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'so' => 'Somali',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'ss' => 'Siswati',
        'st' => 'Sesotho',
        'su' => 'Sundanese',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'ta' => 'Tamil',
        'te' => 'Tegulu',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'ti' => 'Tigrinya',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tn' => 'Setswana',
        'to' => 'Tonga',
        'tr' => 'Turkish',
        'tw' => 'Twi',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        'vi' => 'Vietnamese',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yo' => 'Yoruba',
        'zh' => 'Chinese',
        'zu' => 'Zulu'
    );
    if ($allLang) {
        return $shortList;
    }

    if (is_null($languages)) {
        $languages = Language::all();
    }

    $languages = $languages->pluck('name', 'short_name')->toArray();
    $unique_values = array_unique(array_merge($shortList, $languages));
    $actual_values = array_diff($unique_values, $languages);

    return $actual_values;
}

/**
 * Currency Conversion
 * @param  string $from    
 * @param  string $to      
 * @param  object $service 
 * @return float
 */
function getCurrencyRate($from = null, $to = null, $service = null)
{
    if (isset($service->slug) && $service->slug == "exchange_rate_api" && isset($service->api_key)) {
        return exchangeRateApi($from, $to, $service->api_key);
    } else if (isset($service->slug) && $service->slug == "currency_converter_api" && isset($service->api_key)) {
        return currencyConverterApi($from, $to, $service->api_key);
    }
}

/**
 * Call Exchange Rate Api 
 * @param  string $from  
 * @param  string $to    
 * @param  string $apiKey
 * @return float      
 */
function exchangeRateApi($from = null, $to = null, $apiKey = null) {
    // Fetching JSON
    $req_url = 'https://v6.exchangerate-api.com/v6/' . $apiKey . '/latest/' . $from;

    $response_json = file_get_contents($req_url);
    // Continuing if we got a result
    if(false !== $response_json) {
        // Decoding
        $response = json_decode($response_json, true);
        // Check for success
        if('success' === $response['result']) {
            return $response['conversion_rates'][$to];
        }
    }
}

/**
 * Call Currency Converter Api 
 * @param  string $from  
 * @param  string $to    
 * @param  string $apiKey
 * @return float      
 */
function currencyConverterApi($from = null, $to = null, $apiKey = null) {
    $url = "https://free.currconv.com/api/v7/convert?q=$from" . "_" . "$to&compact=ultra&apiKey=" . $apiKey;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    $variable = $from . "_" . $to;
    return json_decode($result)->$variable;
}

/**
 * create directory method
 * The a directory by the given path if not exists
 * @param string $path
 * @param int $permission
 * @return $path
 */
function createDirectory($path = '', $permission = null)
{
    if (empty($path)) {
        return $path;
    }

    $permission = empty($permission) ? config('app.filePermission') : $permission;

    if (!file_exists($path)) {
        mkdir($path, $permission, true);
    }

    return $path;
}


/**
 * [getMonths description]
 * @param  [string] $from [description]
 * @param  [string] $to   [description]
 * @return [array]       [description]
 */
function getMonths($from = null, $to = null) 
{
    $months = [];
    if (empty($from) || empty($to) || ($to < $from)) {
        return $months;
    }

    while (strtotime($from) <= strtotime($to)) {
        $months[] = date('M Y', strtotime($from));
        $from = date('d M Y', strtotime($from.
            '+ 1 month'));
    }

    return $months;
}

function printPDF($data, $filename, $template, $renderView, $type = null, $pdfVal = null) 
{
    if (strtolower($pdfVal) == "dompdf") {
        $pdf = PDF::loadView($template, $data);
        $pdf->setPaper(array(0, 0, 750, 1060), 'portrait');
        return (!empty($type) && $type == "print") ?  $pdf->stream($filename, array("Attachment" => 0)) :  $pdf->download($filename, array("Attachment" => 0));
    } else if ($pdfVal == "email") {
        $mpdf = initializeMpdf();
        $mpdf->WriteHTML($renderView);
        $mpdf->Output($filename, 'F'); 
    } else  {
        $mpdf = initializeMpdf();
        $mpdf->WriteHTML($renderView);
        (!empty($type) && $type == "print") ? $mpdf->Output($filename, 'I') : $mpdf->Output($filename, 'D'); 
    }
}

function initializeMpdf () 
{
    $path = createDirectory("public/contents/temp");
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => $path,
        'mode'        => 'utf-8',
        'format'      => 'A4',
        'orientation' => 'P',
    ]);

    $mpdf->autoScriptToLang         = true;
    $mpdf->autoLangToFont           = true;
    $mpdf->allow_charset_conversion = false;

    return $mpdf;
}

/**
 * Get Data From Json File 
 * @param  string $file [description]
 * @return array       [description]
 */
function getJsonDataFromFile($file = null)
{
	if (empty($file)) {
		return [];
	}

	$data = json_decode(file_get_contents($file));

	return $data;
}

/**
 * [getUniqueAssocArray description]
 * @param  array  $array [description]
 * @return array        [description]
 */
function getUniqueAssocArray($array = [])
{
	if (!is_array($array) || empty($array)) {
		return array();
	}

	$unique = [];
	foreach ($array as $key => $value) {
		if (!array_key_exists($key, $unique)) {
		    $unique[$key] = $value;   
		}
	}

	return $unique;
}

/**
 * Create a json file from array
 * @param  string $filename [description]
 * @param  array  $data     [description]
 * @return void          [description]
 */
function createJsonFile($filename = 'file.json', $data = array())
{
	$fp = fopen($filename, 'w');
	fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
	fclose($fp);
}


function translateValidationMessages()
{
    $flag = config('app.locale');
    if (!empty($flag) && file_exists(public_path('../resources/lang/validation/'. $flag .'.js'))) {
        return '<script src="' . asset('resources/lang/validation/'. $flag .'.js') . '"></script>';
    }
}

/**
 * To get user profile picture
 * @param  int $userId [user id]
 * @param  int $thumbnail
 * @return string $image [ user profile picture]
 */
function getUserProfilePicture($userId = null, $thumbnail = 1)
{
    $image = Cache::get('gb-user-'. $thumbnail .'-avatar-'. $userId);
    if (empty($image)) {
        $image = url("public/dist/img/avatar.jpg");
        if (!empty($userId)) {
            $userPic = (new File)->getFiles('USER', $userId);
            if (isset($userPic[0])) {
                $path = $thumbnail ? 'uploads/user/thumbnail/' : 'uploads/user/';
                if (file_exists(public_path($path . $userPic[0]->file_name))) {
                    $image = url('public/'. $path . $userPic[0]->file_name); 
                }
            }
        }
        Cache::put('gb-user-'. $thumbnail .'-avatar-'. $userId, $image, 604800);
    }

    return $image;
}

function getMultipleUserProfilePicture($userId = [])
{
    if (empty($userId)) {
        return [];
    }
    $image = Cache::get('gb-multiple-user-avatar-'. implode('-', $userId));
    if (empty($image)) {
        if (!empty($userId)) {
            $userPic = (new File)->getFiles('USER', $userId)->pluck('file_name', 'object_id');
            foreach ($userId as $value) {
                if (isset($userPic[$value]) && file_exists(public_path('uploads/user/'. $userPic[$value]))) {
                    $image[$value] =  url('public/uploads/user/'. $userPic[$value]);
                } else {
                    $image[$value] = url("public/dist/img/avatar.jpg");
                }
            }
        }
        Cache::put('gb-multiple-user-avatar-'.implode('-', $userId), $image, 600);
    }

    return $image;
}

function validatePhoneNumber($number = null)
{
    $pattern = "/^[+0-9 () \-]{8,20}$/";
    if (!empty($number) && preg_match($pattern, $number)) {
      $result = 1;
    } else {
      $result = 0;
    }
    
    return $result;
}

function validateEmail($email = null)
{
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    if (!empty($email) && preg_match($pattern, $email)) {
        $result = 1;
    } else {
        $result = 0;
    }

    return $result;
}

function validateName($name = null)
{
    $pattern = "/^[a-zA-Z-'\s]+[a-zA-Z-'\.?\s]+$/";
    if (!empty($name) && preg_match($pattern, $name)) {
        $result = 1;
    } else {
        $result = 0;
    }

    if ($result) {
        $result = strlen($name) > 0 ? 1 : 0;
    }

    return $result;
}

function getPdfFont()
{
    $fontFamily = [];

    $languageShortCode = config('app.locale');
    switch ($languageShortCode) {
        case 'ar':
            $fontFamily['name'] = "Tajawal" . ", sans-serif";
            $fontFamily['link'] = asset('public/dist/fonts/tajawal.css?v1');
            break;

        default:
            $fontFamily['name'] = "Helvetica Neue" . ", sans-serif";
            $fontFamily['link'] = '#';
            break;
    }
    return $fontFamily;
}

function getThemeClass($tagName = null)
{
    $cssClass = "";
    if (empty($tagName)) {
        return $cssClass;
    }

    $themePreferences = Preference::getAll()->where('field', 'theme_preference')->where('category', 'preference')->first();
    $themePreferences = !empty($themePreferences['value']) ? json_decode($themePreferences['value'], true) : '';

    if (!is_array($themePreferences)) {
        return $cssClass;
    }

    foreach ($themePreferences as $key => $value) {
        if ($value == 'default') {
            $data[$key] = '';
        } else {
            $data[$key] = $value;
        }
    }

    if ($tagName == 'header') {
        $cssClass = (!empty($data['header_background']) ? $data['header_background'] : '') . ' ' . (!empty($data['header-fixed']) ? $data['header-fixed'] : '');
        return $cssClass;
    } 

    if ($tagName == 'body') {
        $cssClass = !empty($themePreferences['box-layout']) ? $themePreferences['box-layout'] : '';
        return $cssClass;
    }

    if ($tagName == 'navbar') {
        $cssClass = (!empty($data['theme_mode']) ? $data['theme_mode'] : 'pcoded-navbar' . ' ') .
        (!empty($data['menu_brand_background']) ? $data['menu_brand_background'] : '') . ' ' .
        (!empty($data['menu_background']) ? $data['menu_background'] : '') . ' ' .
        (!empty($data['menu_item_color']) ? $data['menu_item_color'] : '') . ' ' .
        (!empty($data['navbar_image']) ? $data['navbar_image'] : '') . ' ' .
        (!empty($data['menu-icon-colored']) ? $data['menu-icon-colored'] : '') . ' ' .
        (!empty($data['menu-fixed']) ? $data['menu-fixed'] : '') . ' ' .
        (!empty($data['menu_list_icon']) ? $data['menu_list_icon'] : '') . ' ' .
        (!empty($data['menu_dropdown_icon']) ? $data['menu_dropdown_icon'] : '');
        return $cssClass;
    }

    if ($tagName == 'theme-mode') {
        $cssClass = !empty($themePreferences['theme_mode']) ? $themePreferences['theme_mode'] : '';
        return $cssClass;
    }

    return $cssClass;
}

/**
 * maxFileSize
 * @param  int $fileSize
 * @return string
 */
function maxFileSize($fileSize)
{
    $data = [];
    $maxfileSize = Preference::getAll()->where('field', 'file_size')->where('category', 'preference')->first()->value;
    if (isset($maxfileSize) && !empty($maxfileSize)) {
        $maxFileSize = (int) $maxfileSize;
        if (($fileSize / 1024) <= $maxfileSize * 1024) {
            $data['status'] = 1;
        } else if (($fileSize / 1024) > $maxfileSize * 1024) {
            $data['status'] = 0;
            $data['message'] = __('Maximum File Size :? MB.', ['?' => $maxfileSize]);
        }
        return $data;
    }
}

/**
 * updateLangauageFile method
 * To create OR update Translation File
 * @param string $code [Language short name]
 * @return void
*/
function updateLanguageFile($code)
{
    $jsonString = [];
    if (file_exists(base_path('resources/lang/'. $code .'.json'))) {
        $jsonString = json_decode(file_get_contents(base_path('resources/lang/'. $code .'.json')), true);
        $enString = json_decode(file_get_contents(base_path('resources/lang/en.json')), true);
        $keys = array_keys($enString);
        foreach ($jsonString as $key => $value) {
            if (in_array($key, $keys)) {
                $array = $enString[$key];
                if (is_array($array)) {
                    foreach ($array as $k => $v) {
                        if (in_array($k, array_keys($value))) {
                            $enString[$key][$k] = $value[$k];
                        } else {
                            $enString[$key][$k] = $v;
                        }
                    }
                } else {
                    $enString[$key] = $value;
                }
            }
        }
        saveJSONFile($code, $enString);
    } else {
        file_put_contents(base_path('resources/lang/'. $code .'.json'), file_get_contents(base_path('resources/lang/en.json')));
    }
}

/**
 * Open Translation File
 * @return Response
*/
function openJSONFile($code)
{
    $jsonString = file_get_contents(base_path('resources/lang/'. $code .'.json'));
    $jsonString = json_decode($jsonString, true);
    return $jsonString;
}

/**
 * Save JSON File
 * @return Response
*/
function saveJSONFile($code, $data)
{
    $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/'.$code.'.json'), stripslashes($jsonData));
    \Cache::forget('lanObject-' . $code);
}

function getRouteAccordingToPermission($arr)
{
    
    if (!is_array($arr)) {
        abort(404);
    }

    foreach ($arr as $key => $value) {
        if (Helpers::has_permission(\Auth::user()->id, $key) == 1) {
           return $value;
        }
    }
    abort(404);
}

function xss_clean($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    return $data;
}

function sanitize_output($value, int $strip = 0)
{
    $value = htmlspecialchars($value, ENT_QUOTES);

    if ($strip == 1) {
        $string = stripslashes($value);
    }
    $value = str_replace('&amp;#', '&#', $value);
    return $value;
}
/**
 * set color of file name and icon 
 * @param  string $fileExtension
 * @return string       
 */
function setColor($fileExtension)
{
    $color = "#0F9D58";
    if (in_array($fileExtension, array('csv', 'xls', 'xlsx'))) {
        $color = '#00A953';
    } else if ($fileExtension == 'pdf') {
        $color = '#FB4C2F';
    } else if (in_array($fileExtension, array('jpg', 'png', 'jpeg', 'gif'))) {
        $color = '#D93025';
    } else if (in_array($fileExtension, array('doc', 'docx'))) {
        $color = '#4986E7';
    }

    return $color;
}

function getTimeSpent($timesheetDetail)
{
  if (!empty($timesheetDetail->end_time)) {
      $diff = ($timesheetDetail->end_time > $timesheetDetail->start_time) ? ($timesheetDetail->end_time - $timesheetDetail->start_time) : null;
  } else {
      $timesheetDetail->start_time = (int) $timesheetDetail->start_time;
      $diff = (time() > $timesheetDetail->start_time) ?  time() - $timesheetDetail->start_time : null;
  }
  $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
  $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
  $seconds  = $diff % 60;
  $diffTime = $hours . $minutes . $seconds . 's';

  return $diffTime;
}

function formatText($string, $doSlash = true)
{
    $string = str_replace(["'", "\r\n"], ["`", "\\r\\n"], $string);
    if ($doSlash) {
        $string = addslashes($string);
    }
    return $string;
}

/**
 * stripBeforeSave method
 * This function strips or skips HTML tags
 * 
 * @param string $string [The text that will be stripped]
 * @param array $options 
 * 
 * @return string
 */
function stripBeforeSave($string = null, $options = ['skipAllTags' => true, 'mergeTags' => false]) 
{
    $finalString = [];
    if ($options['skipAllTags'] === false) {
        $allow = '<h1><h2><h3><h4><h5><h6><p><b><br><hr><i><pre><small><strike><strong><sub><sup><time><u><form><input><textarea><button><select><option><label><frame><iframe><img><audio><video><a><link><nav><ul><ol><li><table><th><tr><td><thead><tbody><div><span><header><footer><main><section><article>';
        if (isset($options['mergeTags']) && $options['mergeTags'] === true && isset($options['allowedTags'])) {
            $allow .= is_array($options['allowedTags']) ? implode('', $options['allowedTags']) : trim($options['allowedTags']);
        } else {
            $allow = isset($options['allowedTags']) && is_array($options['allowedTags']) ? implode('', $options['allowedTags']) : trim($options['allowedTags']);
        }
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $finalString[$key] = strip_tags($value, $allow);
            }
        } else {
            $finalString = strip_tags($string, $allow);
        }
    } else {
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $finalString[$key] = strip_tags($value);
            }
        } else {
            $finalString = strip_tags($string);
        }
    }
    return !empty($finalString) ? $finalString : null;
}

/**
 * Read a CSV file and return data as array
 * 
 * @param string $csvFile csv file path
 * @return array
 */
function readCSVFile($csvFile, $associative = false, $delimeter = ",") {
	if (!file_exists($csvFile) || !is_readable($csvFile)) {
		return false;
	}

	$row = 1;
	$handle = fopen($csvFile, "r");
	$out = array();
	while (($data = fgetcsv($handle, 0, $delimeter)) !== FALSE) {
		$out[($row - 1)] = $data;
		$row++;    
	}
	fclose($handle);

	if ($associative) {
		$headers = array_map('trim', array_shift($out));
		$asso = array();
		foreach ($out as $i => &$item) {
			foreach ($item as $j => &$value) {
				$asso[$i][$headers[$j]] = $value;
			}
		}
		$out = $asso;
	}
	
	return $out;
}