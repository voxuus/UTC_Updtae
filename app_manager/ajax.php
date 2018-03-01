<?php
error_reporting(0);
include "config.php";
session_start();

$method = $_REQUEST["method"];

if (function_exists($method))
	echo json_encode($method());


 header('Content-type: text/html; charset=utf-8');


 
    function codepoint_encode($str) {
        return substr(json_encode($str), 1, -1);
    }
 

 
    function codepoint_decode($str) {
        return json_decode(sprintf('"%s"', $str));
    }
 
 


function add_banned_words() {
	global $con;
	$banned_words = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "insert into uwi_banned_words (banned_word,banned_create_date) values('$banned_words',NOW())");

	$result = mysqli_query($con, "select banned_id,banned_word from uwi_banned_words order by  banned_id desc ");

	while ($list = mysqli_fetch_assoc($result)) {
		$out .= '<li id="banned'.$list['banned_id'].'"> ' . $list['banned_word'] . '<a class="pull-right" onclick="delete_banned('.$list['banned_id'].');" ><i class="fa fa-close" aria-hidden="true"></i></a></li>';
	}

	return array("data" => $out);
}

function group_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_groups where group_id='$user_id'");
	mysqli_query($con, " delete from  uwi_message_read where group_id='$user_id'");

	mysqli_query($con, " delete from  uwi_comment_and_message where ref_id='$user_id' and source='group'");

	mysqli_query($con, " delete from  uwi_notifications where ref_id='$user_id' and notifications_type='group'");
	mysqli_query($con, " delete from  uwi_notifications where ref_id='$user_id' and notifications_type='invite_group'");
	mysqli_query($con, " delete from  uwi_notifications where ref_id='$user_id' and notifications_type='group_request'");
	


	return array("data" => "Done");

}

function release_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_comment_flag where flag='$user_id'");

	return array("data" => "Done");
}

function flag_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_comment_and_message where message_id='$user_id'");
	mysqli_query($con, "delete from uwi_comment_flag where comment_id='$user_id'");

	return array("data" => "Done");
}

function page_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_facebook_page where page_id='$user_id'");

	mysqli_query($con, "delete from uwi_post where facebook_id='$user_id'");

	return array("data" => "delete from uwi_post where facebook_id='$user_id'");

}

/*function question_survey()
 {
 global $con;

 $survey_id=mysqli_real_escape_string($con,$_REQUEST['survey_id']);
 $output = mysqli_query($con,"select * from uwi_survey_question where survey_id='$survey_id' ");
 while($row=mysqli_fetch_assoc($output))
 {

 $out.='<option value="'.$row['question_id'].'">'.$row['survey_question'].'</option>';

 }
 while ($row = mysqli_fetch_assoc($output)) {
 $out
 # code...
 }

 return array("data"=>"select * from uwi_survey_question where survey_id='$survey_id' " );

 }*/
function  customer_list_user()
{
  global $con;
  
    $type_id=mysqli_real_escape_string($con,$_REQUEST['type_id']);

    $outr = mysqli_query($con,"select uwi_custom_list_result.custom_list_id,uwi_users_profile.first_name,uwi_users_profile.last_name from uwi_custom_list_result join uwi_users_profile on uwi_custom_list_result.user_id = uwi_users_profile.uid where uwi_custom_list_result.list_id='$type_id'");
 
    while($rows=mysqli_fetch_assoc($outr))
    {

       $outs .= '<li id="list_user_id';
       $outs .= $rows['custom_list_id'].'"';
       $outs .= '  >'.codepoint_decode($rows['first_name']).' '.codepoint_decode($rows['last_name']);
       $outs .= '<a class="pull-right" onclick="delete_user(';
       $outs .= $rows['custom_list_id'];
       $outs .= ');" ><i class="fa fa-close" aria-hidden="true"></i></a></li>';
      
    }
    
    //return array("data"=>"select uwi_users_profile.* from uwi_users_profile $join $where order by uwi_users_profile.first_name asc");
    if(!empty($outs))
    {
      return array("data"=>$outs);
    }
    else
    {
     return array("data"=>"No result found."); 
    }

}

function user_list() {
	global $con;
	//$da = array();

	 $education=mysqli_real_escape_string($con,$_REQUEST['education']);
	$education_and = mysqli_real_escape_string($con, $_REQUEST['education_and']);
	$gender = mysqli_real_escape_string($con, $_REQUEST['gender']);
	$gender_and = mysqli_real_escape_string($con, $_REQUEST['gender_and']);
	$skills=mysqli_real_escape_string($con,$_REQUEST['skills']);
	$skills_and = mysqli_real_escape_string($con, $_REQUEST['skills_and']);
	$interest = mysqli_real_escape_string($con, $_REQUEST['interest']);
	$interest_and = mysqli_real_escape_string($con, $_REQUEST['interest_and']);
	$range_1 = mysqli_real_escape_string($con, $_REQUEST['range_1']);
	$age_and = mysqli_real_escape_string($con, $_REQUEST['age_and']);
	$range_11 = mysqli_real_escape_string($con, $_REQUEST['range_11']);

	$where .= "";
	$join .= "";
 
	if (  $education != '' || $interest != ''  || $range_1 != '0-0'  || $skills != '') {
		$where .= "where ";
	}

	/* if(!empty($education))
	 {
	 $join .=" join uwi_user_education on uwi_user_education.uid=uwi_users_profile.uid ";
	 $where .=" uwi_user_education.course LIKE '%$education%' ";
	 }*/

	if ($_REQUEST['education'] != 'null') {  $count = 0;
		$v = explode(",", $_REQUEST['education']);
		$join .= " join 	uwi_users_job_profile on 	uwi_users_job_profile.uid=uwi_users_profile.uid ";
		foreach ($v as $education) {

			if ($count > 0) {
				$where .= " or ";
			}
			$where .= " 	uwi_users_job_profile.job_company	 LIKE '%$education%' ";

			$count++;
		}
	}
 
	if (!empty($education_and)) {
		$where .= $education_and;
	}
	 
	 

	if ($_REQUEST['skills'] != 'null') {  $count = 0;
		$s = explode(",", $_REQUEST['skills']);
		$join .= " join 	uwi_users_job_profile as jp on jp.uid=uwi_users_profile.uid ";
		foreach ($s as $skills) {

			if ($count > 0) {
				$where .= " or ";
			}
			$where .= " jp.designation LIKE '%$skills%' ";

			$count++;
		}
	}

	if (!empty($skills_and)) {
		$where .= $skills_and;
	}
	 
	if ($_REQUEST['interest'] != 'null') {  $count = 0;
		$i = explode(",", $_REQUEST['interest']);
		$join .= " join uwi_users_achievement as uua on uua.uid=uwi_users_profile.uid ";
		foreach ($i as $interest) {

			if ($count > 0) {
				$where .= " or ";
			}
			$where .= " uua.interests LIKE '%$interest%' ";

			$count++;
		}
	}

	if (!empty($interest_and)) {
		$where .= $interest_and;
	}


	if ($range_1 != '0-0') {
		$pelican = explode("-", $range_1);
		$join .= " join uwi_pelican_sum as p on p.uid=uwi_users_profile.uid ";
		$where .= " p.total between '" . $pelican['0'] . "' and '" . $pelican['1'] . "'";

	}
	 //return array("data" =>"select uwi_users_profile.* from uwi_users_profile $join $where order by uwi_users_profile.first_name asc");

	$outr = mysqli_query($con, "select uwi_users_profile.* from uwi_users_profile $join $where order by uwi_users_profile.first_name asc");

	while ($rows = mysqli_fetch_assoc($outr)) {

		$outs .= '<li  >'.codepoint_decode($rows['first_name']).' '.codepoint_decode($rows['last_name']).'</li><input type="hidden" name="all_u[]" value="'.$rows['uid'].'" />';

	}

	if (!empty($outs)) {
		return array("data" => $outs);
	} else {
		return array("data" => "No Result Found");
	}
}


