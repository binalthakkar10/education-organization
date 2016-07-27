<?php
$validate_fields = array('main_category'=>'','category'=>'','sub_category'=>'','product_code'=>'','product_name'=>'','main_image'=>'','specification'=>'','image'=>'','description'=>'');

if(isset($_POST['id']))
{

	if(!empty($_FILES['file']['name']))
	{
        $filename = $obj->excelUpload();

        $excel = new ITFExcelReader();
        $excel->read(PUBLICFILE."/products/".$filename);
        $allres = $excel->GetDataInfo($excel);
       // echo "<pre>"; print_r($allres); die;
        if($allres){
            $datas = array();
            $success = 0;
            $error = 0;
            $update = 0;
            $catdata = array();
            $catAdded = 0;


            //echo "<pre>"; print_r($allres); die;
            $catobj = new Category();
            foreach($allres as $result){

                $datas = array('code'=>$result['product_code'],'name'=>$result['product_name'],'main_image'=>$result['main_image'],'image'=>$result['main_image'],'logn_desc'=>$result['description'],'specification'=>$result['specification']);

                $valid = array_diff_key($result,$validate_fields);
                $invalidFields = implode(", ", array_keys($valid));

                if(!empty($valid)){
                    flash("Error: Field name not matched of ".$invalidFields." ",'2');
                    redirectUrl("itfmain.php?itfpage=".$currentpagenames."&actions=import");

                }

                $finalcategory = array();

                if(!empty($result['main_category']) && !empty($result['category']) && !empty($result['sub_category'])){

                    $maincat = $catobj->CheckCategoryByName($result['main_category']);
                    if(isset($maincat["id"])){
                         $cat = $catobj->CheckCategoryByName($result['category'],$maincat['id']);
                        if(isset($cat['id'])){
                            $subcat = $catobj->CheckCategoryByName($result['sub_category'],$cat['id']);
                            if(isset($subcat['id'])){

                                $datas['category_id'] = $subcat['id'];

                            } else {
                                $finalcategory[] = array("notfoundSubSub"=>$result ,"PreData"=>$cat);
                                $subcatdata = array('catname'=>$result['sub_category'],'parent'=>$cat['id']);
                                $res3 = $catobj->admin_addCategory($subcatdata);
                                $datas['category_id'] = $res3;
                            }

                        } else {
                            $finalcategory[] = array("notfoundSub"=>$result,"PreData"=>$maincat);
                            $catdata = array('catname'=>$result['category'],'parent'=>$maincat['id']);
                            $res2 = $catobj->admin_addCategory($catdata);
                            $subcatdata = array('catname'=>$result['sub_category'],'parent'=>$res2);
                            $res3 = $catobj->admin_addCategory($subcatdata);
                            $datas['category_id'] = $res3;
                        }

                    } else {

                        $finalcategory[] = array("notfoundmain"=>$result,"PreData"=>"No data");

                        $maincatdata = array('catname'=>$result['main_category'],'parent'=>0);
                        $res = $catobj->admin_addCategory($maincatdata);
                        $catdata = array('catname'=>$result['category'],'parent'=>$res);
                        $res2 = $catobj->admin_addCategory($catdata);
                        $subcatdata = array('catname'=>$result['sub_category'],'parent'=>$res2);
                        $res3 = $catobj->admin_addCategory($subcatdata);
                        $datas['category_id'] = $res3;
                    }


                    //echo"<pre>"; print_r($datas['category_id']); die;

                    if(!empty($result['product_name'])){

                        $prodata = $obj->CheckProductByName($result['product_name']);

                        if(!empty($prodata)){
                            $datas['id'] = $prodata['id'];
                            $obj->admin_update($datas);
                            $update += 1;

                        }  else {
                            $obj->admin_add($datas);
                            $success += 1;
                        }
                    } else {
                        $error += 1;
                    }

                } else {

                    flash("Error: Please check mandatory fields ",'2');
                    redirectUrl("itfmain.php?itfpage=".$currentpagenames."&actions=import");
                }

            }

        }
        flash("Success: <span class='red'>".$success."</span> new products are inserted and <span class='red'>".$update."</span> products are updated and <span class='red'>".$error."</span> products are not inserted !");
        redirectUrl("itfmain.php?itfpage=".$currentpagenames);

	}
}
?>
<script type="text/javascript">

$(document).ready(function() {

    var Validator = jQuery('#itffrminput').validate({
        rules: {
            file: {required:true, accept:"xls"}

        },
        messages: {


        }
    });
});
</script>
<div class="full_w">
	<!-- Page Heading -->
    <div class="h_title"><?php echo "Import". $pagetitle;?></div>
    <!-- Page Heading -->
					
<form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

    <div class="element">
        <label><span class="blue">Notice: File header must be named as <?php echo implode(", ",array_keys($validate_fields)); ?> </span></label>


    </div>

    <div class="element">
        <label>Select Excel File<span class="red">(required)</span> </label>
        <input class="text" name="file" type="file"  id="file" size="35" />
    </div>


<!-- Form Buttons -->
    <div class="entry">
        <button type="submit">Submit</button>
        <button type="button" onclick="history.back()">Back</button>
    </div>
<!-- End Form Buttons -->
</form>
    <!-- End Form -->
</div>