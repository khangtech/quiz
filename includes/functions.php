<?php

//new: Check BU co kiem tra thi

function Check_Exam_Is_Running($connection,$bu_id) {
  $query_select = "SELECT exam_id FROM exams WHERE exam_active = 1 and bu_id =" . $bu_id . " ORDER BY exam_id desc LIMIT 0,1";
  $cmdSelect = mysqli_query($connection,$query_select);
  $countRow = mysqli_num_rows($cmdSelect);
  if ($countRow > 0) {
    while($row = mysqli_fetch_assoc($cmdSelect)) {
      $exam_id = $row["exam_id"];
    }
  }

  return $countRow > 0 ? $exam_id : 0;  
  
}

function Check_Sales_Login($connection, $sale_code, $bu_id) {
  $query_select = "SELECT sale_id  FROM salesman WHERE sale_code ='{$sale_code}' and bu_id={$bu_id} and is_active=1";
  $cmdSelect = mysqli_query($connection,$query_select);
  
  $countRow = mysqli_num_rows($cmdSelect);
  if ($countRow > 0) {
    while($row = mysqli_fetch_assoc($cmdSelect)) {
      $sale_id = $row["sale_id"];
    }
  }

  return $countRow > 0 ? $sale_id : 0;  

}


function Check_Exam_Logs($connection, $sale_id, $exam_id) {

  $query_select = "SELECT status FROM exam_logs WHERE sale_id ={$sale_id} and exam_id={$exam_id} ";

  $cmdSelect = mysqli_query($connection,$query_select);
  
  $countRow = mysqli_num_rows($cmdSelect);
  if ($countRow > 0) {
    while($row = mysqli_fetch_assoc($cmdSelect)) {
      $status = $row["status"];
    }
  }

  return $countRow > 0 ? $status : "empty";  

}


function Check_Exam_Logs_Time($connection, $sale_id, $exam_id) {

  $query_select = "SELECT endtime FROM exam_logs WHERE sale_id ={$sale_id} and exam_id={$exam_id} ";

  $cmdSelect = mysqli_query($connection,$query_select);
  
  $countRow = mysqli_num_rows($cmdSelect);
  if ($countRow > 0) {
    while($row = mysqli_fetch_assoc($cmdSelect)) {
      $endtime = $row["endtime"];
    }
  }

  return $endtime;  

}




function Insert_To_Log($connection, $sale_id,$exam_id, $status, $duration) {
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $begin_time = date("Y-m-d H:i:s");
  $current_timestamp = strtotime($begin_time);
  //calculate end_time 
  $end_timestamp = $current_timestamp + ($duration * 60 );
 // echo $current_timestamp ;
 // echo "<br>" . $end_timestamp;
  $end_time = date("Y-m-d H:i:s", $end_timestamp);
 // echo "day la" . $end_date;
 // echo $current_date;
  $query_insert = "INSERT INTO exam_logs(sale_id,exam_id,starttime,endtime,status) 
  VALUES({$sale_id},{$exam_id},'{$begin_time}','{$end_time}','{$status}')";
 // echo $query_insert;
  $cmdCreate = mysqli_query($connection,$query_insert);
  if (!$cmdCreate) {
    die("Insert failed" . mysqli_error($connection));
  }  else {
    return $begin_time . ';' . $end_time;
  }
}


function Update_Log($connection, $sale_id,$exam_id, $status) {
  $query_insert = "UPDATE exam_logs SET status ='{$status}' WHERE sale_id = {$sale_id} and exam_id={$exam_id}";
  //echo $query_insert;
  $cmdCreate = mysqli_query($connection,$query_insert);
  if (!$cmdCreate) {
    die("Update failed" . mysqli_error($connection));
  } 
}



function Load_Sales_Info($connection, $sale_id) {
  $query_select = "SELECT A.*, B.bu_name FROM salesman A inner join bu B on A.bu_id = B.bu_id WHERE A.sale_id={$sale_id} ";
 // echo $query_select;
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}


function Load_Wrong_Answer($connection, $sale_id, $exam_id) {
  $query_select = "SELECT question_id, choice_id FROM exam_result  WHERE sale_id ={$sale_id} and exam_id={$exam_id} and is_right = 0  ";

  $cmdSelect = mysqli_query($connection,$query_select);
  
  return $cmdSelect;

}

