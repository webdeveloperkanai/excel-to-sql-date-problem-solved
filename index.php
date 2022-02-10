<?php
$con = mysqli_connect('localhost', 'root', '', 'test');
if (isset($_POST['submit'])) {
	$file = $_FILES['doc']['tmp_name'];

	$ext = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
	if ($ext == 'xlsx') {
		require('PHPExcel/PHPExcel.php');
		require('PHPExcel/PHPExcel/IOFactory.php');


		$obj = PHPExcel_IOFactory::load($file);
		foreach ($obj->getWorksheetIterator() as $sheet) {
			$getHighestRow = $sheet->getHighestRow();
			for ($i = 1; $i <= $getHighestRow; $i++) {
				$name = $sheet->getCellByColumnAndRow(0, $i + 1)->getValue();
				$email = $sheet->getCellByColumnAndRow(1, $i + 1)->getValue();
				$phone = $sheet->getCellByColumnAndRow(2, $i + 1)->getValue();

				date_default_timezone_set("Asia/Kolkata");
				$dDate = date_create_from_format('Y-m-d', '1900-01-01');
				$dDate = date_add($dDate, date_interval_create_from_date_string(($phone - 2) . ' days'));
				$dos = $dDate->format('d-M-Y');
  
				if ($name != '') {
					mysqli_query($con, "insert into user(name,email, phone) values('$name','$email', '$dos')");
				}
			}
		}
	} else {
		echo "Invalid file format";
	}
}
?>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="doc" />
	<input type="submit" name="submit" />
</form>