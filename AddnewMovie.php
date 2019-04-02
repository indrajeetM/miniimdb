<?php 
    include 'common_header.php';
    include 'db_connection.php';

   

    if(isset($_POST['submit']))
    {
    	$movie_title 			= str_replace("'"," ",$_POST['Movie_title']);
    	$movie_desc 			= str_replace("'"," ",$_POST['Movie_desc']);
    	$selected_genre_val 	= str_replace("'"," ",$_POST['selected_genre_val']);
    	$cat_movie_language 	= str_replace("'"," ",$_POST['Movie_language']);
    	$Movie_id 		 		= str_replace("'"," ",$_POST['Movie_id']);
    	
    	$movie_length 			= $_POST['Movie_length'];
    	$movie_rel_date 		= date('Y-m-d',strtotime($_POST['Movie_rel_date']));

    	$target_dir = "img/";
    	$dates=date('d-m-YH-i-s');

    	$featured_image     		= "movie_".$movie_title.$dates.$_FILES["Movie_image"]['name'];
        $image_name         		= strtolower($featured_image);
        $new_image_name 			=str_replace(array("'", " ", "&quot;",":",",","-"), "_", htmlspecialchars($image_name));
        $featured_image_location    = $_FILES['Movie_image']['tmp_name'];

        $up_count = 0;
      	if(move_uploaded_file($featured_image_location,$target_dir.$new_image_name))
      	{
	        $up_count++;
      	}

      	//inserting into main movies table
      	$insert_sql = "INSERT INTO movies ( title, description, featured_image, movie_length, movie_rel_date) VALUES ('$movie_title', '$movie_desc', '$target_dir$new_image_name', '$movie_length', '$movie_rel_date')";
      	$insert_sql_result = mysqli_query($conn,$insert_sql);

      	//inserting into relation using language category id and movie id
      	$sql_insert_relation 		= "INSERT INTO relationship (category_id, movie_id) VALUES ('$cat_movie_language','$Movie_id')";
      	$sql_insert_relation_res 	= mysqli_query($conn,$sql_insert_relation);


      	//now we insert all genre selected by user,
      	//we have list of id's with comma sepearted genre selected
      	//now we loop throught it to insert

      	$genre_array = explode(',', $selected_genre_val);

      	//create multiple insert in loop using one single query
      	$multiple_insert = "";
      	foreach ($genre_array as $key => $value) {
      		//we need to insert each value with movie id
      		$multiple_insert .= "INSERT INTO relationship (category_id, movie_id) VALUES ('$value','$Movie_id');";
      	}

      	
      	$multiple_insert_res = mysqli_multi_query($conn,$multiple_insert);
      	if($insert_sql_result)
      	{
      		$new_movie_id = $Movie_id+1;

      		$base_path = Base_url;
      		echo '<script>';
      		echo 'alert("Data Inserted");';
      		echo 'javascript:window.location="'.$base_path.'AddnewMovie.php"';
      		echo '</script>';
      	}
      	else{
      		$new_movie_id = $Movie_id;
			echo '<script>';
      		echo 'alert("Failed To Insert Data '.$insert_sql.'");window.reload();';
      		echo '</script>';
      	}

      	$_POST['submit'] = '';
    }else{
    	$new_movie_id = 0;
		$sql_new_movie_id 			= "SELECT max(id) from movies";
		$sql_new_movie_id_result	= mysqli_query($conn,$sql_new_movie_id);
		$sql_new_movie_id_row		= mysqli_fetch_array($sql_new_movie_id_result);
		if(isset($sql_new_movie_id_row['max(id)']) && !empty($sql_new_movie_id_row['max(id)']))
		{
			$new_movie_id = $sql_new_movie_id_row['max(id)']+1;
		}
		else{
			$new_movie_id = 1;
		}
		
    }
?>

