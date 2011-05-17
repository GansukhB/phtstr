<?PHP
	session_start();
	
	include( "database.php" );
	include( "config_public.php" );
	include( "functions.php" );
	global $slide_start_text, $slide_stop_text, $slide_stop_cycle, $slide_start_cycle;
	if($setting->no_right_click == 1){
		$body = "<body oncontextmenu=\"return false\" bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\">\n";
		} else {
		$body = "<body bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\">\n";
	}
	$style = "\t\t<link rel=\"stylesheet\" href=\"./styles/slideshow.css\">\n";
?>
<html>
<head>
  <? echo $body; ?>
	<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
	<script type='text/javascript' src='js/utils.js'></script>
	<title><? echo $setting->site_title; ?></title>
	<script type="text/javascript" src="js/effects.js"></script>
	<script type="text/javascript" src="js/slideshow.js"></script>
  <?
  echo $style;
  ?>
</head>
<body class="page_bg">
	<table border=0 class="table_border">
		<tr>
			<td height="<? echo $setting->sample_width + 25; ?>" width="<? echo $setting->sample_width + 25; ?>" align="center" valign="center">
	<div>
<div>
<?
$images = $_SESSION['imagenav'];
$images = explode(",", $images);
foreach($images as $value){ 
	$slide_show_images = $slide_show_images . "\"slide" . trim($value) . "\", ";
?>
<img id="slide<? echo trim($value); ?>" src="slidemark.php?i=<? echo trim($value); ?>">
<? } ?>
<div style="display:none" id="end-cycle">
	<div class="slide_text">
	<?PHP echo $slide_end_text; ?>
	</div>
</div>
</div>
<br>
</td>
</tr>

<tr>
<td align="center" valign="center">
<div>
<div id="id_prev" style="visibility:hidden" class="arrow" onclick="ss_prevnext(true)" title="<?PHP echo $slide_previous; ?>">&lt;&lt;</div>
<div id="id_action" class="button1" onclick="ss_action()"></div>
<div id="id_next" style="visibility:hidden" class="arrow" onclick="ss_prevnext(false)" title="<?PHP echo $slide_next; ?>">&gt;&gt;</div>
<div id="id_cycle" class="button1" onclick="ss_cycle()"></div>
<div id="id_nth" class="button1">0 of 0</div>
</td>
</tr>

<tr>
<td align="center" valign="center">
<div class="arrow" onclick="ss_speed(slideshow.speeds, -1000)" title="<?PHP echo $slide_shorter_time; ?>">&lt;&lt;</div>
<div class="arr_label" title="<?PHP echo $slide_time_message; ?>"><?PHP echo $slide_time_text; ?></div>
<div class="arrow" onclick="ss_speed(slideshow.speeds, 1000)" title="<?PHP echo $slide_longer_time; ?>">&gt;&gt;</div>
<div class="arrow" onclick="ss_speed(slideshow.animSpeeds, 3)" title="<?PHP echo $slide_shorter_time; ?>">&lt;&lt;</div>
<div class="arr_label1" title="<?PHP echo $slide_time_between_message; ?>"><?PHP echo $slide_time_between_text; ?></div>
<div class="arrow" onclick="ss_speed(slideshow.animSpeeds, -3)" title="<?PHP echo $slide_longer_time; ?>">&gt;&gt;</div>
</div>
<script type="text/javascript">
//-------------------------------------------------------------------
var slideshow = new Kmods.SlideShow({
	effect: '<? echo $setting->slide_type; ?>', 
	slides: [
		<? echo substr($slide_show_images, 0, -2); ?>
	],
	cycling: true,
	animSpeed: 10,
	speed: <? echo $setting->slide_speed; ?>,
	callback_endcycle: ss_callback_endcycle,
	display_endcycle: 'end-cycle',
	callback: ss_callback
});

function ss_callback_endcycle(bEnd) {
}

function ss_callback(bShow, intSlide) {
	if (!bShow)
		return;

	var e=document.getElementById('id_nth')

	if (slideshow.stopped)
		e.innerHTML='<?PHP echo $slide_stopped_at; ?>'
	else
		e.innerHTML=''

	e.innerHTML=(intSlide+1) + '<?PHP echo $slide_text_of; ?>' + slideshow.slides.length
}


slideshow.start();

var bInit=true;
function ss_action() {
	if (!bInit)
		if (slideshow.stopped)
			slideshow.start();
		else
			slideshow.stop();
		
	e=document.getElementById('id_action')
	if (slideshow.stopped)
	{
		e.innerHTML=('<?PHP echo $slide_start_show; ?>')
		e.style.background=('#DADADA')
		e.title='<?PHP echo $slide_start_text; ?>'
		document.getElementById('id_prev').style.visibility='visible'
		document.getElementById('id_next').style.visibility='visible'
	}
	else
	{
		e.innerHTML=('<?PHP echo $slide_stop_show; ?>')
		e.style.background=('#F0F0F0')
		e.title='<?PHP echo $slide_stop_text; ?>'
		document.getElementById('id_prev').style.visibility='hidden';
		document.getElementById('id_next').style.visibility='hidden';
	}

}

function ss_cycle() {

	if (!bInit)
		slideshow.cycling = !slideshow.cycling
		
	e=document.getElementById('id_cycle')
	if (slideshow.cycling)
	{
		ss_callback_endcycle(false)
		e.innerHTML=('<?PHP echo $slide_stop_looping; ?>')
		e.style.background=('#F0F0F0')
		e.title='<?PHP echo $slide_stop_cycle; ?>'
		if (!bInit)
		{
			slideshow.showNext();
		}
	}
	else
	{
		e.innerHTML=('<?PHP echo $slide_start_loop; ?>')
		e.style.background=('#DADADA')
		e.title='<?PHP echo $slide_start_cycle; ?>'
	}
}

function ss_inrange()
{
return (slideshow.currentSlideNumber >= 0 && slideshow.currentSlideNumber < slideshow.slides.length)
}
function ss_prevnext(bPrev)
{
	if (bPrev)
	{
		if(ss_inrange())
			slideshow.slides[slideshow.currentSlideNumber].style.display = 'none';

		if (slideshow.currentSlideNumber <= 0)
			slideshow.currentSlideNumber=slideshow.slides.length-2
		else
			slideshow.currentSlideNumber -= 2;

		if (slideshow.currentSlideNumber <= -1 || slideshow.currentSlideNumber >= slideshow.slides.length)
			slideshow.currentSlideNumber=-1
		}

	slideshow.showNext();
}

function ss_speed(arrSpeeds, intChange){
	for (var i = 0; i < arrSpeeds.length; i++){
		var intVal = arrSpeeds[i] + intChange

		if (intVal > 0){
			arrSpeeds[i] = intVal;
		}
	}
}

	ss_action()
	ss_cycle()
	bInit=false
</script>
</div>
</div>
</td>
</tr>
</table>
</body>
</html>

