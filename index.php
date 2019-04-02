<?php 
    include 'db_connection.php';
		
	if(isset($_POST['filter']))
	{
		$movie_language =$_POST['Movie_language'];
		$movie_genre 	=$_POST['Movie_genre'];
		$sort_length 	=$_POST['sort_length'];
		$sort_date 		=$_POST['sort_date'];

		$sql_fetch = '';
		if($movie_language=='all' && $movie_genre=='all')
		{
			$sql_fetch = "SELECT * FROM relationship GROUP BY movie_id";
			$sql_fetch_res = mysqli_query($conn,$sql_fetch);

			
		    $sql_movie_list 	= "SELECT count(id) from movies";
		    $sql_movie_list_res	= mysqli_query($conn,$sql_movie_list);	
		    $sql_movie_list_row	= mysqli_fetch_array($sql_movie_list_res);
		    $total_rows			= $sql_movie_list_row['count(id)'];

		    $per_page_count  	= 10;
		    $last_page			= ceil($total_rows/$per_page_count);

		    if($last_page<1)
		    {
		    	$last_page=1;
		    }

		    $current_page_number = 1;
		    if(isset($_POST['page_count']))
		    {
		    	$current_page_number = $_POST['page_count'];
		    }

		    if($current_page_number<1)
		    {
		    	$current_page_number = 1;
		    }else if($current_page_number>$last_page)
		    {
		    	$current_page_number = $last_page;
		    }


		    $set_limit = "LIMIT ".($current_page_number - 1)*$per_page_count.','.$per_page_count;

			$sql_fetch = "SELECT * FROM relationship GROUP BY movie_id $set_limit";
			$sql_fetch_res = mysqli_query($conn,$sql_fetch);

			$msg = "Page $current_page_number of $last_page";

    		$page_controls = '';

    		$page_controls = '';

		    if($last_page !=1)
		    {
		    	if($current_page_number > 1)
		    	{
		    		$pervious = $current_page_number - 1;
		    		$page_controls = '<a class="h4 text-center" onclick="CheckPage('.$pervious.');">&nbsp;&nbsp; Pervious &nbsp;&nbsp;</a>';

		    		for ($i=$current_page_number-4; $i <$current_page_number ; $i++) { 
		    			if($i>0)
		    			{
							$page_controls .='&nbsp;&nbsp;<a class="h4 text-center" onclick="CheckPage('.$i.');">'.$i.'&nbsp;&nbsp;</a>';
		    			}
		    		}

		    	}

		    	$page_controls .= $current_page_number;
		    	for ($i=$current_page_number+1; $i <$last_page ; $i++) { 
		    		$page_controls .= '&nbsp;&nbsp;<a class="h4 text-center"  onclick="CheckPage('.$i.');">'.$i.'&nbsp;&nbsp;</a>';
		    		if($i>$current_page_number+4)
		    		{
		    			break;
		    		}
		    	}

		    	if($current_page_number!=$last_page)
		    	{
		    		$next_page =$current_page_number+1;
		    		$page_controls.='&nbsp;&nbsp;<a class="h4 text-center" onclick="CheckPage('.$next_page.');">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>';
		    		//onclick="CheckPage('..');"
		    	}
		    }

//----------------------

				$data = array();
				$data_to_send = array();
				if($sql_fetch_res->num_rows>=1)
				{
					while ($row = mysqli_fetch_array($sql_fetch_res)) 
					{
						$sql_language_genre 		= "SELECT cat.type,cat.value from movies movi,relationship rel,category cat WHERE movi.id='$row[movie_id]' and rel.movie_id=movi.id and rel.category_id=cat.id";
						$sql_language_genre_res  	= mysqli_query($conn,$sql_language_genre);
						$language_array = array();
						$genre_array = array();
						while ($roww = mysqli_fetch_array($sql_language_genre_res)) {
							if($roww['type']=='Language')
							{
								$language_array[] = $roww['value'];
							}
							else if($roww['type']=='Genre'){
								$genre_array[] = $roww['value'];
							}
						}
						$language_array = array_unique($language_array);
						$genre_array 	= array_unique($genre_array);

						
						$sql_fetch_movies 		= "SELECT * FROM movies where id='$row[movie_id]' "; 
						$sql_fetch_movies_res 	= mysqli_query($conn,$sql_fetch_movies);

						
						$sort_date_array 	= array();
						$sort_length_array 	= array();
						$counter = 0;
						while ($row2 = mysqli_fetch_array($sql_fetch_movies_res)) {
							$data['title'] 				= $row2['title'];
							$data['description'] 		= $row2['description'];
							$data['featured_image'] 	= $row2['featured_image'];
							$data['movie_length'] 		= $row2['movie_length'];
							$data['movie_rel_date'] 	= date('d-m-Y',strtotime($row2['movie_rel_date']));
							$data['movie_lang'] 		= implode(',', $language_array);
							$data['movie_genre'] 		= implode(',', $genre_array);
							$data['msg'] 				= $msg;
							$data['page_controls'] 		= $page_controls;
						
							array_push($data_to_send, $data);
							// $sort_date_array['movie_rel_date'][$counter] 	= date('d-m-Y',strtotime($row2['movie_rel_date']));
							// $sort_length_array['movie_length'][$counter] 	= $row2['movie_length'];
							// $counter++;
						}
							
					}
					$sort = array();
					foreach($data_to_send as $k=>$v) {
					    $sort['movie_rel_date'][$k] = strtotime($v['movie_rel_date']);;
					    $sort['movie_length'][$k] = $v['movie_length'];
					}

					if($sort_length==1 && $sort_date==0){
						array_multisort($sort['movie_length'], SORT_ASC, $data_to_send);
					}else if($sort_date==1 && $sort_length==0){
						// array_multisort($sort['movie_rel_date'], SORT_ASC, $data_to_send);
						array_multisort($sort['movie_rel_date'], SORT_ASC, $data_to_send);
					}else if($sort_length==1 && $sort_date==1){
						array_multisort($sort['movie_rel_date'], SORT_ASC, $sort['movie_length'], SORT_ASC,$data_to_send);
					}
					
					echo json_encode($data_to_send);
					die;
				}
				else{
					$data = 0;
					echo json_encode($data);
					die;
				}
			
		}else{

			// ---------------------------------
					    $sql_movie_list 	= "SELECT count(id) from movies";
		    $sql_movie_list_res	= mysqli_query($conn,$sql_movie_list);	
		    $sql_movie_list_row	= mysqli_fetch_array($sql_movie_list_res);
		    $total_rows			= $sql_movie_list_row['count(id)'];

		    $per_page_count  	= 10;
		    $last_page			= ceil($total_rows/$per_page_count);

		    if($last_page<1)
		    {
		    	$last_page=1;
		    }

		    $current_page_number = 1;
		    if(isset($_POST['page_count']))
		    {
		    	$current_page_number = $_POST['page_count'];
		    }

		    if($current_page_number<1)
		    {
		    	$current_page_number = 1;
		    }else if($current_page_number>$last_page)
		    {
		    	$current_page_number = $last_page;
		    }


		    $set_limit = "LIMIT ".($current_page_number - 1)*$per_page_count.','.$per_page_count;

			// $sql_fetch = "SELECT * FROM relationship GROUP BY movie_id $set_limit";
			// $sql_fetch_res = mysqli_query($conn,$sql_fetch);

			$msg = "Page $current_page_number of $last_page";

    		$page_controls = '';

    		$page_controls = '';

		    if($last_page !=1)
		    {
		    	if($current_page_number > 1)
		    	{
		    		$pervious = $current_page_number - 1;
		    		$page_controls = '<a class="h4 text-center" onclick="CheckPage('.$pervious.');">&nbsp;&nbsp; Pervious &nbsp;&nbsp;</a>';

		    		for ($i=$current_page_number-4; $i <$current_page_number ; $i++) { 
		    			if($i>0)
		    			{
							$page_controls .='&nbsp;&nbsp;<a class="h4 text-center" onclick="CheckPage('.$i.');">'.$i.'&nbsp;&nbsp;</a>';
		    			}
		    		}

		    	}

		    	$page_controls .= $current_page_number;
		    	for ($i=$current_page_number+1; $i <$last_page ; $i++) { 
		    		$page_controls .= '&nbsp;&nbsp;<a class="h4 text-center"  onclick="CheckPage('.$i.');">'.$i.'&nbsp;&nbsp;</a>';
		    		if($i>$current_page_number+4)
		    		{
		    			break;
		    		}
		    	}

		    	if($current_page_number!=$last_page)
		    	{
		    		$next_page =$current_page_number+1;
		    		$page_controls.='&nbsp;&nbsp;<a class="h4 text-center" onclick="CheckPage('.$next_page.');">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>';
		    		//onclick="CheckPage('..');"
		    	}
		    }

//----------------------


			if($movie_language!='all' && $movie_genre=='all'){


				$sql_cat_id 	= "SELECT * FROM category WHERE id='$movie_language'";
				$sql_cat_id_res = mysqli_query($conn,$sql_cat_id);
				$sql_cat_id_row = mysqli_fetch_array($sql_cat_id_res); 

				// $sql_fetch = "SELECT * FROM relationship where category_id='$sql_cat_id_row[id]'";
				$sql_fetch ="SELECT m.id, m.title FROM movies m INNER JOIN relationship a ON a.movie_id = m.id INNER JOIN category c ON a.category_id = c.id GROUP BY m.id, m.title HAVING MAX(c.type = 'Language' AND c.value = '$sql_cat_id_row[value]') = 1 $set_limit";

				// echo '<pre>01=';print_r($sql_fetch);
			}else if($movie_language=='all' && $movie_genre!='all'){
				$sql_cat_id 	= "SELECT * FROM category WHERE id='$movie_genre'";
				$sql_cat_id_res = mysqli_query($conn,$sql_cat_id);
				$sql_cat_id_row = mysqli_fetch_array($sql_cat_id_res); 

				// $sql_fetch = "SELECT * FROM relationship where category_id='$sql_cat_id_row[id]'";
				$sql_fetch = "SELECT m.id, m.title FROM movies m INNER JOIN relationship a ON a.movie_id = m.id INNER JOIN category c ON a.category_id = c.id GROUP BY m.id, m.title HAVING  MAX(c.type = 'Genre' AND c.value = '$sql_cat_id_row[value]') = 1 $set_limit";

				// echo '<pre>02=';print_r($sql_fetch);
			}
			else if($movie_language!='all' && $movie_genre!='all'){

				$sql_cat_id_1 	= "SELECT * FROM category WHERE id='$movie_language'";
				$sql_cat_id_res_1 = mysqli_query($conn,$sql_cat_id_1);
				$sql_cat_id_row_1 = mysqli_fetch_array($sql_cat_id_res_1); 

				$sql_cat_id_2 	= "SELECT * FROM category WHERE id='$movie_genre'";
				$sql_cat_id_res_2 = mysqli_query($conn,$sql_cat_id_2);
				$sql_cat_id_row_2 = mysqli_fetch_array($sql_cat_id_res_2); 

				$sql_fetch 	= "SELECT m.id, m.title FROM movies m INNER JOIN relationship a ON a.movie_id = m.id INNER JOIN category c ON a.category_id = c.id GROUP BY m.id, m.title HAVING MAX(c.type = 'Language' AND c.value = '$sql_cat_id_row_1[value]') = 1 AND MAX(c.type = 'Genre' AND c.value = '$sql_cat_id_row_2[value]') = 1 $set_limit";
				// echo '<pre>03=';print_r($sql_fetch);
			}

			
			$sql_fetch_res = mysqli_query($conn,$sql_fetch);
			$data = array();
				$data_to_send = array();
				if($sql_fetch_res->num_rows>=1)
				{
					while ($row = mysqli_fetch_array($sql_fetch_res)) 
					{
						
						$sql_language_genre 		= "SELECT cat.type,cat.value from movies movi,relationship rel,category cat WHERE movi.id='$row[id]' and rel.movie_id=movi.id and rel.category_id=cat.id";
						$sql_language_genre_res  	= mysqli_query($conn,$sql_language_genre);
						$language_array = array();
						$genre_array = array();
						while ($roww = mysqli_fetch_array($sql_language_genre_res)) {
							if($roww['type']=='Language')
							{
								$language_array[] = $roww['value'];
							}
							else if($roww['type']=='Genre'){
								$genre_array[] = $roww['value'];
							}
						}
						$language_array = array_unique($language_array);
						$genre_array 	= array_unique($genre_array);

						$sql_fetch_movies 		= "SELECT * FROM movies where id='$row[id]'"; 
						$sql_fetch_movies_res 	= mysqli_query($conn,$sql_fetch_movies);
						$sort_date_array 	= array();
						$sort_length_array 	= array();
						$counter = 0;
						while ($row2 = mysqli_fetch_array($sql_fetch_movies_res)) {
							$data['title'] 			= $row2['title'];
							$data['description'] 	= $row2['description'];
							$data['featured_image'] = $row2['featured_image'];
							$data['movie_length'] 	= $row2['movie_length'];
							$data['movie_rel_date'] = date('d-m-Y',strtotime($row2['movie_rel_date']));
							$data['movie_lang'] 	= implode(',', $language_array);
							$data['movie_genre'] 	= implode(',', $genre_array);
							$data['msg'] 				= $msg;
							$data['page_controls'] 		= $page_controls;
							array_push($data_to_send, $data);
						}
							
					}

					$sort = array();
					foreach($data_to_send as $k=>$v) {
					    $sort['movie_rel_date'][$k] = strtotime($v['movie_rel_date']);;
					    $sort['movie_length'][$k] = $v['movie_length'];
					}

					if($sort_length==1 && $sort_date==0){
						array_multisort($sort['movie_length'], SORT_ASC, $data_to_send);
					}else if($sort_date==1 && $sort_length==0){
						// array_multisort($sort['movie_rel_date'], SORT_ASC, $data_to_send);
						array_multisort($sort['movie_rel_date'], SORT_ASC, $data_to_send);
					}else if($sort_length==1 && $sort_date==1){
						array_multisort($sort['movie_rel_date'], SORT_ASC, $sort['movie_length'], SORT_ASC,$data_to_send);
					}
					
					echo json_encode($data_to_send);
					// echo json_encode($data_to_send);
					die;
				}
				else{
					$data = 0;
					echo json_encode($data);
					die;
				}
		}
		


	}
	

	include 'common_header.php';

		