function user_list_key()
{
   global $con;
   $search_key=mysqli_real_escape_string($con,$_REQUEST['search_key']);



   $outr = mysqli_query($con,"select uwi_users_profile.* from uwi_users_profile  left join uwi_user_education on uwi_user_education.uid=uwi_users_profile.uid   left join uwi_users_achievement on uwi_users_achievement.uid=uwi_users_profile.uid  left join uwi_users_achievement as uua on uua.uid=uwi_users_profile.uid  left join uwi_users_job_profile on uwi_users_job_profile.uid=uwi_users_profile.uid where  uua.interests LIKE '%$search_key%'   or uwi_users_job_profile.job_company LIKE '%$search_key%'   or uwi_users_job_profile.designation LIKE '%$search_key%' or uwi_users_profile.first_name LIKE '%$search_key%' or uwi_users_profile.last_name LIKE '%$search_key%' or concat_ws(' ',first_name,last_name) like '%$search_key%' 
  group by uid  order by  uwi_users_profile.first_name asc");

 

    while($rows=mysqli_fetch_assoc($outr))
    {

       $outs .= '<li  >'.codepoint_decode($rows['first_name']).' '.codepoint_decode($rows['last_name']).'</li><input type="hidden" name="all_u[]" value="'.$rows['uid'].'" /> ';
      
    }

    if(!empty($outs))
    {
      return array("data"=>$outs);
    }
    else
    {
     return array("data"=>"No Result Found"); 
    }
}

function question_survey() {
	global $con;

	$survey_id = mysqli_real_escape_string($con, $_REQUEST['survey_id']);
	/*$output = mysqli_query($con,"select * from uwi_survey_question where survey_id='$survey_id' ");
	 $outs.='<option></option>';
	 while($row=mysqli_fetch_assoc($output))
	 {

	 $out.='<option value="'.$row['question_id'].'">'.$row['survey_question'].'</option>';

	 }*/

	$outr = mysqli_query($con, "select uwi_users_profile.* from uwi_survey_push join uwi_users_profile on uwi_users_profile.uid = uwi_survey_push.uid where uwi_survey_push.survey_id='$survey_id' order by uwi_users_profile.first_name asc");

	while ($rows = mysqli_fetch_assoc($outr)) {

		$outs .= '<li style="cursor: pointer;" onclick="select_survey_answer(' . $rows['uid'] . ')" >' . codepoint_decode($rows['first_name']) . ' ' . codepoint_decode($rows['last_name']) . '</li>';

	}
	return array("datae" => $outs);
}

function answer_survey() {
	global $con;

	$survey_id = mysqli_real_escape_string($con, $_REQUEST['survey_id']);
	$users_listsur = mysqli_real_escape_string($con, $_REQUEST['users_listsur']);

	$output = mysqli_query($con, "SELECT uwi_question_option.option_text,uwi_survey_question.survey_question FROM `uwi_survey_answer` join uwi_survey_question on uwi_survey_question.question_id = uwi_survey_answer.question_id  join uwi_question_option on uwi_question_option.option_id =uwi_survey_answer.option_id  where uwi_survey_answer.survey_id='$survey_id' and uwi_survey_answer.uid= '$users_listsur' ");
	while ($row = mysqli_fetch_assoc($output)) {

		$out .= '<b> Ques-: ' . $row['survey_question'] . '</b><p>Ans-; ' . $row['option_text'] . '</p>';

	}

	return array("data" => $out);
}

function question_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_survey_question where question_id='$user_id'");

	return array("data" => "Done");
}

function question_edit() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$serveyQuestion = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_survey_question where question_id='$user_id'"));
	$out .= '<form class="form-horizontal" role="form" action="#" method="post">
              <div class="col-md-12" >
              <input type="hidden" name="question_id"  value="' . $user_id . '" >

          <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control" value="' . $serveyQuestion['survey_question'] . '" required id="sur_ques"   name="question" placeholder="Question ">
                    
                  </div>
                     
                </div>


              </div>
              <div class="col-md-12" >';

	$serveyQuestionop = mysqli_query($con, "select * from uwi_question_option where question_id='" . $user_id . "'");

	$count = 1;
	while ($rowop = mysqli_fetch_assoc($serveyQuestionop)) {
		$out .= '<div class="form-group">
                          
                          <div class="col-md-12">
                            <input type="text" value="' . $rowop['option_text'] . '" class="form-control" name="survey_options[]"       placeholder="Option ' . $count . '">
                            
                          </div>
                      </div>';
		$count++;
	}

	for ($i = $count; $i <= 3; $i++) {

		$out .= '<div class="form-group">
                          
                          <div class="col-md-12">
                            <input type="text" class="form-control" name+"survey_options[]"  name="survey_options[]" placeholder="Option ' . $count . '">
                            
                          </div>
                      </div>';
	}

	$out .= '<div class="form-group">
                  
                  <div class="col-md-12">
                    <button type="submit" class="btn blue">Save changes</button>
                  </div>
                  </div>

              </div>

              </form> ';

	return array("data" => $out);
}

function user_list_survery() {
	global $con;
	$out .= '<select class="form-control" multiple="" name="group_id[]">';
	$groupList = mysqli_query($con, "select * from uwi_users join uwi_users_profile on uwi_users_profile.uid = uwi_users.uid order by uwi_users_profile.first_name asc");

	while ($detail = mysqli_fetch_assoc($groupList)) {

		$out .= '<option value="' . $detail['uid'] . '">' . codepoint_decode($detail['first_name']) . ' ' . codepoint_decode($detail['last_name']) . ' </option>';
	}

	$out .= '</select>';
	return array("data" => $out);
}

function group_list() {
	global $con;

	$out .= '<select   multiple class="form-control" name="group_id[]"   required data-placeholder="Select Group"> 
                        <option value=""></option>';
	$output = mysqli_query($con, "select * from uwi_groups ");
	while ($row = mysqli_fetch_assoc($output)) {

		$out .= '<option value="' . $row['group_id'] . '">' . codepoint_decode($row['group_name']) . '</option>';
	}
	$out .= '</select>';
	return array("data" => $out);
}

function list_list() {
	global $con;

	$out .= '<select   multiple class="form-control" name="group_id[]"   required data-placeholder="Select List"> 
                         ';
	$output = mysqli_query($con, "select * from uwi_custom_list ");
	while ($row = mysqli_fetch_assoc($output)) {

		$out .= '<option value="' . $row['list_id'] . '">' . $row['list_name'] . '</option>';
	}
	$out .= '</select>';
	return array("data" => $out);
}

function user_monitor() {
	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$output = mysqli_query($con, "select *  from uwi_user_monitor join uwi_users_profile on uwi_users_profile.uid = uwi_user_monitor.uid where uwi_user_monitor.uid='$user_id' order by monitor_id desc ");
	while ($row = mysqli_fetch_assoc($output)) {
		if ($row['type'] == 'comment') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> commented in ' . $row['post_type'] . '.</span><span class="body"> </span></div></li>';
		}

		if ($row['type'] == 'like') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> liked a ' . $row['post_type'] . '.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'group_join') {
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));
			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> joined the ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'group_request') {
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));
			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']). ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> sent request to join the ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'create') {$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> created the ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'edit_group') {
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));
			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' .codepoint_decode($row['last_name']) . '</b><span class="datetime "> edited the ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}

		if ($row['type'] == 'leave') {
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));
			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> has left the ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'invite') {
			$user = mysqli_fetch_assoc(mysqli_query($con, "select *  from  uwi_users_profile where uwi_users_profile.uid = '" . $row['ref_id'] . "' "));
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime ">sent ' . codepoint_decode($user['first_name']) . ' ' . codepoint_decode($user['last_name']) . ' invite to join the ' . codepoint_decode($grp['group_name']) . '  group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'request_approve') {
			$user = mysqli_fetch_assoc(mysqli_query($con, "select *  from  uwi_users_profile where uwi_users_profile.uid = '" . $row['ref_id'] . "' "));

			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> approved the request of ' . codepoint_decode($user['first_name']) . ' ' . codepoint_decode($user['last_name']) . ' to join ' . codepoint_decode($grp['group_name']) . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'rate_pelicans') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']). ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> rated stars to a comment.  </span><span class="body"> </span></div></li>';
		}

		if ($row['type'] == 'edit_profile') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> edited profle.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'edit_education') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> edited education.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'edit_job') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> edited job details.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'edit_achievement') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> edited achievements.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'mute') {
			$grp = mysqli_fetch_assoc(mysqli_query($con, "select group_name from uwi_groups where group_id='" . $row['post_id'] . "'"));
			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> mute ' . $grp['group_name'] . ' group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'survey') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> participated in a survery.  </span><span class="body"> </span></div></li>';
		}

		if ($row['type'] == 'app_invite') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> sent app invite.  </span><span class="body"> </span></div></li>';
		}
	}

	return array("data" => $out);
}

