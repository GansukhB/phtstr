<div>
<table>
	<?
							
		$news_result = mysql_query("SELECT id,title,article,publish_date FROM news where homepage = '1' and active = '1' order by publish_date desc", $db);
		$news_rows = mysql_num_rows($news_result);
		if($news_rows > 0){
		?>
		<tr>
			<td align="right" class="featured_news_header" style="padding-right: 120px;" nowrap><?PHP echo $homepage_news; ?></td>
		</tr>	
		<?PHP
		}
		while($news = mysql_fetch_object($news_result)){
			$posted = substr($news->publish_date, 4, 2) . "/" . substr($news->publish_date, 6, 2) . "/" . substr($news->publish_date, 0, 4);
			$posted_short = substr($news->publish_date, 4, 2) . "/" . substr($news->publish_date, 6, 2);
			
			$new_article = strip_tags($news->article);
			
			if(strlen($new_article) > 70){
				$trim_article = substr($new_article, 0, 70) . "...";
			}
			else {
				$trim_article = $new_article;
			}
	?>
		<tr>
			<td>
			<div class="featured_news">
				<a href="news_details.php?id=<? echo $news->id; ?>" class="more_news_links"><b><? echo $news->title; ?></b></a><br><? echo $trim_article; ?> <a href="news_details.php?id=<? echo $news->id; ?>" class="more_news_links"><?PHP echo $news_read_more; ?></a>
			</div>
			<td>
		</tr>
		<tr>
			<td height="3"></td>
		</tr>
	<?
		}
	?>
	<tr>
		<td height="4"></td>
	</tr>
	<?PHP if($news_rows > 0){ ?>
	<tr>
		<td align="right"><a href="news.php" class="more_news_links"><?PHP echo $news_more_news; ?></a><td>
	</tr>
	<?PHP } ?>
</table>
</div>