function Load_Bai_Thi($connection, $sale_id, $exam_id) {
  $query_select = "SELECT question_id, choice_id FROM exam_result  WHERE sale_id ={$sale_id} and exam_id={$exam_id}  ";
  //echo $query_select;
  $cmdSelect = mysqli_query($connection,$query_select);
  
  return $cmdSelect;

}



function Create_Update_Exam($connection) {
  if(isset($_POST["submit"])) {
       $exam_date = $_POST['txtExamDate'];
       $exam_active = $_POST['chkExamActive']==1? 1 : 0;
       $bu_id = $_POST['cboBUId'];
       $the_id = $_POST['txtExamId'];
       $exam_desc = $_POST['txtExamDesc'];
       $exam_duration = $_POST['txtExamDuration'];
       $exam_passed = $_POST['txtExamPassed'];
       $exam_total =  $_POST['txtExamTotal'];

       if ($exam_date =="" || empty($exam_date)) {
          echo '<h4 class="text-danger">Vui lòng nhập ngày nội dung!</h4>';
       } else {
         if ($the_id>0) {
           $query_update = "UPDATE exams SET exam_date='{$exam_date}', exam_active={$exam_active}, bu_id={$bu_id}, exam_desc='{$exam_desc}', exam_duration={$exam_duration}, exam_passed={$exam_passed}, exam_total ={$exam_total} WHERE exam_id={$the_id}";
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: exam_list.php");
           }
         } else {
           // insert
           $query_insert = "INSERT INTO exams(exam_date,exam_active,bu_id,exam_desc, exam_duration, exam_passed, exam_total) VALUES('{$exam_date}',{$exam_active},{$bu_id}, '{$exam_desc}', {$exam_duration}, {$exam_passed},{$exam_total})";
           //echo $query_insert ;
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
                header("Location: exam_list.php");
           }
         }
       }
  }
}


function Create_Update_Question($connection) {
  if(isset($_POST["submit"])) {
       $question = $_POST['txtQuestion'];
       $is_active = $_POST['chkQuestionActive']==1? 1 : 0;
       $topic_id = $_POST['cboTopic'];

      $ext = findexts($_FILES['txtQuestionImage']['name']) ; 


      //This line assigns a random number to a variable. You could also use a timestamp here if you prefer. 
     $ran = rand () ;

     //This takes the random number (or timestamp) you generated and adds a . on the end, so it is ready for the file extension to be appended.
     $ran2 = $ran.".";

     //This assigns the subdirectory you want to save into... make sure it exists!
     $target = "../images/";
     $target = $target . $ran2.$ext;


    //This combines the directory, the random file name and the extension $target = $target . $ran2.$ext;

     
    if(move_uploaded_file($_FILES['txtQuestionImage']['tmp_name'], $target)) 
    {
      $question_image = $ran2.$ext;
    } 
    else
    {
       echo "Sorry, there was a problem uploading your file.";
     
    }


    $the_id = $_POST['txtQuestionId'];
       if ($question_image =="" && empty($question)) {
          echo '<h4 class="text-danger">Vui lòng nhập nội dung!</h4>';
       } else {
         if ($the_id>0) {
           if ($question_image !="") {
            $query_update = "UPDATE questions SET question='{$question}', is_active={$is_active}, topic_id={$topic_id}, question_image='{$question_image}' WHERE question_id={$the_id}";
          } else {
            $query_update = "UPDATE questions SET question='{$question}', is_active={$is_active}, topic_id={$topic_id} WHERE question_id={$the_id}";
          }
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: question_list.php");
           }
         } else {
           // insert

           $query_insert = "INSERT INTO questions(question,is_active,topic_id, question_image) VALUES('{$question}',{$is_active},{$topic_id}, '{$question_image}')";
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
                header("Location: question_list.php");
           }
         }
       }
  }
}


