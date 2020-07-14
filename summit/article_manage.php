<? 
session_start();
if ($_GET['logout'] == 'true' ) {
	session_destroy();
} else {
	if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
	}
}

	require_once('../app/bootstrap.php');
	require_once 'includes/db_conn.php';
	require_once 'includes/functions.php';
	include "wideimage/WideImage.php";
	$dbc = new db();
		
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM articles WHERE id=" . $current;
		$result = mysql_query($query);
		$article = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
	
		if ( $HTTP_POST_FILES['uploaded']['size'] != '0' ) {
		
			$limit = 3500000;
			$file_size = $HTTP_POST_FILES['uploaded']['size'];
			
			if ( $file_size >= $limit )
			{ 
			    $messagefail = "Your file is too large. Please scale down the photo and try uploading again.<br />"; 
			     
			} else {
				// Take the file name and clean it up a bit
				$file_name = $_FILES['uploaded']['name'];
				
				$ext = substr($file_name, -4);
				
				$title = $_POST['title'] ;
		
				$title = preg_replace("/(.*?)([A-Za-z0-9\s]*)(.*?)/", "$2", $title);
				$title = preg_replace('/\%/',' percent',$title); 
				$title = preg_replace('/\@/',' at ',$title); 
				$title = preg_replace('/\&/',' and ',$title); 
				$title = preg_replace('/\s[\s]+/','-',$title);    // Strip off multiple spaces 
				$title = preg_replace('/[\s\W]+/','-',$title);    // Strip off spaces and non-alpha-numeric 
				$title = preg_replace('/^[\-]+/','',$title); // Strip off the starting hyphens 
				$title = preg_replace('/[\-]+$/','',$title); // // Strip off the ending hyphens 
				
				$title = strtolower($title); 
			
				// trim and lowercase
				$title = strtolower(trim($title, '-'));
				$file_name = $title . $ext;
					
				// Where are we uploading?
				$target = "{$_SERVER["DOCUMENT_ROOT"]}/media/articles/";
				
				// Make the full path
				$target = $target . $file_name;
								
				if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
					$messagepass = "The file has been uploaded as ".$file_name. "<br />";
					
				} else {
					$messagefail = "Sorry, there was a problem uploading your file.<br />";
				}
				
				$image = WideImage::load('../media/articles/'.$file_name);
				$resized = $image->resize(700, 500, 'outside', 'down');
				$save = $resized->saveToFile('../media/articles/'.$file_name);
		    	
		    	$image = WideImage::load('../media/articles/'.$file_name);
				$resized = $image->resize(170, 170, 'outside', 'down');
				$cropped = $resized->crop('center','center',170,170);
				$save = $cropped->saveToFile('../media/articles/thumbs/'.$file_name);
			}
		}
				
		if ($file_name != '') {
			$image_url = $file_name;
		} else {
			$image_url = $article['image_url'];
		}
	
		if ($_POST['active'] == 'yes') {
			$active = 1;
		} else {
			$active = 0;
		}
		
		if ($_POST['featured'] == 'yes') {
			$featured = 1;
		} else {
			$featured = 0;
		}
		
		$tags = $_POST['article_tags'];
		
		$tag_check = admin::check_tags($current, $tags);

		$category = mysql_clean($_POST['category']);
		$author = mysql_clean($_POST['author']);
		$pubdate = mysql_clean( date('Y-m-d, G:i:s', strtotime($_POST['pubdate'])) );
		$title = mysql_clean($_POST['title']);
		$slug = slug($title);
		$subtitle = mysql_clean($_POST['subtitle']);
		$pitch = mysql_clean($_POST['pitch']);
		$caption = mysql_clean($_POST['caption']);
		$content = mysql_clean($_POST['content']);
		$timestamp = date("Y-m-d, G:i:s");
		          	
		
		if (isset($_GET['id'])) {
			$id = mysql_clean($_GET['id']);
		
			$query = "UPDATE articles SET
					active = '{$active}',
					featured = '{$featured}',
					category = '{$category}',
					author = '{$author}',
					pubdate = '{$pubdate}',
					title = '{$title}',
					slug = '{$slug}',
					subtitle = '{$subtitle}',
					pitch = '{$pitch}',
					content = '{$content}',
					image_url = '{$image_url}',
					caption = '{$caption}',
					timestamp = '{$timestamp}'
					WHERE id = {$id}";				

			
		} else {
		
		$query = "INSERT INTO articles (
		    active, featured, category, author, pubdate, title, slug, subtitle, pitch, content, image_url, caption, timestamp
		    ) VALUES (
		    '{$active}', '{$featured}', '{$category}', '{$author}', '{$pubdate}', '{$title}', '{$slug}', '{$subtitle}', '{$pitch}', '{$content}', '{$image_url}', '{$caption}', '{$timestamp}'
		    )";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
			// success
			if (isset($_GET['id'])) {
				header('Location:articles.php');
				$messagepass .= "The article has been updated!";
			} else {
				header('Location:articles.php');
			}
		} else {
			// failed
			$messagefail = "There has been an error modifying the article. Please contact the system admin or try again.";
			$messagefail .= "<br />". mysql_error();
			$messagefail .= $query;
		}
		
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM articles WHERE id=" . $current;
		$result = mysql_query($query);
		$article = mysql_fetch_array($result);
		$tags = admin::get_tags($current);
	}

