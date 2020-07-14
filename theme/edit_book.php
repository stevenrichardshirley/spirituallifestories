<?php
    session_start();
    $user = user::identify();
    $db = new db();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    if ( $page->content != 'edit_book' ) {
    
        $book_id = substr($page->content, 5);
        $book = books::get_book($user->user_id, $book_id);
        $book = $book[0];
    }
    
    if (isset($_POST['submit'])) {
  
    if ( $_FILES['uploaded']['error'] === 0 ) {
    
      $limit = 3500000;
      $file_size = $_FILES['uploaded']['size'];
      
      if ( $file_size >= $limit )
      { 
          $messagefail = "Your file is too large. Please scale down the photo and try uploading again.<br />"; 
           
      } else {
        // Take the file name and clean it up a bit
        $file_name = $_FILES['uploaded']['name'];
        
        $ext = substr($file_name, -4);
        
        $title = $book->title;
    
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
        $file_name = $title . '-' . $book_id . $ext;
          
        // Where are we uploading?
        $target = "{$_SERVER["DOCUMENT_ROOT"]}/media/covers/";
        
        // Make the full path
        $target = $target . $file_name;
                
        if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
          $messagepass = "The file has been uploaded as ".$file_name. "<br />";
          
        } else {
          $messagefail = "Sorry, there was a problem uploading your file.<br />";
        }

      }
    }
            
    if ( isset($file_name) ) {
      $image_url = $file_name;
    } else {
      $image_url = $book->image_url;
    }

        $id = $book->id;
        $slug = utils::slug($_POST['title']);
        $query = "UPDATE user_books SET
                title = '{$_POST['title']}',
                cover = '{$_POST['cover']}',
                image_url = '{$image_url}',
                description = '{$_POST['description']}'
                WHERE id = {$id}";
                
        $result = mysql_query($query);
        header('Location:/library/');
    }
    
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">
    <div class="width_setter">
    
    <?php 
        if(!empty($messagepass)) {
            echo "<div class=\"message_pass\">{$messagepass}</div>";
        } elseif (!empty($messagefail)) {
            echo "<div class=\"message_fail\">{$messagefail}</div>";
        }
    ?>
    
    <h2>Update your Book - <?php echo $book->title; ?></h2>
    
    <h3>Book Information:</h3>
    <form method="post" action="" id="new_book" enctype="multipart/form-data">
    <div class="form50">
        <label for="title">Book Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $book->title; ?>" class="field50" />
    </div>
    
    <div class="form50">
        <label for="title">Book Description:</label>
        <textarea name="description" id="description"><?php echo $book->description; ?></textarea>
    </div>
    
    <div class="form100">
        <?php
        if ($book->image_url != '') {
            echo "Current cover:<br /><img src=\"/media/covers/{$book->image_url}\" width=\"215\" style=\"float: left; margin-right: 15px; \" />";
        }
        ?>
        Custom Book Image:<br />
        <input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="3" class="form50" />
    </div>
    
    <h3>Select Book Cover:</h3>
    <ul id="covers">
    <?php $covers = books::get_covers();
    foreach ( $covers as $cover ) {
    ?>
        <li><input type="radio" name="cover" id="cover" value="<?php echo $cover->id; ?>" <?php if ( $book->cover == $cover->id ) { echo "checked"; } ?> />
            <div class="book_cover">
            <img src="<?php echo $theme->book($cover->image_url); ?>" alt="<?php echo $cover->name; ?>" />
            <?php echo $cover->name; ?>
            </div>
        </li>        
    <?php } ?>
    </ul>
    
    <div class="form100">
        <input type="submit" name="submit" id="submit" value="Save Changes" class="form_button" />
    </div>
    
    </form>
        
    </div> <!-- width_setter -->    
</div> <!-- main -->

<?php $theme->load('footer'); ?>