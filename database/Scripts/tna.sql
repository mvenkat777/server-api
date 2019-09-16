-- Function: get_itemstatus(text)

-- DROP FUNCTION get_itemstatus(text);

CREATE OR REPLACE FUNCTION get_itemstatus(tnaitem_id text)
  RETURNS character varying AS
$BODY$
declare
	item_status character varying(100);
	complete boolean;
	dispatch boolean;
begin
		select is_completed, is_dispatched
		from tna_items
		into complete,dispatch
		where id = tnaitem_id;

		if(complete is true and dispatch is true) then
			item_status:='closed';
		elsif(complete is false and dispatch is true) then
			item_status:='active';
		elsif(complete is false and dispatch is false) then
			item_status:='pending';
		else
			item_status:='na';
		end if;
		
		return item_status;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_itemstatus(text)
  OWNER TO postgres;

-- Function: get_tnahealth(integer)

-- DROP FUNCTION get_tnahealth(integer);

CREATE OR REPLACE FUNCTION get_tnahealth(health_id integer)
  RETURNS character varying AS
$BODY$
declare
	tnahealth character varying(100);
begin
		select health
		from tna_health
		into tnahealth
		where id = health_id;

		return tnahealth;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_tnahealth(integer)
  OWNER TO postgres;

-- Function: get_tnaitemvisibility(text)

-- DROP FUNCTION get_tnaitemvisibility(text);

CREATE OR REPLACE FUNCTION get_tnaitemvisibility(tnaitem_id text)
  RETURNS character varying AS
$BODY$
declare
	visibility_id int;
	tnaitem_visibility character varying(100);
begin
		select tna_item_visibility_id
		from tna_item_visibility_tna_item
		into visibility_id
		where tna_item_id = tnaitem_id;

		select visibility
		from tna_item_visibility
		into tnaitem_visibility
		where id = visibility_id;

		return tnaitem_visibility;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_tnaitemvisibility(text)
  OWNER TO postgres;

-- Function: get_tnastate(integer)

-- DROP FUNCTION get_tnastate(integer);

CREATE OR REPLACE FUNCTION get_tnastate(state_id integer)
  RETURNS character varying AS
$BODY$
declare
	tnastate character varying(100);
begin
		select state
		from tna_state
		into tnastate
		where id = state_id;

		return tnastate;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_tnastate(integer)
  OWNER TO postgres;

-- Function: get_useremail(text)

-- DROP FUNCTION get_useremail(text);

CREATE OR REPLACE FUNCTION get_useremail(user_id text)
  RETURNS character varying AS
$BODY$
declare
	user_email character varying(100);
begin
		select email
		from users
		into user_email
		where id = user_id;

		return user_email;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_useremail(text)
  OWNER TO postgres;

-- Function: get_username(text)

-- DROP FUNCTION get_username(text);

CREATE OR REPLACE FUNCTION get_username(user_id text)
  RETURNS character varying AS
$BODY$
declare
	user_name character varying(100);
begin
		select display_name
		from users
		into user_name
		where id = user_id;

		return user_name;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION get_username(text)
  OWNER TO postgres;

-- Function: getassignee_status(integer)

-- DROP FUNCTION getassignee_status(integer);

CREATE OR REPLACE FUNCTION getassignee_status(statusid integer)
  RETURNS character varying AS
$BODY$
declare
	task_status character varying(100);
begin
		select status
		from task_assignee_status
		into task_status
		where id = statusid;

		return task_status;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION getassignee_status(integer)
  OWNER TO postgres;

-- Function: gettask_attachments(text)

-- DROP FUNCTION gettask_attachments(text);

CREATE OR REPLACE FUNCTION gettask_attachments(taskid text)
  RETURNS json AS
$BODY$
declare

json_out json;

begin
		
	json_out := (select array_to_json(array_agg((row_to_json(t))))
				from (
					select id as id,
					(
						select row_to_json(d)
						from 
						(
							select id as "id", display_name as "displayName", email as "email" from users where id=creator_id
						) d
					)as creator,
					task_id as "taskId", type as "type", data as "data"  
					from task_attachments where task_id=taskid
					) t);
			
	
return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_attachments(text)
  OWNER TO postgres;

-- Function: gettask_categories(text)

-- DROP FUNCTION gettask_categories(text);