function Create_Update_Answer($connection,$parent_id) {
  if(isset($_POST["submit"])) {
       $choice = $_POST['txtChoice'];
       $is_right = $_POST['chkRightChoice']==1? 1 : 0;
       $the_id = $_POST['txtChoiceID'];

      $ext = findexts($_FILES['txtChoiceImage']['name']) ; 


      //This line assigns a random number to a variable. You could also use a timestamp here if you prefer. 
     $ran = rand () ;

     //This takes the random number (or timestamp) you generated and adds a . on the end, so it is ready for the file extension to be appended.
     $ran2 = $ran.".";

     //This assigns the subdirectory you want to save into... make sure it exists!
     $target = "../images/";
     $target = $target . $ran2.$ext;


    //This combines the directory, the random file name and the extension $target = $target . $ran2.$ext;

     
    if(move_uploaded_file($_FILES['txtChoiceImage']['tmp_name'], $target)) 
    {
      $choice_image = $ran2.$ext;
    } 
    else
    {
       echo "Sorry, there was a problem uploading your file.";
     
    }



       if ($choice =="" || empty($choice)) {
          echo '<h4 class="text-danger">Vui lòng nhập nội dung!</h4>';
       } else {
         if ($the_id>0) {
            if ($choice_image !="") {
              $query_update = "UPDATE choices SET choice='{$choice}', is_right_choice={$is_right}, choice_image='{$choice_image}' WHERE choice_id={$the_id}";
            } else {
              $query_update = "UPDATE choices SET choice='{$choice}', is_right_choice={$is_right} WHERE choice_id={$the_id}";
            }
           
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: answer_list.php?id={$parent_id}");
           }
         } else {
           // insert
           $query_insert = "INSERT INTO choices(choice,question_id,is_right_choice, choice_image) VALUES('{$choice}',{$parent_id},{$is_right},'{$choice_image}')";
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
              header("Location: answer_list.php?id={$parent_id}");
           }
         }
       }
  }
}


// load exam info 

function Load_Exam_Info($connection,$the_id) {
  $query_select = "SELECT A.exam_id, A.exam_duration, A.exam_passed, sum(B.number_questions) as total_questions FROM exams A inner join exam_topics B on A.exam_id = B.exam_id  WHERE A.exam_active =1 and A.exam_id=" . $the_id . " GROUP BY A.exam_id, A.exam_duration, A.exam_passed";
 //    echo $query_select;
  $rs = mysqli_query($connection, $query_select);
  return $rs;
}








//BU Function
function Load_All_BU($connection) {
  $query_select = "SELECT * FROM bu " ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}


// Question Function
function Delete_Question($connection,$the_id) {
    // xoa het cac cau tra loi
    $query_delete_child = "DELETE FROM choices WHERE question_id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete_child);

    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    }
    else { // delete parents
      $query_delete = "DELETE FROM questions WHERE question_id=" . $the_id;
      $cmd_delete = mysqli_query($connection, $query_delete);
      if (!$cmd_delete) {
        die('Delete failed' . mysqli_error($connection));
      } else {
        header("Location: question_list.php");
      }
    }


}



 function findexts ($filename) 
 { 
 $filename = strtolower($filename) ; 
 $exts = split("[/\\.]", $filename) ; 
 $n = count($exts)-1; 
 $exts = $exts[$n]; 
 return $exts; 
}



function Load_Question($connection,$the_id) {
   $query_select = "SELECT * FROM questions WHERE question_id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   return $rs;
}

function Load_Question_Text($connection,$the_id) {
   $query_select = "SELECT * FROM questions WHERE question_id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   while ($row = mysqli_fetch_array($rs)) {
       return $row["question"];
   }

}


function Load_All_Question($connection) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT Q.*, T.topic_name FROM questions Q inner join topics T on Q.topic_id = T.topic_id  order by Q.question_id DESC " ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}



// end Question Functions


// Exam Function
function Delete_Exam($connection,$the_id) {



    //xoa exam_topics 
    $query_delete = "DELETE FROM exam_topics WHERE exam_id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete);

    //xoa exam_result 
   $query_delete_result = "DELETE FROM exam_result WHERE exam_id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete_result);

    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    } else {
      $query_delete = "DELETE FROM exams WHERE exam_id=" . $the_id;
      $cmd_delete = mysqli_query($connection, $query_delete);
      if (!$cmd_delete) {
        die('Delete failed' . mysqli_error($connection));
      } else {
        header("Location: exam_list.php");
      }
    }
}


function Load_Exam($connection,$the_id) {
   $query_select = "SELECT * FROM exams WHERE exam_id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   return $rs;
}

function Load_All_Exam($connection) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT A.*, B.bu_name FROM exams A inner join bu B on A.bu_id = B.bu_id order by A.exam_id desc " ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}




