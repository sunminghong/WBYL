package web.view.demo;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.InputStream;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.ContentResolver;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.Log;
import android.view.ContextThemeWrapper;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ImageButton;
import android.widget.ImageView;


import android.util.Base64;  


public class WebViewDemoActivity extends Activity {    
	private WebView mWebView;
	private ImageView myImage;
	private byte[] mContent;

	private static final int ACTIVITY_IMAGE_CAPTURE = 1000;
	private static final int ACTIVITY_GET_IMAGE = 2000;
	private static final String MIME_TYPE_IMAGE_JPEG = "image/*";
	
	Uri originalUri;
	private int imgWidth=320;
	private int imgHeight=480;

	
    @Override 
    public void onCreate(Bundle icicle) { 
        super.onCreate(icicle); 
        setContentView(R.layout.main); 
        mWebView = (WebView) findViewById(R.id.webview);
        WebSettings webSettings = mWebView.getSettings(); 
        webSettings.setSavePassword(false); 
        webSettings.setSaveFormData(false); 
        webSettings.setJavaScriptEnabled(true); 
        webSettings.setSupportZoom(true);
        mWebView.addJavascriptInterface(new DemoJavaScriptInterface(), "android");
        mWebView.loadUrl("file:///android_asset/demo.html"); 
    }
    
    public boolean onKeyDown(int keyCode, KeyEvent event) {
    	// 如果是返回键,直接返回到桌面
    	if(keyCode == KeyEvent.KEYCODE_BACK || keyCode == KeyEvent.KEYCODE_HOME){
               showExitGameAlert();
    	}
     
    	return super.onKeyDown(keyCode, event);
    }
    private void showExitGameAlert() {
    	final AlertDialog dlg = new AlertDialog.Builder(this).create();
    	dlg.show();
    	Window window = dlg.getWindow();
            // *** 主要就是在这里实现这种效果的.
            // 设置窗口的内容页面,shrew_exit_dialog.xml文件中定义view内容
    	window.setContentView(R.layout.shrew_exit_dialog);
            // 为确认按钮添加事件,执行退出应用操作
    	ImageButton ok = (ImageButton) window.findViewById(R.id.btn_ok);
    	ok.setOnClickListener(new View.OnClickListener() {
    		public void onClick(View v) {
    			System.exit(0); // 退出应用...
    		}
    	});
     
            // 关闭alert对话框架
            ImageButton cancel = (ImageButton) window.findViewById(R.id.btn_cancel);
            cancel.setOnClickListener(new View.OnClickListener() {
    	  public void onClick(View v) {
    		  dlg.cancel();
    		}
    	  });
    }
    

	public void alert(String msg) {
		mWebView.loadUrl("javascript:alertcallback('"+ msg +"')");
	}

	public void captureV2() {
		// 调用相机
		Intent mIntent = new Intent("android.media.action.IMAGE_CAPTURE");
		startActivityForResult(mIntent, ACTIVITY_IMAGE_CAPTURE);
		alert("capture is open!");
		//startActivity(mIntent);
	}
	
	public void getimage1(int imgW,int imgH) {
		imgWidth=imgW;
		imgHeight=imgH;

		Intent intent = new Intent();
        /* 开启Pictures画面Type设定为image */
        intent.setType("image/*");
        /* 使用Intent.ACTION_GET_CONTENT这个Action */
        intent.setAction(Intent.ACTION_GET_CONTENT); 
        /* 取得相片后返回本画面 */
        startActivityForResult(intent, ACTIVITY_GET_IMAGE);
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		alert("onActivityResult="+resultCode+"/"+requestCode);
		if (resultCode == RESULT_OK) {
			switch(requestCode) {
			case ACTIVITY_IMAGE_CAPTURE:
				alert("return from camera!");
				Bundle dataBundle = data.getExtras();
	
				Bitmap btp = (Bitmap) dataBundle.get("data");

				break;
			case ACTIVITY_GET_IMAGE:

				alert("return from gallery!");
				Uri uri = data.getData();
				Log.e("uri", uri.toString());
				ContentResolver cr = this.getContentResolver();

				Bitmap bitmap = BitmapFactory.decodeStream(cr.openInputStream(uri));					
				break;
			}	
			
			data.putExtra(MediaStore.EXTRA_OUTPUT, btp);
			btp = Bitmap.createScaledBitmap(btp, imgWidth,imgHeight, true);
			
			ByteArrayOutputStream baos = new ByteArrayOutputStream();
			bitmap.compress(Bitmap.CompressFormat.JPEG, 100, baos);
			byte[] buffer = baos.toByteArray();
			
			String data = Base64.encodeToString(buffer, 0, buffer.length,Base64.DEFAULT); 


			//myImage = (ImageView) findViewById(R.id.show_img);	
			//myImage.setImageBitmap(btp);	
			//myImage.setPadding(2, 2, 2, 2);
		}
	}

    
    final class DemoJavaScriptInterface {
    	DemoJavaScriptInterface() { 
        }
    	
    	public void exitapp() {
    		System.exit(0);
    	}

    	public void capture() {
    		captureV2();
    	}
    	
    	public void getimage() {
    		getimage1();
    	}
    	
    }
}