function group_monitor() {
	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	$output = mysqli_query($con, "select *  from uwi_group_activity join uwi_users_profile on uwi_users_profile.uid = uwi_group_activity.uid where group_id='$user_id' order by activity_id desc ");
	while ($row = mysqli_fetch_assoc($output)) {
		if ($row['type'] == 'comment') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> commented in this group.</span><span class="body"> </span></div></li>';
		}

		if ($row['type'] == 'like') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> liked this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'mute') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> muted this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'group_join') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> joined this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'group_request') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> sent request to join this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'create') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' .codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> created this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'edit_group') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> edit this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'leave') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> has left this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'invite') {
			$user = mysqli_fetch_assoc(mysqli_query($con, "select *  from  uwi_users_profile where uwi_users_profile.uid = '" . $row['ref_id'] . "' "));

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']). ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime ">sent ' . codepoint_decode($user['first_name']) . ' ' . codepoint_decode($user['last_name']) . ' invite to join this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'request_approve') {
			$user = mysqli_fetch_assoc(mysqli_query($con, "select *  from  uwi_users_profile where uwi_users_profile.uid = '" . $row['ref_id'] . "' "));

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']). '</b><span class="datetime "> approved the request of ' . codepoint_decode($user['first_name']). ' ' . codepoint_decode($user['last_name']) . ' to join this group.  </span><span class="body"> </span></div></li>';
		}
		if ($row['type'] == 'rate_pelicans') {

			$out .= ' <li class="in"><img class="avatar" alt="" src="' . $row['user_image'] . '"/><div class="message"><span class="arrow"></span><b>' . codepoint_decode($row['first_name']) . ' ' . codepoint_decode($row['last_name']) . '</b><span class="datetime "> rated stars to comment of this group.  </span><span class="body"> </span></div></li>';
		}

	}
	/*$output = mysqli_query($con,"select post_id from uwi_post_group where group_id='$user_id' ");
	 while($row=mysqli_fetch_assoc($output))
	 {
	 $group_arr[]=$row['post_id'];
	 }

	 if(!empty($group_arr))
	 {
	 $group_str=implode("','",$group_arr);
	 }
	 $group_str="'".$group_str."'";

	 $query = mysqli_query($con, "select up.* from uwi_post as up where up.post_id IN ($group_str) order by up.post_id desc LIMIT 0,10");

	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	 $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];
	 } else {
	 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank
	 }
	 // $out.='<div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1"><ul class="chats">';
	 while ($detail = mysqli_fetch_assoc($query))
	 {

	 $post_id = $detail['post_id'];

	 $primary_image = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_images where post_id='$post_id' and is_primary='1'"));
	 $out.=' <li class="in">';
	 if(!empty($primary_image))
	 {

	 //$detail['primary_image'] = $pageURL.'/post_image/'.$primary_image['image'];
	 $out.='<img class="avatar" alt="" src="../post_image/'.$primary_image['image'].'"/>';
	 }
	 else
	 {
	 $detail['primary_image'] = "";

	 }

	 $out.=' <div class="message"><span class="arrow"></span><b>'.$detail['title'].'</b><span class="datetime  pull-right">   </span><span class="body">'.mb_substr($detail['detail'],300).' </span></div></li>';

	 }*/

	// $out.='</ul></div>';
	return array("data" => $out);
}

function user_profile_detail() {
	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	$user = mysqli_fetch_assoc(mysqli_query($con, "select uu.*,uup.* from `uwi_users` as uu join `uwi_users_profile` as uup on uu.uid = uup.uid where uu.`uid`='$user_id'"));

	$edu = mysqli_fetch_assoc(mysqli_query($con, "select *  from `uwi_user_education`  where  `uid`='$user_id'"));

	$job = mysqli_fetch_assoc(mysqli_query($con, "select *  from `uwi_users_job_profile`  where  `uid`='$user_id'"));

	$achev = mysqli_fetch_assoc(mysqli_query($con, "select *  from `uwi_users_achievement`  where  `uid`='$user_id'"));

	$group_detail = mysqli_query($con, "select uwi_groups.* from uwi_group_member join uwi_groups on uwi_groups.group_id=uwi_group_member.group_id  where uid='" . $user_id . "'");
	$out .= ' <div class="row">
                <div class="col-md-3">
                      <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                       ' . codepoint_decode($user['first_name']) . ' ' . codepoint_decode($user['last_name']) . '
                    </div>
                    <div class="profile-usertitle-job">
                      @ ' . $user['username'] . '
                    </div>';
	/*if($user['register_check']==0)
	 {

	 $out.='<h5>Manual</h5>';
	 }
	 if($user['register_check']==1)
	 {

	 $out.='<h5>Facebook</h5>';
	 }
	 if($user['register_check']==2)
	 {

	 $out.='<h5>Linkedin</h5>';
	 }*/
	$out .= '</div>
                  <div class="profile-userbuttons">
                <button type="button" class="btn btn-circle green-haze btn-sm" onclick="add_feature(' . $user['uid'] . ');">Feature</button>
                <button type="button" class="btn btn-circle btn-danger btn-sm" onclick="delete_account(' . $user['uid'] . ');">Delete</button>
              </div>
                </div>
           <div class="col-md-3">
                <div class="profile-userpic">
                <img src="' . $user['user_image'] . '" class="img-responsive" alt="">';

	$out .= '</div>

              </div>';

	$out .= '<div class="col-md-6">';
	$out1 .= '<h4 class="text-erroe"><i class="fa fa-graduation-cap"></i> Education</h4>';

	$out1 .= '  <ul class="list-unstyled">
                <li> ' . $edu['course'] . ' </li>
                <li> ' . $edu['batch'] . '- ' . $edu['year_of_passing'] . ' - Alumni </li>
              </ul>
                <h4 class="text-warning"><i class="fa fa-medkit"></i> Experience</h4>';

	$out1 .= ' <ul class="list-unstyled">
                <li> ' . codepoint_decode($job['designation']) . '</li>
                <li> ' . codepoint_decode($job['job_company']) . ' ' . $job['date_of_start'] . ' </li>
              </ul>
              </div>';
	$out .= ' <div class="col-md-12">
             <h4 class="text-success"><i class="fa fa-gift"></i> Title</h4>
              <ul class="list-unstyled">
              <li> ' . codepoint_decode($job['designation']) . '</li>
              </ul>
             <h4 class="text-success"><i class="fa fa-gift"></i> Department</h4>
              <ul class="list-unstyled">
              <li> ' . codepoint_decode($job['job_company']) . '</li>
              </ul>
             <h4 class="text-success"><i class="fa fa-gift"></i> Interests</h4>';
	$out .= ' <ul class="list-unstyled">';
	$out1 .= ' <li> <strong>Star Rating</strong> - ';
	$total_stars = mysqli_fetch_assoc(mysqli_query($con, "select sum(pelican) as pelican from  uwi_pelican where uid='{$user_id}' and pelican_status='add'"));
	//echo $total_stars['pelican'];

	$out1 .= $total_stars['pelican'] . '</li>
                <li> <strong>Skills</strong> - ' . $achev['skills'] . ' </li>';
	$out .= '<li> ' . $achev['interests'] . ' </li>';
	$out1 .= '<li> <strong>Awards</strong> - ' . $achev['awards'] . ' </li>
                <li> <strong>Honors</strong> - ' . $achev['honors'] . ' </li>';

	$out .= '</ul>';
	$out .= ' <h4 class="text-success">Groups</h4> <ul class="list-unstyled">';

	while ($gropu_list = mysqli_fetch_assoc($group_detail)) {
		$out .= '<li>' . codepoint_decode($gropu_list['group_name']) . ' </li>';
	}

	$out .= ' </ul> </div></div>';
	return array("data" => $out);
}

function user_feature_detail() {

	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$totalf = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as total_feature from uwi_feature_user"));

	if($totalf['total_feature']=='3')
	{
		return array("data" => "error");
	}
	else
	{
		mysqli_query($con, "insert into uwi_feature_user (uid,feature_status,feature_create_date) values('$user_id','Active',NOW())");

		
		$featureList = mysqli_query($con, "select * from uwi_feature_user join uwi_users_profile on uwi_users_profile.uid = uwi_feature_user.uid order by uwi_feature_user.feature_id desc");

		while ($feature = mysqli_fetch_assoc($featureList)) {

			$out .= '<tr>
	                  <td>
	                     <span class="font-red"> ' . codepoint_decode($feature['first_name']) . ' ' . codepoint_decode($feature['last_name']) . ' -</span>
	                  </td>
	                  <td>' . $feature['quotes'] . ' 
	                  </td>
	                   
	                   
	                  <td>
	                    <span class="label label-sm label-danger" onclick="remove_feature(' . $feature['uid'] . ' );">
	                    REMOVE </span>
	                  </td>
	                </tr>';
		}

		return array("data" => $out);
	}

}

function current_time()
{
	mysqli_query($con,"SET  time_zone = '-04:00'") ;

 

date_default_timezone_set("America/Port_of_Spain");
	$datetime = "Current Time ".date('h:i:s');
	return array("data" => $datetime);
}

function user_feature_remove() {
	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_feature_user where uid='$user_id'");
	$featureList = mysqli_query($con, "select * from uwi_feature_user join uwi_users_profile on uwi_users_profile.uid = uwi_feature_user.uid order by uwi_feature_user.feature_id desc");

	while ($feature = mysqli_fetch_assoc($featureList)) {

		$out .= '<tr>
                  <td>
                     <span class="font-red"> ' . $feature['first_name'] . ' ' . $feature['last_name'] . ' -</span>
                  </td>
                  <td>' . $feature['quotes'] . ' 
                  </td>
                   
                   
                  <td>
                    <span class="label label-sm label-danger" onclick="remove_feature(' . $feature['uid'] . ' );">
                    REMOVE </span>
                  </td>
                </tr>';
	}

	return array("data" => $out);

}