// Topic Function
function Delete_Topic($connection,$the_id) {
    $query_delete = "DELETE FROM topics WHERE topic_id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete);
    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    } else {
      header("Location: topic_list.php");
    }
}
function Create_Update_Topic($connection) {
  if(isset($_POST["submit"])) {
       $topic_name = $_POST['txtTopicName'];
       $topic_active = $_POST['chkTopicActive']==1? 1 : 0;
       $the_id = $_POST['txtTopicId'];
       if ($topic_name =="" || empty($topic_name)) {
          echo '<h4 class="text-danger">Vui lòng nhập nội dung!</h4>';
       } else {
         if ($the_id>0) {
           $query_update = "UPDATE topics SET topic_name='{$topic_name}', topic_active={$topic_active} WHERE topic_id={$the_id}";
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: topic_list.php");
           }
         } else {
           // insert
           $query_insert = "INSERT INTO topics(topic_name,topic_active) VALUES('{$topic_name}',{$topic_active})";
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
                header("Location: topic_list.php");
           }
         }
       }
  }
}

function Load_Topic($connection,$the_id) {
   $query_select = "SELECT * FROM topics WHERE topic_id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   return $rs;
}

function Load_All_Topic($connection) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT * FROM topics " ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}

function Load_All_Topic_With_Count($connection) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT T.topic_id, T.topic_name, T.topic_active, count(question_id) as total  FROM topics T left join questions Q on T.topic_id = Q.topic_id group by T.topic_id, T.topic_name, T.topic_active" ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}

function Load_All_Topic_Exclude($connection, $exam_id) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT * FROM topics WHERE topic_id not in (Select topic_id from exam_topics where exam_id=" . $exam_id . ")" ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}



// =========================  Answer Function
function Delete_Answer($connection,$the_id, $parent_id) {
    $query_delete = "DELETE FROM choices WHERE choice_id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete);
    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    } else {
      header("Location: answer_list.php?id={$parent_id}");
    }
}


function Load_Answer($connection,$the_id) {
     $query_select = "SELECT * FROM choices WHERE choice_id=" . $the_id;
     $rs = mysqli_query($connection, $query_select);
     return $rs;
}

function Load_All_Answer($connection,$parent_id) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT * FROM choices WHERE question_id=" . $parent_id ;

  $rs = mysqli_query($connection,$query_select);
  return $rs;
}

function Is_Right_Choice($connection,$the_id) {
     $query_select = "SELECT is_right_choice FROM choices WHERE choice_id=" . $the_id;
     $rs = mysqli_query($connection, $query_select);
     while ($row = mysqli_fetch_array($rs)) {
       return $row["is_right_choice"];
     }
}


function Find_Right_Choice($connection,$question_id) {
     $query_select = "SELECT choice_id  FROM choices WHERE question_id=" . $question_id . " AND is_right_choice = 1";
     $rs = mysqli_query($connection, $query_select);
     while ($row = mysqli_fetch_array($rs)) {
       return $row["choice_id"];
     }
}




// Exam_Topic Function
function Delete_Exam_Topic($connection,$the_id) {
   $exam_id = $_GET['exam_id'];
    $query_delete = "DELETE FROM exam_topics WHERE id=" . $the_id;
    $cmd_delete = mysqli_query($connection, $query_delete);
    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    } else {
      header("Location: exam_topic_list.php?exam_id=" . $exam_id);
    }
}
function Create_Update_Exam_Topic($connection) {
  if(isset($_POST["submit"])) {
       $topic_id = $_POST['cboTopicID'];
       $exam_id = $_POST['txtExamID'];
       $numer_questions = $_POST['txtNumberQuestion'];
       $the_id = $_POST['txtExamTopicId'];
       if ($numer_questions =="" || empty($numer_questions)) {
          echo '<h4 class="text-danger">Vui lòng nhập nội dung!</h4>';
       } else {
         if ($the_id>0) {
           $query_update = "UPDATE exam_topics SET topic_id='{$topic_id}', exam_id={$exam_id}, number_questions={$numer_questions} WHERE id={$the_id}";
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: exam_topic_list.php?exam_id=" . $exam_id);
           }
         } else {
           // insert
           $query_insert = "INSERT INTO exam_topics(topic_id,exam_id,number_questions) VALUES({$topic_id},{$exam_id},{$numer_questions})";
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
             header("Location: exam_topic_list.php?exam_id=" . $exam_id);
           }
         }
       }
  }
}

function Load_Exam_Topic($connection,$the_id) {
   $query_select = "SELECT * FROM exam_topics WHERE id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   return $rs;
}

function Load_All_Exam_Topic_By_Exam($connection,$the_exam_id) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT A.*, B.topic_name FROM exam_topics A inner join topics B on A.topic_id = B.topic_id  WHERE A.exam_id=" . $the_exam_id  ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}




