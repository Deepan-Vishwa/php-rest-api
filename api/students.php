<?php
//Hiring Assignment for Freastal Technologies
//By Deepan Vishwa.S.R
//Email: srdeepansr@gmail.com
//Phone: 7550188018



// Step 1: Connecting To the DataBase
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'student-record';// Enter Your DataBase Name
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$request=$_SERVER["REQUEST_METHOD"];// get The Rquest Type


switch($request){
    case 'GET':// If The Request is GET
        if(!empty($_GET["student_id"]))
        {
            $student_id=intval($_GET["student_id"]);
            display_students($student_id);
        }
        else
        {
            display_students();
        }
        break;
        case 'POST':// If The Request is POST
			insert_student();
			break;
		case 'PUT':// If The Request is PUT
			$student_id=intval($_GET["student_id"]);
			update_student($student_id);
			break;
		case 'DELETE':// If The Request is DELETE
			$student_id=intval($_GET["student_id"]);
			delete_student($student_id);
			break;
        default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;

}
// To Display the Students Record
function display_students($student_id=0)
	{
		global $conn;
		$query="SELECT * FROM students";
		if($student_id != 0)
		{
			$query.=" WHERE id=".$student_id." LIMIT 1";
		}
		$response=array();
		$result=mysqli_query($conn, $query);
		while($row=mysqli_fetch_array($result))
		{
            $res["ID"]=$row["ID"];
            $res["Name"]=$row["Name"];
            $res["Age"]=$row["age"];
            $res["Std"]=$row["Std"];

            array_push($response,$res);
		}
        header('Content-Type: application/json');
        
        echo json_encode($response);
      
    
    }

    //To Insert a New Student Record
    function insert_student()
	{
		global $conn;
		$student_name=$_POST["student_name"];
		$student_age=$_POST["student_age"];
		$student_std=$_POST["student_std"];
		$query="INSERT INTO `students`(`Name`, `age`, `Std`) VALUES ('$student_name',$student_age,$student_std)";
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Student Record Inserted Successfully.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
                'status_message' =>'Student Record Insertion Unsuccessfully.',
                
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response['status_message']);
    }
    //To Update a Student Record
    function update_student($student_id)
	{
		global $conn;
        parse_str(file_get_contents("php://input"),$_PUT);
		$student_name=$_PUT["student_name"];
		$student_age=$_PUT["student_age"];
		$student_std=$_PUT["student_std"];
		$query="UPDATE `students` SET `Name`='$student_name',`age`=$student_age,`Std`=$student_std WHERE `ID`= $student_id";
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Student '.$student_id.' Record Updated Successfully.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
                'status_message' =>'Student '.$student_id.' Record Updation Failed.',
                'sname' => $student_name
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response['status_message']);
    }
    
    // To Delete a Student Record
    function delete_student($student_id)
	{
		global $conn;
		$query="DELETE FROM `students` WHERE ID = $student_id";
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Student '.$student_id.' Deleted Successfully.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
                'status_message' =>'Student '.$student_id.' Deletion Unsuccessfully.',
                
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response['status_message']);
	}
    

?>


