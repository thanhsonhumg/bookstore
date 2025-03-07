<?php
// Start session
session_start();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('Asia/Ho_Chi_Minh');


$config = [
    'pe' => false,
    'env' => 'ntson123',

    'db_host' => 'mysql',
    'db_port' => 3306,

    'db_user' => 'root',
    'db_pass' => 'root',
    'db_name' => 'bookstore'
];

$cm_pg = new common_mysql(
    $config['db_host'],
    $config['db_name'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_port']
);

// echo "Initial successful";
//header_admin
$title = "Trang chủ";
function header_admin($title)
{
    $header_admin = <<<header
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{$title} | TSownStore</title>
    <link rel="icon" href="http://localhost/bookstore/imgs/get-img/book.png">
    <link rel="stylesheet" href="http://localhost/bookstore/css/style-admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".hamburger").click(function() {
                $(".wrapper").toggleClass("active")
            })
        });
    </script>
</head>

<body>

    <div class="wrapper">

        <div class="top_navbar">
            <div class="logo">
                <a href="../index.php">TSownStore</a>
            </div>
            <div class="top_menu">
                <div class="home_link">
                    <a href="dashboard.php">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span>Home</span>
                    </a>
                </div>
                
                <div class="right_info">
                    <div class="search-bar">
                        <form action="http://localhost/bookstore/admin/search.php" method="GET" class="search-form">
                            <input type="text"  name="search" placeholder="Tìm kiếm"/>
                            <button type="submit" class="fa-solid fa-magnifying-glass"></button>
                        </form>
                    </div>
                    <div class="icon_wrap logout">
                        <div class="icon">
                            <a href="../login/logout"class="fas fa-sign-out"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main_body">

            <div class="sidebar_menu">
                <div class="inner__sidebar_menu">

                    <ul>
                        <li>
                            <a href="dashboard">
                                <span class="icon"><i class="fas fa-chart-pie"></i></span>
                                <span class="list">Thống kê</span>
                            </a>
                        </li>
                        <li>
                            <a href="order">
                                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                                <span class="list">Đơn hàng</span>
                            </a>
                        </li>
                        <li>
                            <a href="product">
                                <span class="icon"><i class="fa-solid fa-store"></i></span>
                                <span class="list">Sản phẩm</span>
                            </a>
                        </li>
                        <li>
                            <a href="account">
                                <span class="icon"><i class="fas fa-address-book"></i></span>
                                <span class="list">Tài khoản</span>
                            </a>
                        </li>
                    </ul>

                    <div class="hamburger">
                        <div class="inner_hamburger">
                            <span class="arrow">
                                <i class="fas fa-long-arrow-alt-left"></i>
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container">

header;
    return $header_admin;
}
// footer_admin
function footer_admin()
{
    $footer_admin = <<<footer
		</div>
	</div>
</div>
</body>
</html>
footer;
    return $footer_admin;
}
// header_normal
function header_normal($title)
{

    $header_normal = <<<header
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="http://localhost/bookstore/imgs/get-img/book.png">
    <title>{$title} | TSownStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/bookstore/css/style-normal.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="http://localhost/bookstore/js/script.js"></script>

</head>

<body>
    <!-- header -->
    <header>

        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>
        <img src="http://localhost/bookstore/imgs/get-img/book.png" class="logo-header" alt="">
        <a href="http://localhost/bookstore/index.php" class="logo">TSownStore<span>.</span></a>
        <nav class="navbar">
            <a href="http://localhost/bookstore/index.php">Trang chủ</a>
            <a href="http://localhost/bookstore/index.php#about">Giới thiệu</a>
            <a href="http://localhost/bookstore/index.php#products">Cửa hàng</a>
            <a href="http://localhost/bookstore/index.php#review">Đánh giá</a>
            <a href="http://localhost/bookstore/index.php#contact">Liên hệ</a>
        </nav>
        <div class="search-bar">
            <form action="http://localhost/bookstore/store/search.php" method="GET" class="search-form">
                <input type="text"  name="search" placeholder="tìm kiếm"/>
                <button type="submit" class="fa-solid fa-magnifying-glass"></button>
            </form>
        </div>
        <div class="icons">
            <a href="http://localhost/bookstore/store/favorites" class="fas fa-heart"></a>
            <a href="http://localhost/bookstore/store/cart" class="fas fa-shopping-cart"></a>
            <a href="http://localhost/bookstore/store/user-infor" class="fas fa-user"></a>
            <a href="http://localhost/bookstore/login/logout" class="fa-solid fa-right-from-bracket logout"></a>
            
        </div>

    </header>
    <!-- ------- -->
header;
    return $header_normal;
}

