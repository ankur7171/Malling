<?php
header("Content-Type: text/html; charset=ISO-8859-1");
//index.php
ini_set('max_execution_time', 0); // 0 = Unlimited
ini_set("display_errors",1);
$connect = new PDO("mysql:host=localhost;dbname=gemc2020_webinar", "gemc2020_webinar", "4?HJ1wb+TkR3");

$query = "SELECT * FROM user where lname ='0' ORDER BY id";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
//echo "mail";
if(isset($_POST['fname1']))
	//echo ($_POST['fname1']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Send Bulk Email</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<br />
		<div class="container">

			<h3 align="center">Send Bulk Email </h3>
			<br />
			
			
<!-- Method can be set as POST for hiding values in URL-->
<html>
<head>

<link rel="stylesheet" type="text/css" href="style.css">
<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
</style>
</head>
<body>
<script src="//cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">
document.addEventListener( 'DOMContentLoaded',function()
{
 CKEDITOR.replace( 'text_editor' );	
});
</script>
<div id="main">

<div id="login">

<hr/>
<form action="" method="post">

<label> Email Template  :</label>
<textarea name="box_msg" id ="text_editor" rows="10" cols="30" class="box_msg"></textarea>
<br>
<input type="submit" value=" Submit " name="submit"/><br />
</form>
</div>
<!-- Right side div -->
<br> </br>

</div>
<?php
if(isset($_POST["submit"])){
$servername = "localhost";
$username = "gemc2020_webinar";
$password = "4?HJ1wb+TkR3";
$dbname =  "gemc2020_webinar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$sql = "update mail_template set template = '".$_POST["box_msg"]."' where id ='1'";
//echo $sql;
if ($conn->query($sql) === TRUE) {
echo "<script type= 'text/javascript'>alert('New record created successfully');</script>";
} else {
echo "<script type= 'text/javascript'>alert('Error: " . $sql . "<br>" . $conn->error."');</script>";
}

$conn->close();
}
?>
</body>
</html>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<tr>
						<th>Id</th>
						<th>Name</th>
						<th>Email</th>
						<th>Subject</th>
						<th><input type="checkbox" id="checkAll" > Check All</th>
						<th>Action</th>
					</tr>
				<?php
				$count = 0;
				foreach($result as $row)
				{
					$count = $count + 1;
					echo '
					<tr>
						<td>'.$row["id"].'</td>
						<td>'.$row["username"].'</td>
						<td>'.$row["email"].'</td>
						<td>'.$row["fname"].'</td>
						<td>
							<input type="checkbox" name="single_select" class="single_select" data-id="'.$row["id"].'" data-email="'.$row["email"].'" data-name="'.$row["username"].'" data-fname="'.$row["fname"].'" />
						</td>
						<td>
						<button type="button" name="email_button" class="btn btn-info btn-xs email_button" id="'.$count.'" data-id="'.$row["id"].'" data-email="'.$row["email"].'" data-name="'.$row["username"].'" data-fname="'.$row["fname"].'" data-action="single">Send Single</button>
						</td>
					</tr>
					';
				}
				?>
				
					<tr>
						<td colspan="5"></td>
						<td><button type="button" onclick="setTimeout(myFunction, 350*12000);" name="bulk_email" class="btn btn-info email_button" id="bulk_email" data-action="bulk">Send Bulk</button></td></td>
					</tr>
					<center><p><b>*Please Delete All Mail Id After Sending Mail.*</b></p></center>
				</table>
				
				

<script>
function myFunction() {
  alert('Mail Are send, Please delete All Mail Id.');
  window.location.reload();
}
</script>
				
				<?php
$dbc = mysqli_connect('localhost','gemc2020_webinar','4?HJ1wb+TkR3','gemc2020_webinar') or die('Error connecting to MySQL server.'); 
if(isset($_POST['submit_button']))
{
    mysqli_query($dbc, 'TRUNCATE TABLE `user`');
    //header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>
<form method="post" action="">
    <input name="submit_button" class ="button" type="submit" value="Delete All Mail id" />
</form>
			</div>
		</div>
	</body>
</html>

<script>
$('#checkAll').click(function () {    
     $('input:checkbox').prop('checked', this.checked);    
 });

$(document).ready(function(){
	$('.email_button').click(function(){
		$(this).attr('disabled', 'disabled');
		var id  = $(this).attr("id");
		var action = $(this).data("action");
		var email_data = [];
		if(action == 'single')
		{
			email_data.push({
				id: $(this).data("id"),
				email: $(this).data("email"),
				name: $(this).data("name"),
				fname: $(this).data("fname")
			});
		}
		else
		{
			$('.single_select').each(function(){
				if($(this).prop("checked") == true)
				{
					email_data.push({
						id: $(this).data("id"),
						email: $(this).data("email"),
						name: $(this).data('name'),
						fname: $(this).data("fname")
					});
				} 
			});
		}

		$.ajax({
			url:"send_mail.php",
			method:"POST",
			data:{email_data:email_data},
			beforeSend:function(){
				$('#'+id).html('Sending...');
				$('#'+id).addClass('btn-danger');
			},
			success:function(data){
				if(data == 'done')
				{
					$('#'+id).text('Success');
					$('#'+id).removeClass('btn-danger');
					$('#'+id).removeClass('btn-info');
					$('#'+id).addClass('btn-success');
				}
				else
    {
     $('#'+id).text(data);
    }
    $('#'+id).attr('disabled', false);
   }
		})

	});
});
</script>





