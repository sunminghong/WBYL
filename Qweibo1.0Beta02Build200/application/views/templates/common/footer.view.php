<?php
/******************************************************************************
 * Author: yourname
 * Last modified: 2010-11-22 01:26
 * Filename: footer.view.php
 * Description: 页脚模板
******************************************************************************/
?>
<?php
@include_once MB_ADMIN_DIR.'/data/verify_icon.php';
if(isset($verify_icon_file_name))
{
if(file_exists(MB_ROOT_DIR."/style/images/admin/upload/".$verify_icon_file_name))
{echo "<style type=\"text/css\">.renzheng{background-image:url(style/images/admin/upload/".$verify_icon_file_name.");}</style>\n";}
}		
?>
<div class="footer" id="footer">
	<?php if( isset($footstring) && !empty($footstring) ){ ?>
		<?php echo $footstring;?>
	<?php }else{ ?>
	Copyright &copy; 1998 - 2011 All Rights Reserved.
	<?php } ?>
</div>