<!-- Step 1 -->
<?php
    $pack_id = isset($_GET['pack_id'] ) ? $_GET['pack_id'] : 0;
    global $user_ID, $ae_post_factory;
    $ae_pack = $ae_post_factory->get('bid_plan');
    $packs = $ae_pack->fetch('bid_plan');

?>
<div id="fre-post-project-1 step-plan" class="fre-post-project-step step-wrapper step-plan active">
    <div class="fre-post-project-box">
        <div class="step-post-package">
            <h2><?php _e('Choose your most appropriate package', ET_DOMAIN)?></h2>
            <?php do_action('html_select_currency');?>
            <ul class="fre-post-package">
                <?php
                foreach ($packs as $key => $package) {
                    $checked        = '';
                    $sku            = $package->sku;
                    $pack_des       = '';
                    $number_of_post =   $package->et_number_posts;

                    $pack_des = fre_pack_bid_description($package);
                    if ( $pack_id && $package->ID == $pack_id){
                        $checked = 'checked';
                    }
                ?>
                    <li data-sku="<?php echo trim($package->sku);?>"
                        data-id="<?php echo $package->ID ?>"
                        data-package-type="<?php echo $package->post_type; ?>"
                        data-price="<?php echo $package->et_price; ?>"
                        data-title="<?php echo $package->post_title ;?>"
                        data-description="<?php echo $pack_des;?>">
                        <label class="fre-radio" for="package-<?php echo $package->ID?>">
                            <input id="package-<?php echo $package->ID?>" name="post-package" type="radio" <?php echo $checked;?>>
                            <span><?php echo $package->post_title ; ?></span>
                        </label>
                        <span class="disc package_description"><?php echo $pack_des;?></span>
                    </li>
                <?php } ?>
            </ul>
            <?php echo '<script type="data/json" id="package_plans">'.json_encode($packs).'</script>'; ?>
            <div class="fre-select-package-btn">
                <!-- <a class="fre-btn" href="">Select Package</a> -->
                <input class="fre-btn fre-post-project-next-btn select-plan primary-bg-color" type="button" value="<?php _e('Next Step', ET_DOMAIN);?>">
            </div>
        </div>
    </div>
</div>
