CREATE VIEW view_student_performance_with_progress AS
SELECT student_id, batch_id, course_id,
ROUND(((AVG(`attendence`)*(SELECT attendence FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_attendence, 
ROUND(((AVG(`class_mark`)*(SELECT class_mark FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_class_mark, 
ROUND(((AVG(`assignment`)*(SELECT assignment FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_assignment, 
ROUND(((AVG(`quiz`)*(SELECT quiz FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_quiz_mark, 
ROUND(((AVG(`video_watch_time`)*(SELECT video_watch_time FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_video_watch_time, 
ROUND(((AVG(`practice_time`)*(SELECT practice_time FROM `edu_student_progress` WHERE `valid`=1))/100), 2) AS gained_practice_time
FROM `edu_student_performance_view` GROUP BY student_id, batch_id;