CREATE OR REPLACE FUNCTION gettask_categories(taskid text)
  RETURNS json AS
$BODY$
declare

json_out json;

begin
	
	json_out := (select array_to_json(array_agg((row_to_json(t))))
				from (
					select category_id as id, gettaskcategory_title(category_id) as title  
					from task_category_task where task_id=taskid
					) t);
			
	
return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_categories(text)
  OWNER TO postgres;

-- Function: gettask_comments(text)

-- DROP FUNCTION gettask_comments(text);

CREATE OR REPLACE FUNCTION gettask_comments(taskid text)
  RETURNS json AS
$BODY$
declare
json_out json;

begin
	
	json_out := (select array_to_json(array_agg((row_to_json(t))))
				from (
				
					select id as id,
					(
						select row_to_json(d)
						from 
						(
							select id as "id", display_name as "displayName", email as "email" from users where id=creator_id
						) d
					)as creator,
					task_id as "taskId", type as "type", data as "data"  
					from task_comments where task_id=taskid
					) t);
			
	
return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_comments(text)
  OWNER TO postgres;

-- Function: gettask_details(text, text)

-- DROP FUNCTION gettask_details(text, text);

CREATE OR REPLACE FUNCTION gettask_details(taskid text, tnaitemid text)
  RETURNS json AS
$BODY$
declare
json_out json;
begin
json_out := (select COALESCE(json_agg(row_to_json(t)),'[]')
				from (
					select id as "id",creator_id as "userId",title as title,description as description,
					(
						select row_to_json(d)
						from 
						(
							select id as "id", display_name as "displayName", email as "email" from users where id=assignee_id
						) d
					) as assignee,
					due_date as "dueDate", seen as "seen", is_submitted as "isSubmitted", is_completed as "isCompleted", completion_date as "completionDate",
					(
						select row_to_json(d)
						from 
						(
							select id as "PriorityId", priority as "PriorityName" from priorities where id=priority_id
						) d
					) as priority,
					location as "location", gettask_status(status_id) as status,deleted_at as "deletedAt",created_at as "createdAt",updated_at as "updatedAt",
					(
						select row_to_json(d)
						from 
						(
							select id as "id", display_name as "displayName", email as "email" from users where id=creator_id
						) d
					) as creator,
					submission_date as "submissionDate",					
					(
						gettask_attachments(id)
					) as attachments,
					(
						gettask_comments(id)
					) as comments,
					(
						gettask_categories(id)
					) as categories,
					(
						gettask_tags(id)
					) as tags,
					(
						gettask_followers(id)
					) as followers
					
					from tasks where id=taskid and tna_item_id=tnaitemid
					) t);
			return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_details(text, text)
  OWNER TO postgres;

-- Function: gettask_followers(text)

-- DROP FUNCTION gettask_followers(text);

CREATE OR REPLACE FUNCTION gettask_followers(taskid text)
  RETURNS json AS
$BODY$
declare

json_out json;
vfollowerid text;
begin

		
	json_out := (select array_to_json(array_agg((row_to_json(t))))
				from (
					select follower_id as id, get_username(follower_id) as "displayName", get_useremail(follower_id) as "email" from task_followers where task_id=taskid
					) t);
			
	
return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_followers(text)
  OWNER TO postgres;

-- Function: gettask_status(integer)

-- DROP FUNCTION gettask_status(integer);

CREATE OR REPLACE FUNCTION gettask_status(statusid integer)
  RETURNS character varying AS
$BODY$
declare
	task_status character varying(100);
begin
		select status
		from task_status
		into task_status
		where id = statusid;

		return task_status;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_status(integer)
  OWNER TO postgres;
-- Function: gettask_tags(text)

-- DROP FUNCTION gettask_tags(text);

CREATE OR REPLACE FUNCTION gettask_tags(taskid text)
  RETURNS json AS
$BODY$
declare

json_out json;
vtagid text;
begin
	
	json_out := (select array_to_json(array_agg((row_to_json(t))))
				from (
					select tag_id as id, gettasktag_title(tag_id) as title
					from task_tag_task where task_id=taskid
					) t);
			
	
return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettask_tags(text)
  OWNER TO postgres;

-- Function: gettaskcategory_title(text)