function user_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from  uwi_users where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_feature_user where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_users_profile where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_comment_and_message where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_likes where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_message_read where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_users_achievement where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_users_job_profile where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_user_education where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_comment_likes where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_comment_flag where uid='$user_id'");

	mysqli_query($con, "delete from  uwi_notifications where uid='$user_id'");

	return array("data" => "Done");
}

function user_block() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "update uwi_users set user_status='Blocked' where uid='$user_id'");

	return array("data" => "Done");
}

function user_unblock() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "update uwi_users set user_status='Active' where uid='$user_id'");

	return array("data" => "Done");
}

function post_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_post where post_id='$user_id'");

	return array("data" => "Done");
}

function post_hide() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	mysqli_query($con, "update uwi_post set post_hide='1' where post_id='$user_id'");
	return array("data" => "Done");
}

function post_unhide() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	mysqli_query($con, "update uwi_post set post_hide='0' where post_id='$user_id'");
	return array("data" => "Done");
}

function admin_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_super_admins where id='$user_id'");

	return array("data" => "Done");
}

function survey_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_survey where survey_id='$user_id'");

	return array("data" => "Done");
}

function bannd_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_banned_words where banned_id='$user_id'");

	return array("data" => "Done");
}

function list_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_custom_list where list_id='$user_id'");

	return array("data" => "Done");
}

function list_user_remove()
{
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_custom_list_result where custom_list_id='$user_id'");

	return array("data" => "Done");
}

function post_archive() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	$detail = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post where post_id='$user_id'"));
	if ($detail['archive'] == '0') {
		mysqli_query($con, "update  uwi_post set archive='1' where post_id='$user_id'");
	}
	if ($detail['archive'] == '1') {
		mysqli_query($con, "update  uwi_post set archive='0' where post_id='$user_id'");
	}

	return array("data" => "Done");
}

function campus_remove() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_campus where campus_id='$user_id'");

	return array("data" => "Done");
}

function article_edit() {

	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$detail = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post where post_id='$user_id'"));
	
	 //$video_link=str_replace(array("<iframe id='existing-iframe-example' height='HEIGHT_VALUE' width='WIDTH_VALUE' src='","' frameborder='0' allowfullscreen></iframe>","<iframe id='existing-iframe-example' height='HEIGHT_VALUE' width='WIDTH_VALUE' src=\"","\" frameborder='0' allowfullscreen></iframe>"),array("",""), $detail['video_link']);
	

	$out = '<div class="row">

          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                 
              </div>
              <div class="portlet-body">


                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Article Title" name="article_edit_title" value="' . $detail['title'] . '" required class="form-control">
                    <span class="help-block">
                    By UNITE Team. </span>
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  
                  <div class="col-md-9">
                      <textarea class="wysihtml5 form-control" name="article_detail"  placeholder="Article Detail" rows="6" name="editor1" data-error-container="#editor1_error">' . $detail['detail'] . '</textarea>
                      <div id="editor1_error">
                      </div>
                    </div>
                </div> <!-- End Article Detail group -->
                
				<div class="form-group">
                     
                    <div class="col-md-8">
                    <input type="text" placeholder="Vimeo Embedded Link" name="video_link"   class="form-control" value="'.$detail['video_link'].'">

                     </div>
                </div> 
				

            <div class="form-group">
                     
                    <div class="col-md-8">
                      <select id="select2_sample2" class="form-control select2" name="groups[]" placeholder="Select Group"  multiple>';
	$groupList = mysqli_query($con, "select group_name,group_id from uwi_groups where group_status='Active' order by group_id desc");

	while ($detailg = mysqli_fetch_assoc($groupList)) {

		$out .= '<option value="' . $detailg['group_id'] . '"';
		$detailgr = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_group where post_id='$user_id' and group_id='" . $detailg['group_id'] . "'"));
		if (!empty($detailgr))
			$out .= ' selected';

		$out .= '>' . codepoint_decode($detailg['group_name']) . '</option>';
		//$out.='<option value="'. $detailg['group_id']; .' ">'.  $detailg['group_name'].'</option>';
		/* $out.='<option value="'. $detailg['group_id']; .' "'.

		 $detailgr = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_group where post_id='$user_id' and group_id='".$detailg['group_id']."'"));

		 if(!empty($detailgr))
		 $out.=' selected';

		 .'>'.  $detail['group_name'].'</option>';*/

	}

	$out .= '</select>
                    </div>
                  </div>
                  <div class="form-group">
                    
                    <div class="col-md-3">
                      <input class="form-control form-control-inline input-medium form_datetime" value="' . $detail['publish_date'] . '" required data-date-format="yyyy-mm-dd" name="post_date" size="16" type="text" value=""/>
                      <span class="help-block">
                      Select date </span>
                    </div>
                  </div>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                
              </div>
              <div class="portlet-body">
                <div class="form-group">
                  
                  <div class="col-md-9">';

	$detail_images = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_images where post_id='$user_id' and is_primary='1'  "));

	if (!empty($detail_images)) {
		$out .= ' <input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img   id="blah" height="150" src="../post_image/' . $detail_images['image'] . '"/> <p class="help-block"> PRIMARY IMAGE </p>
                      ';
	} else {
		$out .= '<input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img id="blah" src="noimagefound.jpg" alt="your image" height="200" width="150" /><p class="help-block"> PRIMARY IMAGE </p>
                     ';
	}

	

	$out .= '
                  </div>
                    <div class="col-md-3">
                   
                    <label>
                    
                    <input type="checkbox" name="image_big"';
	if ($detail['image_big'] == 1)
		$out .= 'checked';
	$out .= ' value="1">
                     
                   Image Big
                    </label>
                      </div>
                </div> <!-- End Primary Image Group -->

                



               <div class="form-group"> 
                  <div class="col-md-6">';
	$detail_images = mysqli_query($con, "select * from uwi_post_images where post_id='$user_id' and is_primary='0'");

	while ($img = mysqli_fetch_assoc($detail_images)) {
		$out .= ' <img  height="50" src="../post_image/' . $img['image'] . '"/><a class="btn btn-xs default"   onclick="remove_add_img(' . $img['image_id'] . ',' . $user_id . ')"><i class="fa fa-remove"></i></a> ';
	}

	$out .= '  <p class="help-block"> ADDITIONAL IMAGES </p>
                  </div>
                  <div class="col-md-6">
                   
                  </div>
                </div> <!-- End additional Image Group -->
                <div class="form-group"> 
                  <div class="col-md-6">
                    <input id="exampleInputFile" type="file" name="additional_image[]">
                      <p class="help-block"> ADDITIONAL IMAGES </p>
                  </div>
                  <div class="col-md-6">
                   <button class="btn default" type="button" id="suppl_add">ADD MORE ADDITIONAL IMAGES</button>
                  </div>
                </div> <!-- End additional Image Group -->
                <div class="form-group" id="more_additional">
                <input type="hidden" value="1" id="remove_id"/> 
                <input type="hidden" value="' . $detail['post_id'] . '" id="article_id" name="post_id"/> 
                                    
                </div> <!-- End more additional Image Group -->
              </div>
            </div>

          </div>

        </div>

      ';
	return array("data" => $out);
}