$content = "";
// footer_normal
function footer_normal()
{
    $footer_normal = <<<footer
    <!-- section footer -->
    <section class="footer" id="footer">
        <div class="box-container">
            <div class="box">
                <h3>Liên kết nhanh</h3>
                <a href="/bookstore/index.php">Trang chủ</a>
                <a href="#about">Giới thiệu</a>
                <a href="#products">Cửa hàng</a>
                <a href="#review">Đánh giá</a>
                <a href="#contact">Liên hệ</a>
            </div>

            <div class="box">
                <h3>Khác</h3>
                <a href="#">Thông tin cá nhân</a>
                <a href="#">Giỏ hàng</a>
                <a href="#">Yêu thích</a>

            </div>

            <div class="box">
                <h3>Thông tin liên lạc</h3>
                <p>(+84) 965 970 545</p>
                <p>son29102k2@gmail.com</p>
                <p>Hiệp Hòa, Bắc Giang</p>
                <img src="imgs/get-img/payment.png" alt="">

            </div>
        </div>
        <div class="credit">Được tạo bởi <span>Thanh Son Nguyen</span> | all right reserved</div>
    </section>

    <!-- ------- -->

</body>

</html>
footer;
    return $footer_normal;
}



class common_mysql
{

    var $cn;
    var $dbname;

    // Constructor
    function __construct($host, $dbname, $user, $pass, $port)
    {

        // Connection
        $cn = $this->connect($host, $dbname, $user, $pass, $port);
        // Set members
        $this->cn = $cn;
        $this->dbname = $dbname;
    }

    // Connect
    function connect($host, $dbname, $user, $pass, $port = 3306)
    {
        $cn = mysqli_connect($host, $user, $pass, $dbname, $port)
            or die('Failed to connect to database');

        // Encode to utf8
        mysqli_set_charset($cn, 'utf8');

        return $cn;
    }

    /*
	 * Escape query
	 */
    function execute($query, $data = array())
    {

        // Format the given query
        if (count($data) > 0) {
            $query = vsprintf(str_replace('?', '%s', $query), $data);
        }

        // print $query;
        if (!($rs = mysqli_query($this->cn, $query))) {
            $_SESSION['error_query'] = $query;
        }

        return $rs;
    }

    /*
	 * Retrieve a single row of data
	 * Return as an associative array
	 */
    function select_one($query, $data = array())
    {

        $result = $this->execute($query, $data);

        // if (pg_num_rows($result)) {
        if (mysqli_num_rows($result)) {
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $data;
        } else {
            return false;
        }
    }

    /*
	 * Save data
	 * If $where is specified, update the matching data,
	 * otherwise execute as a new data insertion.
	 */
    function save($table, $data = array(), $where = null, $return_sql_only = null)
    {

        // Record the update time and updater ID
        $id = (int)$id;

        $now = date('Y-m-d H:i:s');
        $data['update_dt'] = $now;
        $data['update_id'] = $id;

        // UPDATE
        if ((bool)$where) {
            $data = $this->format_fields($table, $data);
            $where = $this->format_fields($table, $where);

            $set = null;
            foreach ($data as $key => $value) {
                $set .= ((bool)$set) ? ", $key = $value" : "$key = $value";
            }

            foreach ($where as $key => $value) {
                $where_s .= ((bool)$where_s) ? " And $key = $value" : "$key = $value";
            }

            $query = "UPDATE $table SET $set WHERE $where_s ;";

            // INSERT
        } else {
            // When new, also save the creation time and creator
            $data['insert_dt'] = $now;
            $data['insert_id'] = $id;

            $data = $this->format_fields($table, $data);

            $column = null;
            $values = null;
            foreach ($data as $key => $value) {
                $column .= ((bool)$column) ? ', ' . $key : $key;
                $values .= ($values . '' != '') ? ', ' . $value : $value;
            }

            $query = "INSERT INTO $table ($column) VALUES($values);";
        }

        $_SESSION['query'] = $query;

        // echo $query . '<br />';

        // If only returning the SQL
        if ($return_sql_only + 0 == 1) {
            return $query;
        }

        return mysqli_query($this->cn, $query);
    }
    /*
	 * Delete data
	 */
    function delete($table, $data = array())
    {
        if ((bool)$data) {
            foreach ($data as $key => $value) {
                $where .= ((bool)$where) ? " And $key = $value" : "$key = $value";
            }
            $where = ' WHERE ' . $where;
        }
        $query = "DELETE FROM $table$where ;";
        $_SESSION['query'] = $query;

        return mysqli_query($this->cn, $query);
    }

