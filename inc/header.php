<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/../lib/Session.php');
Session::init();
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/Format.php');

spl_autoload_register(function($class) {
     include_once "classes/" . $class . ".php";   
});

$db  = new Database();
$fm  = new Format();
$ct  = new Cart();
$pd  = new Product();
$cat = new Category();
$cmr = new Customer();
#fesf
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>
<!DOCTYPE HTML>
<head>
    <title>Store Website</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/menu.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="jquery-bar-rating-master/dist/themes/fontawesome-stars.css" rel="stylesheet" type="text/css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="js/jquery-3.3.1.min.js"></script>

    <script src="js/jquerymain.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
    <script type="text/javascript" src="js/nav.js"></script>
    <script type="text/javascript" src="js/move-top.js"></script>
    <script type="text/javascript" src="js/easing.js"></script> 
    <script type="text/javascript" src="js/nav-hover.js"></script>
    <script src="jquery-bar-rating-master/dist/jquery.barrating.min.js" type="text/javascript"></script>

    <link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Doppio+One' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('#dc_mega-menu-orange').dcMegaMenu({rowItems: '4', speed: 'fast', effect: 'fade'});
        });
    </script>
    <script type="text/javascript">
    function DisableAutoComp() {
        var username = document.getElementById("form");
        if ('autocomplete' in username) {
            username.autocomplete = "off";
        }
        else {
            // Firefox
            username.setAttribute("autocomplete", "off");
        }
    }
   </script>
</head>
<body onload="DisableAutoComp();">
    <div class="wrap">
        <div class="header_top">
            <div class="logo">
                <img src="images/logo_e.png" />
            </div>
            <div class="header_top_right">
                <div class="search_box" style="">
                    <form class="form" action="search.php" method="post" enctype="multipart/form-data">
                        <input type="text"   name="product_search" placeholder="search for products">
                        <input type="submit" class="btn fas fa-search" value="&#x1F50E;&#xFE0E;">
                    </form>
                </div>

                <div class = "container">

                <div class = "row">
                <div class = "col-lg">
                <div class="shopping_cart">
                    <div class="cart">
                        <a href="cart.php" title="View my shopping cart" rel="nofollow">
                            <span class="cart_title">Cart</span>
                            <span class="no_product">
                                <?php
                                $getdata = $ct->checkCartTable();
                                if($getdata){
                                    $sum = SESSION::get("sum");
                                    $qty = SESSION::get("qty");
                                    
                                    echo "RM" . $sum . " | " . "Qty:" . $qty;
                                }else{    
                                    echo 'empty';
                                }?>
                            </span>
                        </a>
                    </div>
                </div>
                </div>

                  <div class="col-sm">
                <?php 
                /// cart item delete,when session destroy
                  if(isset($_GET['cId'])){
                   $customerid = Session::get("customerId");

                   $deldata = $ct->deleteCustomerCart();
                   $delCompare = $pd->deleteCompareProduct($customerid);
                   SESSION::destroy();                    
                  }?>                
               
                    <?php
                        $login = Session::get("customerlogin");
                        if($login==FALSE){?>
                    <a href="login.php" class="btn btn-info font-weight-bold" style="padding: 7px; margin:auto; background-color: #FFD686; color:#000; border-color:#FFD686; outline-color:#FFD686;">Login</a>
                           
                      <?php }else{                            
                          ?>                      
                    <a href="?cId=<?php Session::get("customerId");?>" class="btn btn-info font-weight-bold" style="padding: 7px; margin:auto; background-color: #FFD686; color:#000; border-color:#FFD686; outline-color:#FFD686;">Logout</a>
                      <?php } ?>
                  </div>
                    
                    </div>
                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

        <nav class="navbar navbar-custom navbar-expand-sm navbar-light" style="background-color: #FFD686;" >
                 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
                 </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">              
                    <a class="nav-item nav-link " href="index.php" style="color:#000; font-weight:600;">Home</a>               
                   <?php
                    $checkCart = $ct->checkCartTable();
                    //as long as user dont select product,the cart & payment option remain invisible
                    if($checkCart){?>                
                    <a class="nav-item nav-link " href="cart.php" style="color:#000; font-weight:600;">Cart</a>  
                    <a class="nav-item nav-link " href="payment.php" style="color:#000; font-weight:600;">Payment</a> 
                    <?php }?>
                    <?php
                    $customerid = Session::get("customerId");
                    // if user is logged in and buy products then order will visible using session
                    $checkOrder = $ct->checkOrder($customerid);
                    if($checkOrder){?>                  
                    <a class="nav-item nav-link " href="orderdetails.php" style="color:#000; font-weight:600;">Order</a> 
                    <?php }?>
                    
                    <?php
                    $login = Session::get("customerId");
                    // if user is logged in,then profile will visible using session
                    if($login==TRUE){?>
                   <a class="nav-item nav-link " href="profile.php" style="color:#000; font-weight:600;">Profile</a>        
                   <?php } ?>
                   
                    
                    <?php 
                    $customerid = Session::get("customerId");
                    $checkWlist = $pd->getWlistData($customerid);
                    // product add in cart using user session id 
                    if($checkWlist){ ?>       
                    <a class="nav-item nav-link " href="wishlist.php" style="color:#000; font-weight:600;">Wishlist</a> 
                    <?php }?>
                    
                    <a class="nav-item nav-link " href="contact.php" style="color:#000; font-weight:600;">Contact</a>
            </div> 
        </nav>