<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('CryptoPay', 'cryptopay'); ?>
    </h1>
    <hr class="wp-header-end">
    <br>
    <div class="wrapper cp-product-list">
        <div class="box box-100">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 style="padding-left: 20px; box-sizing: border-box"><?php echo esc_html__('Network supports', 'cryptopay'); ?> - (<?php echo esc_html__('Only for premium', 'cryptopay'); ?>)</h2>
                </div>
                
                <div class="activity-block" style="padding: 20px; box-sizing: border-box; margin:0">
                    <ul class="cp-product-list">
                        <?php if (isset($products->networkSupports)) :
                            foreach ($products->networkSupports as $product) : 
                                $viewEcho('pages/home-page/product', compact('product'));
                            endforeach;
                        else :
                            echo esc_html__('No product found!');
                        endif; ?>
                    </ul>
                </div>

            </div>
        </div>
        <div class="box box-100">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 style="padding-left: 20px; box-sizing: border-box"><?php echo esc_html__('Converter API\'s', 'cryptopay'); ?> - (<?php echo esc_html__('Only for premium', 'cryptopay'); ?>)</h2>
                </div>
                
                <div class="activity-block" style="padding: 20px; box-sizing: border-box; margin:0">
                    <ul class="cp-product-list">
                        <?php if (isset($products->converterApis)) : 
                            foreach ($products->converterApis as $product) : 
                                $viewEcho('pages/home-page/product', compact('product'));
                            endforeach;
                        else :
                            echo esc_html__('No product found!');
                        endif; ?>
                    </ul>
                </div>
                
            </div>
        </div>
        <div class="box box-100">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 style="padding-left: 20px; box-sizing: border-box"><?php echo esc_html__('Add-ons', 'cryptopay'); ?> - (<?php echo esc_html__('Premium & Lite', 'cryptopay'); ?>)</h2>
                </div>
                
                <div class="activity-block" style="padding: 20px; box-sizing: border-box; margin:0">
                    <ul class="cp-product-list">
                        <?php if (isset($products->addOns)) : 
                            foreach ($products->addOns as $product) : 
                                $viewEcho('pages/home-page/product', compact('product'));
                            endforeach;
                        else :
                            echo esc_html__('No product found!');
                        endif; ?>
                    </ul>
                </div>
                
            </div>
        </div>
        <div class="box box-100">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 style="padding-left: 20px; box-sizing: border-box"><?php echo esc_html__('Integrations', 'cryptopay'); ?> - (<?php echo esc_html__('Premium & Lite', 'cryptopay'); ?>)</h2>
                </div>
                
                <div class="activity-block" style="padding: 20px; box-sizing: border-box; margin:0">
                    <ul class="cp-product-list">
                        <?php if (isset($products->integrations)) : 
                            foreach ($products->integrations as $product) : 
                                $viewEcho('pages/home-page/product', compact('product'));
                            endforeach;
                        else :
                            echo esc_html__('No integration found!');
                        endif; ?>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
</div>