    function format_fields($table, $data)
    {
        $types = $this->get_field_types($table);
        foreach ($data as $key => $value) {
            if (!(empty($types[$key]))) {
                if ($types[$key] == 'text' or $types[$key] == 'varchar') {
                    $data[$key] =  $this->set_string($value);
                } elseif ($types[$key] == 'timestamp') {

                    if (strpos(' ' . $value, '(0)') > 0) {
                    } else {
                        $value = date('Y-m-d H:i:s', strtotime($value));
                        $data[$key] =  $this->set_string($value);
                    }
                } else {
                    if (strpos(' ' . $value, 'nextval') > 0) {
                    } elseif (strpos(' ' . $value, 'select') > 0) {
                    } elseif (strpos(' ' . $value, '(') > 0) {
                    } else {
                        $data[$key] =  $this->set_numeric($value);
                    }
                }
            } else {

                unset($data[$key]);
            }
        }

        return $data;
    }

    function get_field_types($table)
    {
        $reflesh_each_time = false;
        $reflesh_each_time = true;

        if (count($_SESSION['types'][$table]) == 0 or $reflesh_each_time) {
            $sql = " select * from " . $table . " limit 0 ";
            $_SESSION['sql'] = $sql;
            //if ($rs = pg_query($this->cn, $sql)) {
            if ($rs = mysqli_query($this->cn, $sql)) {
                $finfo = mysqli_fetch_fields($rs);

                $field_type_array = array(
                    1 => "bool",
                    2 => "int2",
                    3 => "int4",
                    4 => "numeric",
                    5 => "float8",
                    7 => "timestamp",
                    8 => "int4",
                    10 => "date",
                    11 => "time",
                    16 => "bit",
                    246 => "numeric",
                    252 => "text",
                    253 => "varchar",
                    254 => "bpchar"
                );
                foreach ($finfo as $val) {
                    $ret[$val->name] =  $field_type_array[$val->type];
                }
            }
            $_SESSION['types'][$table] = $ret;
        }
        return $_SESSION['types'][$table];
    }

    // Convert to numeric
    function set_numeric($str)
    {
        $str = str_replace(",", "", $str);
        $str = str_replace("%", "", $str);
        return $str + 0;
    }

    function set_integer($str)
    {
        return (int)$str;
    }

    // String (escape and add quotes)
    function set_string($str)
    {
        return "'" . mysqli_real_escape_string($this->cn, $str) . "'";
    }
}

$stop_flg_arr = array(0 => "Đang bán", 1 => "Dừng bán");

function data2input($txt)
{
    if ($txt + 0 == 0) return "";

    if (strlen($txt) == 4) {
        $mode = "nendo";
        $txt .= "0101";
    } elseif (strlen($txt) == 6) {
        $mode = "nengetsu";
        $txt .= "01";
    }


    if ($mode == "nendo") {
        return format('Y', $txt);
    } elseif ($mode == "nengetsu") {
        return format('Y-m', $txt);
    } else {
        return format('Y-m-d', $txt);
    }
}

function input2data($txt)
{
    if ($txt . '' == '') return 0;

    if (strlen($txt) == 7) {
        //年月の時
        return date('Ym', strtotime($txt . "/01"));
    } elseif (strlen($txt) == 4) {
        //年
        return date('Y', strtotime($txt . "/01/01"));
    } else {
        return date('Ymd', strtotime($txt));
    }
}

function format($format, $ymd)
{
    $date = strtotime($ymd);

    if ($date . "" == "") {
        return "";
    }
    return date($format, $date);
}

function generateShortCode($item)
{
    // Thay thế tất cả ký tự đặc biệt bằng ký tự tương ứng
    $item = preg_replace('/[^a-zA-Z0-9\s]/', '', $item);

    // Tách chuỗi thành mảng các từ
    $words = explode(' ', $item);

    // Lấy chữ cái đầu tiên của từng từ
    $shortCode = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $shortCode .= strtoupper($word[0]); // Chữ cái đầu tiên, chuyển thành chữ hoa
        }
    }

    // Cắt chuỗi kết quả về tối đa 5 ký tự
    return substr($shortCode, 0, 5);
}
