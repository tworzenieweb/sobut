<?php 
class likeboxfree extends Module {
	function __construct(){
		$this->name = 'likeboxfree';
		$this->tab = 'Other';
		$this->version = '1.7';
        $this->module_key = '';
        $this->dir = '/modules/likeboxfree/';
		parent::__construct();
		$this->displayName = $this->l('Fanpage Likebox Free');
		$this->description = $this->l('This module add special likebox block with your fanpage on Facebook. Now anybody can like your facebook fanpage!');
        
	}
    
	function install(){
        if (parent::install() == false 
	    OR $this->registerHook('rightColumn') == false
	    OR $this->registerHook('leftColumn') == false
	    OR $this->registerHook('home') == false
	    OR $this->registerHook('footer') == false
	    OR Configuration::updateValue('likeboxfree_position', '2') == false
        OR Configuration::updateValue('likeboxfree_width', '191') == false
        OR Configuration::updateValue('likeboxfree_height', '300') == false
        OR Configuration::updateValue('likeboxfree_colorscheme', 'light') == false
        OR Configuration::updateValue('likeboxfree_showfaces', '1') == false
        OR Configuration::updateValue('likeboxfree_bordercolor', '000000') == false
        OR Configuration::updateValue('likeboxfree_showstream', '0') == false
        OR Configuration::updateValue('likeboxfree_showheader', '0') == false
        OR Configuration::updateValue('likeboxfree_fanpageurl', 'http://www.facebook.com/MyPresta') == false       
        ){
            return false;
        }
        return true;
	}
    
	public function getContent(){
	   $output="";
		if (Tools::isSubmit('submit_settings')){
            if (Tools::getValue('new_likebox_showheader')=="on"){$showheader="1";}else{$showheader="0";}
            if (Tools::getValue('new_likebox_showstream')=="on"){$showstream="1";}else{$showstream="0";}
            if (Tools::getValue('new_likebox_showfaces')=="on"){$showfaces="1";}else{$showfaces="0";}
            Configuration::updateValue('likeboxfree_position', Tools::getValue('new_likebox_position'), true);
			Configuration::updateValue('likeboxfree_width', Tools::getValue('new_likebox_width'), true);
            Configuration::updateValue('likeboxfree_height', Tools::getValue('new_likebox_height'), true);
            Configuration::updateValue('likeboxfree_colorscheme', Tools::getValue('new_likebox_colorscheme'), true);
            Configuration::updateValue('likeboxfree_showfaces', $showfaces, true);
            Configuration::updateValue('likeboxfree_bordercolor', Tools::getValue('new_likebox_bordercolor'), true);
            Configuration::updateValue('likeboxfree_showstream', $showstream, true);
            Configuration::updateValue('likeboxfree_showheader', $showheader, true);
            Configuration::updateValue('likeboxfree_fanpageurl', Tools::getValue('new_likebox_fanpageurl'), true);
            $output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';                                    
        }	   
        $output.="";
        return $output.$this->displayForm();
	}

