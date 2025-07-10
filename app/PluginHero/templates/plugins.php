<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('BeycanPress Plugins'); ?>
    </h1>
    <hr class="wp-header-end">
    <br>
    <div class="wrapper">
        <div class="box box-33">
            <div class="postbox">
                <div class="activity-block" style="padding: 20px; box-sizing: border-box; margin:0">
                    <ul class="product-list">
                        <?php if (isset($plugins)) :
                            foreach ($plugins as $product) : ?>
                                <li>
                                    <a href="<?php echo isset($product->landing_page) ? esc_url($product->landing_page) : esc_url($product->permalink); ?>" target="_blank">
                                        <img src="<?php echo esc_url($product->image); ?>" alt="<?php echo esc_attr($product->title) ?>">
                                        <span><?php echo esc_html($product->title) ?></span>
                                    </a>
                                </li>
                            <?php endforeach;
                        else :
                            echo esc_html__('No product found!', 'cryptopay');
                        endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
add_action('admin_footer', function (): void {
    echo '<style>
        .product-list {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }
    
        .product-list li {
            width: 20%;
            padding: 10px;
            box-sizing: border-box;
        }
    
        .product-list li a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #333;
        }
    
        .product-list li a img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    
        @media screen and (max-width: 768px) {
            .product-list li {
                width: 50%;
            }
            
        }
    
        @media screen and (max-width: 400px) {
            .product-list li {
                width: 100%;
            }
            
        }</style>';
});
