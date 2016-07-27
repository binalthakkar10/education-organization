<?php
if (!empty($_GET["name"])) {
    $names = explode('_____', $_REQUEST["name"]);
    $studentnames = array();
    $studentnames['first_name'] = $names['0'];
    $studentnames['last_name'] = $names['1'];
    $objDebate = new Debate();
    $studentDetails = $objDebate->CheckDebatRanking($studentnames);

} else {
    echo 'No Records exit.';
    exit;
}
?>
<div class="list_classes" id="list_debt_students">

    <table cellpadding="0" cellspacing="0" border="1">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Student Name</th>
                <th>Date of Birth</th>
                <th>Score</th>
                <th>Attend Date</th>

            </tr>
        </thead>
        <tbody>
            <?php
            echo count($studentDetails);
            if (count($studentDetails) > 0) {
                foreach ($studentDetails as $values) {
                    ?>
                    <tr>
                        <td><?php echo $values['first_name'] ?></td>
                        <td><?php echo $values['last_name'] ?></td>
                         <td><?php echo $values['dob'] ?></td>
                        <td><?php echo $values['debt_score'] ?></td>
                        <td><?php echo $values['attend_date'] ?></td>
                    </tr>
                <?php
                }
            } else {
             echo '<tr><td colspan="5">No Record Exits</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>