function Create_Sales($connection, $sale_code,$sale_name, $bu_id, $bu_sub, $is_active) {
  $query_insert = "INSERT INTO salesman(sale_code,sale_name,bu_id, bu_sub, is_active) 
  VALUES('{$sale_code}','{$sale_name}',{$bu_id},'{$bu_sub}',{$is_active})";
  //echo $query_insert;
  $cmdCreate = mysqli_query($connection,$query_insert);
  if (!$cmdCreate) {
    die("Insert failed" . mysqli_error($connection));
  } 
}

function Inactive_Sales($connection, $sale_code) {
  $query_update = "UPDATE salesman SET is_active = 0 WHERE sale_code ='{$sale_code}'";
  $cmdUpdate = mysqli_query($connection,$query_update);
  if (!$cmdUpdate) {
    die("Update failed" . mysqli_error($connection));
  }
}

function Active_Sales($connection, $sale_code) {
  $query_update = "UPDATE salesman SET is_active = 1 WHERE sale_code ='{$sale_code}'";
  $cmdUpdate = mysqli_query($connection,$query_update);
  if (!$cmdUpdate) {
    die("Update failed" . mysqli_error($connection));
  }
}

function Active_Sales_With_BU($connection, $sale_code, $bu_sub, $bu_id) {
  $query_update = "UPDATE salesman SET is_active = 1,bu_sub='{$bu_sub}', bu_id={$bu_id}  WHERE sale_code ='{$sale_code}'";
  $cmdUpdate = mysqli_query($connection,$query_update);
  if (!$cmdUpdate) {
    die("Update failed" . mysqli_error($connection));
  }
}

function Check_Exist_Sales($connection, $sale_code) {
  $query_select = "SELECT *  FROM salesman WHERE sale_code ='{$sale_code}'";
  $cmdSelect = mysqli_query($connection,$query_select);
  $countRow = mysqli_num_rows($cmdSelect);
  return $countRow > 0 ? 1 : 0;
}

function ConvertBU_Name_To_ID($buname){
  switch ($buname) {
    case "BU Bình Định" : return 2;
    case "BU Đồng Nai" : return 1;
    case "BU Hưng Yên" : return 3;
    case "BU Hà Nam" : return 4;
    case "BU Long An" : return 5;
    case "BU Myanmar" : return 6;
    case "BU Sản phẩm thú y" : return 7;
    case "BU Thủy sản" : return 8;
    case "BU Vĩnh Long" : return 9;
    case "Kinh doanh thức ăn chăn nuôi khác" : return 10;
  }
}



function Load_All_Sales($connection) {
  $query_select = "SELECT A.*, B.bu_name  FROM salesman A inner join bu B on A.bu_id = B.bu_id  ";
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}


function Find_Sales($connection, $sale_code, $bu_id) {
  $query_select = "SELECT *  FROM salesman WHERE sale_code ='{$sale_code}' and bu_id={$bu_id}";
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}

function Find_Sales_SuperAdmin($connection, $sale_code) {
  $query_select = "SELECT *  FROM salesman WHERE sale_code ='{$sale_code}'";
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}

function Find_BUSales_SuperAdmin($connection, $bu_id) {
  $query_select = "SELECT A.*, B.bu_name FROM salesman A inner join bu B on A.bu_id = B.bu_id WHERE A.bu_id={$bu_id} ";
 // echo $query_select ;
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}

function Disable_All_Sales($connection, $bu_id) {
  $query_update = "Update salesman SET is_active = 0 where bu_id={$bu_id} ";
  $cmdUpdate = mysqli_query($connection,$query_update);
 
}





// For Front-End //



function Check_Sales_Cheat($connection, $sale_id, $exam_id) {
   $query_select = "SELECT id FROM  exam_result WHERE sale_id={$sale_id} and exam_id={$exam_id}" ;
 // echo $query_select;
  $cmdCheck = mysqli_query($connection,$query_select);
  $countRow = mysqli_num_rows($cmdCheck);
  return $countRow > 0 ? 1 : 0;
}


function Result_Sales_Reset($connection, $sale_id, $exam_id) {

   $query_select = "Delete from exam_result WHERE sale_id={$sale_id} and exam_id={$exam_id}" ;
  //echo $query_select;
  $cmdReset = mysqli_query($connection,$query_select);

   $query_select = "Delete from exam_logs WHERE sale_id={$sale_id} and exam_id={$exam_id}" ;
  //echo $query_select;
  $cmdReset = mysqli_query($connection,$query_select);
 
  header("Location: report_detail.php?id={$exam_id}");
}