-- DROP FUNCTION gettaskcategory_title(text);

CREATE OR REPLACE FUNCTION gettaskcategory_title(categoryid text)
  RETURNS character varying AS
$BODY$
declare
	category_title character varying(100);
begin
		select title
		from task_categories
		into category_title
		where id = categoryid;

		return category_title;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettaskcategory_title(text)
  OWNER TO postgres;

-- Function: gettasktag_title(text)

-- DROP FUNCTION gettasktag_title(text);

CREATE OR REPLACE FUNCTION gettasktag_title(tagid text)
  RETURNS character varying AS
$BODY$
declare
	tag_title character varying(100);
begin
		select title
		from task_tags
		into tag_title
		where id = tagid;

		return tag_title;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettasktag_title(text)
  OWNER TO postgres;


  -- Function: gettnaitem_creator(text)

-- DROP FUNCTION gettnaitem_creator(text);

CREATE OR REPLACE FUNCTION gettnaitem_creator(tnaitemid text)
  RETURNS json AS
$BODY$
declare
	json_out json;
begin
		json_out:=(select COALESCE(row_to_json(t),'[]')
		from
		(select tnaitem_creatorid as id, tnaitem_creator_displayname as "displayName", tnaitem_creator_email as "email"
		 from vw_tna_items where tnaitem_id = tnaitemid)t);
		return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettnaitem_creator(text)
  OWNER TO postgres;


-- Function: gettnaitem_representor(text)

-- DROP FUNCTION gettnaitem_representor(text);

CREATE OR REPLACE FUNCTION gettnaitem_representor(tnaitemid text)
  RETURNS json AS
$BODY$
declare
	json_out json;
begin
		json_out:=(select COALESCE(row_to_json(t),'[]')
		from
		(select tnaitem_representorid as id, tnaitem_representator_displayname as "displayName", tnaitem_representator_email as "email"
		 from vw_tna_items where tnaitem_id = tnaitemid)t);
		return json_out;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettnaitem_representor(text)
  OWNER TO postgres;



-- View: vw_tna_items

-- DROP VIEW vw_tna_items;

CREATE OR REPLACE VIEW vw_tna_items AS 
 SELECT b.id AS tnaitem_id,
    b.tna_id AS tnaitem_tnaid,
    b.title AS tnaitem_title,
    b.description AS tnaitem_description,
    b.creator_id AS tnaitem_creatorid,
    get_username(b.creator_id::text) AS tnaitem_creator_displayname,
    get_useremail(b.creator_id::text) AS tnaitem_creator_email,
    b.task_days AS tnaitem_taskdays,
    b.planned_date AS tnaitem_planneddate,
    b.actual_date AS tnaitem_actualdate,
    b.representor_id AS tnaitem_representorid,
    get_username(b.representor_id::text) AS tnaitem_representator_displayname,
    get_useremail(b.representor_id::text) AS tnaitem_representator_email,
    b.dependor_id AS tnaitem_dependorid,
    b.is_milestone AS tnaitem_ismilestone,
    b.is_completed AS tnaitem_iscompleted,
    b.is_dispatched AS tnaitem_isdispatched,
    get_itemstatus(b.id::text) AS tnaitem_itemstatus,
    get_tnaitemvisibility(b.id::text) AS tnaitem_visibility,
    b.is_parallel AS tnaitem_isparallel,
    b.label AS tnaitem_label,
    b.projected_date AS tnaitem_projecteddate,
    b.delta AS tnaitem_delta,
    b.department_id AS tnaitem_department,
    b.task_id AS tnaitem_task,
    b.created_at AS tnaitem_createdat,
    b.updated_at AS tnaitem_updatedat,
    a.id AS tna_id,
    a.projected_date AS tna_projecteddate,
    get_tnahealth(a.tna_health_id) AS tna_health,
    get_tnastate(a.tna_state_id) AS tna_state
   FROM tna a,
    tna_items b
  WHERE a.id::text = b.tna_id::text AND b.deleted_at IS NULL;

ALTER TABLE vw_tna_items
  OWNER TO postgres;



-- Function: gettna_json(text)

-- DROP FUNCTION gettna_json(text);

CREATE OR REPLACE FUNCTION gettna_json(itnaid text)
  RETURNS json AS
