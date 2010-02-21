package
{
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.Loader;
	import flash.display.LoaderInfo;
	import flash.events.DataEvent;
	import flash.events.Event;
	import flash.geom.Matrix;
	import flash.net.FileReference;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.utils.ByteArray;
	
	import mx.controls.Alert;
	import mx.graphics.codec.JPEGEncoder;
	import mx.utils.Base64Encoder;
	import mx.utils.UIDUtil;

	public class UploadHelper {
		
		private var _fileReference: FileReference;
		
		public function UploadHelper(value: FileReference) {
			_fileReference = value;
			_fileReference.addEventListener(Event.COMPLETE, load_completeHandler);
			_fileReference.load();
		}
		
		public function set fileReference(value: FileReference) : void {
			_fileReference = value;
		}
		
		public function get fileReference() : FileReference {
			return _fileReference;
		}

		[Bindable]
		public var thumbnail: Bitmap;
		
		[Bindable]
		public var readyForUpload: Boolean = false;
		
		[Bindable]
		public var uploading: Boolean = false;
		
		[Bindable]
		public var done: Boolean = false;
		
		[Bindable]
		public var omschrijving: String;
		
		public var onReady: Function;
		
		private var _realWidth: int;

		private var _realHeight: int;
		
		private var _uid: String;
		
		protected function setRealHeight(value: int) : void {
			_realHeight = value;
			dispatchEvent(new Event("realHeightChanged"));
		}
		
		[Bindable(event='realHeightChanged')]
		public function get realHeight() : int {
			return this._realHeight;
		}

		protected function setRealWidth(value: int) : void {
			_realWidth = value;
			dispatchEvent(new Event("realWidthChanged"));
		}
		
		public function get realWidth() : int {
			return _realWidth;
		}
		
		public function load_completeHandler(event:Event):void {
			var fileRef :FileReference = FileReference(event.target);
			
			var loader: Loader = new Loader();
			loader.contentLoaderInfo.addEventListener(Event.COMPLETE, img_completeHandler);
			loader.loadBytes(fileRef.data);
			
			fileRef.removeEventListener(Event.COMPLETE, load_completeHandler);
			fileRef.cancel();
		}
		
		public function img_completeHandler(event:Event): void {
			var loaderInfo: LoaderInfo = event.target as LoaderInfo;
			var bitmapData: BitmapData = Bitmap(loaderInfo.content).bitmapData;
			
			setRealHeight(bitmapData.height);
			setRealWidth(bitmapData.width);
			
			// Resize image
			bitmapData = resizeBitmapData(bitmapData, 128, 96);
			
			// Display resized image
			thumbnail = new Bitmap(bitmapData);
			
			loaderInfo.loader.unload();
			readyForUpload = true;
		}

		public function upload_completeHandler(event:Event):void {
			var fileRef :FileReference = FileReference(event.target);
			done = true;
			uploading = false;
			
			if (onReady != null) {
				onReady(this, _uid);
			}
		}
		
		public function upload() : void {
			var bitmapData: BitmapData = thumbnail.bitmapData;
			
			// Create JPEG byte array
			var jpge: JPEGEncoder = new JPEGEncoder(90);
			var ba: ByteArray = jpge.encode(bitmapData);
			
			// Base64Encode the JPEG for upload
			var b64enc: Base64Encoder = new Base64Encoder();
			b64enc.encodeBytes(ba);
			
			var urlLoader:URLLoader = new URLLoader();
			var request:URLRequest = new URLRequest("upload_foto2.php");
			request.method = URLRequestMethod.POST;
			var data:URLVariables = new URLVariables();	
			
			_uid = UIDUtil.createUID();
			
			data.width = _realWidth;
			data.height = _realHeight;
			data.uid = _uid;
			
			data.thumb_content = b64enc.flush();
			data.thumb_mimetype = "image/jpeg";
			data.thumb_size = ba.length;
			data.thumb_width = bitmapData.width;
			data.thumb_height = bitmapData.height;
			
			data.omschrijving = omschrijving;
			
			request.data = data;
			
			_fileReference.addEventListener(Event.COMPLETE, upload_completeHandler);
			//_fileReference.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, upload_completeDataHandler);
			_fileReference.upload(request, "file");
			uploading = true;
			readyForUpload = false;
		}
		
		/*
		public function upload_completeDataHandler(event: DataEvent) : void {
			Alert.show(event.data);
		}
		*/

		public static function resizeBitmapData(bitmapData: BitmapData, maxWidth: int = 128, maxHeight: int = 96) : BitmapData {
			
			var bw: int = bitmapData.width;
			var bh: int = bitmapData.height;
			
			var scaledData: BitmapData;
			
			var w: int;
			var h: int;
			
			if (maxWidth * bh <= bw * maxHeight) {
				w = maxWidth;
				h = Math.ceil(maxWidth * bh / bw);
			} else {
				w = Math.ceil(maxHeight * bw / bh);
				h = maxHeight;
			}
			
			var n: int = 0;
			while (w << 1 <= bw && h << 1 <= bh) {
				w <<= 1;
				h <<= 1;
				n++;
			}
			
			var scaleMatrix: Matrix = new Matrix();
			scaleMatrix.scale(w / bw, h / bh);
			scaledData = new BitmapData(w, h, false, 0x00FFFFFF);
			scaledData.draw(bitmapData, scaleMatrix, null, null, null, true);
			bitmapData = scaledData;
			
			scaleMatrix = new Matrix();
			scaleMatrix.scale(0.5, 0.5);
			while (n > 0) {
				w >>= 1;
				h >>= 1;
				scaledData = new BitmapData(w, h, false, 0x00FFFFFF);
				scaledData.draw(bitmapData, scaleMatrix, null, null, null, true);
				bitmapData = scaledData;
				n--;
			}
			
			return bitmapData;
		}

	}
}