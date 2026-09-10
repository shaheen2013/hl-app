

<!--get cookies
<body></body>
<script>
window.onload = function() {
    //set cookies
    var data = {'HLTI':2898923,'hlguid':'jkfhasldfkjhaslfdkjhasdlfkjhasflkjh'};
    for(var i in data){
        var key = i;
        var val = data[i];
        var elem = document.createElement("img");
        elem.setAttribute("src", "http://127.0.0.1/app/tucasa/hlSetCookie?name="+key+"&value="+val+'&path=tucasa');
        elem.setAttribute("style", "display:none");
        document.body.appendChild(elem);
    }

    //get cookies
    /*var cookie;

    var f = function (e) {
        var falem = null;
        cookie =  e.data || falem;
        alert(cookie);
        this.removeEventListener('message',f,false);
    };

    window.addEventListener("message", f);

    var elem = document.createElement("iframe");
    elem.setAttribute("src", "http://127.0.0.1/app/tucasa/hlGetCookie");
    elem.setAttribute("style", "display:none");
    document.body.appendChild(elem);*/

    //Codigo incrustado en el cliente (seguramente el listener puede ir en el builder y no estorbar aqui)
    var f = function (e) {
        var falem = null;
        cookie =  e.data || falem;
        alert(cookie);
        document.getElementById("hotelinkingWidgetContent").contentWindow.postMessage(cookie, '*');
    };
    window.addEventListener("message", f);
    var hotelinkingWidgetContainer = document.createElement('div');
	hotelinkingWidgetContainer.setAttribute("id", "app");
	document.body.appendChild(hotelinkingWidgetContainer);
    (function (w,d,s,o,f,js,fjs) {
        w['hlwidget']=o;w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) };
        js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
        js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs);
    }(window, document, 'script', 'hlw', 'https://camuflaje.org/wp-content/widget/index.js'));
    hlw('message', 'Hello world!');
    hlw('uid', 1344234324);
}
    

    

</script>-->