	public function displayForm(){
	   $likebox_showstream_checked="0";
       $likebox_showheader_checked="0";
       $likebox_showfaces_checked="0";
       
	   
	   $likebox_position = Configuration::get('likeboxfree_position');	
		$new_likebox1=""; $new_likebox2=""; $new_likebox3=""; $new_likebox4="";
		if ($likebox_position=="4"){$new_likebox4="checked=\"yes\"";}
	   	if ($likebox_position=="3"){$new_likebox3="checked=\"yes\"";}
		if ($likebox_position=="2"){$new_likebox2="checked=\"yes\"";}
		if ($likebox_position=="1"){$new_likebox1="checked=\"yes\"";}

       
        $likebox_fanpageurl = Configuration::get('likeboxfree_fanpageurl');
        $likebox_width = Configuration::get('likeboxfree_width');
        $likebox_height = Configuration::get('likeboxfree_height');
        $likebox_colorscheme = Configuration::get('likeboxfree_colorscheme');
            if ($likebox_colorscheme=="light"){$selected_light="SELECTED"; $selected_dark=""; $likebox_colorscheme_bg="white";}
            if ($likebox_colorscheme=="dark"){$selected_dark="SELECTED"; $selected_light=""; $likebox_colorscheme_bg="black";}
        $likebox_showfaces = Configuration::get('likeboxfree_showfaces');
            if ($likebox_showfaces=="1"){$likebox_showfaces_checked="checked='YES'";}
        $likebox_bordercolor = Configuration::get('likeboxfree_bordercolor');    
        $likebox_showstream = Configuration::get('likeboxfree_showstream');
            if ($likebox_showstream=="1"){$likebox_showstream_checked="checked='YES'";}
        $likebox_showheader = Configuration::get('likeboxfree_showheader');
            if ($likebox_showheader=="1"){$likebox_showheader_checked="checked='YES'";}      
            
        
		return'
        <script type="text/javascript" src="../modules/likeboxfree/script.js"></script>
        <script type="text/javascript" src="../modules/likeboxfree/jscolor/jscolor.js"></script>
        <img src="'.$this->_path.'logo.gif" alt="" title="" style="margin-bottom:10px;"/>
        <iframe src="http://mypresta.eu/content/uploads/2012/10/facebook_advertise.html" width="100%" height="130" border="0" style="border:none;"></iframe>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
            <div style="display:block; margin:auto; overflow:hidden; ">
                    <div style="clear:both; display:block; ">
                        <fieldset>
            				<legend>'.$this->l('Likebox configuration').'</legend>
                            <h3 style="margin-bottom:0px; padding-bottom:0px;">'.$this->l('LikeBox Visual Settings').'</h3>
                            <hr style="margin-top:5px;">
                            
                            
			                <div style="clear:both;display:block;">
							    <label>'.$this->l('Left column').':</label>
								<div class="margin-form" valign="middle">
			                        <div style="margin-top:7px;">
									<input type="radio" name="new_likebox_position" value="1" '.$new_likebox1.'> '.$this->l('yes').'
			                        </div>
								</div>
			                </div>
			                <div style="clear:both;display:block;">
							    <label>'.$this->l('Right column').':</label>
								<div class="margin-form" valign="middle">
			                        <div style="margin-top:7px;">
									<input type="radio" name="new_likebox_position" value="2" '.$new_likebox2.'> '.$this->l('yes').'
			                        </div>
								</div>
			                </div>
			                <div style="clear:both;display:block;">
							    <label>'.$this->l('Homepage').':</label>
								<div class="margin-form" valign="middle">
			                        <div style="margin-top:7px;">
									<input type="radio" name="new_likebox_position" value="3" '.$new_likebox3.'> '.$this->l('yes').'
			                        </div>
								</div>
			                </div>
							
			                <div style="clear:both;display:block;">
							    <label>'.$this->l('Footer').':</label>
								<div class="margin-form" valign="middle">
			                        <div style="margin-top:7px;">
									<input type="radio" name="new_likebox_position" value="4" '.$new_likebox4.'> '.$this->l('yes').'
			                        </div>
								</div>
			                </div>									                
				
				                            
            				<label>'.$this->l('Facebook Page URL').'</label>
            					<div class="margin-form">
            						<input type="text" style="width:400px;" value="'.$likebox_fanpageurl.'" id="new_likebox_fanpageurl" name="new_likebox_fanpageurl" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('The URL of the Facebook Page for LikeBox').'</p>
                                </div>
                                 
            				<label>'.$this->l('Width').'</label>
            					<div class="margin-form">
            						<input type="text" style="width:100px;" value="'.$likebox_width.'" id="new_likebox_width" name="new_likebox_width" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('The width of the LikeBox plugin').'</p>
                                </div> 
                   
            				<label>'.$this->l('Height').'</label>
            					<div class="margin-form">
            						<input type="text" style="width:100px;" value="'.$likebox_height.'" id="new_likebox_height" name="new_likebox_height" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('The height of the LikeBox plugin').'</p>
                                </div>
                                                  
            				<label>'.$this->l('Color Scheme').'</label>
            					<div class="margin-form">
                                    <select name="new_likebox_colorscheme" id="new_likebox_colorscheme" onchange="changelikebox();">
                                        <option value="dark" '.$selected_dark.'>'.$this->l('dark').'</option>
                                        <option value="light" '.$selected_light.'>'.$this->l('light').'</option>
                                    </select>
                                    <p class="clear">'.$this->l('The color scheme of the LikeBox plugin. Note that background is always transparent to match your background color. This setting changes the foreground color for work well on light or dark backgrounds').'</p>
                                </div>
                                
            				<label>'.$this->l('Show Faces').'</label>
            					<div class="margin-form">
            						<input type="checkbox" '.$likebox_showfaces_checked.' id="new_likebox_showfaces" name="new_likebox_showfaces" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('Show profile photos in the LikeBox plugin').'</p>
                                </div>
                                                                                                                          
            				<label>'.$this->l('Border Color: ').'</label>
            					<div class="margin-form">
            						<input style="width:100px;" class="color" value="'.$likebox_bordercolor.'" id="new_likebox_bordercolor" name="new_likebox_bordercolor" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('The border color of the LikeBox plugin. Click and check color').'</p>
                                </div>    
                                
            				<label>'.$this->l('Show Stream').'</label>
            					<div class="margin-form">
            						<input type="checkbox" '.$likebox_showstream_checked.' id="new_likebox_showstream" name="new_likebox_showstream" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('Show profile stream (on wall) in the LikeBox plugin').'</p>
                                </div>
                                                                
            				<label>'.$this->l('Show Header').'</label>
            					<div class="margin-form">
            						<input type="checkbox" '.$likebox_showheader_checked.' id="new_likebox_showheader" name="new_likebox_showheader" onchange="changelikebox();">
                                    <p class="clear">'.$this->l('Show FIND US ON FACEBOOK bar at top. Only show when either stream or faces are present').'</p>
                                </div>
                                
                                
                                                                                                                                                         
                                <div align="center">
            				        <input type="submit" name="submit_settings" value="'.$this->l('Save Settings').'" class="button" />
                                </div>
                        </fieldset>                    
                    </div>
                    <div style="hright:auto; clear:both; display:block; margin-top:20px; text-align:center;">
                        <fieldset>
                            <legend>'.$this->l('Likebox Preview').'</legend>
                            <div id="likeboxpreview">
                            
                            <div style="margin:auto; background:'.$likebox_colorscheme_bg.'; display:block; width:'.$likebox_width.'px;"><iframe src="//www.facebook.com/plugins/likebox.php?href='.$likebox_fanpageurl.'&amp;width='.$likebox_width.'&amp;height='.$likebox_height.'&amp;colorscheme='.$likebox_colorscheme.'&amp;show_faces='.$likebox_showfaces.'&amp;border_color='.$likebox_bordercolor.'&amp;stream='.$likebox_showstream.'&amp;header='.$likebox_showheader.'&amp;appId=112465995526913" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$likebox_width.'px; height:'.$likebox_height.'px;" allowTransparency="true"></iframe></div>                            
                            
                            </div>
                        </fieldset>
                    </div>
            </div>
		</form>
        ';
	}   
   
