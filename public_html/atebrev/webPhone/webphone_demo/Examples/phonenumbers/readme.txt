
Turn all strings on your website which looks like a phone number to a clickable link


The webphone can load its parameters also from the webpage URL. So you can create a html file as mentioned above with the webphone embedded in “applet” tag and then simply launch the page like this:
For this, just copy-paste the code below into your html:
<script type="text/JavaScript" src="http://www.mizu-voip.com/G/webphone/skins/wp_tel.js"></script>
<script type="text/javascript">
                wp_tel.serveraddress = ''; // yoursipdomain.com your VoIP server IP address or domain name
                wp_tel.username = '';
                wp_tel.password = '';
                wp_tel.md5 = '';  //use either password or md5 (leave it empty if you set the password)
                wp_tel.skin = ' skin_click2callB'; // skin folder name
</script>

As you can seem this refers to Mizutech website. You can easily host the same on your webserver, just copy the webphone.jar, the wp_tel.js and optionally a skin to your web directory.
