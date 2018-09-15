<!DOCTYPE html>
<html lang="en">

<head>

	<?=$data['site_title'];?>
	<?=$data['meta_keywords'];?>
	<?=$data['meta_description'];?>
	<?=$data['site_icon'];?>
	<?=$data['css'];?>
	<?=$data['js'];?>

</head>
	
<body>

	<div id="page-wrap">

		<?=$data['header'];?>
		
		<div id="page-content">
		
			<?=$data['content'];?>
			
			<div class="thanks">

				<?=$data['thank_you'];?>

			</div>

			<?php
			//foreach($data['records'] as $row){ 
			//	echo $row['firstname'] . '<br/>';
			//}
			?>

			<!--<?=$data['page_links'];?>-->

		</div>

	</div>

	<?=$data['footer'];?>

</body>

</html>