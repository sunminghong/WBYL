package web.view;

import java.io.ByteArrayOutputStream;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.InputStream;

import android.app.Activity;
import android.app.DebugDialog;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.ContentResolver;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.provider.Settings;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.view.ContextThemeWrapper;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.Window;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.Toast;


import android.util.Base64;  


public class webview extends Activity {
//	private String hostAddres = "file:///android_asset/android_app_plat.html";
	private String hostAddres = "http://faner.miqiba.com/m_android.php";
	
	private WebView mWebView;
	private ImageView myImage;
	private byte[] mContent;

	private static final int ACTIVITY_IMAGE_CAPTURE = 1000;
	private static final int ACTIVITY_GET_IMAGE = 2000;
	private static final String MIME_TYPE_IMAGE_JPEG = "image/*";
	
	private int imgWidth=320;
	private int imgHeight=480;
	private int imgQuality=80;
	private String fnCallback="";
	private byte[] _imgbytes=null;

	private boolean _bDebug=false;
	private NotificationManager nm;
	private Notification n;
	private static final int _notifyid = 1256;
	
	private String _locationUpdateCallback = "";
	private String _locationEnableCallback = "";
	private String _locationStausChangedCallback = "";

	private LocationManager locationManager;
	private String provider;
	private Location location;

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
        mWebView.addJavascriptInterface(new MyJSInterface(), "android");
        mWebView.loadUrl(hostAddres); 
    }
    	
    // 判断是否开启 GPS ，若未开启，打开 GPS 设置界面
    private void openGPS() {       
        if ( locationManager .isProviderEnabled(android.location.LocationManager. GPS_PROVIDER )
        || locationManager .isProviderEnabled(android.location.LocationManager. NETWORK_PROVIDER )
        ) {
            Toast.makeText ( this , " 位置源已设置！ " , Toast. LENGTH_SHORT ).show();
            return ;
        }
        Toast.makeText ( this , " 位置源未设置！ " , Toast. LENGTH_SHORT ).show();
        // 转至 GPS 设置界面
        Intent intent = new Intent(Settings. ACTION_SECURITY_SETTINGS );
        startActivityForResult(intent,0);
    }
    
 // 获取 Location Provider
    private void getProvider(){
    // 构建位置查询条件
        Criteria criteria = new Criteria();
        // 查询精度：高
        criteria.setAccuracy(Criteria. ACCURACY_FINE );
         // 是否查询海拨：否
        criteria.setAltitudeRequired( false );
        // 是否查询方位角 : 否
        criteria.setBearingRequired( false );
        // 是否允许付费：是
        criteria.setCostAllowed( true );
        // 电量要求：低
        criteria.setPowerRequirement(Criteria. POWER_LOW );
        // 返回最合适的符合条件的 provider ，第 2 个参数为 true 说明 , 如果只有一个 provider 是有效的 , 则返回当前 provider
        provider = locationManager .getBestProvider(criteria, true );  
    }  

	// gps function 
	protected void StartLocationUpdateV2(int intervalMS,intervalMeter,String updatecallback,String enablecallback,String statusChanged) {
		_locationUpdateCallback=updatecallback;
		_locationEnableCallback=enablecallback;
		_locationStausChangedCallback=statusChanged;

        // 获取 LocationManager 服务
        locationManager = (LocationManager) getSystemService(Context. LOCATION_SERVICE );
        // 获取 Location Provider
        getProvider();
        // 如果未设置位置源，打开 GPS 设置界面
        openGPS();
		//告诉系统，我们需要从GPS获取位置信息，并且是每隔1000ms更新一次，并且不考虑位置的变化。
		//最后一个参数是LocationListener的一个引用，我们必须要实现这个类。 
		debug("StartLocationUpdateV2 in");
		locationManager.requestLocationUpdates(provider, intervalMS,intervalMeter, locationListener); 
	}

	private String convertPos(lat,lng,alti) {
		
		/*自经纬度取得地址，可能有多行地址*/
		List<Address> listAddress=gc.getFromLocation((int)lat,(int)lng,1);
		StringBuilder sb=new StringBuilder();
		/*判断是不否为多行*/
		if(listAddress.size()>0){
			Address address=listAddress.get(0);
		　　for(int i=0;i<address.getMaxAddressLineIndex();i++){
		　　　　sb.append(address.getAddressLine(i)).append("\n");
		　　}
		　　sb.append(address.getLocality()).append("\n");
		　　sb.append(address.getPostalCode()).append("\n");
		　　sb.append(address.getCountryName ()).append("\n");
		}
		
		return lat + "///"+ lng+"///"+sb.toString();
	}
	
	private final LocationListener locationListener = new LocationListener() {  
		public void onLocationChanged(Location location) { //当坐标改变时触发此函数，如果Provider传进相同的坐标，它就不会被触发  
			// log it when the location changes  
			debug("onLocationChanged in");
			if (location != null) {  
				if(_locationUpdateCallback.length() <=0 ) {
			    	double latitude = location.getLatitude();
					double longititude = location.getLongitude();
					double altitude = location.getAltitude();

					mWebView.loadUrl("javascript:"+_locationUpdateCallback+"('"+convertPos(latitude,longititude,altitude)+"')");
				}
			}  
		}  
	  
		public void onProviderDisabled(String provider) {  
			// Provider被disable时触发此函数，比如GPS被关闭
			debug("onProviderDisabled in");
			if(_locationEnableCallback.length() <=0 )
				mWebView.loadUrl("javascript:"+_locationEnableCallback+"('enabled','"+provider+"')");
		}  
	  
		public void onProviderEnabled(String provider) {  
			//  Provider被enable时触发此函数，比如GPS被打开
			debug("onProviderEnabled in");

			if(_locationEnableCallback.length() <=0 )
				mWebView.loadUrl("javascript:"+_locationEnableCallback+"('disabled','"+provider+"')");
		}  
	  
		public void onStatusChanged(String provider, int status, Bundle extras) {  
			// Provider的转态在可用、暂时不可用和无服务三个状态直接切换时触发此函数  
			debug("onStatusChanged in");
			if(_locationStausChangedCallback.length() <=0 )
					mWebView.loadUrl("javascript:"+_locationStausChangedCallback+"('"+provider+"',"+status+")");
		}  
	};  

    
    // get location
    protected String getLocationInfoV2() {

        location = locationManager .getLastKnownLocation( provider );

		debug("getLocationInfoV2 in");
		if(location==null) {
			debug("location is null!");
			return "";
		}
		debug("location is not null!");
        // 获取位置
    	double latitude = location.getLatitude();
    	double longititude = location.getLongitude();
    	double altitude = location.getAltitude();

		return convertPos(latitude,longititude,altitude);
    }

    /**
    * 添加顶部通知
    */
    public void AddNotification(String tips,String title,String msg,int nofityStyle,boolean isnewnotify){
	    debug("notify is in!");
	    //添加通知到顶部任务栏
	    //获得NotificationManager实例
	    String service = NOTIFICATION_SERVICE;
	    nm = (NotificationManager)getSystemService(service);
	    //实例化Notification
	    n = new Notification();
	    n.defaults=nofityStyle;
	     
    	//这是设置通知是否同时播放声音或振动，声音为Notification.DEFAULT_SOUND  ==1
    	//振动为Notification.DEFAULT_VIBRATE  == 2;
    	//Light为Notification.DEFAULT_LIGHTS=4，在我的Milestone上好像没什么反应
    	//全部为Notification.DEFAULT_ALL==0xffffffff;
    	//如果是振动或者全部，必须在AndroidManifest.xml加入振动权限
	    //设置显示图标
	    int icon = R.drawable.home;
	    //设置提示信息
	    String tickerText = tips;
	    //显示时间
	    long when = System.currentTimeMillis();
	    n.icon = icon;
	    n.tickerText = tickerText;
	    n.when = when;
	    /*
	    //显示在“正在进行中”
	    n.flags = Notification.FLAG_ONGOING_EVENT;
	    */
	    n.flags = Notification.FLAG_AUTO_CANCEL;
	    //实例化Intent
	    Intent intent = new Intent(webview.this,webview.class);
	    //获得PendingIntent
	    PendingIntent pi = PendingIntent.getActivity(webview.this, 0, intent, 0);

	    //设置事件信息，显示在拉开的里面
	    n.setLatestEventInfo(webview.this, title, msg, pi);
	    //发出通知
	    if(isnewnotify)
	    	nm.notify((int)when,n);
	    else
	    	nm.notify(_notifyid,n);
	    	
	    debug("notify execute end");
    }
    //onDestroy doesn't work
    @Override  
    protected void onDestroy() {  
        super.onDestroy();        
    }  
    protected void clearV2() {
    	nm.cancelAll();
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.menu, menu);
        return true;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
    	// Handle item selection
    	switch(item.getItemId()) {
    	case R.id.refresh:
    		mWebView.loadUrl(hostAddres);
    		return true;
    	case R.id.exit:
    		showExitGameDebug();
    		return true;
    	default:
    		return super.onOptionsItemSelected(item);
    	}
    }
    
    public boolean onKeyDown(int keyCode, KeyEvent event) {
    	// 如果是返回键,直接返回到桌面
    	if(keyCode == KeyEvent.KEYCODE_BACK || keyCode == KeyEvent.KEYCODE_HOME){
               //showExitGameDebug();
			   mWebView.loadUrl("javascript:exitCallback()");
    	}
     
    	return super.onKeyDown(keyCode, event);
    }
    private void showExitGameDebug() {
    	final DebugDialog dlg = new DebugDialog.Builder(this).create();
    	dlg.show();
    	Window window = dlg.getWindow();
            // *** 主要就是在这里实现这种效果的.
            // 设置窗口的内容页面,shrew_exit_dialog.xml文件中定义view内容
    	window.setContentView(R.layout.shrew_exit_dialog);
            // 为确认按钮添加事件,执行退出应用操作
    	ImageButton ok = (ImageButton) window.findViewById(R.id.btn_ok);
    	ok.setOnClickListener(new View.OnClickListener() {
    		public void onClick(View v) {
    			clearV2();
    			System.exit(0); // 退出应用...
    		}
    	});
     
            // 关闭debug对话框架
            ImageButton cancel = (ImageButton) window.findViewById(R.id.btn_cancel);
            cancel.setOnClickListener(new View.OnClickListener() {
    	  public void onClick(View v) {
    		  dlg.cancel();
    		}
    	  });
    }
    

	public void debug(String msg) {
		if(!_bDebug) return;
		mWebView.loadUrl("javascript:debugcallback('"+ msg +"')");
	}

	public void captureV2(String callback,int imgW,int imgH,int imgQ) {
		fnCallback=callback;
		imgWidth=imgW;
		imgHeight=imgH;
		imgQuality=imgQ;

		debug("capture is open!");
		// 调用相机
		Intent mIntent = new Intent("android.media.action.IMAGE_CAPTURE");
		startActivityForResult(mIntent, ACTIVITY_IMAGE_CAPTURE);
		
		//startActivity(mIntent);
	}
	
	public void getImageV2(String callback,int imgW,int imgH,int imgQ) {
		fnCallback=callback;
		imgWidth=imgW;
		imgHeight=imgH;
		imgQuality=imgQ;
		debug("getimage is open!");

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
		Bitmap btp = null;
		debug("onActivityResult="+resultCode+"/"+requestCode);
		if (resultCode == RESULT_OK) {
			switch(requestCode) {
				case ACTIVITY_IMAGE_CAPTURE:
					debug("return from camera!");
					Bundle dataBundle = data.getExtras();
		
					btp = (Bitmap) dataBundle.get("data");
					data.putExtra(MediaStore.EXTRA_OUTPUT, btp);

					break;
				case ACTIVITY_GET_IMAGE:

					debug("return from gallery!");
					Uri uri = data.getData();
					Log.e("uri", uri.toString());
					ContentResolver cr = this.getContentResolver();

				try {
					btp = BitmapFactory.decodeStream(cr.openInputStream(uri));
				} catch (FileNotFoundException e) {
					e.printStackTrace();
				}					
					break;
			}	
			
			btp = Bitmap.createScaledBitmap(btp, imgWidth,imgHeight, true);
			
			ByteArrayOutputStream baos = new ByteArrayOutputStream();
			btp.compress(Bitmap.CompressFormat.JPEG, imgQuality, baos);
			_imgbytes = baos.toByteArray();
			
			debug("length:" + _imgbytes.length);
			mWebView.loadUrl("javascript:"+fnCallback+"(true)");
		}
		else 
			mWebView.loadUrl("javascript:"+fnCallback+"(false)");
	}

    
    final class MyJSInterface {
    	MyJSInterface() { 
        }
    	
    	public String getImgBase64() {
			String database64 = Base64.encodeToString(_imgbytes, 0, _imgbytes.length,Base64.DEFAULT); 
			debug("returning base64: "+database64.substring(0, 20));
			return database64;		
    	}
    	
    	public byte[] getImgBytes() {
    		return _imgbytes;
    	}
		public void alertExitApp() {
			showExitGameAlert();
		}
    	public void exitApp() {
    		System.exit(0);
    	}
    	
    	public void notify(String tips,String title,String msg,int notifyStyle) {
    		AddNotification(tips,title,msg,notifyStyle,false);
    	}

    	public void notify(String tips,String title,String msg,int notifyStyle,boolean isnewnotify) {
    		AddNotification(tips,title,msg,notifyStyle,isnewnotify);
    	}
    	
    	public void clearNotify() {
    		clearV2();
    	}

    	public void capture(String callback,int imgW,int imgH,int imgQ) {
    		
			if(callback == null || callback.length() <= 0) {
				debug("callback is empty!");
				return;
			}
    		captureV2(callback,imgW,imgH,imgQ);
    	}
    	
    	public void getImage(String callback,int imgW,int imgH,int imgQ) {
		if(callback == null || callback.length() <= 0) {
			debug("callback is empty!");
			return;
		}
    		getImageV2(callback,imgW,imgH,imgQ);
    	}

		public String readTelIMSI() {
			TelephonyManager tm = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
			debug("readPhoneNumberV2 in");

			String imsi = tm.getSubscriberId();
			return imsi;
		}

		public String readTelNumber() {
			//创建电话管理
			TelephonyManager tm = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
			debug("readPhoneNumber in");
			return tm.getLine1Number();
		}
				
		public void startLocationUpdate(int intervalMS,int intervalMeter,String updatecallback,String enablecallback,String statusChanged) {
			StartLocationUpdateV2(intervalMS,intervalMeter updatecallback, enablecallback,statusChanged);
		}
		public String getLocationInfo() { 
			return getLocationInfoV2();
		}
		
		public String switchDebug(boolean bDebug) {
			_bDebug = bDebug;
		}
    }
}