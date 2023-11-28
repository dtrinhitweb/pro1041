<div class="container checkout-container">
    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
            <li class="breadcrumb-item" aria-current="page">Giỏ hàng</li>
            <li class="breadcrumb-item active" aria-current="page">Xác nhận thanh toán</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-7">
            <ul class="checkout-steps">
                <li>
                    <h2 class="step-title">Chi tiết đơn hàng</h2>
                    
                    <form action="" id="checkout-form">
                        <div class="form-group">
                            <label>Họ và tên
                                <abbr class="required" title="required">*</abbr></label>
                            <input type="text" class="form-control" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['HoTen'] : "" ?>" required />
                        </div>

                        <div class="form-group mb-1 pb-2">
                            <label>Địa chỉ nhận hàng
                                <abbr class="required" title="required">*</abbr></label>
                            <input type="text" class="form-control" placeholder="Địa chỉ nhận hàng" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['DiaChi'] : "" ?>" required />
                        </div>

                        <div class="form-group">
                            <label>Điện thoại <abbr class="required" title="required">*</abbr></label>
                            <input type="tel" class="form-control" required value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['SoDienThoai'] : "" ?>">
                        </div>

                        <div class="form-group">
                            <label>Địa chỉ email
                                <abbr class="required" title="required">*</abbr></label>
                            <input type="email" class="form-control" required value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['Email'] : "" ?>" />
                        </div>

                        <div class="form-group">
                            <label class="order-comments">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea class="form-control"
                                placeholder="Ghi chú về đơn hàng của bạn, ví dụ như ghi chú đặc biệt khi giao hàng."></textarea>
                        </div>
                    </form>
                </li>
            </ul>
        </div>

        <div class="col-lg-5">
            <div class="order-summary">
                <h3>Đơn hàng đã đặt</h3>

                <table class="table table-mini-cart">
                    <thead>
                        <tr>
                            <th colspan="2">Sản phẩm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($show_cart_for_user as $value):extract($value)?>
                            <tr>
                                <td class="product-col">
                                    <h5 class="product-title text-title">
                                        Tên sản phẩm:
                                        <?= $TenSP ?>
                                    </h5>
                                    <p class="product-qty" style="font-weight: 400">Số lượng:
                                        <?= $SoLuongSP ?>
                                        
                                    </p>
                                </td>

                                <td class="price-col">
                                    <span>
                                        <?= number_format($total, 0, ',', '.') ?> VNĐ
                                    </span>
                                </td>
                            </tr>
                            <?php $_SESSION['MaHD'] = $MaHD ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr class="cart-subtotal">
                            <td>
                                <h4>Tổng tiền</h4>
                            </td>
                            <td class="price-col">
                                <span class="total_checkout"></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <form action="<?=$base_url?>product/update_status_cart" method="post">
                    <input type="hidden" value="<?=$_SESSION['MaHD']?>" name="MaHD">
                    <input type="submit" name="btn_update_status_cart" class="btn btn-primary w-100 " value="ĐẶT HÀNG">
                </form>
            </div>
        </div>
    </div>
</div>