function Latest_Exam_Per_BU_With_Total_Question($connection,$bu_id) {
  $query_select = "SELECT E.exam_id,E.exam_date, sum(number_questions) as total_questions FROM exams E INNER JOIN exam_topics ET on E.exam_id = ET.exam_id WHERE E.exam_active = 1 and E.bu_id={$bu_id} GROUP BY  E.exam_id,E.exam_date ORDER BY E.exam_date desc Limit 0,1";
  //echo $query_select ;
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}


function Load_Topic_By_Exam($connection,$exam_id) {
  $query_select = "SELECT T.topic_id, topic_name, number_questions FROM exam_topics ET INNER JOIN topics T on ET.topic_id = T.topic_id WHERE T.topic_active = 1 and ET.exam_id={$exam_id} ORDER BY T.topic_id ASC";
  //echo $query_select;
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}

function Load_Question_By_Topic_And_Number($connection, $topic_id, $quantity) {
  $query_select = "SELECT * FROM questions WHERE is_active =1 and topic_id ={$topic_id} ORDER BY rand() LIMIT {$quantity}";
  //echo $query_select;
  $cmdSelect = mysqli_query($connection,$query_select);
  return $cmdSelect;
}


function Insert_Answer($connection, $sale_id,$exam_id, $question_id, $choice_id, $is_right) {
  $query_insert = "INSERT INTO  exam_result (sale_id,exam_id, question_id, choice_id, is_right) VALUES({$sale_id},{$exam_id},{$question_id},{$choice_id}, {$is_right})";
  $cmdCreate = mysqli_query($connection,$query_insert);
  if (!$cmdCreate) {
    die("Insert failed" . mysqli_error($connection));
  }
}

function Edit_Answer($connection, $sale_id,$exam_id, $question_id, $choice_id, $is_right) {
  $query_edit = "UPDATE  exam_result SET choice_id={$choice_id}, is_right={$is_right} WHERE sale_id={$sale_id} and exam_id={$exam_id} and question_id={$question_id}";
  $cmdEdit = mysqli_query($connection,$query_edit);
  if (!$cmdEdit) {
    die("Insert failed" . mysqli_error($connection));
  }
}

function Check_Exist_Answer($connection, $sale_id,$exam_id, $question_id) {
  $query_select = "SELECT id FROM  exam_result WHERE sale_id={$sale_id} and exam_id={$exam_id} and question_id={$question_id}";
//  echo $query_select;
  $cmdCheck = mysqli_query($connection,$query_select);
  $countRow = mysqli_num_rows($cmdCheck);
  return $countRow > 0 ? 1 : 0;
}

function Selected_Answer($connection, $sale_id,$exam_id, $question_id) {
  $query_select = "SELECT choice_id FROM  exam_result WHERE sale_id={$sale_id} and exam_id={$exam_id} and question_id={$question_id}";
  //echo $query_select;
  $cmdCheck = mysqli_query($connection,$query_select);
  $value = mysqli_fetch_object($cmdCheck);
  return $value->choice_id > 0 ? $value->choice_id : 0 ;
}


function Count_Right_Answer($connection, $sale_id, $exam_id) {
  $query_select = "SELECT count(id) as total FROM  exam_result WHERE sale_id={$sale_id} and exam_id={$exam_id} and is_right=1";
//  echo $query_select;
  $cmdCount = mysqli_query($connection,$query_select);

  return $cmdCount;
}


// Admin User

