<div id="ctc-wrp">
	<div id="ctc-header" onclick="ctcOpen()">
		Click to call
	</div>
	<div id="ctc-body">
		<form action="javascript:void(0);" onsubmit="call()">
		<div class="ctc-fld">
			<label class="ctc-lbl">Select Department</label>
			<select id="ctc-dpt">				
			</select>
		</div>
		<div class="ctc-fld">
			<label class="ctc-lbl">Enter your number</label>
			<input id="ctc-customerNum" name="ctc-customerNum" value=""  />
		</div>
		<div class="ctc-fld" id="ctc-submit-btn">
			<input type="submit" value="Call to me"  />			
		</div>
		<div class="ctc-fld" id="ctc-status">
			<span id="ctc-connecting-img" ></span>Connecting...
		</div>
		<div class="ctc-fld" id="ctc-msg"></div>
		</form>
		<div id="ctc-pwdby"><span>powered by </span><a href="http://phone91.com/" target="_blank">Phone91</a></div>
	</div>
</div>