function article_edit_add() {

	global $con;
	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	$image_id = mysqli_real_escape_string($con, $_REQUEST['image_id']);

	mysqli_query($con, "delete from uwi_post_images where image_id='$image_id'");
	//delete from uwi_post_images where image_id='259'
	//return array("data"=>"delete from uwi_post_images where image_id='$image_id'");

	$detail = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post where post_id='$user_id'"));

	$out = '<div class="row">

          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                 
              </div>
              <div class="portlet-body">


                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Article Title" name="article_edit_title" value="' . $detail['title'] . '" required class="form-control">
                    <span class="help-block">
                    By UNITE Team. </span>
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  
                  <div class="col-md-9">
                      <textarea class="wysihtml5 form-control" name="article_detail"  placeholder="Article Detail" rows="6" name="editor1" data-error-container="#editor1_error">' . $detail['detail'] . '</textarea>
                      <div id="editor1_error">
                      </div>
                    </div>
                </div> <!-- End Article Detail group -->

            <div class="form-group">
                     
                    <div class="col-md-8">
                      <select id="select2_sample2" class="form-control select2" name="groups[]" placeholder="Select Group"  multiple>';
	$groupList = mysqli_query($con, "select group_name,group_id from uwi_groups where group_status='Active' order by group_id desc");

	while ($detailg = mysqli_fetch_assoc($groupList)) {

		$out .= '<option value="' . $detailg['group_id'] . '"';
		$detailgr = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_group where post_id='$user_id' and group_id='" . $detailg['group_id'] . "'"));
		if (!empty($detailgr))
			$out .= ' selected';

		$out .= '>' . codepoint_decode($detailg['group_name']) . '</option>';
		//$out.='<option value="'. $detailg['group_id']; .' ">'.  $detailg['group_name'].'</option>';
		/* $out.='<option value="'. $detailg['group_id']; .' "'.

		 $detailgr = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_group where post_id='$user_id' and group_id='".$detailg['group_id']."'"));

		 if(!empty($detailgr))
		 $out.=' selected';

		 .'>'.  $detail['group_name'].'</option>';*/

	}

	$out .= '</select>
                    </div>
                  </div>
                  <div class="form-group">
                    
                    <div class="col-md-3">
                      <input class="form-control form-control-inline input-medium form_datetime" value="' . $detail['publish_date'] . '" required data-date-format="yyyy-mm-dd" name="post_date" size="16" type="text" value=""/>
                      <span class="help-block">
                      Select date </span>
                    </div>
                  </div>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                
              </div>
              <div class="portlet-body">
                <div class="form-group">
                  
                  <div class="col-md-9">';

	$detail_images = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_images where post_id='$user_id' and is_primary='1'"));

	if (!empty($detail_images)) {
		$out .= ' <input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img   id="blah" height="150" src="../post_image/' . $detail_images['image'] . '"/> <p class="help-block"> PRIMARY IMAGE </p>
                      ';
	} else {
		$out .= '<input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img id="blah" src="noimagefound.jpg" alt="your image" height="200" width="150" /><p class="help-block"> PRIMARY IMAGE </p>
                     ';
	}
	$out .= '
                  </div>
                    <div class="col-md-3">
                   
                    <label>
                    
                    <input type="checkbox" name="image_big"';
	if ($detail['image_big'] == 1)
		$out .= 'checked';
	$out .= ' value="1">
                     
                   Image Big
                    </label>
                      </div>
                </div> <!-- End Primary Image Group -->
               <div class="form-group"> 
                  <div class="col-md-6">';
	$detail_images = mysqli_query($con, "select * from uwi_post_images where post_id='$user_id' and is_primary='0'");

	while ($img = mysqli_fetch_assoc($detail_images)) {
		$out .= ' <img  height="50" src="../post_image/' . $img['image'] . '"/><a class="btn btn-xs default"   onclick="remove_add_img(' . $img['image_id'] . ',' . $user_id . ')"><i class="fa fa-remove"></i></a> ';
	}

	$out .= '  <p class="help-block"> ADDITIONAL IMAGES </p>
                  </div>
                  <div class="col-md-6">
                   
                  </div>
                </div> <!-- End additional Image Group -->
                <div class="form-group"> 
                  <div class="col-md-6">
                    <input id="exampleInputFile" type="file" name="additional_image[]">
                      <p class="help-block"> ADDITIONAL IMAGES </p>
                  </div>
                  <div class="col-md-6">
                   <button class="btn default" type="button" id="suppl_add">ADD MORE ADDITIONAL IMAGES</button>
                  </div>
                </div> <!-- End additional Image Group -->
                <div class="form-group" id="more_additional">
                <input type="hidden" value="1" id="remove_id"/> 
                <input type="hidden" value="' . $detail['post_id'] . '" id="article_id" name="post_id"/> 
                                    
                </div> <!-- End more additional Image Group -->
              </div>
            </div>

          </div>

        </div>

      ';
	return array("data" => $out);
}

function event_edit() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	$detail = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post where post_id='$user_id'"));

	$out = '
  
  <div class="col-md-5"> 
  <div class="form-group">
    
    <div class="col-md-12"><div class="input-group date form_datetime" >
                        <input type="text" size="16"  placeholder="Event From" value="' . $detail['date_of_start'] . ' ' . $detail['start_timing'] . '" name="from" required  class="form-control">
                        <span class="input-group-btn">
                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div></div>
      </div>
  <div class="form-group">                
    <div class="col-md-12">
    <div class="input-group date form_datetime">
                        <input type="text" size="16"  placeholder="Event To" value="' . $detail['date_of_end'] . ' ' . $detail['end_timing'] . '" name="to" required class="form-control">
                        <span class="input-group-btn">
                        <button class="btn default date-set"  type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div>
                      </div>
  </div>
   
  <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Latitude" value="' . $detail['latitude'] . '"  name="latitude"  class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->
                 <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Longitude" value="' . $detail['longitude'] . '"  name="longitude"  class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Location Address" value="' . $detail['location_address'] . '"  name="location_address"   class="form-control">
                    
                  </div>
                </div>
                <div class="form-group">
                    
                    <div class="col-md-3">
                      <input class="form-control form-control-inline input-medium form_datetime" value="' . $detail['publish_date'] . '" required data-date-format="yyyy-mm-dd" name="post_date" size="16" type="text" value=""/>
                      <span class="help-block">
                      Select date </span>
                    </div>
                  </div>
</div>
  <div class="col-md-7"> 
    <h3 class="block"> Event Detail </h3>
    <div class="col-md-4">
      <div class="form-group">
                  
                  <div class="col-md-12">';

	$detail_images = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_images where post_id='$user_id' and is_primary='1'"));

	if (!empty($detail_images)) {
		$out .= ' <input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img id="blah"  height="200" width="150" src="../post_image/' . $detail_images['image'] . '"/>
                      ';
	} else {
		$out .= '<input id="exampleInputFile"  onchange="readURL(this);" type="file" name="primary_image">
                      <img id="blah" src="noimagefound.jpg" alt="your image" height="200" width="150" />
                     ';
	}
	$out .= ' </div> <!-- End Primary Image Group -->
           </div></div>
           <div class="col-md-8">
<div class="portlet gren">
      <div class="portlet-title">
        
        
      </div>
      <div class="portlet-body" >
      <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Event Name"  value="' . $detail['title'] . '" name="article_edit_title" required class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  <div class="col-md-12">
                   
                      <textarea class="wysihtml5 form-control" name="article_detail"   rows="6" name="editor1" data-error-container="#editor1_error">  ' . $detail['detail'] . ' </textarea>
                      <div id="editor1_error">
                  </div>
                </div> <!-- End Article Detail group -->
              </div>

            <div class="form-group">
                     
                    <div class="col-md-8">
                     <select id="select2_sample2" class="form-control select2" name="groups[]" placeholder="Select Group"  multiple>';
	$groupList = mysqli_query($con, "select group_name,group_id from uwi_groups where group_status='Active' order by group_id desc");

	while ($detailg = mysqli_fetch_assoc($groupList)) {

		$out .= '<option value="' . $detailg['group_id'] . '"';
		$detailgr = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_post_group where post_id='$user_id' and group_id='" . $detailg['group_id'] . "'"));
		if (!empty($detailgr))
			$out .= ' selected';

		$out .= '>' . codepoint_decode($detailg['group_name']) . '</option>';

	}

	$out .= '</select>
                    </div>
                    <div class="col-md-4">
                    <input type="hidden" value="' . $detail['post_id'] . '" id="article_id" name="post_id"/> 
                <button class="btn green btn-sm pull-right" type="submit">Save</button>
                 </div>
                  </div>
          <!-- End Article Detail group -->

              </div></div>  
           </div>
  </div>
';

	return array("data" => $out);
}

function campus_edit() {
	global $con;

	$user_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$campus_detail = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_campus where campus_id='$user_id'"));
	$out = '<div class="row">

          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                 
              </div>
              <div class="portlet-body">


                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Title" value="' . $campus_detail['campus_title'] . '" name="campus_edit_title" required class="form-control">
                     
                  </div>
                    </div> 
                  <div class="form-group">
                         
                        <div class="col-md-12">
                            <textarea class="form-control" name="campus_description" required rows="3" placeholder="Description" id="address" aria-required="true">' . $campus_detail['campus_description'] . '</textarea>
                            
                        </div>
                     </div>

                     <div class="form-group">
                  
                  <div class="col-md-6">
                    <input type="text" placeholder="Lat " name="latitude" required id="latitude" value="' . $campus_detail['latitude'] . '" required class="form-control">
                     
                  </div>
                  <div class="col-md-6">
                    <input type="text" placeholder=" Long" name="longitude" required id="longitude" value="' . $campus_detail['longitude'] . '" required class="form-control">
                     
                  </div>
                    </div> 
              <!-- End Article Title group -->
                
                  

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                
              </div>
              <div class="portlet-body">
                <div class="form-group">
                  
                  <div class="col-md-8">
                    <img  height="150" src="../campus_image/' . $campus_detail['campus_image'] . '"/>
                      <p class="help-block"> POI IMAGE </p>
                  </div>
                  
                </div> <!-- End Primary Image Group -->
                
                <div class="form-group" id="more_additional">
                <input type="hidden" value="' . $campus_detail['campus_id'] . '" name="campus_id" id="campus_id"/> 
                                    
                </div> <!-- End more additional Image Group -->
              </div>
            </div>

          </div>

        </div>

      ';

	return array("data" => $out);
}


