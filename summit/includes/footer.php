		
		<div id="footer">&copy; Copyright <a href="http://www.bigbadcollab.com/" target="_blank">BigBadCollab</a>. All Rights Reserved.</div>
		</div> <!-- outside_margin -->
	</div> <!-- content_column_fluid -->
	</div> <!-- fullWide closer -->
	
	<!-- close database connection from includes/db_conn.php -->
	<?php 
	
	if (isset($db))
	{
		mysql_close($db);
	}
		
	?>
    <script type="text/javascript" src="scripts/jquery-ui.js"></script>		
</body>
</html>