// AdminUser Function
function Delete_AdminUser($connection,$the_id) {
    $query_delete = "DELETE FROM supermans WHERE id=" . $the_id . " and id <> 1 and role_name <> 'superadmin'";
    $cmd_delete = mysqli_query($connection, $query_delete);
    if (!$cmd_delete) {
      die('Delete failed' . mysqli_error($connection));
    } else {
      header("Location: admin_list.php");
    }
}
function Create_Update_AdminUser($connection) {
  if(isset($_POST["submit"])) {
       $_account = $_POST['txtAdminAccount'];
       $_password = md5($_POST['txtAdminPassword']);
       $_resetpassword = $_POST['txtResetPassword'];
       //$_password = password_hash($_POST['txtAdminPassword'], PASSWORD_BCRYPT, array('cost' => 12));
       $_active = $_POST['chkAdminActive']==1? 1 : 0;
       $_bu_id = $_POST['cboBU'];
       $the_id = $_POST['txtAdminId'];
       if ($_account =="" || empty($_account)) {
          echo '<h4 class="text-danger">Vui lòng nhập tên tài khoản!</h4>';
       } else {
         if ($the_id>0) {
          // xem co nhap value cho password khong, neu co thi doi
          if ($_resetpassword =="") {
                $query_update = "UPDATE supermans SET account='{$_account}',  bu_id = {$_bu_id}, active={$_active} WHERE id={$the_id}";
          } else {
                $_newpassword = md5($_resetpassword);
                $query_update = "UPDATE supermans SET account='{$_account}', password='{$_newpassword}',bu_id = {$_bu_id}, active={$_active} WHERE id={$the_id}";
          }
          // khong cho doi pass nha
         
           $cmdUpdate = mysqli_query($connection,$query_update);
           if (!$cmdUpdate) {
             die("Update failed" . mysqli_error($connection));
           } else {
              header("Location: admin_list.php");
           }
         } else {
           // insert
           $query_insert = "INSERT INTO supermans(account,password, active, bu_id, role_name) VALUES('{$_account}','{$_password}',{$_active},{$_bu_id}, 'admin')";
           $cmdCreate = mysqli_query($connection,$query_insert);
           if (!$cmdCreate) {
             die("Insert failed" . mysqli_error($connection));
           } else {
                header("Location: admin_list.php");
           }
         }
       }
  }
}

function Load_AdminUser($connection,$the_id) {
   $query_select = "SELECT * FROM supermans WHERE id <> 1 and id=" . $the_id;
   $rs = mysqli_query($connection, $query_select);
   return $rs;
}

function Load_All_AdminUser($connection) {
  //display cau tra loi cua cau hoi duoc chon
  $query_select = "SELECT A.*, B.bu_name FROM supermans A inner join bu B on A.bu_id = B.bu_id WHERE A.id <> 1 " ;
  $rs = mysqli_query($connection,$query_select);
  return $rs;
}

function checkAdminLogin ($connection, $username, $password) {
  $password_md5 = md5($password);
  $query_select = "SELECT * FROM supermans A WHERE account='{$username}' and password='{$password_md5}' and active =1  " ;
  //echo $query_select;
  $rs = mysqli_query($connection,$query_select);

  return $rs;
}


function checkOldPassword ($connection, $userid, $password_enter) {
  $query_select = "SELECT password FROM supermans A WHERE id={$userid}  and active =1  " ;
  
  $rs = mysqli_query($connection,$query_select);

  while ($row = mysqli_fetch_array($rs)) {
       $old_pass = $row["password"];
  }

  if ($old_pass ==  md5($password_enter)) {
    return true;
  } else {
    return false;
  }
  
}

function changePassword($connection, $userid, $password_new) {
   $md5_pass = md5($password_new);
   $query_update = "Update supermans SET password='{$md5_pass}' where id={$userid} ";
  // echo $query_update;
   $cmdUpdate = mysqli_query($connection,$query_update);
}


// 20 cau hoi thoi, dung 14 cau la ok
function Load_Report($connection, $bu_id=0) {
  if ($bu_id==0) {
    $query_select = 'Select EX.exam_id,bu.bu_name,exam_date,exam_desc,exam_passed,count(sale_id) as total ,  count(if(right_answer * 100 / exam_total >=exam_passed , 1, NULL)) as DAT,  count(if(right_answer * 100 / exam_total < exam_passed , 1, NULL)) as KHONGDAT from exams EX inner join

(SELECT DISTINCT(sale_id) as sale_id, exam_id, count(if(is_right=1,1,NULL)) as right_answer
from exam_result
GROUP by sale_id, exam_id ) as KetQua
on EX.exam_id = KetQua.exam_id inner join bu on EX.bu_id = bu.bu_id

GROUP BY EX.exam_id, bu.bu_name, exam_date ORDER by exam_date DESC ';
  } else {
    $query_select = 'Select EX.exam_id,bu.bu_name,exam_date,exam_desc,exam_passed,exam_total,count(sale_id) as total ,  count(if(right_answer * 100 / exam_total >=exam_passed , 1, NULL)) as DAT,  count(if(right_answer * 100 / exam_total < exam_passed , 1, NULL)) as KHONGDAT from exams EX inner join

(SELECT DISTINCT(sale_id) as sale_id, exam_id, count(if(is_right=1,1,NULL)) as right_answer
from exam_result
GROUP by sale_id, exam_id ) as KetQua
on EX.exam_id = KetQua.exam_id inner join bu on EX.bu_id = bu.bu_id
where EX.bu_id = ' . $bu_id . ' GROUP BY EX.exam_id,bu.bu_name, exam_date ORDER by exam_date DESC' ;
  }

   //echo $query_select;

    $rs = mysqli_query($connection,$query_select);

    return $rs ;
}