<!-- Adding new Movies to Database -->
<main style="margin-top: 6em">
		<p class="h1 text-center mb-4">Add New Movie</p>
	<div class="container d-flex justify-content-center">
		<form action="AddnewMovie.php" method="POST" enctype="multipart/form-data" autocomplete="off" onsubmit="return validaion();">
				
			<div class="md-form">
		        <input type="text" id="Movie_id" name="Movie_id" value="<?php echo $new_movie_id; ?>" class="form-control disabled" readonly="" required="">
		        <label for="Movie_id">Movie Id</label>
		    </div>

			<div class="md-form">
		        <input type="text" id="Movie_title" name="Movie_title" class="form-control" required="">
		        <label for="Movie_title">Movie Title</label>
		    </div>
		
		
			<div class="md-form">
		        <textarea type="text" id="Movie_desc" name="Movie_desc" class=" md-textarea form-control"  required=""></textarea>
		        <label for="Movie_desc">Movie Description</label>
		    </div>					
		
		
			<div class="md-form">
		        Movie Image
		        <input type="file" id="Movie_image" name="Movie_image" class="form-control"  required="">
		    </div>					
		
		
			<div class="md-form">
		        <input type="text" id="Movie_length" name="Movie_length" class="form-control" required="">
		        <label for="Movie_length">Movie Length</label>
		    </div>					
		
		
			<div class="md-form">
				Release Date
		        <input type="date" id="Movie_rel_date" name="Movie_rel_date" class="form-control" required="">
		        <!-- <label for="Movie_rel_date">Movie Release Date</label> -->
		    </div>	
		
			<div class="md-form">
		        Select Language
		        <select id="Movie_language" name="Movie_language" class="form-control" required="">
		        	<?php

		        		$sql_language 			= "SELECT * FROM category WHERE type='Language' ORDER BY value ASC";
		        		$sql_language_result 	= mysqli_query($conn,$sql_language);
		        		if($sql_language_result->num_rows>=1)
		        		{
		        			echo '<option value="select">Select One</option>';
		        			while ($row = mysqli_fetch_array($sql_language_result)) {
		        				echo '<option value="'.$row['id'].'">'.$row['value'].'</option>';
		        			}
		        		}else{
		        			echo '<option value="eng">Default English</option>';
		        		} 
	        	 	?>
		        	
		        </select>
		    </div>	
			<div class="md-form">
		        Select Genre
		        <select id="Movie_genre" name="Movie_genre" class="form-control" required="" onchange="add_genre()">
		        	<?php

		        		$sql_language 			= "SELECT * FROM category WHERE type='Genre' ORDER BY value ASC";
		        		$sql_language_result 	= mysqli_query($conn,$sql_language);
		        		if($sql_language_result->num_rows>=1)
		        		{
		        			echo '<option value="select">Select One/More</option>';
		        			while ($row = mysqli_fetch_array($sql_language_result)) {
		        				echo '<option value="'.$row['id'].'">'.$row['value'].'</option>';
		        			}
		        		}else{
		        			echo '<option value="eng">Default Drama</option>';
		        		} 
	        	 	?>
		        </select>
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
		Movie_language 	= $('#Movie_language').val();
		Movie_genre 	= $('#Movie_genre').val();

		if(Movie_language=='select'){
			alert("Please Select Language");
			return false;
		}
		else if(Movie_genre=='select'){
			alert("Please Select One/More Genre");
			return false;
		}
		else{
			return true;
		}
	}
	Genre_array = new Array();
	function add_genre() {
		
		Movie_genre 		= $('#Movie_genre').val();
		if(Movie_genre=='select')
		{

		}else{

			Movie_genre_text 	= $("#Movie_genre option:selected").text();
			
			console.log("check val="+Movie_genre);
			console.log("check="+Genre_array.indexOf(Movie_genre));


			if(Genre_array.indexOf(Movie_genre)>=0)
			{
				alert("Already Added");
				return;
			}
			else{
				Genre_array.push(Movie_genre);

				var dummy = '<span class="badge badge-info badge-pill py-2 mt-2">'+Movie_genre_text+'</span><br>';
				$('#selected_genre').prepend(dummy);
			}
			
			console.log("Genre_array"+Genre_array);
			$('#selected_genre_val').val(Genre_array);
	
		}
	}
</script>

<?php
	mysqli_close($conn);
 ?>