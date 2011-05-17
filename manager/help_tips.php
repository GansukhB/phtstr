<html>
	<head>
		<title>Tips/Help</title>
		<link rel="stylesheet" href="mgr_style.css">
	</head>
	<body bgcolor="#5E85CA">
		<table width="100%">
			<?
				switch($pmode){
					case "editor":
			?>
					<tr>
						<td>
							<table>
								<tr>
									<td colspan="2"><font color="#ffffff"><b>Shortcut Keys</b></td>
								</tr>
								<tr>
									<td><font color="#ffffff">Shift+Enter</td>
									<td><font color="#ffffff">= Line Break</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-A</td>
									<td><font color="#ffffff">= Select All</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-I</td>
									<td><font color="#ffffff">= Bitalic</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-U</td>
									<td><font color="#ffffff">= Underline</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-S</td>
									<td><font color="#ffffff">= Strikethrough</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-L</td>
									<td><font color="#ffffff">= Justify left</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-E</td>
									<td><font color="#ffffff">= Justify center</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-R</td>
									<td><font color="#ffffff">= Justify right</td>
								</tr>
								<tr>
									<td><font color="#ffffff">CTRL-J</td>
									<td><font color="#ffffff">= Justify full</td>
								</tr>
								<tR>
									<td><br><br><br><br></td>
								</tr>
							</table>
						</td>
					</tr>
			<?
					break;
					case "html_tags":
			?>
					<tr>
						<td><font color="#ffffff">
						<b>Here are some simple html tags that you might want to use.</b>
						<br><br>
						<b>Line Break</b><br>
						&lt;br&gt;
						<br><br>
						<b>Bold</b><br>
						&lt;b&gt;your text here&lt;/b&gt;						
						<br><br>
						<b>Underline</b><br>
						&lt;u&gt;your text here&lt;/u&gt;
						 <br><br>
						<b>Italics</b><br>
						&lt;i&gt;your text here&lt;/i&gt;
						 <br><br>
						<b>Font Color</b><br>
						&lt;font color="red"&gt;your text here&lt;/font&gt;
						<br><br>
						You can also use the following font colors
						blue, navy, green, tan, orange
						<br><br> 
						<b>Font Size</b><br>
						&lt;font size="2"&gt;your text here&lt;/font&gt;
						<br><br>
						You can also use font size and color at the same time like this
						<br>
						&lt;font color="red" size="2"&gt;your text here&lt;/font&gt;
						<br><br>
						<b>Horizontal Rule</b><br>
						&lt;hr&gt;
						<br><br>
						<b>Link</b><br>
						&lt;a href="http://www.address.com"&gt;What you want your link to say&lt;/a&gt;
						</td>
					</tr>			
			<?
				}
			?>
		</table>
	</table>
</table>