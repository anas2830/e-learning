<?php 
	require_once("dbConfig.php");

	$curDateTime = new DateTime();
	$curDateTime->setTimezone(new DateTimeZone('UTC'));
	$curDate = $curDateTime->format('Y-m-d');
	mysqli_query($db, 'SET CHARACTER SET utf8');

	$data = array();
	$expire_installment = "SELECT edu_stu_payment_histories.*, edu_assign_batch_students.is_freez ";
	$expire_installment .= "FROM edu_stu_payment_histories ";
	$expire_installment .= "INNER JOIN edu_assign_batch_students ON edu_stu_payment_histories.assign_batch_std_id = edu_assign_batch_students.id ";
	$expire_installment .= "WHERE edu_stu_payment_histories.valid=1 and edu_stu_payment_histories.is_running=2 and edu_assign_batch_students.is_freez=0 order by id asc limit 10";
	$expire_installment = mysqli_query($db, $expire_installment);

	while($expire_installment_data = mysqli_fetch_object($expire_installment)) { $data[] = $expire_installment_data; }
	$expire_installment = (object) $data;

    if (!empty($expire_installment)) {
		foreach ($expire_installment as $key => $expire_data) {
            
            $start_date = $expire_data->start_date;
            $expire_date = date('Y-m-d', strtotime($start_date. ' + 10 days'));

            if($expire_date < $curDate){
                // echo $expire_data->assign_batch_std_id;
                // echo "<pre>";
                $updateQuery = "update edu_assign_batch_students set is_freez = 1, freez_reason = 'Installment Time Expire' where id = '".$expire_data->assign_batch_std_id."'";
                mysqli_query($db, $updateQuery);
                echo "success";
            }

		}
	}

?>