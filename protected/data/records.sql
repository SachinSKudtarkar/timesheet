 select CONCAT(first_name,' ',last_name) as name,t.day,pm.project_name,sb.sub_project_name,
 st.sub_task_name ,t.comment,t.hours from tbl_day_comment as t 
 INNER JOIN tbl_project_management pm ON (t.pid = pm.pid) 
 INNER JOIN tbl_employee emp ON (emp.emp_id = t.emp_id) 
 LEFT join tbl_sub_project sb ON (sb.spid=t.spid )
 left Join tbl_sub_task as st on (st.stask_id = t.stask_id)  
 where emp.emp_id = 3616 order by id DESC limit 1;