function group_edit_details()
{
	
	$content=' <div class="col-md-12">             
           <form class="form-horizontal" role="form" method="post" action="#"  id="grp_edit" enctype="multipart/form-data">
            <div class="form-body"><!-- <div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""><a class="btn  green btn-sm" href="javascript:;">Edit </a></div></div> -->
              
          
			<div class="col-md-7">	  
			<div class="col-md-10" style=""> 
			  <div class="form-group">
                  <input type="text" name="group_name" required placeholder="Group Name" class="form-control">
                   </div>
				   </div>
           <div class="col-md-12" style=""> 
                 <div class="form-group">
                <p>Group name</p>
				  <label>Public</label> 
                <input type="radio" name="group_type" value="Public" >
                <label>Public</label> 
                <input type="radio" name="group_type" value="Private">
				</div>
                   </div>
                       
				  
			<!--	  
	<div class="col-md-10" style=""> 
                   <div class="form-group">
                   <input type="hidden" name="group_type"  value="Public">
                   </div>
            </div>-->
				  
				  
            <div class="col-md-10" style=""> 
                <div class="form-group">
                   <textarea rows="3" name="group_detail"  class="form-control" placeholder="Group Detail"></textarea>
                    </div>
					</div>
                 
				  
				  
				  
           <div class="col-md-10" style=""> 
                  <div class="form-group">
                   <input id="tags_1" type="text" placeholder="Group Tags" class="form-control tags" name="group_tags" />
                    </div>
					</div>
                  </div>
                 
				 	 <div class="col-md-5">
			  <div class="form-group">
               <input id="exampleInputFile" onchange="readURL(this);" required  type="file" name="primary_image">
                      <img id="blah" src="noimagefound.jpg" alt="your image" height="180" width="180" style="border:solid 1px #eee" />
                    </div>
                  </div>    
			
				  
				  
                </div>

            <div class="form-actions"><div class="row">
                    <div class="col-md-offset-5 col-md-12">
                      <button class="btn green" type="submit" style="margin:30px 0 20px 0">Save Group</button>
                      <!-- <button class="btn red" type="button">Delete Group</button> -->
                    </div>
                  </div></div>

          </form>
           </div>';
		   
	
}

function group_message_detail() {

	global $con;

	$group_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$group = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_groups where group_id='$group_id'"));

	$query = mysqli_query($con, "SELECT * FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'");

	$list = array();

	$out .= '';
	$count = 0;
	while ($detail = mysqli_fetch_assoc($query)) {

		//$list[]=$detail;
		$out .= '<li class="in">';

		/*if(!empty($detail['user_image']))
		 {
		 $out.='<img class="avatar" alt="" src="'.$detail['user_image'].'"/>';
		 }
		 else
		 {
		 $out.='<img class="avatar" alt="" src="noimagefound.jpg"/>';
		 }*/

		if ($detail['user_image'] == '') {
			if ($detail['gender'] == 'Male') {
				$out .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			} else {
				$out .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			}
		} else {
			$out .= '<img src="' . $detail['user_image'] . '" alt="" class="avatar">';
		}

		$out .= '<div class="message">
               <span class="arrow">
               </span>
               <a href="javascript:;" class="name">
               ' . codepoint_decode($detail['first_name']) . ' ' . codepoint_decode($detail['last_name']) . '</a> <span class="datetime  pull-right"> <button class="btn  btn-warning btn-xs" type="button" onclick="remove_member(' . $detail['member_id'] . ',' . $group_id . ')">Remove</button> </span>
              
            </div>
         </li>';
		$count++;

	}

	$comments = mysqli_query($con, "select ucam.*, uup.first_name,uup.last_name,uup.user_image,uup.gender, uup.user_thumbnail, (select IF(sum(rate_star)  IS NULL,0,sum(rate_star) ) from uwi_group_comment_rating where  comment_id = ucam.message_id and group_id='" . $group_id . "' ) as total_pelicans from uwi_comment_and_message as ucam left join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.ref_id='$group_id' and ucam.source='group' order by ucam.message_create_date ");
	$commentsl = "";

	while ($list = mysqli_fetch_assoc($comments)) {

		$commentsl .= '<li class="in">';
		if ($list['user_type'] == 'admin') { $commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
		}
		else
		if ($list['user_image'] == '') {
			if ($list['gender'] == 'Male') {
				$commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			} else {
				$commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			}
		} else {
			$commentsl .= '<img src="' . $list['user_image'] . '" alt="" class="avatar">';
		} 
		
		$commentsl .= '<div class="message">
                      <span class="arrow">
                      </span>';
                      $commentsl.="<div>";
                      $name="Administrator";
		if ($list['user_type'] == 'user') {
			$name = codepoint_decode($list['first_name']) . ' ' . codepoint_decode($list['last_name']);
		} 
		
		$commentsl .= '<span class="datetime">'.$name.'
                      at ' . date('H:i d M,Y ', strtotime($list['message_create_date']));

		$mods = mysqli_fetch_assoc(mysqli_query($con, "SELECT IF(count(*) IS NULL,0,count(*)) as moo FROM `uwi_comment_likes` where ref_id='" . $list['message_id'] . "' "));
		$message_id =  $list['message_id'];

		$commentsl .= '<a class="pull-right" data-toggle="modal" href="#reactionGroup" onclick="message_reaction_detail('.$message_id.')"> | &nbsp;&nbsp;' . $list['total_pelicans'] . '<img src="red_star@3x.png" height="17" />  </a><a onclick="message_reaction_detail('.$message_id.')" class="pull-right" data-toggle="modal" href="#reactionGroup">' . ($mods['moo'] == '0' ? "0" : '');

		$mods = mysqli_query($con, "SELECT count(*) as con,moods FROM `uwi_comment_likes` where ref_id='" . $list['message_id'] . "'  group by moods");
		$moods = array();
		while ($mods_list = mysqli_fetch_assoc($mods)) {$commentsl .= "<div class='pull-right'>";
			$commentsl .= $mods_list['con'];
			if ($mods_list['moods'] == 'amazing') {
				$commentsl .= '&nbsp;<img src="smily/amazing@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'sad') {
				$commentsl .= '&nbsp;<img src="smily/sad_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'happy') {
				$commentsl .= '&nbsp;<img src="smily/happy_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'love') {
				$commentsl .= '&nbsp;<img src="smily/love_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'indifferen') {
				$commentsl .= '&nbsp;<img src="smily/indifferent_smiley@3x.png" height="17" />&nbsp;';
			}
			$commentsl .= "</div>";
		}

		$commentsl .= '</a>';

		$commentsl .= ' </span>
                      <span></span>
                      <span class="body">' . codepoint_decode($list['content']);
		
		if ($list['msg_thumbnail'] != "") {
            $commentsl .= '<br><img height="100" width="100" src="' . $list['msg_image'] . '" alt="" class="msg_thumbnail">';
        }
        
        $commentsl .= '</span><span></span>
                      </div></div>
                  </li>';

/*		$commentsl .= ' </span>
                      <span></span>
                      <span class="body">' . codepoint_decode($list['content']) . '</span>
                      <span></span>
                      </div></div>
                  </li>';*/
	}
	 
	$date = date('d M,Y', strtotime($group['group_create_date']));
	return array("data" => $out, "comments" => $commentsl, "created_on" =>  codepoint_decode($group['group_name']) . '- Created on ' . $date, "total_member" => 'Members (' . $count . ')');
}

function reaction_detail_message()
{	
	global $con;
	$message_id = mysqli_real_escape_string($con, $_REQUEST['message_id']);
	$type = mysqli_real_escape_string($con, $_REQUEST['type']);

	$l = mysqli_query($con,"select * from uwi_message_like_rate join uwi_users_profile on uwi_users_profile.uid = uwi_message_like_rate.uid where uwi_message_like_rate.message_id='$message_id'");
$out="";
	while ($list = mysqli_fetch_assoc($l)) {
		# code...
	
	$out .='<div class="col-md-2"> </div><div class="col-md-4">'.codepoint_decode($list['first_name']).' '.codepoint_decode($list['last_name']).'</div><div class="col-md-2">';
			if ($list['mood'] == 'amazing') {
				$out .= '&nbsp;<img src="smily/sel_amazing@3x.png" height="17" />&nbsp;';
			}
			if ($list['mood'] == 'sad') {
				$out .= '&nbsp;<img src="smily/sel_sad_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($list['mood'] == 'happy') {
				$out .= '&nbsp;<img src="smily/sel_happy_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($list['mood'] == 'love') {
				$out .= '&nbsp;<img src="smily/sel_love_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($list['mood'] == 'indifferen') {
				$out .= '&nbsp;<img src="smily/sel_indifferent_smiley@3x.png" height="17" />&nbsp;';
			}

	 
			$out .='</div><div class="col-md-4">';
			for($i =0;$i<$list['rate_star'];$i++ )
			{
			$out .='<img src="red_star@3x.png" height="17" />';
			}
			$out .='</div> ';
	}
        
        return array("data" => $out);
}

