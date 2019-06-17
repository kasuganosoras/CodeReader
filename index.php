<?php
function curl_request($url, $post = '', $cookie = '', $headers = '', $returnHeader = 0) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	curl_setopt($curl, CURLOPT_REFERER, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	if($post) {
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
	}
	if($cookie) {
		curl_setopt($curl, CURLOPT_COOKIE, $cookie);
	}
	if($headers) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	}
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if(curl_errno($curl)) {
		return curl_error($curl);
	} elseif($httpCode !== 200) {
		return "Error: server return http code {$httpCode}";
	}
	curl_close($curl);
	return $data;
}

$fchar = ["\\", "*", ":", "?", "<", ">", "|", '"'];
if($_POST['codefile']) {
	foreach($fchar as $char) {
		if(stristr($_POST['codefile'], $char)) {
			exit("Invalid file name");
		}
	}
	if(!preg_match("/^[A-Za-z0-9\-\_\/]+$/", $_POST['project'])) {
		exit("Invalid project name");
	}
	$project  = $_POST['project'];
	$codefile = $_POST['codefile'];
	$data     = curl_request("https://raw.githubusercontent.com/{$project}/master/{$codefile}");
	echo $data;
	exit;
}
$cssname = "github.css";
$bgcolor = "#F1F1F1";
$spcolor = "#000";
if(isset($_GET['darkmode'])) {
	$cssname = "monokai.min.css";
	$bgcolor = "#111111";
	$spcolor = "#FFF";
}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=11">
		<link rel="stylesheet" href="https://cn.tql.ink:4443/css/bootstrap.min.css" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cn.tql.ink:4443/css/highlight.js/<?php echo $cssname; ?>">
		<title>Code Reader</title>
		<style>
			body {
				background: #F1F1F1;
				color: #333;
				font-family: Consolas;
				font-size: 16px;
				transition-duration: 0.5s;
			}
			.fontbox {
				width: 100%;
				height: 100%;
				font-weight: 300;
			}
			.fontbox tr {
				height: 100%;
			}
			.pd32 {
				padding: 32px;
			}
			#inputblock {
				color: <?php echo $spcolor; ?> ! important;
			}
			#inputblock, .readme {
				display: inline-block;
			}
			#fontbox_container {
				font-family: Consolas;
				word-wrap: break-word;
				white-space: pre-wrap;
			}
			.readme {
				max-width: 70%;
				margin: 0;
			}
			hr {
				border-top: 5px solid #2d2d2d;
			}
		</style>
		<style type="text/css">.pageid{margin-bottom:-26px}code{color:#484848;background-color:#f5f5f5;border-radius:0px;border:1px solid #dadada;}pre>code{color:unset;background-color:unset;border-radius:unset;border:0px;}.post-a {color: #000;text-decoration: none ! important;}.post-box {padding: 12px 20px 12px 20px;border-bottom: 1px solid rgba(0,0,0,0.07);cursor: pointer;border-left: 0px solid rgba(66, 66, 66, 0);transition-duration: 0.3s;}.post-box:hover {transition-duration: 0.3s;border-left: 5px solid rgba(66, 66, 66, 0.15);}.thread h2 {border-bottom: 1px solid rgb(238,238,238);padding-bottom: 10px;}.editor-preview pre, .editor-preview-side pre{padding: 0.5em;}.hljs{background: unset ! important;padding: 0px;}.CodeMirror{height: calc(100% - 320px);min-height: 360px;}.msgid{font-family:Consolas;}.tooltip {word-break: break-all;}h2 a{font-weight: 400;}body{/*background:url(https://i.natfrp.org/cbf5973ce9da283bc9abe307cdea7f30.jpg);*/font-family:'-apple-system','BlinkMacSystemFont','Segoe UI','Helvetica','Arial','sans-serif','Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol' ! important;font-weight:400;background-attachment:fixed;background-size:cover;background-repeat:no-repeat;background-position:center;}h2 a{color: #000;} h2 a:hover{color: #000; text-decoration: none;}.full-width{width: 100%;}.thread img{vertical-align:text-bottom ! important;max-width:100% ! important;margin-top:8px;margin-bottom:8px;}.thread table{display:block;width:100%;overflow:auto;margin-bottom:8px;}.thread table tr{background-color:#fff;border-top:1px solid #c6cbd1;}.thread table tr:nth-child(2n){background-color:#f6f8fa;}.thread table th,.thread table td{padding:6px 13px;border:1px solid #dfe2e5;font-size:14px;}.thread pre{margin-bottom:16px;}pre{border:none ! important;}blockquote{font-size:15px ! important;}@media screen and(max-width:768px){.copyright{text-align:center;}}</style>
	</head>
	<body>
		<div class="fontbox">
			<div class="pd32" id="box1">
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<h2>设置项目信息</h2>
					<p>Github 项目（例子：<code>kasuganosoras/cloudflare-worker-blog）</code></p>
					<p><input type="text" id="project_input" class="form-control" value="kasuganosoras/cloudflare-worker-blog" size="256"></p>
					<p>文件名称（例子：<code>README.md</code>，<code>src/org/natfrp/client/main.java</code>）</p>
					<p><input type="text" id="codefile_input" class="form-control" value="README.md" size="512"></p>
					<p>代码类型（例子：<code>java</code>，<code>php</code>，<code>markdown</code>）</p>
					<p><input type="text" id="codetype_input" class="form-control" value="markdown" size="16"></p>
					<p>延迟时间（可以是 <code>1</code> 至 <code>150</code> 之间的任意数字）</p>
					<p><input type="number" id="interval_input" class="form-control" value="50" min="1" max="150" onchange="if(this.value > 150){this.value=150;}if(this.value < 1){this.value=1;}"></p>
					<p><button class="btn btn-default full-width" onclick="getCode()">执行</button></p>
				</div>
			</div>
			<div class="pd32" id="box2" style="display: none;">
				<span id="fontbox_container"></span><div id='inputblock'>▋</div>
			</div>
		</div>
		<script src="https://cn.tql.ink:4443/js/jquery.min.js"></script>
		<script src="https://cn.tql.ink:4443/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<script src="https://cn.tql.ink:4443/js/highlight.min.js"></script>
		<script src="https://cn.tql.ink:4443/js/highlight.pack.js"></script>
		<script>
			hljs.initHighlightingOnLoad();
			var project  = "kasuganosoras/cloudflare-worker-blog";
			var codefile = "README.md";
			var codetype = "markdown";
			var interval = 20;
			function getCode() {
				if(project_input.value != "") {
					project = project_input.value;
				}
				if(codefile_input.value != "") {
					codefile = codefile_input.value;
				}
				if(codetype_input.value != "") {
					codetype = codetype_input.value;
				}
				if(interval_input.value != "") {
					interval = parseInt(interval_input.value);
				}
				document.body.style.background = "<?php echo $bgcolor; ?>";
				document.body.style.color = "<?php echo $spcolor; ?>";
				$("#box1").css({"display":"none"});
				$("#box2").fadeIn(300);
				fontbox_container.innerHTML = "Loading...";
				var htmlobj = $.ajax({
					type: 'POST',
					data: {
						project: project,
						codefile: codefile
					},
					async: true,
					success: function() {
						str = htmlobj.responseText;
						setTimeout(PrintText, 1000);
					},
					error: function() {
						alert(htmlobj.responseText);
					}
				});
			}
			function PrintText() {
				var i = 0;
				var storage = "";
				var hiddenblock = false;
				setInterval(function() {
					var tmpstr = str.charAt(i);
					if(storage != str) {
						storage += tmpstr;
						var tmptxt = hljs.highlight(codetype, storage);
						fontbox_container.innerHTML = tmptxt.value;
						i++;
						document.body.scrollTop = document.body.scrollHeight;
					}
				}, interval);
				setInterval(function() {
					if(hiddenblock) {
						inputblock.style.opacity = '1';
						hiddenblock = false;
					} else {
						inputblock.style.opacity = '0';
						hiddenblock = true;
					}
				}, 500);
			}
		</script>
	</body>
</html>
