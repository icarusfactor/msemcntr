<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/msemcntr/assets/view.css" media="all">
<script type="text/javascript" src="<?php echo plugins_url(); ?>/msemcntr/assets/view.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/msemcntr/assets/tabs.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/msemcntr/assets/tabs.css">

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'SETTINGS')">SETTINGS</button>
  <button class="tablinks" onclick="openTab(event, 'MANUAL')">MANUAL</button>
  <button class="tablinks" onclick="openTab(event, 'ABOUTINFO')">EMMACNX</button>
</div>

<div id="SETTINGS" class="tabcontent">
<h3 style="font-size: 14px;"  >SETTINGS</h3>

<form id="form_111687" class="appnitro"  method="post" action="">
                
                <div class="form_description">
                        <h3 style="font-size: 14px;font-weight: 600;"  >REQUIRED EMMA ACCOUNT INFO</h3>
                        <h3 id="display_rec" ><span style="font-size: 10px;color: red;" >ALL VALUES ARE !REQUIRED!</span></h3>
                </div>               


               <li id="li_4" >
                <label class="description" for="element_4">Emma Account ID</label>                
                <div>
                <input id="element_4" name="element_4" class="element text small" type="text" maxlength="255" value=""/> 
                </div>
                <p class="guidelines" id="guide_4"><small>EMMA account number.</small></p> 
                </li>             


               <li id="li_5" >
                <label class="description" for="element_5">Emma Public Key</label>                
                <div>
                <input id="element_5" name="element_5" class="element text small" type="text" maxlength="255" value=""/> 
                </div>
                <p class="guidelines" id="guide_5"><small>EMMA Public Key.</small></p> 
                </li>        
        
              <li id="li_6" >
                <label class="description" for="element_6">Emma Private Key</label>                
                <div>
                <input id="element_6" name="element_6" class="element text small" type="text" maxlength="255" value=""/> 
                </div>
                <p class="guidelines" id="guide_6"><small>EMMA Private Key.</small></p> 
                </li>          
              
                
                 <li class="buttons">
                            <input type="hidden" name="form_id" value="111687" />
                            <input type="hidden" name="if_ajaxid"  value="003">
                            
                            <input id="saveForm" class="button_text" type="submit" name="submit" value="SAVE" />
                </li>
                        </ul>
</form>


</div>

<div id="ABOUTINFO" class="tabcontent">
<img width="400" height="300" src="<?php echo plugins_url(); ?>/msemcntr/assets/emmacnx_logo.png" />
</div>



<div id="MANUAL" class="tabcontent">
<ul >
<li id="li_manual" >
<div>
<label class="description" for="manual">MANUAL:</label>

<pre>
*To find your API and KEY data. Open your EMMA account.
    1: Click on your name at the top left hand side after you log in. 
    2: Select profile. 
    3: Click on the "Api key" sub menu.
    4: Copy the Account ID.
    5: Copy the Public Key.
    6: If you do not see a private key, then Click Regenrate api key.
    7: Copy Private key. 
    8: Click on settings in your Wordpress admin area for this plugin.
    9: Enter the three items. Account ID, Public and Private key. 
    10: Click Save.
    11:  If you get a notification saying its saved  , the plugin is ready to detect Modal Survey updates.
    12:  If you did'nt get a notifcation ,you may have wrong information or another private key has been generated. 
    
* EMMA Account has to be correct or the data will be rejected. 
* All account data has to be entered or will also be rejected. 
* This plugin can be active without Modal Survey plugin active.
* When the Modal Survey Plugin is active and a survey submitted this plugin will respond to the user update trigger
    and send the user's email information to your associated EMMA account. 
* Email and First Name and Last Name will be sent to EMMA and enrolled into accounts contact list. 

</pre>

</div> <!-- END OF MANUAL  -->

<script> 
//  //load up Wordpress options into input fields 
 var input_accountid = document.getElementById("element_4"); 
 var input_publickey = document.getElementById("element_5");
 var input_privatekey = document.getElementById("element_6");

var msemcntrObj = JSON.parse( '<?php echo addslashes( get_option('msemcntr_configs' ) ); ?>' );

input_accountid.value = msemcntrObj.accountid; 
input_publickey.value = msemcntrObj.publickey; 
input_privatekey.value = msemcntrObj.privatekey; 



  //Start with About in view
  setTab('ABOUTINFO')




</script> 



