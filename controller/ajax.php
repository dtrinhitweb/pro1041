<?php
session_start();
ob_start();

include_once '../model/m_pdo.php';
include_once '../config.php';

// xử lí data 
switch ($_GET['act']) {
    case 'ajax_search':
        include_once '../model/m_product.php';
        //lay du lieu
        // if(isset($_POST['keyword'])) {
        $keyword = $_POST['keyword'];
        $show_search = search_live_product($keyword);
        // echo json_encode($show_search);
        if ($show_search) {
            foreach ($show_search as $value):
                extract($value);
                echo '<a href="login" class="col-md-4 img-focus">
                    <img src="' . $base_url . 'upload/products/' . $AnhSP . '"
                        width="50" height="50" alt="' . $TenSP . '">
                </a>
                <div class="col-md-8 mt-2 content-focus">' . $TenSP . ' </div>';
            endforeach;
        } else {
            echo '<span class="not_product">Sản phẩm không tồn tại</span>';
        }
        break;
    case 'ajax_cart_quantity':
        include_once '../model/m_cart.php';
        $SoLuongSP = $_POST['quantity'];
        $MaSP = $_POST['MaSP'];
        $Quantity = quantity_cart_max($MaSP);

        if ($SoLuongSP > $Quantity['SoLuong']) {
            // lưu session vào là disiable true
            echo '
            <div class="toast toast--error">
                <div class="toast__icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="toast__body">
                    <h3 class="toast__title">Thất bại</h3>
                    <p class="toast__msg">Rất tiếc, bạn chỉ có thể mua tối đa ' . $Quantity['SoLuong'] . ' sản phẩm.</p>
                </div>
    
                <div class="toast__close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            ';
        } else {
            // lưu session vào là disiable flase
            echo '
            <div class="toast toast--success">
                <div class="toast__icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="toast__body">
                    <h3 class="toast__title">Thành công</h3>
                    <p class="toast__msg">Cập nhật số lượng thành công</p>
                </div>
    
                <div class="toast__close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            ';
            update_quantity_by_cart($SoLuongSP, $MaSP);
        }
        break;
    case 'ajax_cart_coupon':
        include_once '../model/m_cart.php';
        // Kiểm tra btn_coupon có tồn tại và có được click vào hay không
        if (count_cart($_SESSION['user']['MaTK']) >= 1) {
            if (isset($_POST['btn_coupon']) && $_POST['btn_coupon']) {
                // lấy giá trị input của couponcode
                $couponcode = $_POST['couponcode'];
                // Nếu counponcode trống => Bắt người dùng nhập mã
                if (empty($couponcode)) {
                    echo "error_coupon_null";
                } else {
                    // Nếu có nhập mã code thì select xem mã đó có đúng không
                    $has_coupon = has_coupon_code($couponcode);
                    // Nếu mã đó có tồn tại thì
                    if ($has_coupon) {
                        // Kiểm tra mã đó còn hạn dùng hay không
                        $counpon_date = $has_coupon['NgayKetThuc'];
                        // biến ngày giờ thành số giây
                        $counpon_date_timestamp = strtotime($counpon_date);
                        if (time() > $counpon_date_timestamp) {
                            echo "Mã giảm giá không còn hạn sử dụng";
                        } else {
                            // Kiểm tra mã đó còn số lượng dùng hay không
                            if ($has_coupon['SoLuong'] > 0) {
                                update_quantity_coupon($has_coupon['CodeKM']);
                                echo '<input type="hidden" class="coupon_value" value="' . $has_coupon['GiaKM'] . '">';
                                echo "Đơn hàng của bạn được giảm " . number_format($has_coupon['GiaKM'], 0, ',', '.') . ' VNĐ';
                            } else {
                                echo "Mã giảm giá đã hết";
                            }
                        }
                    } else {
                        echo "error_coupon_false";
                    }
                }
            }
        } else {
            echo "Vui lòng mua hàng để dùng chức năng này";
        }
        break;

    case 'addwish':
        include_once '../model/m_wishlist.php';
        $MaTK = $_SESSION['user']['MaTK'];
        $MaSP = $_POST['MaSP'];
        add_to_wishlist($MaTK, $MaSP);
        break;

    case 'select_option':
        include_once '../model/m_product.php';

        if($_POST['search_key'] === 'popularity') {
            // hiển thị phổ biến
            $product_shop = product_search_option_by_poplarity($page=1);

             foreach ($product_shop as $product): ?>
                
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="product-default inner-quickview inner-icon">
                            <figure>
                                <a href="<?=$base_url?>product/detail/<?=$product['MaSP']?>">
                                    <img src="<?=$base_url?>upload/products/<?= $product['AnhSP'];?>"
                                        alt="product" style="width: 207px; height: 220px;">
                                </a>
                                <div class="btn-icon-group">
                                    <a href="<?= $base_url ?>product/detail/<?= $product['MaSP'];?>"
                                        class="btn-icon btn-add-cart fa-solid fa-cart-shopping"></a>
                                </div>
                                <?php if($product['SoLuong']==0):?>
                                    <a href="<?= $base_url?>product/detail/<?= $product['MaSP'];?>" class="btn-quickview">Xem chi tiết</a>
                                    <div class="product-countdown-container">
                                        <span class="product-countdown-title">Đã bán hết</span>
                                    </div>
                                <?php else:?>
                                    <a href="<?= $base_url?>product/detail/<?= $product['MaSP'];?>" class="btn-quickview">Xem chi tiết</a>
                                <?php endif;?>
                            </figure>
                            <div class="product-details">
                                <div class="category-wrap">
                                    <div class="category-list">
                                        <a href="<?=$base_url?>product/detail/<?=$product['MaSP']?>" class="product-category"><?= $product['TenDM'];?></a>
                                    </div>
                                    <a href="<?=$base_url?>page/wishlist" 
                                    <?php if(isset($_SESSION['user'])){
                                        $MaTK=$_SESSION['user']['MaTK'];
                                        $CheckWish=check_wishByProductAndUser($MaTK,$product['MaSP']);
                                        if($CheckWish!=""){
                                            echo 'title="Đến trang yêu thích" class="btn-icon-wish added-wishlist" ';
                                        }else{
                                            echo 'onclick="ThemSPYT('.$product['MaSP'].')" title="Yêu thích sản phẩm" class="btn-icon-wish"';
                                        }
                                    } ?>><i class="fa-solid fa-heart"></i></a>
                                </div>
                                <h3 class="product-title">
                                    <a href="<?=$base_url?>product/detail/<?=$product['MaSP']?>"><?= $product['TenSP'];?></a>
                                </h3>
                                <?php 
                                    $product['rating']=ratings_trungbinh($product['MaSP']);
                                    if($product['rating']['SoSao']!=""&&$product['rating']['SoBinhLuan']>0){
                                        $product['trungbinh_rating']=ceil(($product['rating']['SoSao']*10)/($product['rating']['SoBinhLuan']/2));
                                    }else{
                                        $product['trungbinh_rating']=0;
                                    }
                                ?>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:<?=$product['trungbinh_rating']?>%"></span>
                                        
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                </div>
                                <div class="price-box">
                                    <span class="product-price"><?=number_format($product['Gia'],0,",",".")?>đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                   
            <?php endforeach;

        } else if($_POST['search_key'] === 'rating') {
            echo "Đánh giá tốt";
        } else if($_POST['search_key'] == 'date') {
            echo "Mới nhất";
        } else if($_POST['search_key'] == 'price') {
            echo "Giá thấp đến cao";
        } else if($_POST['search_key'] == 'price-desc') {
            echo 'Giá cao đến thấp';
        }

        // echo $_POST['search_key'];
        // print_r(product_search_option($_POST['search_key'], $page=1));
        break;

    default:
        break;
}

?>