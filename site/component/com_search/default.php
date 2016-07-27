<?php
    if(isset($_POST["txt_search"])) {
        $_SESSION["SEARCHDATA"]= $_POST;
    }else{
        $_POST = $_SESSION["SEARCHDATA"];
    }

    if(isset($_POST["txt_search"]))
    {

        if(!empty($_POST["txt_search"]) && $_POST["txt_search"] != 'Search here'){

            $perpage = isset($stieinfo['front_paging_size'])?$stieinfo['front_paging_size']:'14' ;
            $urlpath = CreateLink(array($currentpage))."&";
            $InfoData1 = $objProduct->search($_POST["txt_search"]);
            $count = count($InfoData1);
            $pagingobj = new ITFPagination($urlpath,$perpage);
            $searchproducts = $pagingobj->setPaginateData($InfoData1);

        } else {
            flashMsg("Please enter text for search product","2");
            redirectUrl(CreateLink(array("index")));
        }
    } else {

        redirectUrl(CreateLink(array("index")));
    }


?>

<div class="main_mat">
    <p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="">Search Results</a></p>
</div>

<div class="product_rgt search_result">

    <?php if(count($searchproducts) > 0 ) { ?>
    <p><?php echo $count; ?> records found matching with "<span style="color: #0000AF;"><?php echo $_POST["txt_search"]; ?></span>" . </p>
    <div class="product_rgt_top">
        <?php echo $pagingobj->Pages(); ?>
    </div>
    <div class="pro_cate">
        <?php foreach ($searchproducts as $product){  ?>
            <div class="pro_cate_cont"> <a href="<?php echo CreateLink(array('product','itemid'=>'detail&id='.$product['id'])); ?>">
                    <?php if(file_exists(PUBLICFILE."/products/".$product['main_image'])) { ?>
                    <img src="<?php echo PUBLICPATH."/products/".$product['main_image']; ?>" width="120px" height="122px" alt=""></a>
                <?php } else { ?>
                    <img src="<?php echo PUBLICPATH."/products/noImageProduct.jpg"; ?>" width="120px" height="122px" alt=""></a>
                <?php } ?>
                <p><a href="<?php echo CreateLink(array('product','itemid'=>'detail&id='.$product['id'])); ?>"><?php echo $product['name']; ?></a></p>
            </div>
        <?php } ?>

        <div class="clear"></div>
    </div>

    <?php } else { ?>
        <p style="text-align: center; margin-top: 50px;">No record found matching with "<span style="color: #0000AF;"><?php echo $_POST["txt_search"]; ?></span>" !</p>
    <?php } ?>
</div>