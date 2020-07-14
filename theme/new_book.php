<?php
    session_start();
    $user = user::identify();
    $db = new db();
    
    if ( isset($_POST['submit']) ) {
        $target1 = "{$_SERVER["DOCUMENT_ROOT"]}/media/covers/"; 
        $target1 = $target1 . basename( $_FILES['uploaded']['name']) ;
        $file = basename( $_FILES['uploaded']['name']);
        $ok=1; 
        
        //This is our size condition 
        if ($_FILES['uploaded']['size'] > 350000) 
        { 
            echo "Your file is too large.<br>"; 
            $ok=0; 
        } 
        
        //This is our limit file type condition 
        if ($uploaded_type =="text/php") 
        { 
            echo "No PHP files<br>"; 
            $ok=0; 
        } 
        
        
        //If everything is ok we try to upload it 
        else 
        { 
            if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target1)) 
            { 
                $messagepass = "The file ". $file. " has been uploaded<br /><br />";
                $image_url = $file;
            } 
        }

        $slug = utils::slug($_POST['title']);

        $query = "INSERT INTO user_books ( user_id, title, slug, cover, image_url, description ) VALUES ( '{$_POST['user_id']}', '{$_POST['title']}', '{$slug}', '{$_POST['cover']}', '{$image_url}', '{$_POST['description']}' )";
        $result = mysql_query($query);
        header('Location:/library/');
    }
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">
    <div class="width_setter">
    
    <h2 class="tk-freight-sans-pro">Add a Book to Your Library</h2>
    <p>
    See something that you like?  Why don't you add it to your own library?
    </p>
    
    <h3>Book Information:</h3>
    <form method="post" action="" id="new_book" enctype="multipart/form-data">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user->user_id; ?>" />
    <div class="form50">
        <label for="title">Book Title:</label>
        <input type="text" name="title" id="title" class="field50" />
    </div>
    
    <div class="form50">
        <label for="title">Book Description:</label>
        <textarea name="description" id="description"></textarea>
    </div>
    
    <h3>Select Book Cover:</h3>
    <ul id="covers">
    <?php $covers = books::get_covers();
    foreach ( $covers as $cover ) {
    ?>
        <li><input type="radio" name="cover" id="cover" value="<?php echo $cover->id; ?>" />
            <div class="book_cover">
            <img src="<?php echo $theme->book($cover->image_url); ?>" alt="<?php echo $cover->name; ?>" />
            <?php echo $cover->name; ?>
            </div>
        </li>        
    <?php } ?>
    </ul>
    
    <div class="form100">
        Custom Book Image:<br />
        <input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="3" class="form50" />
    </div>
    
    <div class="form100">
        <input type="submit" name="submit" id="submit" value="Add Book" class="form_button_small" />
    </div>
    
    </form>
        
</div> <!-- main -->

<?php $theme->load('footer'); ?>