$BODY$
	declare
		cur cursor for select tnaitem_id
				from   vw_tna_items
				where  tnaitem_tnaid = itnaid and tnaitem_ismilestone is true;
	vtnaitem_id text;
	task_id text;
	json_out json;			
	begin
		open cur;
		loop
			
		fetch cur into vtnaitem_id;
		exit when not found;

		select tnaitem_task from vw_tna_items into task_id where tnaitem_id = vtnaitem_id;
		
		json_out := (select COALESCE(array_to_json(array_agg(row_to_json(t))), '[]')
		from (
			select tnaitem_id as "itemId",tnaitem_title as title,tnaitem_description as description,
			(
				select row_to_json(d)
				from 
				(
					select distinct tnaitem_creatorid as id,tnaitem_creator_displayname as "displayName",tnaitem_creator_email as email
					from vw_tna_items where tnaitem_id=vtnaitem_id
				) d
			) as creator,
			tnaitem_taskdays as "taskDays",tnaitem_planneddate as "plannedDate",tnaitem_actualdate as "actualDate",
			(
				select row_to_json(d)
				from 
				(
					select distinct tnaitem_representorid as id,tnaitem_representator_displayname as "displayName",tnaitem_representator_email as email
					from vw_tna_items where tnaitem_id=vtnaitem_id
				) d
			) as representor,
			tnaitem_dependorid as dependor,tnaitem_ismilestone as "isMilestone",tnaitem_iscompleted as "isCompleted",tnaitem_isdispatched as "isDispatched", tnaitem_isparallel as "isParallel", tnaitem_label as "label", tnaitem_itemstatus as "itemStatus",tnaitem_visibility as visibility,tna_id as "tnaId",
			(
				gettna_nodes(tnaitem_id)
			) as nodes,
			tnaitem_delta as delta, tnaitem_projecteddate as "projectedDate",tnaitem_department as department,
			(
				gettask_details(tnaitem_task,tnaitem_id)
				
			) as task,
			tnaitem_createdat as "createdAt",tnaitem_updatedat as "updatedAt",
			(
				select row_to_json(d)
				from 
				(
					select distinct tna_projecteddate as "projectedDate",tna_health as "tnaHealth",tna_state as "tnaState"
					from vw_tna_items where tnaitem_tnaid=itnaid
				) d
			) as tna
  
  
   
  
			from vw_tna_items where tnaitem_tnaid=itnaid and tnaitem_ismilestone is true order by tnaitem_planneddate
			) t);	

		
		
		end loop;
		close cur;
	return json_out;
	end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettna_json(text)
  OWNER TO postgres;

-- Function: gettna_nodes(text)

-- DROP FUNCTION gettna_nodes(text);

CREATE OR REPLACE FUNCTION gettna_nodes(tnaitemid text)
  RETURNS json AS
$BODY$
declare
tnaid text;
json_out json;
begin
	select tnaitem_tnaid into tnaid from vw_tna_items where tnaitem_id = tnaitemid;
	
	
	json_out := (select COALESCE(json_agg(row_to_json(t)),'[]')
				from (
					select tnaitem_id as "itemId",tnaitem_title as title,tnaitem_description as description,
					(
						gettnaitem_creator(tnaitem_id)
					) as creator,
					tnaitem_taskdays as "taskDays",tnaitem_planneddate as "plannedDate",tnaitem_actualdate as "actualDate",
					(
						gettnaitem_representor(tnaitem_id)
					) as representor,
					tnaitem_dependorid as dependor,tnaitem_ismilestone as "isMilestone",tnaitem_iscompleted as "isCompleted",tnaitem_isdispatched as "isDispatched", tnaitem_isparallel as "isParallel", tnaitem_label as "label", tnaitem_itemstatus as "itemStatus",tnaitem_visibility as visibility,tna_id as tnaId,
					(
						gettna_nodes(tnaitem_id)
					) as nodes,
					tnaitem_delta as delta, tnaitem_projecteddate as "projectedDate",tnaitem_department as department,
					(
						gettask_details(tnaitem_task,tnaitem_id)
					) as task,
					tnaitem_createdat as "createdAt",tnaitem_updatedat as "updatedAt",
					(
						select row_to_json(d)
						from 
						(
							select distinct tna_projecteddate as "projectedDate",tna_health as "tnaHealth",tna_state as "tnaState"
							from vw_tna_items where tnaitem_tnaid=tnaid
						) d
					) as tna
  
  
   
  
					from vw_tna_items where tnaitem_tnaid=tnaid and tnaitem_dependorid=tnaitemid order by tnaitem_planneddate
					) t);
			return json_out;


