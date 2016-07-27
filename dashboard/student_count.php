<?php
require('../itfconfig.php');
$obj = new Class1();
$InfoData = $obj->getStudentCountDetail($_REQUEST['id']);
error_reporting(0);
?>

<table border='1' cellpadding='5' width='100%'>
    
    <?php
    if (count($InfoData) > 0) {
        for ($i = 0; $i < count($InfoData); $i++) {
            ?>

    <tr><td><?php echo $InfoData[$i]['status_name']; ?></td>&nbsp;&nbsp;&nbsp;&nbsp; <td><?php echo $InfoData[$i]['student_total']; ?></td></tr>              
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="10" class="align-center">No Record Available !</td>
        </tr>
    <?php } ?>

</table>

<!-- End Box -->
