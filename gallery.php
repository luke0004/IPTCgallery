<?php 

$sortByImageName = true;
$newestImagesFirst = false;

$imageFolder = 'img/';
$imageTypes = '{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG,*.gif,*.GIF}';
$images = glob($imageFolder . $imageTypes, GLOB_BRACE);
$count = count($images);

if ($sortByImageName) {
    $sortedImages = $images;
    natsort($sortedImages);
} else {
    $sortedImages = array();
    $count = count($images);
    for ($i = 0; $i < $count; $i++) {
        $sortedImages[date('YmdHis', filemtime($images[$i])) . $i] = $images[$i];
    }
    if ($newestImagesFirst) {
        krsort($sortedImages);
    } else {
        ksort($sortedImages);
    }
}
?>

<?php include 'header.php'; ?>

<?php if(file_exists('gallery-description.html'))
    include 'gallery-description.html'; ?>

<html>
	<div class="container">
	
		<!-- Read IPTC data from photos -->
		<?php
		foreach ($sortedImages as $image) {
		    $image_properties = getimagesize($image, $info);      
		    if(isset($image['APP13'])) {
				$iptc = iptcparse($info["APP13"]);
				if (is_array($iptc)) {
					$headline = $iptc['2#105'][0];
						$headline = iconv('macintosh', 'UTF-8', $headline);
					$caption = $iptc["2#120"][0];
						$caption = iconv('macintosh', 'UTF-8', $caption);
					$time = $iptc['2#055'][0];
					$year = substr($time, 0, 4);
					$month = substr($time, 4, 2);
					$day = substr($time, -2);
					$datetaken = date('d-m-Y', mktime(0, 0, 0, $month, $day, $year));
					$city = $iptc["2#090"][0];
					$country = $iptc["2#101"][0];
					$creator = $iptc["2#080"][0];
				}
			}
			
			# Generate and cache thumbnails if not already done 
			if (!file_exists("t_" . $imageFolder)) mkdir("t_" . $imageFolder);
			$path_image = pathinfo($image);
			$fname_image = $path_image['filename'];
			if (!file_exists("t_" . $imageFolder . "t_" . $fname_image . '.jpg')) {
				$image_properties = getimagesize($image);
				$width = $image_properties[0];
				$height = $image_properties[1];
				$image_ratio = $width / $height;
				if ($image_ratio > 1) {
					$thumb_width = 400; 
					$thumb_height = round($thumb_width / $image_ratio);
				}
				else {
					$thumb_height = round(400 * $image_ratio); 
					$thumb_width = round($image_ratio * $thumb_height); 
				}
				$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
				$temp_image = imagecreatefromjpeg($image);
				imagecopyresampled($thumb, $temp_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
				imagejpeg($thumb, "t_" . $imageFolder . "t_" . $fname_image . '.jpg', 50);
				imagedestroy($thumb);
				imagedestroy($temp_image);
			}
		
			echo '<div class="thumbnail">';
				echo '<a href="picture.php?image=' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '">';
					echo'<img src="' . htmlspecialchars("t_" . $imageFolder . "t_" . $fname_image . '.jpg', ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($caption ?? '', ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars($caption ?? '', ENT_QUOTES, 'UTF-8') . '">';
				echo '</a>';
				echo '<div class="thumbnail-description">';
					// echo $caption;
					// echo '<br>File ref: ' . $image . ' / ' . $datetaken ;
				echo '</div>';
			echo '</div>';
		}
		?>
		<!-- Fill last line layout -->
		<div class="thumbnail">
		</div>
		<div class="thumbnail">
		</div>
		<div class="thumbnail">
		</div>

		<div class="footer">
			&copy; 
		</div>

</div>
</div>
</body>
</html>