//20 cau hoi nha

function Load_Report_Detail($connection, $bu_id=0, $exam_id) {
  if ($bu_id==0) {
    $query_select = 'SELECT DISTINCT(exam_result.sale_id) as sale_id, sale_code,sale_name,count(if(is_right=1,1,NULL)) as right_answer, count(if(is_right=0,1,NULL)) as wrong_answer, exam_total,exam_passed from exam_result inner join salesman on exam_result.sale_id = salesman.sale_id inner join exams on exam_result.exam_id = exams.exam_id  WHERE exam_result.exam_id = '. $exam_id . ' GROUP by salesman.sale_id, sale_code,sale_name ORDER BY right_answer DESC ';
  } else {
    $query_select = 'SELECT DISTINCT(exam_result.sale_id) as sale_id, sale_code,sale_name,count(if(is_right=1,1,NULL)) as right_answer, count(if(is_right=0,1,NULL)) as wrong_answer,exam_total, exam_passed
from exam_result inner join salesman on exam_result.sale_id = salesman.sale_id inner join exams on exam_result.exam_id = exams.exam_id WHERE exam_result.exam_id = ' . $exam_id . ' and exams.bu_id = ' . $bu_id . ' GROUP by salesman.sale_id, sale_code,sale_name ORDER BY right_answer DESC' ;
  }

   //echo $query_select;

    $rs = mysqli_query($connection,$query_select);

    return $rs ;
}



function Load_Most_Wrong($connection) {
   $query_select = 'SELECT A.question_id, B.question, C.topic_name, count(if(is_right=0,1,NULL)) as wrong_answer FROM `exam_result` A inner join questions B on A.question_id = B.question_id inner join topics C on B.topic_id = C.topic_id  WHERE A.is_right =0 group by A.question_id, C.topic_name order by wrong_answer desc, topic_name ASC limit 0,50';
   
   //echo $query_select;

    $rs = mysqli_query($connection,$query_select);

    return $rs ;
}



function Load_Topic_Report_Per_BU($connection, $bu_id, $exam_id) {
    $query_select = 'Select T.topic_id,T.topic_name, ER.exam_id, EX.exam_date, B.bu_name, count(if(is_right =1,1,null)) as total_right, count(if(is_right =0,1,null)) as total_wrong from exam_result ER inner join questions Q on ER.question_id = Q.question_id inner join topics T on Q.topic_id = T.topic_id inner join exams EX on ER.exam_id = EX.exam_id inner join bu B on EX.bu_id = B.bu_id where EX.bu_id = ' . $bu_id . ' and ER.exam_id = ' . $exam_id . ' group by T.topic_id, T.topic_name, ER.exam_id, EX.exam_date,B.bu_name ' ;

    //echo $query_select;

    $rs = mysqli_query($connection,$query_select);

    return $rs ;

}

function Load_All_Exam_Per_BU($connection, $bu_id) {
   $query_select = 'Select exam_id,exam_date from exams inner join bu on exams.bu_id = bu.bu_id where exams.bu_id =' . $bu_id . ' order by exam_id desc' ;

   //echo $query_select;

   $rs = mysqli_query($connection,$query_select);

   return $rs ;
}


function Load_Topic_Report_Per_BU_Admin($connection, $bu_id) {
    $query_select = 'Select T.topic_id,T.topic_name, ER.exam_id, EX.exam_date, B.bu_name, count(if(is_right =1,1,null)) as total_right, count(if(is_right =0,1,null)) as total_wrong from exam_result ER inner join questions Q on ER.question_id = Q.question_id inner join topics T on Q.topic_id = T.topic_id inner join exams EX on ER.exam_id = EX.exam_id inner join bu B on EX.bu_id = B.bu_id where EX.bu_id = ' . $bu_id . ' group by T.topic_id, T.topic_name, ER.exam_id, EX.exam_date,B.bu_name ' ;

    //echo $query_select;

    $rs = mysqli_query($connection,$query_select);

    return $rs ;

}

?>
