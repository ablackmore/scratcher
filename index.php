<?
$base = $_SERVER['SERVER_NAME'];
$base .= "/scratch/";

$prize = rand(1,4); // prize5 is dummy
$win_img = "prize".$prize;

$dummy_imgs = array();

for($i=0;$i<6;$i++){
	if ($i != $prize){
		$dummy_imgs[] = "prize".$i;
	}
}

$dummy_img = $dummy_imgs[rand(0,4)];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Scratch N Win</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
  <link href='http://fonts.googleapis.com/css?family=Junge' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" href="http://<?=$base; ?>favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="css/ipad_style.css" type="text/css" media="all">
<style type="text/css">
.canvas_style {
	border: 1px solid #222;
	cursor: pointer;
	position:absolute;
	left: 0px;
	z-index: 100;
}
</style>  
	<link rel="apple-touch-icon" href="http://<?=$base; ?>apple-touch-icon.png" />
	<link rel="apple-touch-icon-precomposed" href="http://<?=$base; ?>apple-touch-icon.png" />
  <script src="js/jquery-1.6.4.min.js"></script>
  <script src="js/jquery.mobile-1.0.1.min.js"></script>  
<script type="text/javascript">
var fromwebsite = false;
var formaction = 'http://<?=$base;?>/submit.php';
var touches = new Array();
var prize = '';
var matches = 0;
var formView = false;
function scratch(canvas_id,canvasWidth,canvasHeight,top_offset,left_offset,bottom_src){
	top_offset += 74;
	left_offset += 10;
	var opacity = 0.1;
	var topImage = new Image();
	var bottomImage = new Image();
	bottomImage.src = 'http://<?=$base; ?>'+bottom_src;
	var isMouseDown = false;
	$('body').append('<canvas id="canvas'+canvas_id+'" class="canvas_style" width="'+canvasWidth+'" height="'+canvasHeight+'" style="top:'+top_offset+'px;left:'+left_offset+'px" />'); // Create the bottom canvas
	$('body').append('<canvas id="overlay'+canvas_id+'" class="canvas_style" width="'+canvasWidth+'" height="'+canvasHeight+'" style="top:'+top_offset+'px;left:'+left_offset+'px" />'); // Create the overlay canvas
	var overlay = document.getElementById('overlay'+canvas_id);
	var overlayctx = overlay.getContext('2d');

	function scratchOff(x, y) {
		mainctx.save();
		mainctx.beginPath();
		mainctx.arc(x,y,radius,0,Math.PI*2,false); // we don't fill or stroke the arc intentionally
		mainctx.clip();
		mainctx.globalAlpha = opacity;
		mainctx.drawImage(bottomImage, 0, 0);
		mainctx.restore();
		opacity = opacity + 0.01;
	}

	$('#overlay'+canvas_id).bind('vmousedown',function(e){
		isMouseDown = true;
		var relX = e.pageX - this.offsetLeft;
		var relY = e.pageY - this.offsetTop;
		scratchOff(relX, relY, true);
	});
	$('#overlay'+canvas_id).bind('vmousemove',function(e){
		var relX = e.pageX - this.offsetLeft;
		var relY = e.pageY - this.offsetTop;
		overlayctx.clearRect(0,0,canvasWidth,canvasHeight);
		if (isMouseDown) scratchOff(relX, relY, false);
	});
	$('#overlay'+canvas_id).bind('vmouseup',function(e){
		isMouseDown = false;
		if (touches.indexOf(canvas_id)<0){
			touches.push(canvas_id);
			if (prize == '') {
				prize = bottom_src;
				matches = 1;
			} else if (bottom_src == prize) {
				matches++;
			}
		}
		if (touches.length == 3){		
			for(i=6;i<14;i++){
				for(j=6;j<15;j++){
					scratchOff((i*9), (j*9), true);
				}
			}
			$('#game_end').css('display','block');			
			setTimeout(function() {optinPrompt(matches,prize)} , 1000);
		}		
	});

	var maincanvas = document.getElementById('canvas'+canvas_id);
	var mainctx = maincanvas.getContext('2d');
	var radius = 30;
	topImage.onload = function(){
		mainctx.drawImage(topImage, 0, 0);
	};
	topImage.src = "http://<?=$base; ?>images/scratch_top.png";
}

function optinPrompt(matches,prize){
	formView = true;
	if (matches == 3){		
		$('#prize').val(prize);
		$('#intro_text').html('To receive this prize to use on your next visit and hear about other great deals & offers, enter your details below:');
		$('#optin').css('background','url(http://<?=$base; ?>images/banner_won.png) 0 0 no-repeat');
		$('#prize_img').attr('src','http://<?=$base; ?>'+prize);
		$('#optin_won').css('display','block');
	} else {
		$('#prize').val('');
		$('#optin').css('background','url(http://<?=$base; ?>images/banner_lost.png) 0 0 no-repeat');
		$('#optin_lose').css('display','block');			
	}
	$('#optin').fadeIn("slow");
}

jQuery.extend({
	random: function(X) {
	    return Math.floor(X * (Math.random() % 1));
	},
	randomBetween: function(MinV, MaxV) {
	  return MinV + jQuery.random(MaxV - MinV + 1);
	},
	shuffle: function(arr) {
		for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);
		return arr;
	}
});

function landscape(){
	//$('body').css('-webkit-transform','rotate(-90deg)');
	$('#landscape').fadeIn("fast");
}

function portrait(){
	//$('body').css('-webkit-transform','rotate(0deg)');
	$('#landscape').fadeOut("fast");
}