function group_message_send() {
	global $con;
	require('../push.php');
	$group_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);
	//$grp_msg = mysqli_real_escape_string($con, base64_encode($_REQUEST['grp_msg']));
	$grp_msg =    mysqli_real_escape_string($con,codepoint_encode($_REQUEST['grp_msg']));
	$type = mysqli_real_escape_string($con, $_REQUEST['type']);

		$detailcontent = explode(" ", $grp_msg);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				

		$banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));

				//print_r($banned);
				
		if($banned['conss']>0 )
		{

			return array("data" => "err");
		
		}

	mysqli_query($con, "insert into `uwi_comment_and_message` (`content`, `source`,`uid`,`ref_id`,`message_status`,`user_type`,`message_create_date`) values('$grp_msg','$type','','$group_id','Active','admin',NOW())");

	$msg_id = mysqli_insert_id($con);
	$query = mysqli_query($con, "select uwi_users.badge,uwi_users.device_token,uwi_users.device_type,uwi_users.uid,uwi_users.phone_no,uwi_users.username from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid  where group_id = $group_id and mute_group='0' and member_status='Active'");


	 while ($device_list = mysqli_fetch_assoc($query)) {

			$device_token = $device_list['device_token'];
			$device_type = $device_list['device_type'];
			$uid = $device_list['uid'];
			  
		 $badge=$device_list['badge']+1;

		 $group_info= mysqli_fetch_assoc(mysqli_query($con,"SELECT group_name FROM `uwi_groups` where group_id='{$group_id}'"));
		 $group_name =  codepoint_decode($group_info['group_name']);

		 	$message = "Administrator has commented in the $group_name ".$type .".";
		 	 $message1 ="Administrator has commented in the ". $group_info['group_name'] ." ".$type .".";

			      //send_message_ios($device_token,$message,$badge,$type,$group_id);

			      if($device_type=='ios')
			        {
			                 send_message_ios($device_token,$message,$badge,$type,$group_id);
			               // $device_list['username'].'<br>';
			           mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
			           
			        }
			        if($device_type=='android')
			        {
			          send_notification_android($device_token,$message,$type,$group_id);
			        }

				//mysqli_query($con,"update uwi_users set badge='$badge' where uid='$uid'");
			       
			 mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$uid','$message1','$message','group',NOW(),'$group_id')");

			 mysqli_query($con,"insert into `uwi_message_read` (`group_id`, `message_id`,`uid`,`read_check`,`create_date`) values('$group_id','$msg_id','$uid','0',NOW())" );  

			 
			
		}


	return array("data" => "Done");
}

function group_member_detail() {
	global $con;

	$group_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$group = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_groups where group_id='$group_id'"));

	$query = mysqli_query($con, "SELECT * FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'");

	$list = array();

	$out .= '';
	$count = 0;
	while ($detail = mysqli_fetch_assoc($query)) {

		//$list[]=$detail;
		$out .= '<li class="in">';

		if (!empty($detail['user_image'])) {
			$out .= '<img class="avatar" alt="" src="' . $detail['user_image'] . '"/>';
		} else {
			$out .= '<img class="avatar" alt="" src="noimagefound.jpg"/>';
		}

		$out .= '<div class="message">
               <span class="arrow">
               </span>
               <a href="javascript:;" class="name">
               ' . codepoint_decode($detail['first_name']) . ' ' . codepoint_decode($detail['last_name']) . '</a>
              <span class="datetime  pull-right"> <button class="btn  btn-warning btn-xs" type="button" onclick="remove_member(' . $detail['member_id'] . ',' . $group_id . ')">Remove</button> </span>
              
            </div>
         </li>';
		$count++;

	}

	$outer .= '
            <div class="form-body"><!-- <div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""><a class="btn  green btn-sm" href="javascript:;">Edit </a></div></div> -->
                <div class="col-md-7">
               <div class="form-group">
                     
                    <div class="col-md-9">
                    <p>
                      <input type="text" name="group_edit_name" value="' . codepoint_decode($group['group_name']) . '" required placeholder="Group Name" class="form-control">
                     </p>
                    </div>
                  </div>

                  <div class="form-group">
                     
                   <div class="col-md-9"> Group Type 
                    <label><input type="radio" name="group_type"';
	if ($group['group_type'] == 'Public') {
		$outer .= 'checked';
	}
	$outer .= ' value="Public"/> Public </label>

	<label><input type="radio" name="group_type"';
	if ($group['group_type'] == 'Private') {
		$outer .= 'checked';
	}
	$outer .= ' value="Private"/> Private </label>
                       </div>
                  </div>

                <div class="form-group">
                     
                    <div class="col-md-9">
                      <textarea rows="3" name="group_detail"  class="form-control" placeholder="Group Detail">' . codepoint_decode($group['group_detail']) . '</textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    
                    <div class="col-md-9">
                     <input id="tags_1" type="text" placeholder="Group Tags" value="' . $group['group_tags'] . '" class="form-control tags" name="group_tags" />
                    </div>
                  </div> </div>

                  <div class="col-md-5">
                  <div class="form-group">
                     
                    <div class="col-md-9">';

	$outer .= ' <input id="exampleInputFiles" onchange="readURLe(this);"  type="file" name="primary_image">
                     <img id="blahs" height="150"  width="150"  src="../group_image/' . $group['group_image'] . '"/> <p class="help-block"> PRIMARY IMAGE </p>
                    </div>
                  </div></div>
                  
                </div>

            <div class="form-actions"><div class="row">
                    <div class="col-md-offset-4 col-md-9">
                    <input type="hidden" name="group_id" value="' . $group_id . '"/>
                      <button class="btn red" type="submit">Save Group</button>
                      <!-- <button class="btn red" type="button">Delete Group</button> -->
                    </div>
                  </div></div>

          ';

	$comments = mysqli_query($con, "select ucam.*, uup.first_name,uup.last_name,uup.user_image, uup.user_thumbnail, (select IF(sum(rate_star)  IS NULL,0,sum(rate_star) ) from uwi_group_comment_rating where  comment_id = ucam.message_id and group_id='" . $group_id . "' ) as total_pelicans from uwi_comment_and_message as ucam join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.ref_id='$group_id' and ucam.source='group' order by ucam.message_id asc ");
	$commentsl .= "";

	while ($list = mysqli_fetch_assoc($comments)) {

		$commentsl .= '<li class="in">';
		if ($list['user_type'] == 'admin') { $commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
		}
		else
		if ($list['user_image'] == '') {
			if ($list['gender'] == 'Male') {
				$commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			} else {
				$commentsl .= '<img src="app_icon_60@3x.png" alt="" class="avatar">';
			}
		} else {
			$commentsl .= '<img src="' . $list['user_image'] . '" alt="" class="avatar">';
		} 
		
		$commentsl .= '<div class="message">
                      <span class="arrow">
                      </span>';
                      $commentsl.="<div>";
                      $name="Administrator";
		if ($list['user_type'] == 'user') {
			$name = codepoint_decode($list['first_name']) . ' ' . codepoint_decode($list['last_name']);
		} 
		
		$commentsl .= '<span class="datetime">'.$name.'
                      at ' . date('H:i d M,Y ', strtotime($list['message_create_date']));

		$mods = mysqli_fetch_assoc(mysqli_query($con, "SELECT IF(count(*) IS NULL,0,count(*)) as moo FROM `uwi_comment_likes` where ref_id='" . $list['message_id'] . "' "));
		$message_id =  $list['message_id'];

		$commentsl .= '<a class="pull-right" data-toggle="modal" href="#reactionGroup" onclick="message_reaction_detail('.$message_id.')"> | &nbsp;&nbsp;' . $list['total_pelicans'] . '<img src="red_star@3x.png" height="17" />  </a><a onclick="message_reaction_detail('.$message_id.')" class="pull-right" data-toggle="modal" href="#reactionGroup">' . ($mods['moo'] == '0' ? "0" : '');

		$mods = mysqli_query($con, "SELECT count(*) as con,moods FROM `uwi_comment_likes` where ref_id='" . $list['message_id'] . "'  group by moods");
		$moods = array();
		while ($mods_list = mysqli_fetch_assoc($mods)) {$commentsl .= "<div class='pull-right'>";
			$commentsl .= $mods_list['con'];
			if ($mods_list['moods'] == 'amazing') {
				$commentsl .= '&nbsp;<img src="smily/amazing@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'sad') {
				$commentsl .= '&nbsp;<img src="smily/sad_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'happy') {
				$commentsl .= '&nbsp;<img src="smily/happy_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'love') {
				$commentsl .= '&nbsp;<img src="smily/love_smiley@3x.png" height="17" />&nbsp;';
			}
			if ($mods_list['moods'] == 'indifferen') {
				$commentsl .= '&nbsp;<img src="smily/indifferent_smiley@3x.png" height="17" />&nbsp;';
			}
			$commentsl .= "</div>";
		}

		$commentsl .= '</a>';

		$commentsl .= ' </span>
                      <span></span>
                      <span class="body">' . codepoint_decode($list['content']) . '</span>
                      <span></span>
                      </div></div>
                  </li>';
	}
	$date = date('d M,Y', strtotime($group['group_create_date']));
	return array("data" => $out, "dataer" => $outer, "comments" => $commentsl, "created_on" => codepoint_decode($group['group_name']) . '- Created on ' . $date, "total_member" => 'Members (' . $count . ')');

}

