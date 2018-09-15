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

			<p>Basics</p>

			<ul>
				<li>What is MVC?</li>
				<li>File structure.</li>
				<li>Getting Started.</li>
			</ul>

			<p><?=create_link('Back');?></P>

		</div>
	
	</div>
	
	<?=$data['footer'];?>

</body>

</html>