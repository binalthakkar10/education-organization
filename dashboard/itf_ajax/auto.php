<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require('../../itfconfig.php');
$objStudent = new Student();
$classId = $_GET['classId'];
$type = $_GET['type'];
$grade_id = $_GET['grade_id'];
switch ($type) {

    default:
        echo '
            <option>Select an Option...</option>
           ';
        break;
    case 'course':
        $courseName = $objStudent->CourseNameByClass($classId);
        foreach ($courseName as $item) {
            $idtest = '';
            $classId = $item['id'];
            echo '<option value="' . $item['id'] . '"  >' . $item['name'] . ' </option>';
        }
        break;
    case 'loc':
        $locationName = $objStudent->LocationNameByClass($classId);
        foreach ($locationName as $item) {
            $idtest = '';
            $classId = $item['id'];
            echo '<option value="' . $item['id'] . '"  >' . ucfirst($item['code'] . ' - ' . $item['name']) . ' </option>';
        }
        break;
    case 'grade':
        $classDetails = $objStudent->GradeByClass($classId);
        // '<pre>';print_r($classDetails);
        $startGrade = $classDetails['start_grade'];
        $endGrade = $classDetails['end_grade'];

        $gradeObj = new Grade();
        $gradesList = $gradeObj->showClassGrade($startGrade, $endGrade);

        foreach ($gradesList as $values) {
            //echo '<option value="' . $values["id"] . '" if($grade_id==$values[id]){echo selected="selected"}>' . $values["grade_desc"] . '</option>';
            ?>
            <option value="<?php echo $values['id']; ?>"   <?php if ($grade_id == $values['id']) { ?>selected="selected"<?php } ?>><?php echo $values["grade_desc"]; ?> </option>
            <?php
        }
        break;
}
?>