    public function setlikeboxoptions(){
        $likebox_showstream_checked="0";
        $likebox_showheader_checked="0";
        $likebox_showfaces_checked="0";
        $likebox_fanpageurl = Configuration::get('likeboxfree_fanpageurl');
        $likebox_width = Configuration::get('likeboxfree_width');
        $likebox_height = Configuration::get('likeboxfree_height');
        $likebox_colorscheme = Configuration::get('likeboxfree_colorscheme');
            if ($likebox_colorscheme=="light"){$selected_light="SELECTED"; $selected_dark=""; $likebox_colorscheme_bg="white";}
            if ($likebox_colorscheme=="dark"){$selected_dark="SELECTED"; $selected_light=""; $likebox_colorscheme_bg="black";}
        $likebox_showfaces = Configuration::get('likeboxfree_showfaces');
            if ($likebox_showfaces=="1"){$likebox_showfaces_checked="checked='YES'";}
        $likebox_bordercolor = Configuration::get('likeboxfree_bordercolor');    
        $likebox_showstream = Configuration::get('likeboxfree_showstream');
            if ($likebox_showstream=="1"){$likebox_showstream_checked="checked='YES'";}
        $likebox_showheader = Configuration::get('likeboxfree_showheader');
            if ($likebox_showheader=="1"){$likebox_showheader_checked="checked='YES'";} 
        $array['likeboxfree_fanpageurl']=$likebox_fanpageurl;
        $array['likeboxfree_width']=$likebox_width;
        $array['likeboxfree_height']=$likebox_height;
        $array['likeboxfree_colorscheme']=$likebox_colorscheme;
        $array['likeboxfree_showfaces']=$likebox_showfaces;
        $array['likeboxfree_showheader']=$likebox_showheader;
        $array['likeboxfree_showstream']=$likebox_showstream;
        $array['likeboxfree_bordercolor']=$likebox_bordercolor;
        return $array;    
    }
   
	function hookrightColumn($params){
		if (Configuration::get('likeboxfree_position')==2){
		    $likeboxarray=$this->setlikeboxoptions();
	        global $smarty;
	        $smarty->assign(array('likebox' => $likeboxarray));
			return $this->display(__FILE__, 'rightcolumn.tpl');
		}	
	}
	function hookleftColumn($params){
		if (Configuration::get('likeboxfree_position')==1){
		    $likeboxarray=$this->setlikeboxoptions();
	        global $smarty;
	        $smarty->assign(array('likebox' => $likeboxarray));
			return $this->display(__FILE__, 'rightcolumn.tpl');
		}	
	}
	function hookHome($params){
		if (Configuration::get('likeboxfree_position')==3){
		    $likeboxarray=$this->setlikeboxoptions();
	        global $smarty;
	        $smarty->assign(array('likebox' => $likeboxarray));
			return $this->display(__FILE__, 'rightcolumn.tpl');
		}	
	}
	function hookFooter($params){
		if (Configuration::get('likeboxfree_position')==4){
		    $likeboxarray=$this->setlikeboxoptions();
	        global $smarty;
	        $smarty->assign(array('likebox' => $likeboxarray));
			return $this->display(__FILE__, 'rightcolumn.tpl');
		}	
	}		   
}
?>