function group_members_detail() {
	global $con;

	$group_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	$group = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_groups where group_id='$group_id'"));

	//$query = mysqli_query($con,"SELECT * FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'");

	$query = mysqli_query($con, "SELECT * FROM uwi_users_profile where  first_name!='' and uid NOT IN (SELECT uid from uwi_group_member where group_id='$group_id' and member_status='Active' )   order by first_name asc");

	$list = array();

	$outs .= '<input type="hidden" name="group_id" value="' . $group_id . '"/>';
	$count = 0;
	while ($detail = mysqli_fetch_assoc($query)) {

		//$list[]=$detail;
		$out .= '<li class="in">';

		if (!empty($detail['user_image'])) {
			$out .= '<img class="avatar" alt="" src="' . $detail['user_image'] . '"/>';
		} else {
			$out .= '<img class="avatar" alt="" src="app_icon_60@3x.png"/>';
		}

		$out .= '<div class="message">
               <span class="arrow">
               </span>
                
               ' . codepoint_decode($detail['first_name']) . ' ' . codepoint_decode($detail['last_name']). '
              <span class="datetime  pull-right"><input type="checkbox" name="ids[]" value="' . $detail['uid'] . '"/></span>
              
            </div>
         </li>';
		$count++;

	}

	$date = date('d M,Y', strtotime($group['group_create_date']));
	return array("data" => $out, "des" => $outs);

}

function group_user_remove() {

	global $con;

	$group_id = mysqli_real_escape_string($con, $_REQUEST['group_id']);

	$type_id = mysqli_real_escape_string($con, $_REQUEST['type_id']);

	mysqli_query($con, "delete from uwi_group_member where `member_id`='$type_id' and `group_id`='$group_id'");

	$query = mysqli_query($con, "SELECT * FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'");

	$list = array();

	$out .= '';

	while ($detail = mysqli_fetch_assoc($query)) {

		//$list[]=$detail;
		$out .= '<li class="in">
            <img class="avatar" alt="" src="' . $detail['user_image'] . '"/>
            <div class="message">
               <span class="arrow">
               </span>
               <a href="javascript:;" class="name">
               ' . codepoint_decode($detail['first_name']) . ' ' . codepoint_decode($detail['last_name']). '</a>
              <span class="datetime  pull-right"> <button class="btn  btn-warning btn-xs" type="button" onclick="remove_member(' . $detail['member_id'] . ',' . $group_id . ')">Remove</button> </span>
              
            </div>
         </li>';

	}
	return array("data" => $out);

}

function add_survey() {

	global $con;

	$survey_question = mysqli_real_escape_string($con, $_REQUEST['survey_question']);
	$survey_points = mysqli_real_escape_string($con, $_REQUEST['survey_points']);

	mysqli_query($con, "insert into uwi_survey (`survey_question`,`survey_pelicans`,`survey_status`,`survey_create_date`) values('$survey_question','$survey_points','active',NOW())");

	$survey_id = mysqli_insert_id($con);

	$out .= '
<input type="hidden" name="survey_id" id="survey_id" value="' . $survey_id . '"/>
   <div class="col-md-6" >

          <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control" required id="sur_ques"   name="question" placeholder="Question">
                    
                  </div>
                     
                </div>


              </div>
              <div class="col-md-6" >

              <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control"  required   id="sur_option1" name="question" placeholder="Option 1">
                    
                  </div>
                  <div class="col-md-12">
                    <input type="text" class="form-control"  required id="sur_option2"  name="question" placeholder="Option 2">
                    
                  </div>
                  <div class="col-md-12">
                    <input type="text" class="form-control"    id="sur_option3" name="question" placeholder="Option 3">
                    
                  </div>
                     
                </div>
            <div class="form-group">
                  
                  <div class="col-md-12">
                  <button class="btn green btn-sm pull-right" type="submit" onclick="survery_lists();" >Save </button> &nbsp;&nbsp;&nbsp;&nbsp;
                  <button class="btn green btn-sm pull-right" type="submit" onclick="survery_list();" >Add More Question</button>&nbsp;&nbsp;&nbsp;&nbsp;
                  </div>
                  </div>

              </div>';

	return array("data" => $out);
}

function survey_add_quest() {
	global $con;

	$survey_id = mysqli_real_escape_string($con, $_REQUEST['survey_id']);

	$sur_ques = mysqli_real_escape_string($con, $_REQUEST['sur_ques']);

	$sur_option1 = mysqli_real_escape_string($con, $_REQUEST['sur_option1']);

	$sur_option2 = mysqli_real_escape_string($con, $_REQUEST['sur_option2']);

	$sur_option3 = mysqli_real_escape_string($con, $_REQUEST['sur_option3']);

	//return array("data"=>"insert into uwi_survey_question (survey_id,survey_question,create_date) values('$survey_id','$sur_ques',NOW())");
	mysqli_query($con, "insert into uwi_survey_question (survey_id,survey_question,create_date) values('$survey_id','$sur_ques',NOW())");

	$question_id = mysqli_insert_id($con);
	if (!empty($sur_option1)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option1',NOW())");
	}
	if (!empty($sur_option2)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option2',NOW())");
	}
	if (!empty($sur_option3)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option3',NOW())");
	}
	$out .= '
<input type="hidden" name="survey_id" id="survey_id" value="' . $survey_id . '"/>
   <div class="col-md-6" >

          <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control" required id="sur_ques"   name="question" placeholder="Question">
                    
                  </div>
                     
                </div>


              </div>
              <div class="col-md-6" >

              <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control" required=""  id="sur_option1" name="question" placeholder="Option 1">
                    
                  </div>
                  <div class="col-md-12">
                    <input type="text" class="form-control" required="" id="sur_option2"  name="question" placeholder="Option 2">
                    
                  </div>
                  <div class="col-md-12">
                    <input type="text" class="form-control" required=""  id="sur_option3" name="question" placeholder="Option 3">
                    
                  </div>
                     
                </div>
            <div class="form-group">
                  
                  <div class="col-md-12">
                  <button class="btn green btn-sm pull-right" type="submit" onclick="survery_lists();" >Save </button> &nbsp;&nbsp;&nbsp;&nbsp;
                  <button class="btn green btn-sm pull-right" type="submit" onclick="survery_list();" >Add More Question</button>&nbsp;&nbsp;&nbsp;&nbsp;
                  </div>
                  </div>

              </div>';
	return array("data" => $out);
}

function survey_edit_quest() {
	global $con;

	$survey_id = mysqli_real_escape_string($con, $_REQUEST['survey_id']);

	$sur_ques = mysqli_real_escape_string($con, $_REQUEST['sur_ques']);

	$sur_option1 = mysqli_real_escape_string($con, $_REQUEST['sur_option1']);

	$sur_option2 = mysqli_real_escape_string($con, $_REQUEST['sur_option2']);

	$sur_option3 = mysqli_real_escape_string($con, $_REQUEST['sur_option3']);

	//return array("data"=>"insert into uwi_survey_question (survey_id,survey_question,create_date) values('$survey_id','$sur_ques',NOW())");
	mysqli_query($con, "insert into uwi_survey_question (survey_id,survey_question,create_date) values('$survey_id','$sur_ques',NOW())");

	$question_id = mysqli_insert_id($con);
	if (!empty($sur_option1)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option1',NOW())");
	}
	if (!empty($sur_option2)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option2',NOW())");
	}
	if (!empty($sur_option3)) {
		mysqli_query($con, "insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$sur_option3',NOW())");
	}

	return array("data" => "Done");
}
?>