<?php 
    include 'common_header.php';
    include 'db_connection.php';

    if(isset($_POST['submit']))
    {
    	
    	$cat_val 	= $_POST['cat_val'];
    	$cat_type 	= $_POST['cat_type'];
    	$cat_id 	= $_POST['cat_id'];


    	
      	
      	$sql_insert_cat 		= "INSERT INTO category(type, value) VALUES ('$cat_type','$cat_val')";
      	$sql_insert_cat_res 	= mysqli_query($conn,$sql_insert_cat);


      	if($sql_insert_cat_res)
      	{
      		$new_cat_id = $cat_id+1;
      		$base_path = Base_url;
      		echo '<script>';
      		echo 'alert("Data Inserted");';
      		echo 'javascript:window.location="'.$base_path.'AddnewCategory.php"';
      		echo '</script>';
      	}
      	else{
      		$new_cat_id = $cat_id;
			echo '<script>';
      		echo 'alert("Failed To Insert Data '.$insert_sql.'");window.reload();';
      		echo '</script>';
      	}

      	$_POST['submit'] = '';
    }else{
    	$new_cat_id = 0;
		$sql_new_cat_id 		= "SELECT max(id) from category";
		$sql_new_cat_id_result	= mysqli_query($conn,$sql_new_cat_id);
		$sql_new_cat_id_row		= mysqli_fetch_array($sql_new_cat_id_result);
		if(isset($sql_new_cat_id_row['max(id)']) && !empty($sql_new_cat_id_row['max(id)']))
		{
			$new_cat_id = $sql_new_cat_id_row['max(id)']+1;
		}
		else{
			$new_cat_id = 1;
		}
		mysqli_close($conn);
    }
?>

<!-- Adding new Movies to Database -->
<main style="margin-top: 6em">
		<p class="h1 text-center mb-4">Add New Category</p>
	<div class="container d-flex justify-content-center">
		<form action="AddnewCategory.php" method="POST" enctype="multipart/form-data" autocomplete="off" onsubmit="return validaion();">
				
			<div class="md-form">
		        <input type="text" id="cat_id" name="cat_id" value="<?php echo $new_cat_id; ?>" class="form-control disabled" readonly="" required="">
		        <label for="cat_id">Movie Id</label>
		    </div>

			<div class="md-form">
		       Select Type
		        <select id="cat_type" name="cat_type" class="form-control" required="">
		        	<option value="select">Select One</option>
		        	<option value="Language">Language</option>
		        	<option value="Genre">Genre</option>
		        </select>
		    </div>
		
		
			<div class="md-form">
		         <input type="text" id="cat_val" name="cat_val" class="form-control" required="">
		        <label for="cat_val">Category Value</label>
		    </div>					
		
		    
		    <div class="md-form">
		    	<div id="selected_genre"></div>
		    </div>				
			<input type="hidden" name="selected_genre_val" id="selected_genre_val">
			<button class="btn btn-success" type="submit" name="submit" id="submit">Save</button>
		</form>
	</div>

</main>
<?php 
    include 'footer.php';
?>

<script type="text/javascript">

	function validaion(){
		cat_type 	= $('#cat_type').val();

		if(cat_type=='select'){
			alert("Please Select Type");
			return false;
		}
		else{
			return true;
		}
	}
</script>