?>

<?php include('includes/header.php'); ?>
<script>
	
	$(function(){
		
		$("#tags").tag({
			width: '98%',						//Set a width
			height: 30,							//Set a height of 90px
			inputName: 'article_tags',					//Name the field 'inputName'
			key: ['enter', 'comma'],	//Add tag on 'enter', 'space', or 'comma'
			clickRemove: true					//Remove tag when clicked
		});
		<?php if ( $tags[0]->tags != '' ) {
		$tags = $tags[0]->tags;
		foreach ($tags as $tag) {		
		?>
			$("#tags").addTag('<?php echo $tag; ?>');
		<?php } } ?>
	});
</script>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3><?php echo $article['title']; ?></h3>
		<?php } else { ?>
			<h3>Add Article</h3>
		<?php } ?>
	
		<div class="add_button_holder"><a href="articles.php"> &laquo; Back to Articles</a></div>
	</div>
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	}
	if (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	?>
	<div id="content_holder">
	<div id="inner_left_column">

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$_GET['id']}"; }?>" method="post" enctype="multipart/form-data" >
	
		<div class="grey_contain">
			<ul>
			<li>
			<?php if ( $article['active'] == 1 ) { $active_checked = "checked"; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>
			<li>
			<?php if ( $article['featured'] == 1 ) { $featured = "checked"; } ?>
	       	    <input type="checkbox" name="featured" value="yes" <?php echo $featured; ?> />&nbsp;&nbsp;Featured?
	       	</li>
	       	<li>    
		       	Author:
				<select name="author" id="author">
				<?php
					$result = mysql_query("SELECT * FROM authors ORDER BY last_name");
					while ( $row = mysql_fetch_array($result) )
					{
					?>
					<option value="<?php echo $row['id']; ?>" <?php if ( $article['author'] == $row['id'] ) { echo "selected"; } ?>>
						<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>
					</option>
					<?php
					}
				?>				
				</select>
		    </li>
		    <li>
			    Category:
				<select name="category" id="category">
				<?php
					$result = mysql_query("SELECT * FROM categories");
					while ( $row = mysql_fetch_array($result) )
					{
					?>
					<option value="<?php echo $row['id']; ?>" <?php if ( $article['category'] == $row['id'] ) { echo "selected"; } ?>>
						<?php echo $row['name']; ?>
					</option>
					<?php
					}
				?>				
				</select>
		    </li>
	       	</ul>    
		</div>
		
	
		<div class="form_row">
			<label for="title">Article Title</label>
			<input type="text" name="title" id="title" value='<?php if (isset($_GET['id'])) { echo $article['title']; } else { echo $_POST['title']; }; ?>' class="field100" tabindex="1" style="font-weight: bold;" />
		</div>
		
		<div class="form100" style="margin-bottom: 0;">
			<label for="article_tags">Tags separated by comma. Click tag to delete.</label>
		</div>
		
		<div class="form_row">
			<div id="tags"></div>
		</div>
		
		<div class="form_row">
		<div class="form50">
			<label for="pubdate">Publish Date</label>
			<input type="text" name="pubdate" id="date" value="<?php if (isset($_GET['id'])) { echo date('Y-m-d G:i a', strtotime($article['pubdate'])); } else { echo $_POST['pubdate']; }; ?>" class="field50" tabindex="3" />
		</div>
		
		</div>
		
		<div class="form_row">
			Article Content:<br />
			<textarea name="content" id="content"><?php echo $article['content']; ?></textarea>
			<script>
				var editor = CKEDITOR.replace( 'content',
			   				 {
			   				 	height: "450",
			   				    toolbar : 'Base'
			   				 });
				CKFinder.setupCKEditor( editor, '/admin/ckfinder/' );
			</script>
		</div>
		
		<div class="form_row">
			Article Pitch:<br />
			<textarea name="pitch" id="pitch"><?php echo $article['pitch']; ?></textarea>
			<script>
				var editor = CKEDITOR.replace( 'pitch',
				 {
				    toolbar : 'Simple'
				 });
			</script>
		</div>
		
		<div class="form_button_row">
			<input type="submit" name="submit" value="Update Article" class="form_button" />
		</div>
	</div> <!-- inner_left_column -->
	
	<div id="inner_right_column">
		<h4>Article Image</h4>
		<div class="form_row">
		<?php
        if ($article['image_url'] != NULL) {
            echo "<div class=\"form_row\">Current Image:<br /><img src=\"/media/articles/{$article['image_url']}\"/></div>";
        }
        ?>
      	<input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="6" />
		</div>
		
		<div class="form_row">
			Caption:<br />
			<textarea name="caption" id="caption" tabindex="7" rows="3" style="font-size: 12px;"><?php echo $article['caption']; ?></textarea>
		</div>
		
		<div class="form_row">
			Link to this article:<br />
			<strong>/feed/<?php echo $article['slug']; ?></strong><br />
			<a href="/feed/<?php echo $article['slug']; ?>" target="_blank">View</a>
		</div>	
	
	</div>

	</form>
	
	</div> <!-- content_holder -->
	    		
<?php include('includes/footer.php'); ?>