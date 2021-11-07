UPDATE edu_student_practice_time AS a INNER JOIN edu_assign_batch_classes AS b ON a.batch_id = b.batch_id SET a.assign_batch_class_id = b.id WHERE a.valid = 1 AND b.complete_status = 1 AND a.student_id = 162 AND date BETWEEN b.start_date AND b.end_date


-- In 100% Calculation

CREATE VIEW edu_student_performance_view AS
SELECT edu_assign_batch_students.student_id, 
edu_assign_batch_students.batch_id, 
edu_assign_batch_classes.id AS assign_batch_class_id, 
edu_assign_batch_students.course_id, 
edu_assign_batch_classes.class_id, 

IF( (SELECT edu_student_attendences.is_attend FROM edu_student_attendences WHERE edu_assign_batch_students.student_id = edu_student_attendences.student_id AND edu_assign_batch_students.batch_id = edu_student_attendences.batch_id AND edu_assign_batch_students.course_id = edu_student_attendences.course_id AND edu_student_attendences.class_id = edu_assign_batch_classes.id AND edu_student_attendences.valid = 1) = 1, 100, 0) AS attendence,

(SELECT edu_student_attendences.mark FROM edu_student_attendences WHERE edu_assign_batch_students.student_id = edu_student_attendences.student_id AND edu_assign_batch_students.batch_id = edu_student_attendences.batch_id AND edu_assign_batch_students.course_id = edu_student_attendences.course_id AND edu_student_attendences.class_id = edu_assign_batch_classes.id AND edu_student_attendences.valid = 1) AS class_mark,

ROUND((SELECT AVG(s.mark) FROM edu_assignment_submissions AS s WHERE s.assignment_id IN(SELECT id FROM edu_class_assignments AS ca WHERE ca.batch_id = edu_assign_batch_students.batch_id AND ca.course_id = edu_assign_batch_students.course_id AND ca.assign_batch_class_id = edu_assign_batch_classes.id) AND s.created_by = edu_assign_batch_students.student_id), 2) AS assignment,

(SELECT (ex.total_correct_answer * ex.per_question_mark) FROM edu_student_exams AS ex WHERE ex.exam_config_id = edu_exam_configs.id AND ex.created_by = edu_assign_batch_students.student_id AND ex.batch_id = edu_assign_batch_students.batch_id AND ex.assign_batch_class_id = edu_assign_batch_classes.id) AS quiz,

IF( ROUND((((SELECT SUM(w.watch_time) FROM edu_student_video_watch_infos AS w WHERE w.assign_batch_class_id = edu_assign_batch_classes.id AND w.batch_id = edu_assign_batch_students.batch_id AND w.student_id = edu_assign_batch_students.student_id) * 100) / (SELECT SUM(m.video_duration) FROM edu_course_class_materials AS m WHERE edu_assign_batch_classes.course_id = m.course_id AND edu_assign_batch_classes.class_id = m.class_id AND m.valid = 1)), 2) > 100, 100, ROUND((((SELECT SUM(w.watch_time) FROM edu_student_video_watch_infos AS w WHERE w.assign_batch_class_id = edu_assign_batch_classes.id AND w.batch_id = edu_assign_batch_students.batch_id AND w.student_id = edu_assign_batch_students.student_id) * 100) / (SELECT SUM(m.video_duration) FROM edu_course_class_materials AS m WHERE edu_assign_batch_classes.course_id = m.course_id AND edu_assign_batch_classes.class_id = m.class_id AND m.valid = 1)), 2) ) AS video_watch_time, 

IF( ( ( (SELECT SUM(p.total_time) FROM edu_student_practice_time AS p WHERE p.batch_id = edu_assign_batch_students.batch_id AND p.student_id = edu_assign_batch_students.student_id AND p.date BETWEEN edu_assign_batch_classes.start_date AND edu_assign_batch_classes.end_date GROUP BY edu_assign_batch_classes.start_date, edu_assign_batch_classes.end_date)*100) / (SELECT COUNT(*)*14400 FROM edu_student_practice_time AS bp WHERE bp.batch_id = edu_assign_batch_students.batch_id AND bp.student_id = edu_assign_batch_students.student_id AND bp.date BETWEEN edu_assign_batch_classes.start_date AND edu_assign_batch_classes.end_date)) > 100, 100, ROUND( ( ( (SELECT SUM(p.total_time) FROM edu_student_practice_time AS p WHERE p.batch_id = edu_assign_batch_students.batch_id AND p.student_id = edu_assign_batch_students.student_id AND p.date BETWEEN edu_assign_batch_classes.start_date AND edu_assign_batch_classes.end_date GROUP BY edu_assign_batch_classes.start_date, edu_assign_batch_classes.end_date)*100) / (SELECT COUNT(*)*14400 FROM edu_student_practice_time AS bp WHERE bp.batch_id = edu_assign_batch_students.batch_id AND bp.student_id = edu_assign_batch_students.student_id AND bp.date BETWEEN edu_assign_batch_classes.start_date AND edu_assign_batch_classes.end_date) ), 2) ) AS practice_time, 

edu_assign_batch_classes.start_date, edu_assign_batch_classes.end_date

FROM edu_assign_batch_students

INNER JOIN edu_assign_batch_classes ON edu_assign_batch_classes.batch_id = edu_assign_batch_students.batch_id AND edu_assign_batch_classes.course_id = edu_assign_batch_students.course_id

LEFT JOIN edu_exam_configs ON edu_exam_configs.assign_batch_class_id = edu_assign_batch_classes.id AND edu_exam_configs.batch_id = edu_assign_batch_classes.batch_id AND edu_exam_configs.valid = 1

where edu_assign_batch_students.valid = 1 AND edu_assign_batch_classes.valid =1 AND edu_assign_batch_classes.complete_status = 1;
