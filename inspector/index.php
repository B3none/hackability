<!doctype HTML>
<html>
<head>
<meta charset="UTF-8" />
<title>Inspector</title>
<script src="js/inspector.js"></script>
<link href="styles.css" rel="stylesheet" />
</head>
<body>
<form autocomplete="off" onsubmit="return false;">
    <input type="text" id="input" autofocus placeholder="Shortcuts:RETURN (Eval js), CTRL+RETURN (Exec HTML), SHIFT+RETURN (Eval but don't inspect), CTRL+Backspace (Clear), CTRL+SHIFT+Backspace (Clear history)" />
</form>
<div id="output"></div>
<div id="usage">
  <h1>Usage:</h1>
  <ul>
    <li>Pass <a href="index.html?input=window">input</a> to the inspector</li>
    <li>Only show <a href="index.html?input=window&interesting=true">interesting</a> properties</li>
    <li>Filter properties by <a href="index.html?input=window&regex=__$">regex</a></li>
    <li>Execute <a href="index.html?input=window&js=obj[prop](prop)">js</a> on every property
        <ul>
          <li>obj contains a reference to current enumerated object.</li>
          <li>prop contains a string of the property name</li>
        </ul>
    </li>
    <li>Filter properties by <a href="index.html?input=window&type=string">type</a></li>
    <li>
      The <a href="index.html?html=<b>1337</b>">html</a> parameter can be used to pass html
      <ul>
        <li>You can also pass html and then <a href="index.html?input=window&html=<iframe src=//burpcollaborator.net id=x>&type=x-domain window">inspect</a> it</li>
      </ul>
    </li>
    <li>
      Use the <a href="index.html?input=window&blind=1">blind</a> option to save a copy of the enumeration html when you can't see it's output
      <ul>
          <li>The results are stored <a href="display.php">here</a></li>
          <li>Note you can't interact with this display but you can send multiple requests to determine an objects structure</li>
      </ul>
    </li>
    <li>You can <a href="index.html?input=window&regex=^.{1,3}$&js=alert(prop)&type=function">combine</a> all parameters</li>
  </ul>
</div>
<script>
!function(){
  var params = inspector.getParams(location.search.slice(1));
  inspector.setDomObjects({input: document.getElementById('input'), output: document.getElementById('output'), usage: document.getElementById('usage')});
  if(location.search.length) {
    if(params.blind) {
      inspector.setCallbacks({sendRootHTML: function(objName, html){
        var xhr = new XMLHttpRequest();
        html = '<div class="output"><div>'+html+'</div></div>';
        xhr.open('POST', 'save.php', true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send('objName='+encodeURIComponent(objName)+'&html='+encodeURIComponent(html));
      }});
    }
    if(params.html) {
      inspector.inspect(params.html, true);
    }
    window.onload = function(){
      if(typeof params.input === 'string' && params.input.length) {
        inspector.inspect(params.input, false, false, params);
      }
    };
  } else if(location.hash.length) {
    inspector.inspect(location.hash.slice(1));
  }
}();
</script>
</body>
</html>