end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION gettna_nodes(text)
  OWNER TO postgres;


 -- Function: sync_tna(json)

-- DROP FUNCTION sync_tna(json);

CREATE OR REPLACE FUNCTION sync_tna(input_data json)
  RETURNS json AS
$BODY$
declare
	json_length int;
	i json;
	j json;
	nodes json;
	nodes_count int;
	tna_startdate date;
	milestone_planneddate date;
	maxitem_planneddate date;
	item_planneddate date;
	taskdays int;
	element int;
	melement int;
	previous_milestone text;
	premilestone_planneddate date;
	previous_item_sequential text;
	previous_itemfirst_sequential text;
	previous_item_parallel text;
	preitem_planneddate date;
	isparallel boolean;
	isparallel_first boolean;
	isparallel_milestone boolean;
	tnaid text;
	json_output json;
begin
	
	previous_milestone='';
	previous_item_sequential='';
	previous_item_parallel='';
	melement :=0;
	for i in select * from json_array_elements(input_data)
	loop
		melement := melement + 1;
		nodes := i->>'nodes';
		nodes_count := json_array_length(nodes);

		
			if (nodes_count=0) then

				
					select start_date from tna into tna_startdate where id=i->>'tnaId';
					taskdays := i->>'taskDays';
					milestone_planneddate :=  tna_startdate + interval '1' day * taskdays;
					update tna_items set planned_date = milestone_planneddate where id=i->>'itemId' and is_milestone is TRUE;
					
			
			else
				element :=0;
				previous_milestone := i->>'itemId';
				for j in select * from json_array_elements(nodes)
				loop
					element := element + 1;
					
					if(element=1) then
							if(melement=1) then
								select start_date from tna into premilestone_planneddate where id=i->>'tnaId';
							else
								select planned_date from tna_items into premilestone_planneddate where id=previous_milestone;
							end if;			
							
							taskdays := j->>'taskDays';
							item_planneddate := premilestone_planneddate + interval '1' day * taskdays;
							update tna_items set planned_date = item_planneddate where id=j->>'itemId' and dependor_id=i->>'itemId';

							isparallel_first := j->>'isParallel';
							if(isparallel_first) then
								previous_item_sequential := previous_milestone;
								previous_itemfirst_sequential := j->>'itemId';
							else
								previous_item_sequential := j->>'itemId';
								
							end if;
							
						
					else

						isparallel := j->>'isParallel';

						if(isparallel) then
							select planned_date from tna_items into preitem_planneddate where id=previous_item_sequential;
							taskdays := j->>'taskDays';
							item_planneddate := preitem_planneddate + interval '1' day * taskdays;
							update tna_items set planned_date = item_planneddate where id=j->>'itemId' and dependor_id=i->>'itemId';
							previous_item_parallel := j->>'itemId';
						
						else
							if(previous_item_sequential=previous_milestone) then
								previous_item_sequential := previous_itemfirst_sequential;
							end if;
							
							select planned_date from tna_items into preitem_planneddate where id=previous_item_sequential;
							taskdays := j->>'taskDays';
							item_planneddate := preitem_planneddate + interval '1' day * taskdays;
							update tna_items set planned_date = item_planneddate where id=j->>'itemId' and dependor_id=i->>'itemId';
							previous_item_sequential := j->>'itemId';	
						end if;
					
					
					
					
					end if;
					select max(planned_date) from tna_items into maxitem_planneddate where dependor_id=i->>'itemId';
					taskdays := i->>'taskDays';
					milestone_planneddate :=  maxitem_planneddate + interval '1' day *  taskdays;
					update tna_items set planned_date = milestone_planneddate where id=i->>'itemId' and is_milestone is TRUE;
				end loop;
			end if;

		previous_milestone := i->>'itemId';
		
		tnaid := i->>'tnaId';	
	end loop;
	
	json_output := (select gettna_json(tnaid));
	return json_output;

end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION sync_tna(json)
  OWNER TO postgres;