?>

<main style="margin-top: 6em;">
	<div class="container">
		<h1 class="text-center">Movie Listings</h1>
		<div class="row d-flex justify-content-center">
			<div class="col-lg-5 col-md-5">
				<div class="md-form">
			        Select Language
			        <select id="Movie_language" name="Movie_language" class="form-control"  onchange="movie_filter()">
			        	<?php

			        		$sql_language 			= "SELECT * FROM category WHERE type='Language' ORDER BY value ASC";
			        		$sql_language_result 	= mysqli_query($conn,$sql_language);
			        		if($sql_language_result->num_rows>=1)
			        		{
			        			echo '<option value="all">All</option>';
			        			while ($row = mysqli_fetch_array($sql_language_result)) {
			        				echo '<option value="'.$row['id'].'">'.$row['value'].'</option>';
			        			}
			        		}else{
			        			echo '<option value="eng">Default English</option>';
			        		} 
		        	 	?>
			        	
			        </select>
		    	</div>	
			</div>
		</div>
		<div class="row d-flex justify-content-center">
			<div class="col-lg-5 col-md-5">
							
				<div class="md-form">
			        Select Genre
			        <select id="Movie_genre" name="Movie_genre" class="form-control"  onchange="movie_filter()">
			        	<?php

			        		$sql_genre 			= "SELECT * FROM category WHERE type='Genre' ORDER BY value ASC";
			        		$sql_genre_result 	= mysqli_query($conn,$sql_genre);
			        		if($sql_genre_result->num_rows>=1)
			        		{
			        			echo '<option value="all">All</option>';
			        			while ($row = mysqli_fetch_array($sql_genre_result)) {
			        				echo '<option value="'.$row['id'].'">'.$row['value'].'</option>';
			        			}
			        		}else{
			        			echo '<option value="eng">Default Drama</option>';
			        		} 
		        	 	?>
			        </select>
			    </div>

			</div>
		</div>
		<div class="row d-flex justify-content-center">
			<div class="custom-control">
                <input type="checkbox" class="custom-control" id="sortbylength" name="sortbylength" onclick="CheckStatus();">
                <label class="custom-control-label" for="sortbylength" >Sort by Length <span class="red-text" id="sortbylength_status"></span></label>
                <input type="hidden" name="sortbylength_val" id="sortbylength_val" value="0">
            </div>
            <div class="custom-control">
                <input type="checkbox" class="custom-control" id="sortbydate" name="sortbydate" onclick="CheckStatus();">
                <label class="custom-control-label" for="sortbydate" >Sort by Date <span class="" id="sortbydate_status"></span></label>
                <input type="hidden" name="sortbydate_val" id="sortbydate_val" value="0">
            </div>
            
		</div>
		<div id="listing">
			
			<?php 

				/*$sql = "SELECT * FROM movies";
				$sql_res = mysqli_query($conn,$sql);

				while ($row = mysqli_fetch_array($sql_res)) 
				{
				
					$sql_language_genre 		= "SELECT cat.type,cat.value from movies movi,relationship rel,category cat WHERE movi.id='$row[id]' and rel.movie_id=movi.id and rel.category_id=cat.id";
					$sql_language_genre_res  	= mysqli_query($conn,$sql_language_genre);
					$language_array = array();
					$genre_array = array();
					while ($roww = mysqli_fetch_array($sql_language_genre_res)) {
						if($roww['type']=='Language')
						{
							$language_array[] = $roww['value'];
						}
						else if($roww['type']=='Genre'){
							$genre_array[] = $roww['value'];
						}
					}
					$language_array = array_unique($language_array);
					$genre_array 	= array_unique($genre_array);


				//SELECT movi.title as title,movi.description as description,movi.movie_length as movie_length,movi.movie_rel_date as movie_rel_date,cat.type,cat.value from movies movi,relationship rel,category cat WHERE movi.id='1' and rel.movie_id=movi.id and rel.category_id=cat.id

				echo '<div class="row d-flex justify-content-center mt-3">
		    	<div class="col-lg-8 col-md-8" >
		    		<div class="card card-cascade hoverable">
		    			<div class="row">
			    			<div class="col-lg-6 col-md-6">
			    				<img src="'.$row['featured_image'].'" class=" z-depth-5 ml-2 mt-2 mb-2 mr-2 img-responsive img-fluid" style="height: 500px;width: auto;" alt="'.$row['title'].'">
			    			</div>
			    			<div class="col-lg-6 col-md-6 mt-5">
			    				<p class="h3 text-center">'.$row['title'].'</p>
			    				<hr>
			    				<p class="h6 text-center"><b>Description :</b>'.$row['description'].'</p>
			    				<hr>
			    				<p class="h6 text-center"><b>Length :</b>'.$row['movie_length'].'-Min</p>
			    				<hr>
			    				<p class="h6 text-center"><b>Release Date :</b>'.date('d-m-Y',strtotime($row['movie_rel_date'])).'</p>
			    				<hr>
			    				<p class="h6 text-center"><b>Language :</b>'.implode(',', $language_array).'</p>
			    				<p class="h6 text-center"><b>Genre :</b>'.implode(',', $genre_array).'</p>
			    			</div>
			    		</div>
		    		</div>
		    	</div>
		    </div>';
		}
*/
			?>
		</div>
		<div class="row d-flex justify-content-center py-5" id="pagination">
			
		</div>
	</div>
	    
	<input type="hidden" name="basepath" id="basepath" value="<?php echo Base_url; ?>">
	<input type="hidden" name="page_count_val" id="page_count_val" value="1">