$(document).ready(function() {	
<? if (!empty($_GET['force'])) echo 'optinPrompt(2,"");'; ?>
	
	$('span .ui-btn-text').css('display','none');		

	if (!formView){
		$(window).bind( 'orientationchange', function(e){
			if (e.orientation == "portrait"){			
				portrait();
			} else {
				landscape();
			}
		});		
		if (window.orientation == 90){
			landscape();
		}
	}
		
	$(document).bind("touchmove",function(event){
		event.preventDefault();
	});	
	var dummy = '<?=$dummy_img; ?>'; 
	var winicon = '<?=$win_img; ?>'; 
	
	var positions = new Array();
	positions[0] = winicon;
	positions[1] = winicon;
	positions[2] = winicon;
	positions[3] = winicon;
	positions[4] = winicon;
	positions[5] = winicon;
	positions[6] = winicon;
	positions[7] = dummy;	
	positions[8] = dummy;				
	positions = $.shuffle(positions);

	scratch('0',170,170,148,77,'images/'+positions[0]+'.jpg');
	scratch('1',170,170,148,290,'images/'+positions[1]+'.jpg');
	scratch('2',170,170,148,503,'images/'+positions[2]+'.jpg');
	scratch('3',170,170,361,77,'images/'+positions[3]+'.jpg');
	scratch('4',170,170,361,290,'images/'+positions[4]+'.jpg');
	scratch('5',170,170,361,503,'images/'+positions[5]+'.jpg');
	scratch('6',170,170,573,77,'images/'+positions[6]+'.jpg');
	scratch('7',170,170,573,290,'images/'+positions[7]+'.jpg');
	scratch('8',170,170,573,503,'images/'+positions[8]+'.jpg');	

	var image = new Image();
    image.src = "http://<?=$base; ?>images/banner_won.png";
    image.src = "http://<?=$base; ?>images/banner_lost.png";	
});

function submitForm(){
	$.post("/scratch_diageo/submit.php", { name: $('#name').val(), email: $('#email').val(), phone: $('#phone').val(), prize: $('#prize').val() });
	//$('#ContactForm').fadeOut('fast');
	$('#ContactForm').html('<h1>Thanks!</h1><p><strong>Stay tuned for great deals and offers!</strong></p>');
	//$('#formMessage').fadeIn('slow');
}
</script>  
<script type="text/javascript" src="js/forms.js"></script>
</head>

<body>
<div id="landscape" style="display:none">
<p style="font-size:24px;font-weight:bold;padding-top:100px">
	Oops!<br/><br/>This app must be used in Portait orientation.<br/><br/>
	Rotate your iPad to continue
</p>
</div>
<div id="game_end">
</div>
<div id="optin">	
	<div id="optin_won" class="optin_box">	
		<img id="prize_img" />
	</div>
	<div id="optin_lose" class="optin_box">
	</div>	
	<div id="formMessage" style="display:none"></div>
	<form id="ContactForm">		
	<input type="hidden" name="prize" id="prize" />
	  <fieldset>
		<div class="wrapper">
			<div id="intro_text">You can still be entered to win other prizes and receive coupon and deal alerts. Enter below:</div>
			<div>
				<div class="wrapper">
					<div class="col1">					
						<label class="name">
						<input type="text" name="name" id="name" class="input" value="Name:">
						<span class="error">*This is not a valid name.</span> <span class="empty">*This field is required.</span> </label>
						
						<label class="email">
						<input type="text" name="email" id="email" class="input" value="E-mail:">
						<span class="error">*This is not a valid email address.</span> <span class="empty">*This field is required.</span> </label>
					</div>
					<div class="col1">
						<label class="phone">
						<input type="tel" name="phone" id="phone" class="input" value="Cell:" onkeypress="return isNumberKey(event)" maxlength="10">
						<span class="error">*This is not a valid mobile phone number.</span> <span class="empty">*This field is required.</span> </label>										
					</div>	
				</div>

			</div>
		</div>	
	  </fieldset>
	  <a href="#" class="button1" data-type="submit" style="margin-left:180px;margin-top:10px;width:100px">Submit</a>
	</form>
</div>
<div class="over">
		<div class="main">
			<!--header -->
			<header>
				<h1><a href="#close" id="logo">Guinness</a></h1>
				<h3 style="padding-top:10px;">SCRATCH 'N WIN Instant Prizes!</h3>
			</header>			
			<!--header end-->
			<div style="font-size:18px;z-index:4;position:absolute;top:150px;left:250px">Scratch Any 3 Squares To Win!</div>
			<!--content -->
			<article id="content">
				<img src="images/pattern_bot_left.png" alt="" class="bot_left">
				<img src="images/pattern_bot_right.png" alt="" class="bot_right">
				<img src="images/pattern_top_left.png" alt="" class="top_left">
				<img src="images/pattern_top_right.png" alt="" class="top_right">
				<span class="box"></span>
				<div class="splash">
					<ul>
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>												
						<li><span><img src="images/scratch_top.png" alt=""></span></li>						
						<li><span><img src="images/scratch_top.png" alt=""></span></li>	
						<li><span><img src="images/scratch_top.png" alt=""></span></li>												
					</ul>
				</div>
			</article>
			<!--content end-->			
			<!--footer -->
			<footer>
				Diageo &copy; 2012
				<!-- {%FOOTER_LINK} -->
			</footer>
			<!--footer end-->
		</div>
</div>
</body>
</html>