</main>

<?php 
	include 'footer.php';

?>

<script type="text/javascript">
	$(document).ready(function(){
		movie_filter();
	})
	function movie_filter() {
		Movie_language		= $('#Movie_language').val();
		Movie_genre			= $('#Movie_genre').val();
		basepath			= $('#basepath').val();
		sortbylength_val	= $('#sortbylength_val').val();
		sortbydate_val		= $('#sortbydate_val').val();
		page_count			= $('#page_count_val').val();


		$.ajax({
			type:'POST',
			url:basepath+'index.php',
			data:{filter:'filter',Movie_language:Movie_language,Movie_genre:Movie_genre,sort_length:sortbylength_val,sort_date:sortbydate_val,page_count:page_count},
			success:function(response){
				console.log("response="+response);
				if(response=='')
				{
					alert("Data Not Found");
					return;
				}
				else if(response==0)
				{
					alert("Data Not Found");
					return;
				}else{
					var DataParsed = $.parseJSON(response);
            		dummyData ='';
            		console.log('DataParsed='+DataParsed);

            		for (var i = 0; i < DataParsed.length; i++) 
		            {
		              	dummyData += '<div class="row d-flex justify-content-center mt-3">'
							    	+'<div class="col-lg-8 col-md-8" >'
							    		+'<div class="card card-cascade hoverable">'
							    			+'<div class="row">'
								    			+'<div class="col-lg-6 col-md-6">'
								    				+'<img src="'+DataParsed[i].featured_image+'" class="z-depth-5 ml-2 mt-2 mb-2 mr-2 img-responsive img-fluid" style="height: 500px;width: auto;" alt="'+DataParsed[i].title+'">'
								    			+'</div>'
								    			+'<div class="col-lg-6 col-md-6 mt-5">'
								    				+'<p class="h3 text-center">'+DataParsed[i].title+'</p>'
								    				+'<hr>'
								    				+'<p class="h6 text-center"><b>Description :</b>'+DataParsed[i].description+'</p>'
								    				+'<hr>'
								    				+'<p class="h6 text-center"><b>Length :</b>'+DataParsed[i].movie_length+'-Min</p>'
								    				+'<hr>'
								    				+'<p class="h6 text-center"><b>Release Date :</b>'+DataParsed[i].movie_rel_date+'</p>'
								    				+'<hr>'
								    				+'<p class="h6 text-center"><b>Language :</b>'+DataParsed[i].movie_lang+'</p>'
								    				+'<p class="h6 text-center"><b>Genre :</b>'+DataParsed[i].movie_genre+'</p>'
								    			+'</div>'
								    		+'</div>'
							    		+'</div>'
							    	+'</div>'
							    +'</div>';

						pagination = DataParsed[i].page_controls;
		            }

		            $('#listing').empty();
		            $('#listing').html(dummyData);
		            $('#pagination').empty();
		            $('#pagination').html(pagination);
				}
			},
			error:function(){

			}
		});
	}

	function CheckStatus(c){
		if($('#sortbylength').prop('checked')){
			$('#sortbylength_val').val(1);

		}else{
			$('#sortbylength_val').val(0);
		}
		
		if($('#sortbydate').prop('checked')){
			$('#sortbydate_val').val(1);
		}else{
			$('#sortbydate_val').val(0);
		}
		movie_filter();
	}
	function CheckPage(c){
		//alert("CheckPage="+c)
		$('#page_count_val').val(c);
		movie_filter();
	}

</script>


<?php 

?>
